<?php
session_start();
require '../db_connect.php';
header('Content-Type: application/json');

$employee_id = $_SESSION['employee_id'] ?? 1;

$response = [
    'leave_credits' => [],
    'leave_history' => []
];

// 1. Fetch Leave Credits for the employee
$sql_credits = "SELECT lt.name, lc.balance
                FROM leave_credits lc
                JOIN leave_types lt ON lc.leave_type_id = lt.id
                WHERE lc.employee_id = ?";
$stmt_credits = $conn->prepare($sql_credits);
$stmt_credits->bind_param("i", $employee_id);
$stmt_credits->execute();
$result_credits = $stmt_credits->get_result();

$totals = ['Vacation' => 12, 'Sick' => 10, 'Paternity' => 7, 'Maternity' => 105, 'Bereavement' => 3];
while($row = $result_credits->fetch_assoc()){
    $response['leave_credits'][$row['name']] = [
        'balance' => $row['balance'],
        'total' => $totals[$row['name']] ?? 0
    ];
}
$stmt_credits->close();

// 2. Fetch Leave History for the employee
$sql_history = "SELECT lr.date_from, lr.date_to, lr.days, lr.reason, lr.status, lt.name as leave_type
                FROM leave_requests lr
                JOIN leave_types lt ON lr.leave_type_id = lt.id
                WHERE lr.employee_id = ?
                ORDER BY lr.date_from DESC LIMIT 15";
$stmt_history = $conn->prepare($sql_history);
$stmt_history->bind_param("i", $employee_id);
$stmt_history->execute();
$result_history = $stmt_history->get_result();
$response['leave_history'] = $result_history->fetch_all(MYSQLI_ASSOC);
$stmt_history->close();

$conn->close();
echo json_encode($response);