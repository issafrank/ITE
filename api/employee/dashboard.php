<?php
session_start();
require '../db_connect.php'; // Assumes db_connect.php is in the parent 'api' directory
header('Content-Type: application/json');

// --- Set default session values for testing ---
$_SESSION['employee_id'] = $_SESSION['employee_id'] ?? 1;
$_SESSION['username'] = $_SESSION['username'] ?? 'Aris';

// --- Prepare the main data payload ---
$response_data = [
    'username' => $_SESSION['username'],
    'team_members' => []
    // Add more data to fetch here, like stats
];

// --- Team Status Logic ---
$today = (new DateTime())->format('Y-m-d');

$sql = "SELECT e.id as employee_id, e.full_name,
        (SELECT a.time_in FROM attendance_logs a WHERE a.employee_id = e.id AND a.log_date = ? ORDER BY a.id DESC LIMIT 1) AS last_time_in,
        (SELECT a.time_out FROM attendance_logs a WHERE a.employee_id = e.id AND a.log_date = ? ORDER BY a.id DESC LIMIT 1) AS last_time_out
    FROM employees e
    ORDER BY e.full_name";

$stmt = $conn->prepare($sql);
$stmt->bind_param('ss', $today, $today);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $status = 'out';
    $time = null;
    $image_bg = 'f56565'; // Red for OUT

    if ($row['last_time_in'] && !$row['last_time_out']) {
        $status = 'in';
        $time = date('H:i', strtotime($row['last_time_in']));
        $image_bg = '6cb2eb'; // Blue for IN
    } elseif ($row['last_time_out']) {
        $time = date('H:i', strtotime($row['last_time_out']));
    }

    $response_data['team_members'][] = [
        'name' => $row['full_name'],
        'status' => $status,
        'img' => 'https://ui-avatars.com/api/?name=' . urlencode($row['full_name']) . '&background=' . $image_bg . '&color=fff',
        'time' => $time
    ];
}

$stmt->close();
$conn->close();

// --- Send the final JSON response ---
echo json_encode($response_data);