<?php
session_start();
require '../db_connect.php';
header('Content-Type: application/json');

$employee_id = $_SESSION['employee_id'] ?? 1;
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'get_leave_history':
        $stmt = $conn->prepare(
            "SELECT lr.date_from, lr.date_to, lr.days, lr.reason, lr.status, lt.name as leave_type
             FROM leave_requests lr
             JOIN leave_types lt ON lr.leave_type_id = lt.id
             WHERE lr.employee_id = ?
             ORDER BY lr.date_from DESC"
        );
        $stmt->bind_param("i", $employee_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $history = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($history);
        break;

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
                echo json_encode(['success' => false, 'message' => 'Start date cannot be after the end date.']);
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
        break;

    default:
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Unknown API action requested.']);
}

$stmt->close();
$conn->close();
