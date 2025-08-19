<?php
session_start();
require '../db_connect.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die(json_encode(['error' => 'Method not allowed']));
}

$employee_id = $_SESSION['employee_id'] ?? 1;
$target_dir = "../../uploads/"; // Go up two directories to the root, then into uploads

if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
}

if (!isset($_FILES['photo']) || $_FILES['photo']['error'] != 0) {
    http_response_code(400);
    die(json_encode(['error' => 'No file uploaded or upload error.']));
}

$file = $_FILES['photo'];
$file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
$safe_filename = "employee_" . $employee_id . "_" . time() . "." . $file_extension;
$target_file_path = $target_dir . $safe_filename;
$db_path = "uploads/" . $safe_filename; // Path to store in DB

// Validations
$image_size = getimagesize($file['tmp_name']);
if (!$image_size) {
    http_response_code(400);
    die(json_encode(['error' => 'File is not a valid image.']));
}
if ($file['size'] > 2 * 1024 * 1024) { // 2MB
    http_response_code(400);
    die(json_encode(['error' => 'File is too large (max 2MB).']));
}
$allowed_types = ['jpg', 'jpeg', 'png'];
if (!in_array($file_extension, $allowed_types)) {
    http_response_code(400);
    die(json_encode(['error' => 'Only JPG, JPEG, & PNG files are allowed.']));
}

if (move_uploaded_file($file['tmp_name'], $target_file_path)) {
    $stmt = $conn->prepare("UPDATE employees SET photo_path = ? WHERE id = ?");
    $stmt->bind_param("si", $db_path, $employee_id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'filepath' => $db_path]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Database update failed: ' . $stmt->error]);
    }
    $stmt->close();
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to move uploaded file.']);
}

$conn->close();
