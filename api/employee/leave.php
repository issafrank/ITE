<?php
session_start();
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "nordlich";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Database connection failed: " . $conn->connect_error);

$employee_id = 1; // Replace with $_SESSION['employee_id'] in production

// Messages from leave.php
$success_message = isset($_GET['success']) ? $_GET['success'] : '';
$error_message = isset($_GET['error']) ? $_GET['error'] : '';

// Fetch leave history
$history = [];
$stmt = $conn->prepare("SELECT leave_type, start_date, end_date, days, status, reason FROM leave_requests WHERE employee_id = ? ORDER BY start_date DESC");
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) $history[] = $row;
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>My Leave | Employee Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>

<body>
    <div class="container mt-5">
        <h1 class="mb-4">My Leave</h1>
        <!-- Leave Credits (hardcoded for demo) -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card p-3"><strong>Vacation:</strong> 10/10</div>
            </div>
            <div class="col-md-3">
                <div class="card p-3"><strong>Sick:</strong> 10/10</div>
            </div>
            <div class="col-md-3">
                <div class="card p-3"><strong>Paternity:</strong> 7/7</div>
            </div>
            <div class="col-md-3">
                <div class="card p-3"><strong>Maternity:</strong> 105/105</div>
            </div>
        </div>
        <!-- Success/Error Message -->
        <?php if ($success_message): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success_message) ?></div>
        <?php endif; ?>
        <?php if ($error_message): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>
        <!-- Button to open modal -->
        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#leaveRequestModal">File a New Leave Request</button>
        <!-- Leave Request History Table -->
        <div class="card mb-5">
            <div class="card-header">
                <h5 class="mb-0">Leave Request History</h5>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Dates</th>
                            <th>Days</th>
                            <th>Status</th>
                            <th>Reason</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($history): ?>
                            <?php foreach ($history as $leave): ?>
                                <tr>
                                    <td><?= htmlspecialchars($leave['leave_type']) ?></td>
                                    <td>
                                        <?= htmlspecialchars(date('M d, Y', strtotime($leave['start_date']))) ?>
                                        to
                                        <?= htmlspecialchars(date('M d, Y', strtotime($leave['end_date']))) ?>
                                    </td>
                                    <td><?= htmlspecialchars($leave['days']) ?></td>
                                    <td>
                                        <span class="badge <?=
                                                            $leave['status'] == 'Approved' ? 'bg-success' : ($leave['status'] == 'Pending' ? 'bg-warning text-dark' : 'bg-danger') ?>">
                                            <?= htmlspecialchars($leave['status']) ?>
                                        </span>
                                    </td>
                                    <td><?= htmlspecialchars($leave['reason']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted">No leave history.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Leave Request Modal -->
    <div class="modal fade" id="leaveRequestModal" tabindex="-1" aria-labelledby="leaveRequestModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="leave.php">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="leaveRequestModalLabel">New Leave Request</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="file_leave" value="1">
                        <div class="mb-3">
                            <label for="leaveTypeModal" class="form-label">Leave Type</label>
                            <select class="form-select" id="leaveTypeModal" name="leaveType" required>
                                <option value="">Select leave type...</option>
                                <option value="Vacation">Vacation Leave</option>
                                <option value="Sick">Sick Leave</option>
                                <option value="Bereavement">Bereavement Leave</option>
                                <option value="Maternity">Maternity Leave</option>
                                <option value="Paternity">Paternity Leave</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="startDateModal" class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="startDateModal" name="startDate" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="endDateModal" class="form-label">End Date</label>
                                <input type="date" class="form-control" id="endDateModal" name="endDate" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="reasonModal" class="form-label">Reason</label>
                            <textarea class="form-control" id="reasonModal" name="reason" rows="3" required></textarea>
                        </div>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            Your request will be reviewed by HR. You'll receive a notification once processed.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">
                            Submit Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>