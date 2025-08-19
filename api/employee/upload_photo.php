<?php
// upload_photo.php
require 'db_connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die(json_encode(['error' => 'Method not allowed']));
}

$employee_id = $_SESSION['employee_id'] ?? 12;
$target_dir = "uploads/";

// Create uploads directory if it doesn't exist
if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
}

// Validate file
if (!isset($_FILES['photo'])) {
    http_response_code(400);
    die(json_encode(['error' => 'No file uploaded']));
}

$file = $_FILES['photo'];
$target_file = $target_dir . basename($file['name']);
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Check if image file is a actual image
if (!getimagesize($file['tmp_name'])) {
    http_response_code(400);
    die(json_encode(['error' => 'File is not an image']));
}

// Check file size (2MB max)
if ($file['size'] > 2000000) {
    http_response_code(400);
    die(json_encode(['error' => 'File too large (max 2MB)']));
}

// Allow certain file formats
if ($imageFileType != "jpg" && $imageFileType != "png") {
    http_response_code(400);
    die(json_encode(['error' => 'Only JPG/PNG allowed']));
}

// Upload file
if (move_uploaded_file($file['tmp_name'], $target_file)) {
    // Update database
    $sql = "UPDATE employees SET photo_path = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $target_file, $employee_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'filepath' => $target_file]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $stmt->error]);
    }
    $stmt->close();
} else {
    http_response_code(500);
    echo json_encode(['error' => 'File upload failed']);
}

$conn->close();
