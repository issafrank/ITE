<?php
session_start();
require '../db_connect.php'; // Adjust path if needed
header('Content-Type: application/json');

$employee_id = $_SESSION['employee_id'] ?? 1; // Default for testing
$action = $_GET['action'] ?? '';

// Use a switch to handle different actions
switch ($action) {
    case 'get_data':
        handleGetData($conn, $employee_id);
        break;
    case 'file_leave':
        handleFileLeave($conn, $employee_id);
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action specified.']);
        http_response_code(400);
        break;
}

$conn->close();

// Function to fetch leave credits and history
function handleGetData($conn, $employee_id) {
    $response = [
        'leave_credits' => [],
        'leave_history' => []
    ];

    // 1. Fetch Leave Credits
    $sql_credits = "SELECT lt.name, lc.balance FROM leave_credits lc JOIN leave_types lt ON lc.leave_type_id = lt.id WHERE lc.employee_id = ?";
    $stmt_credits = $conn->prepare($sql_credits);
    $stmt_credits->bind_param("i", $employee_id);
    $stmt_credits->execute();
    $result_credits = $stmt_credits->get_result();
    $totals = ['Vacation' => 10, 'Sick' => 10, 'Paternity' => 7, 'Maternity' => 105, 'Bereavement' => 3];
    while ($row = $result_credits->fetch_assoc()) {
        $response['leave_credits'][$row['name']] = [
            'balance' => $row['balance'],
            'total' => $totals[$row['name']] ?? 0
        ];
    }
    $stmt_credits->close();

    // 2. Fetch Leave History
    $sql_history = "SELECT lr.date_from, lr.date_to, lr.days, lr.reason, lr.status, lt.name as leave_type FROM leave_requests lr JOIN leave_types lt ON lr.leave_type_id = lt.id WHERE lr.employee_id = ? ORDER BY lr.requested_at DESC";
    $stmt_history = $conn->prepare($sql_history);
    $stmt_history->bind_param("i", $employee_id);
    $stmt_history->execute();
    $result_history = $stmt_history->get_result();
    $response['leave_history'] = $result_history->fetch_all(MYSQLI_ASSOC);
    $stmt_history->close();

    echo json_encode($response);
}

// Function to handle new leave request submission
function handleFileLeave($conn, $employee_id) {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405); // Method Not Allowed
        echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
        return;
    }

    $data = json_decode(file_get_contents('php://input'), true);

    // Basic validation
    if (empty($data['leaveTypeId']) || empty($data['startDate']) || empty($data['endDate']) || empty($data['reason'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
        return;
    }

    $leave_type_id = $data['leaveTypeId'];
    $date_from = $data['startDate'];
    $date_to = $data['endDate'];
    $reason = htmlspecialchars($data['reason']); // Sanitize input
    
    // Calculate number of days
    $start = new DateTime($date_from);
    $end = new DateTime($date_to);
    $days = $start->diff($end)->days + 1;

    $sql = "INSERT INTO leave_requests (employee_id, leave_type_id, date_from, date_to, days, reason, status) VALUES (?, ?, ?, ?, ?, ?, 'Pending')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iissis", $employee_id, $leave_type_id, $date_from, $date_to, $days, $reason);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Leave request submitted successfully.']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to submit leave request.']);
    }
    $stmt->close();
}
?>