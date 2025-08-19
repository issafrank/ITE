<?php
require 'db_connect.php';
session_start();
$employee_id = $_SESSION['employee_id'] ?? 12;

$sql = "SELECT leave_type, date_from, date_to, reason, status 
        FROM leave_requests 
        WHERE employee_id = ? 
        ORDER BY date_from DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();

$history = [];
while ($row = $result->fetch_assoc()) {
    $history[] = $row;
}

header('Content-Type: application/json');
echo json_encode($history);

$stmt->close();
$conn->close();
