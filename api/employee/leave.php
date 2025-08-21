<?php
session_start();
require '../db_connect.php';
header('Content-Type: application/json');

$employee_id = $_SESSION['employee_id'] ?? 1;
$action = $_GET['action'] ?? '';

if ($action === 'file_leave') {
    $input = json_decode(file_get_contents('php://input'), true);

    // 1. Validate Input
    if (empty($input['leaveTypeId']) || empty($input['startDate']) || empty($input['endDate']) || empty($input['reason'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
        exit;
    }

    $leave_type_id = $input['leaveTypeId'];
    $date_from = $input['startDate'];
    $date_to = $input['endDate'];
    $reason = $input['reason'];

    try {
        $start = new DateTime($date_from);
        $end = new DateTime($date_to);
        if ($start > $end) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Start date cannot be after the end date.']);
            exit;
        }
        // Auto-calculate days
        $days_requested = $end->diff($start)->format("%a") + 1;
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid date format provided.']);
        exit;
    }

    // Start a database transaction
    $conn->begin_transaction();

    try {
        // 2. Check if employee has enough leave credits (lock the row for update)
        $stmt_check = $conn->prepare("SELECT balance FROM leave_credits WHERE employee_id = ? AND leave_type_id = ? FOR UPDATE");
        $stmt_check->bind_param("ii", $employee_id, $leave_type_id);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows === 0) {
            throw new Exception("You do not have a credit balance for this leave type.");
        }

        $credit = $result_check->fetch_assoc();
        $current_balance = $credit['balance'];

        if ($days_requested > $current_balance) {
            throw new Exception("Insufficient leave credits. You only have " . $current_balance . " days available.");
        }

        // 3. Insert the new leave request
        $stmt_insert = $conn->prepare("INSERT INTO leave_requests (employee_id, leave_type_id, date_from, date_to, days, reason, status) VALUES (?, ?, ?, ?, ?, ?, 'Pending')");
        $stmt_insert->bind_param("iissds", $employee_id, $leave_type_id, $date_from, $date_to, $days_requested, $reason);
        $stmt_insert->execute();

        // 4. Deduct the days from the leave_credits table
        $new_balance = $current_balance - $days_requested;
        $stmt_update = $conn->prepare("UPDATE leave_credits SET balance = ? WHERE employee_id = ? AND leave_type_id = ?");
        $stmt_update->bind_param("dii", $new_balance, $employee_id, $leave_type_id);
        $stmt_update->execute();

        // If all queries were successful, commit the transaction
        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Leave request submitted successfully!']);
    } catch (Exception $e) {
        // If any step fails, roll back the entire transaction
        $conn->rollback();
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }

    $stmt_check->close();
    if (isset($stmt_insert)) $stmt_insert->close();
    if (isset($stmt_update)) $stmt_update->close();
} else {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Unknown API action requested.']);
}

$conn->close();
