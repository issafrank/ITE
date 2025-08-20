<?php
session_start();
require '../db_connect.php';
header('Content-Type: application/json');

$employee_id = $_SESSION['employee_id'] ?? 1;
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'file_leave':
        $input = json_decode(file_get_contents('php://input'), true);

        if (empty($input['leaveTypeId']) || empty($input['startDate']) || empty($input['endDate']) || empty($input['reason'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'All fields are required.']);
            exit;
        }

        try {
            $start = new DateTime($input['startDate']);
            $end = new DateTime($input['endDate']);
            if ($start > $end) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Start date cannot be after end date.']);
                exit;
            }
            $days = $end->diff($start)->format("%a") + 1;
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid date format provided.']);
            exit;
        }

        $stmt = $conn->prepare(
            "INSERT INTO leave_requests (employee_id, leave_type_id, date_from, date_to, days, reason, status)
             VALUES (?, ?, ?, ?, ?, ?, 'Pending')"
        );
        $stmt->bind_param("iissds", $employee_id, $input['leaveTypeId'], $input['startDate'], $input['endDate'], $days, $input['reason']);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Leave request submitted!']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $stmt->error]);
        }
        $stmt->close();
        break;

    default:
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Unknown API action requested.']);
}
$conn->close();