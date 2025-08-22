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

