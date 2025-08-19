<?php
session_start();
require '../db_connect.php';
header('Content-Type: application/json');

$employee_id = $_SESSION['employee_id'] ?? 1;
// A real payslip API would calculate earnings, deductions, etc.
// For now, we'll just fetch employee info to demonstrate a dynamic modal.

$stmt = $conn->prepare(
    "SELECT e.employee_code, e.full_name, p.title as position
     FROM employees e
     LEFT JOIN positions p ON e.position_id = p.id
     WHERE e.id = ?"
);
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();
$payslip_data = $result->fetch_assoc();

// You would add payroll calculations here...
$payslip_data['basic_salary'] = 35000.00; // Example data
$payslip_data['total_deductions'] = 2500.00;
$payslip_data['net_pay'] = $payslip_data['basic_salary'] - $payslip_data['total_deductions'];

echo json_encode($payslip_data);

$stmt->close();
$conn->close();
