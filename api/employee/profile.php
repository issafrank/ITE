<?php
// get_profile.php
require 'db_connect.php';
session_start();
$employee_id = $_SESSION['employee_id'] ?? 12;

// Query database
$sql = "SELECT full_name, email, position, area, status 
        FROM employees 
        WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(404);
    die(json_encode(['error' => 'Profile not found']));
}

$profile = $result->fetch_assoc();

// Return JSON
header('Content-Type: application/json');
echo json_encode($profile);

$stmt->close();
$conn->close();
