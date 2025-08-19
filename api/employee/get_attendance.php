<?php
session_start();
require '../db_connect.php'; // Assumes db_connect.php is in the parent 'api' directory
header('Content-Type: application/json');

$employee_id = $_SESSION['employee_id'] ?? 1; // Default to employee 1 for testing
$month = $_GET['month'] ?? date('Y-m');

$start_date = $month . '-01';
$end_date = date("Y-m-t", strtotime($start_date));

$stmt = $conn->prepare(
    "SELECT log_date, time_in, time_out, status
     FROM attendance_logs
     WHERE employee_id = ? AND log_date BETWEEN ? AND ?
     ORDER BY log_date ASC"
);
$stmt->bind_param("iss", $employee_id, $start_date, $end_date);
$stmt->execute();
$result = $stmt->get_result();
$logs = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode($logs);

$stmt->close();
$conn->close();
