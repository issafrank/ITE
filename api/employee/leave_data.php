<?php
session_start();
require '../db_connect.php';
header('Content-Type: application/json');

$employee_id = $_SESSION['employee_id'] ?? 1;

$response = [
    'leave_credits' => [],
    'leave_history' => []
];

// 1. Fetch Leave Credits
$sql_credits = "SELECT lt.name, lc.balance
                FROM leave_credits lc
                JOIN leave_types lt ON lc.leave_type_id = lt.id
                WHERE lc.employee_id = ?";
$stmt_credits = $conn->prepare($sql_credits);
$stmt_credits->bind_param("i", $employee_id);
$stmt_credits->execute();
$result_credits = $stmt_credits->get_result();

// CORRECTED: Set the correct total for Vacation to 10
$totals = ['Vacation' => 10, 'Sick' => 10, 'Paternity' => 7, 'Maternity' => 105, 'Bereavement' => 3];
while ($row = $result_credits->fetch_assoc()) {
    $response['leave_credits'][$row['name']] = [
        'balance' => $row['balance'],
        'total' => $totals[$row['name']] ?? 0
    ];
}
$stmt_credits->close();


// 2. Fetch Leave History (This part is correct)
// ...

$conn->close();
echo json_encode($response);
