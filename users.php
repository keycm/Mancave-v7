<?php
session_start();
include 'config.php';

// Security Check - Only Admins can access this page
if (!isset($_SESSION['username']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// --- SORTING LOGIC ---
$sortOption = $_GET['sort'] ?? 'id_asc';
$orderBy = "id ASC"; // Default

switch ($sortOption) {
    case 'id_desc': $orderBy = "id DESC"; break;
    case 'name_asc': $orderBy = "username ASC"; break;
    case 'name_desc': $orderBy = "username DESC"; break;
    case 'role_asc': $orderBy = "role ASC"; break;
    case 'role_desc': $orderBy = "role DESC"; break;
    case 'id_asc': default: $orderBy = "id ASC"; break;
}

// Fetch Users with Sorting
$users = [];
$sql = "SELECT id, username, email, role FROM users ORDER BY $orderBy";
$result = mysqli_query($conn, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers & Staff | ManCave Admin</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;600;700;800&family=Playfair+Display:wght@600;700&family=Pacifico&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="admin_new_style.css">
    
    <style>
        /* --- LOGO STYLES --- */
        .sidebar-logo { display: flex; flex-direction: column; align-items: center; gap: 0; line-height: 1; text-decoration: none; }
        .logo-top { font-family: 'Playfair Display', serif; font-size: 0.7rem; font-weight: 700; color: #ccc; letter-spacing: 2px; }
        .logo-main { font-family: 'Pacifico', cursive; font-size: 1.8rem; transform: rotate(-4deg); margin: 5px 0; color: #fff; }
        .logo-red { color: #ff4d4d; }
        .logo-bottom { font-family: 'Nunito Sans', sans-serif; font-size: 0.6rem; font-weight: 800; color: #ccc; letter-spacing: 3px; text-transform: uppercase; }

        /* --- NOTIFICATION STYLES --- */
        .header-actions { display: flex; align-items: center; gap: 25px; }
        .notif-wrapper { position: relative; }
        
        .notif-bell { 
            background: #fff; width: 45px; height: 45px; border-radius: 50%; 
            display: flex; align-items: center; justify-content: center; 
            font-size: 1.2rem; color: var(--secondary); cursor: pointer; 
            box-shadow: var(--shadow-sm); border: 1px solid var(--border); transition: 0.3s; 
        }
        .notif-bell:hover { color: var(--accent); transform: translateY(-2px); box-shadow: var(--shadow-md); }
        .notif-bell .dot { 
            position: absolute; top: -2px; right: -2px; background: var(--red); 
            color: white; font-size: 0.65rem; font-weight: 700; border-radius: 50%; 
            min-width: 18px; height: 18px; display: flex; align-items: center; justify-content: center; border: 2px solid #fff; 
        }
        
        .notif-dropdown { 
            display: none; position: absolute; right: -10px; top: 55px; 
            width: 320px; background: white; border-radius: 16px; 
            box-shadow: 0 10px 40px rgba(0,0,0,0.15); border: 1px solid var(--border); 
            z-index: 1100; overflow: hidden; transform-origin: top right; animation: slideDown 0.2s ease-out; 
        }
        .notif-dropdown.active { display: block; }
        
        .notif-header { 
            padding: 15px 20px; border-bottom: 1px solid #f0f0f0; 
            display: flex; justify-content: space-between; align-items: center; 
            background: #fafafa; font-weight: 700; font-size: 0.9rem; color: var(--primary); 
        }
        .small-btn { border: none; background: none; font-size: 0.75rem; cursor: pointer; font-weight: 700; color: var(--accent); text-transform: uppercase; }
        .small-btn:hover { color: #b07236; }
        
        .notif-list { max-height: 300px; overflow-y: auto; list-style: none; margin: 0; padding: 0; }
        
        /* Updated Notification Item with Close Button */
        .notif-item { 
            padding: 15px 35px 15px 20px; /* Extra padding right for button */
            border-bottom: 1px solid #f9f9f9; font-size: 0.9rem; 
            cursor: pointer; transition: 0.2s; position: relative; 
        }
        .notif-item:hover { background: #fdfbf7; }
        .notif-item.unread { background: #fff8f0; border-left: 4px solid var(--accent); }
        .notif-msg { color: #444; line-height: 1.4; margin-bottom: 4px; }
        .notif-time { font-size: 0.75rem; color: #999; font-weight: 600; }
        
        /* Close Button Style */
        .btn-notif-close {
            position: absolute; top: 10px; right: 10px;
            background: none; border: none; color: #aaa;
            font-size: 1.2rem; line-height: 1; cursor: pointer;
            padding: 0; transition: color 0.2s;
        }
        .btn-notif-close:hover { color: #ff4d4d; }

        .no-notif { padding: 20px; text-align: center; color: #999; font-style: italic; }
        
        @keyframes slideDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }

        /* Page Specific Styles */
        .controls-bar {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 25px; gap: 15px; flex-wrap: wrap;
        }
        .search-group { display: flex; gap: 10px; flex: 1; max-width: 600px; }
        
        .search-wrapper { position: relative; flex: 1; }
        .search-wrapper i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: var(--secondary); }
        .search-wrapper input {
            width: 100%; padding: 12px 15px 12px 45px;
            border-radius: 50px; border: 1px solid var(--border);
            outline: none; transition: 0.3s;
        }
        .search-wrapper input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(205, 133, 63, 0.1); }

        .sort-select {
            padding: 0 20px; border-radius: 50px; border: 1px solid var(--border);
            background: white; color: var(--primary); font-weight: 700;
            cursor: pointer; outline: none; min-width: 150px;
        }

        .btn-add {
            background: var(--primary); color: white; padding: 12px 25px;
            border-radius: 50px; font-weight: 700; border: none; cursor: pointer;
            display: flex; align-items: center; gap: 10px; transition: 0.3s;
            box-shadow: var(--shadow-sm);
        }
        .btn-add:hover { background: var(--accent); transform: translateY(-2px); }

        /* Modal Specifics */
        .modal-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.5); backdrop-filter: blur(5px);
            display: flex; align-items: center; justify-content: center;
            opacity: 0; visibility: hidden; transition: 0.3s; z-index: 2000;
            padding: 20px;
        }
        .modal-overlay.show { opacity: 1; visibility: visible; }
        
        .modal-container {
            background: white; width: 500px; max-width: 100%;
            max-height: 90vh; overflow-y: auto;
            border-radius: 16px; padding: 30px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.2);
            transform: translateY(20px); transition: 0.3s;
        }
        .modal-overlay.show .modal-container { transform: translateY(0); }
        
        .modal-container::-webkit-scrollbar { width: 6px; }
        .modal-container::-webkit-scrollbar-thumb { background: #ccc; border-radius: 10px; }
        
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
        .modal-header h3 { font-family: var(--font-head); font-size: 1.5rem; color: var(--primary); margin: 0; }
        .btn-close-modal { background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--secondary); transition: 0.3s; }
        .btn-close-modal:hover { color: var(--red); transform: rotate(90deg); }
    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="sidebar-header">
            <a href="index.php" class="sidebar-logo">
                <span class="logo-top">THE</span>
                <span class="logo-main"><span class="logo-red">M</span>an<span class="logo-red">C</span>ave</span>
                <span class="logo-bottom">GALLERY</span>
            </a>
        </div>
        <nav class="sidebar-nav">
            <ul>
                <li><a href="admin.php"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>
                <li><a href="reservations.php"><i class="fas fa-calendar-check"></i> <span>Appointments</span></a></li>
                <li><a href="content.php"><i class="fas fa-layer-group"></i> <span>Inventory & Services</span></a></li>
                <li class="active"><a href="users.php"><i class="fas fa-users"></i> <span>Customers & Staff</span></a></li>
                <li><a href="feedback.php"><i class="fas fa-comments"></i> <span>Feedback</span></a></li>
                <li><a href="trash.php"><i class="fas fa-trash-alt"></i> <span>Recycle Bin</span></a></li>
            </ul>
        </nav>
        <div class="sidebar-footer">
            <a href="logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a>
        </div>
    </aside>

    <main class="main-content">
        <header class="top-bar">
            <div class="page-header">
                <h1>User Management</h1>
                <p>Manage customers, managers, and administrators.</p>
            </div>
            
            <div class="header-actions">
                <div class="notif-wrapper">
                    <div class="notif-bell" id="adminNotifBtn">
                        <i class="far fa-bell"></i>
                        <span class="dot" id="adminNotifBadge" style="display:none;">0</span>
                    </div>
                    
                    <div class="notif-dropdown" id="adminNotifDropdown">
                        <div class="notif-header">
                            <span>Notifications</span>
                            <button id="adminMarkAllRead" class="small-btn">Mark all read</button>
                        </div>
                        <ul class="notif-list" id="adminNotifList">
                            <li class="no-notif">Loading...</li>
                        </ul>
                    </div>
                </div>

                <div class="user-profile">
                    <div class="profile-info">
                        <span class="name">Administrator</span>
                        <span class="role">Super Admin</span>
                    </div>
                    <div class="avatar"><img src="https://ui-avatars.com/api/?name=Admin&background=cd853f&color=fff" alt="Admin"></div>
                </div>
            </div>
        </header>

        <div class="controls-bar">
            <form method="GET" class="search-group">
                <div class="search-wrapper">
                    <i class="fas fa-search"></i>
                    <input type="text" id="userSearch" placeholder="Search by name or email...">
                </div>
                
                <select name="sort" class="sort-select" onchange="this.form.submit()">
                    <option value="id_asc" <?= $sortOption == 'id_asc' ? 'selected' : '' ?>>ID (Oldest)</option>
                    <option value="id_desc" <?= $sortOption == 'id_desc' ? 'selected' : '' ?>>ID (Newest)</option>
                    <option value="name_asc" <?= $sortOption == 'name_asc' ? 'selected' : '' ?>>Name (A-Z)</option>
                    <option value="name_desc" <?= $sortOption == 'name_desc' ? 'selected' : '' ?>>Name (Z-A)</option>
                    <option value="role_asc" <?= $sortOption == 'role_asc' ? 'selected' : '' ?>>Role (A-Z)</option>
                    <option value="role_desc" <?= $sortOption == 'role_desc' ? 'selected' : '' ?>>Role (Z-A)</option>
                </select>
            </form>

            <button class="btn-add" id="addNewUserBtn">
                <i class="fas fa-user-plus"></i> Add New User
            </button>
        </div>

        <div class="card table-card">
            <div class="table-responsive">
                <table class="styled-table" id="usersTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User Profile</th>
                            <th>Email Address</th>
                            <th>Role</th>
                            <th style="text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($users)): ?>
                            <tr><td colspan="5" class="text-center" style="padding:40px; color:#999;">No users found.</td></tr>
                        <?php else: ?>
                            <?php foreach ($users as $user): 
                                // Role Logic
                                $role = strtolower($user['role']);
                                $roleClass = 'status-approved'; 
                                $roleLabel = 'Collector';

                                if ($role === 'admin') {
                                    $roleClass = 'status-cancelled'; // Red
                                    $roleLabel = 'Admin';
                                } elseif ($role === 'manager') {
                                    $roleClass = 'status-completed'; // Blue
                                    $roleLabel = 'Manager';
                                }

                                $initial = strtoupper(substr($user['username'], 0, 1));
                            ?>
                            <tr data-id="<?= $user['id'] ?>" 
                                data-username="<?= htmlspecialchars($user['username']) ?>"
                                data-email="<?= htmlspecialchars($user['email']) ?>"
                                data-role="<?= htmlspecialchars($role) ?>">
                                
                                <td><span class="id-badge">#<?= $user['id'] ?></span></td>
                                
                                <td>
                                    <div class="user-cell">
                                        <div class="user-avatar-sm"><?= $initial ?></div>
                                        <strong><?= htmlspecialchars($user['username']) ?></strong>
                                    </div>
                                </td>
                                
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                
                                <td>
                                    <span class="status-badge <?= $roleClass ?>"><?= $roleLabel ?></span>
                                </td>
                                
                                <td style="text-align: right;">
                                    <div class="actions" style="justify-content: flex-end;">
                                        <button class="btn-icon edit" onclick="editUser(this)" title="Edit User">
                                            <i class="fas fa-pen"></i>
                                        </button>
                                        
                                        <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                            <button class="btn-icon delete" onclick="deleteUser(<?= $user['id'] ?>)" title="Move to Trash">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <div class="modal-overlay" id="userModal">
        <div class="modal-container">
            <div class="modal-header">
                <h3 id="modalTitle">Add New User</h3>
                <button class="btn-close-modal" onclick="closeModal()">&times;</button>
            </div>
            <form id="userForm">
                <input type="hidden" id="userId" name="id">
                <input type="hidden" id="actionType" name="action" value="add">
                
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" id="username" name="username" required placeholder="Enter full name">
                </div>
                
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" id="email" name="email" required placeholder="name@example.com">
                </div>
                
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" id="password" name="password" placeholder="Leave blank to keep current">
                    <small style="color:var(--secondary); font-size:0.8rem; margin-top:5px; display:block;">
                        Minimum 8 characters. Required for new users.
                    </small>
                </div>
                
                <div class="form-group">
                    <label>Role</label>
                    <select id="role" name="role">
                        <option value="user">Collector (User)</option>
                        <option value="manager">Manager</option>
                        <option value="admin">Admin</option>
                    </select>
                    <small style="color:var(--secondary); font-size:0.75rem; margin-top:5px;">
                        <strong>Manager:</strong> Can manage content. <strong>Admin:</strong> Full access.
                    </small>
                </div>
                
                <button type="submit" class="btn-primary" style="margin-top:10px;">Save User</button>
            </form>
        </div>
    </div>

    <script>
        // === NOTIFICATION LOGIC (UPDATED) ===
        document.addEventListener('DOMContentLoaded', () => {
            const notifBtn = document.getElementById('adminNotifBtn');
            const notifDropdown = document.getElementById('adminNotifDropdown');
            const notifBadge = document.getElementById('adminNotifBadge');
            const notifList = document.getElementById('adminNotifList');
            const markAllBtn = document.getElementById('adminMarkAllRead');

            // 1. Toggle Dropdown
            notifBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                notifDropdown.classList.toggle('active');
            });

            // 2. Fetch Notifications
            function fetchNotifications() {
                fetch('fetch_notifications.php')
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success') {
                            if (data.unread_count > 0) {
                                notifBadge.innerText = data.unread_count;
                                notifBadge.style.display = 'flex';
                            } else {
                                notifBadge.style.display = 'none';
                            }

                            notifList.innerHTML = '';
                            if (data.notifications.length === 0) {
                                notifList.innerHTML = '<li class="no-notif">No new notifications</li>';
                            } else {
                                data.notifications.forEach(notif => {
                                    const li = document.createElement('li');
                                    li.className = `notif-item ${notif.is_read == 0 ? 'unread' : ''}`;
                                    li.innerHTML = `
                                        <div class="notif-msg">${notif.message}</div>
                                        <div class="notif-time">${notif.created_at}</div>
                                        <button class="btn-notif-close" title="Delete">&times;</button>
                                    `;
                                    
                                    // Mark Read on Click
                                    li.addEventListener('click', (e) => {
                                        if (e.target.classList.contains('btn-notif-close')) return;
                                        const formData = new FormData();
                                        formData.append('id', notif.id);
                                        fetch('mark_as_read.php', { method: 'POST', body: formData })
                                            .then(() => fetchNotifications());
                                    });

                                    // Delete Notification
                                    const closeBtn = li.querySelector('.btn-notif-close');
                                    closeBtn.addEventListener('click', (e) => {
                                        e.stopPropagation();
                                        if(!confirm('Delete this notification?')) return;
                                        const formData = new FormData();
                                        formData.append('id', notif.id);
                                        fetch('delete_notifications.php', { method: 'POST', body: formData })
                                            .then(res => res.json())
                                            .then(d => { if(d.status === 'success') fetchNotifications(); });
                                    });

                                    notifList.appendChild(li);
                                });
                            }
                        }
                    })
                    .catch(err => console.error(err));
            }

            if (markAllBtn) {
                markAllBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    fetch('mark_all_as_read.php', { method: 'POST' })
                        .then(() => fetchNotifications());
                });
            }

            window.addEventListener('click', () => {
                if (notifDropdown.classList.contains('active')) {
                    notifDropdown.classList.remove('active');
                }
            });
            notifDropdown.addEventListener('click', (e) => e.stopPropagation());

            fetchNotifications();
            setInterval(fetchNotifications, 30000);
        });

        // === MODAL LOGIC ===
        const modal = document.getElementById('userModal');
        const form = document.getElementById('userForm');

        function closeModal() { modal.classList.remove('show'); }

        // Open Add Modal
        document.getElementById('addNewUserBtn').addEventListener('click', () => {
            form.reset();
            document.getElementById('userId').value = '';
            document.getElementById('actionType').value = 'add';
            document.getElementById('modalTitle').textContent = 'Add New User';
            modal.classList.add('show');
        });

        // Open Edit Modal
        function editUser(btn) {
            const row = btn.closest('tr');
            document.getElementById('userId').value = row.dataset.id;
            document.getElementById('username').value = row.dataset.username;
            document.getElementById('email').value = row.dataset.email;
            
            // Set Role
            const roleVal = row.dataset.role || 'user';
            document.getElementById('role').value = roleVal;
            
            document.getElementById('actionType').value = 'update';
            document.getElementById('modalTitle').textContent = 'Edit User';
            modal.classList.add('show');
        }

        window.onclick = function(event) {
            if (event.target === modal) closeModal();
        }

        // === FORM SUBMISSION ===
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            const action = document.getElementById('actionType').value;
            
            try {
                const res = await fetch(`manage_user.php?action=${action}`, { 
                    method: 'POST', 
                    body: formData 
                });
                const data = await res.json();
                
                if(data.success) {
                    alert('User saved successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred processing your request.');
            }
        });

        // === DELETE LOGIC (UPDATED MESSAGE) ===
        async function deleteUser(id) {
            if(!confirm('Are you sure you want to move this user to the Recycle Bin?')) return;
            
            const formData = new FormData();
            formData.append('id', id);
            
            try {
                const res = await fetch('manage_user.php?action=delete', { 
                    method: 'POST', 
                    body: formData 
                });
                const data = await res.json();
                
                if(data.success) {
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred.');
            }
        }

        // === CLIENT-SIDE SEARCH LOGIC ===
        document.getElementById('userSearch').addEventListener('keyup', function() {
            const val = this.value.toLowerCase();
            document.querySelectorAll('#usersTable tbody tr').forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(val) ? '' : 'none';
            });
        });
    </script>
</body>
</html>