<?php
// get_attendance.php
require 'db_connect.php';
session_start();
$employee_id = $_SESSION['employee_id'] ?? 12;

// Get month parameter
$month = $_GET['month'] ?? date('Y-m');
$start_date = $month . '-01';
$end_date = date("Y-m-t", strtotime($start_date));

// Query database
$sql = "SELECT log_date, time_in, time_out, status 
        FROM attendance_logs 
        WHERE employee_id = ? AND log_date BETWEEN ? AND ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iss", $employee_id, $start_date, $end_date);
$stmt->execute();
$result = $stmt->get_result();

// Fetch results
$logs = [];
while ($row = $result->fetch_assoc()) {
    $logs[] = $row;
}

// Return JSON
header('Content-Type: application/json');
echo json_encode($logs);

$stmt->close();
$conn->close();
