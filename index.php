<?php
// --- Database Connection ---
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "nordlich"; // Change to your actual database name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Database connection failed: " . $conn->connect_error);
}

// --- API Router ---
if (isset($_GET['action'])) {
  header('Content-Type: application/json');
  $action = $_GET['action'];

  // In a real app, you'd get this from session after login
  $employee_id = 1;

  switch ($action) {
    case 'get_leave_history':
      $stmt = $conn->prepare("SELECT leave_type, start_date, end_date, days, status, reason FROM leave_requests WHERE employee_id = ? ORDER BY start_date DESC");
      $stmt->bind_param("i", $employee_id);
      $stmt->execute();
      $result = $stmt->get_result();
      $history = $result->fetch_all(MYSQLI_ASSOC);
      echo json_encode($history);
      $stmt->close();
      break;

    case 'file_leave':
      $input = json_decode(file_get_contents('php://input'), true);

      if (
        empty($input['leaveType']) ||
        empty($input['startDate']) ||
        empty($input['endDate']) ||
        empty($input['reason'])
      ) {
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
        exit;
      }

      $start = new DateTime($input['startDate']);
      $end = new DateTime($input['endDate']);
      $days = $end->diff($start)->format("%a") + 1;

      $stmt = $conn->prepare("INSERT INTO leave_requests (employee_id, leave_type, start_date, end_date, days, reason, status) VALUES (?, ?, ?, ?, ?, ?, 'Pending')");
      $stmt->bind_param(
        "isssis",
        $employee_id,
        $input['leaveType'],
        $input['startDate'],
        $input['endDate'],
        $days,
        $input['reason']
      );

      if ($stmt->execute()) {
        echo json_encode(['success' => true]);
      } else {
        echo json_encode(['success' => false, 'message' => $stmt->error]);
      }
      $stmt->close();
      break;

    default:
      echo json_encode(['error' => 'Unknown action']);
  }
  $conn->close();
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>Nordlich Employee Panel</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
  <link href="assets/css/style.css" rel="stylesheet" />
</head>

<body>
  <?php
  // Sidebar
  require 'includes/sidebar.php';

  // Top Navbar
  require 'includes/navbar.php';
  ?>

  <!-- Main Content -->
  <main class="main-content">
    <?php require 'page/attendance-view.php'; ?>
    <?php require 'page/leave-view.php'; ?>
  </main>

  <?php
  // Modals
  require 'includes/modals.php';
  ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/script.js"></script>
</body>

</html>