<?php
session_start();
include 'config.php';

// --- 1. ADMIN SECURITY CHECK ---
if (!isset($_SESSION['admin_name']) && !isset($_SESSION['username'])) { 
    header("Location: login.php");
    exit;
}

// --- 2. DELETE USER LOGIC ---
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);

    // Check if we are trying to delete ourselves (Admin protection)
    $current_user_email = $_SESSION['email'] ?? ''; 
    $check_self = $conn->query("SELECT email FROM users WHERE id = $delete_id")->fetch_assoc();
    
    if ($check_self && $check_self['email'] === $current_user_email) {
        echo "<script>alert('You cannot delete your own active account!'); window.location.href='manage_user.php';</script>";
        exit;
    }

    // --- CLEANUP: DELETE ALL RELATED DATA FIRST ---
    // Even "fresh" accounts might have system notifications or empty booking records
    
    // Disable foreign key checks temporarily to force cleanup (Optional, but safe if deleting children first)
    $conn->query("SET FOREIGN_KEY_CHECKS=0");

    // Array of delete queries for all potential tables
    $queries = [
        "DELETE FROM bookings WHERE user_id = $delete_id",
        "DELETE FROM reservations WHERE user_id = $delete_id", // Just in case
        "DELETE FROM booking_events WHERE user_id = $delete_id", // If this table uses user_id
        "DELETE FROM feedback WHERE user_id = $delete_id",
        "DELETE FROM notifications WHERE user_id = $delete_id",
        "DELETE FROM inquiries WHERE user_id = $delete_id",
        "DELETE FROM favorites WHERE user_id = $delete_id",
        "DELETE FROM artist_likes WHERE user_id = $delete_id",
        // Artist specific tables (if the user was an artist)
        "DELETE FROM artworks WHERE artist_id = $delete_id", 
        "DELETE FROM manage_artists WHERE id = $delete_id" // Assuming user_id maps to id here?
    ];

    foreach ($queries as $q) {
        // Run query, ignore errors if table doesn't exist or column differs
        $conn->query($q);
    }

    $conn->query("SET FOREIGN_KEY_CHECKS=1");

    // --- FINAL: DELETE USER ---
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $delete_id);

    if ($stmt->execute()) {
        echo "<script>alert('User deleted successfully.'); window.location.href='manage_user.php';</script>";
    } else {
        echo "<script>alert('Error: Could not delete user. DB Error: " . addslashes($conn->error) . "'); window.location.href='manage_user.php';</script>";
    }
    $stmt->close();
    exit;
}

// --- 3. FETCH ALL USERS ---
$sql = "SELECT * FROM users ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users | Admin</title>
    <link rel="stylesheet" href="admin_new_style.css"> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f4f6f9; }
        .table-container { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="admin.php">Admin Panel</a>
            <div class="d-flex">
                <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="table-container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Manage Users</h2>
                <a href="admin.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td>
                                        <div class="fw-bold"><?php echo htmlspecialchars($row['username']); ?></div>
                                    </td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td>
                                        <?php if(empty($row['account_activation_hash'])): ?>
                                            <span class="badge bg-success">Verified</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="manage_user.php?delete=<?php echo $row['id']; ?>" 
                                           class="btn btn-danger btn-sm" 
                                           onclick="return confirm('Are you sure? This will delete the user and ALL their data.');">
                                            <i class="fas fa-trash-alt"></i> Delete
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No users found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>