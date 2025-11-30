<?php
include 'config.php';
header('Content-Type: application/json');

try {
    $action = $_POST['action'] ?? '';

    if ($action === 'save') {
        $id = $_POST['id'] ?? '';
        $name = $_POST['name'] ?? '';
        $style = $_POST['style'] ?? '';
        $quote = $_POST['quote'] ?? '';
        $bio = $_POST['bio'] ?? '';
        
        $imagePath = null;
        if (!empty($_FILES['image']['name'])) {
            $uploadDir = 'uploads/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            $fileName = time() . '_artist_' . basename($_FILES['image']['name']);
            if(move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $fileName)) {
                $imagePath = $fileName;
            }
        }

        if ($id) {
            $sql = "UPDATE artists SET name=?, style=?, quote=?, bio=?";
            if ($imagePath) $sql .= ", image_path=?";
            $sql .= " WHERE id=?";
            $stmt = $conn->prepare($sql);
            if ($imagePath) $stmt->bind_param("sssssi", $name, $style, $quote, $bio, $imagePath, $id);
            else $stmt->bind_param("ssssi", $name, $style, $quote, $bio, $id);
        } else {
            $stmt = $conn->prepare("INSERT INTO artists (name, style, quote, bio, image_path) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $name, $style, $quote, $bio, $imagePath);
        }

        if ($stmt->execute()) echo json_encode(['success' => true]);
        else throw new Exception($stmt->error);
        $stmt->close();
        exit;
    }

    if ($action === 'delete') {
        $id = intval($_POST['id'] ?? 0);
        
        // 1. Fetch Artist Data
        $res = $conn->query("SELECT * FROM artists WHERE id=$id");
        if ($row = $res->fetch_assoc()) {
            // 2. Prepare Trash Data
            $trashName = $row['name'] . '|' . json_encode($row);
            
            // 3. Insert into Trash
            $stmt = $conn->prepare("INSERT INTO trash_bin (item_id, item_name, source, deleted_at) VALUES (?, ?, 'artists', NOW())");
            $stmt->bind_param("is", $id, $trashName);
            $stmt->execute();
            $stmt->close();
            
            // 4. Delete from Artists
            $conn->query("DELETE FROM artists WHERE id=$id");
            echo json_encode(['success' => true]);
        } else {
            throw new Exception("Artist not found");
        }
        exit;
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>