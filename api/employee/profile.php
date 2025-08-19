<?php
session_start();
require '../db_connect.php';
header('Content-Type: application/json');

$employee_id = $_SESSION['employee_id'] ?? 1;

$stmt = $conn->prepare(
    "SELECT e.full_name, e.email, e.area, e.status, e.photo_path, p.title as position, d.name as department
     FROM employees e
     LEFT JOIN positions p ON e.position_id = p.id
     LEFT JOIN departments d ON p.department_id = d.id
     WHERE e.id = ?"
);
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(404);
    die(json_encode(['error' => 'Profile not found']));
}

$profile = $result->fetch_assoc();
echo json_encode($profile);

$stmt->close();
$conn->close();
