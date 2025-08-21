<?php
// This PHP code will run on the server before the page is sent to the browser.
// It assumes $conn is available because index.php requires 'api/db_connect.php'.
$employee_id_for_credits = $_SESSION['employee_id'] ?? 1;
$leave_credits = [];

$sql_credits = "SELECT lt.name, lc.balance
                FROM leave_credits lc
                JOIN leave_types lt ON lc.leave_type_id = lt.id
                WHERE lc.employee_id = ?";
$stmt_credits = $conn->prepare($sql_credits);
$stmt_credits->bind_param("i", $employee_id_for_credits);
$stmt_credits->execute();
$result_credits = $stmt_credits->get_result();

// Use the required default total values
$totals = ['Vacation' => 10, 'Sick' => 10, 'Paternity' => 7, 'Maternity' => 105, 'Bereavement' => 3];
while ($row = $result_credits->fetch_assoc()) {
  $leave_credits[$row['name']] = [
    'balance' => $row['balance'],
    'total' => $totals[$row['name']] ?? 0
  ];
}
$stmt_credits->close();
?>

<div id="leave-view" class="view">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2 mb-0">My Leave</h1>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#leaveRequestModal">File a New Leave Request</button>
  </div>
  <div class="row">
    <div class="col-12 mb-4">
      <div class="card shadow-sm">
        <div class="card-header bg-white">
          <h5 class="mb-0">Leave Credits</h5>
        </div>
        <div class="card-body">
          <!-- This section is now built by PHP and loads instantly -->
          <div id="leave-credits-container" class="row">
            <?php
            $displayOrder = ['Vacation', 'Sick', 'Paternity', 'Maternity', 'Bereavement'];
            $creditColors = ['Vacation' => '#237ab7', 'Sick' => '#ffc107', 'Paternity' => '#0dcaf0', 'Maternity' => '#0dcaf0', 'Bereavement' => '#6c757d'];

            foreach ($displayOrder as $leaveType):
              if (isset($leave_credits[$leaveType])):
                $credit = $leave_credits[$leaveType];
                $balance = floatval($credit['balance']);
                $total = floatval($credit['total']);
                $percentage = $total > 0 ? ($balance / $total) * 100 : 0;
            ?>
                <div class="col-md-6 mb-3">
                  <div class="d-flex justify-content-between">
                    <span><?php echo $leaveType; ?></span>
                    <span class="fw-bold"><?php echo $balance; ?>/<?php echo $total; ?></span>
                  </div>
                  <div class="progress" style="height: 10px;">
                    <div class="progress-bar" role="progressbar"
                      style="width: <?php echo $percentage; ?>%; background-color: <?php echo $creditColors[$leaveType] ?? '#6c757d'; ?>;"
                      aria-valuenow="<?php echo $balance; ?>" aria-valuemin="0" aria-valuemax="<?php echo $total; ?>"></div>
                  </div>
                </div>
            <?php
              endif;
            endforeach;
            ?>
            <div class="col-md-6 mb-3 d-flex align-items-center">
              <div class="row w-100">
                <div class="col-6">
                  <p class="mb-0"><span class="fw-bold">AWOL:</span> 0 days</p>
                </div>
                <div class="col-6">
                  <p class="mb-0"><span class="fw-bold">Suspended:</span> 0 days</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-12">
      <div class="card shadow-sm">
        <div class="card-header bg-white">
          <h5 class="mb-0">Leave Request History</h5>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>Type</th>
                  <th>Dates</th>
                  <th>Days</th>
                  <th>Status</th>
                  <th>Reason</th>
                </tr>
              </thead>
              <tbody><!-- This part will be populated by leave.js --></tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Leave Request Modal (Updated to send leave_type_id) -->
<div class="modal fade" id="leaveRequestModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">New Leave Request</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="leaveForm">
        <div class="modal-body">
          <div class="mb-3">
            <label for="leaveTypeModal" class="form-label">Leave Type</label>
            <select class="form-select" id="leaveTypeModal" required>
              <option value="" disabled selected>Select...</option>
              <option value="1">Vacation</option>
              <option value="2">Sick</option>
              <option value="5">Bereavement</option>
              <option value="4">Maternity</option>
              <option value="3">Paternity</option>
            </select>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3"><label for="startDateModal" class="form-label">Date From</label><input type="date" class="form-control" id="startDateModal" required></div>
            <div class="col-md-6 mb-3"><label for="endDateModal" class="form-label">Date To</label><input type="date" class="form-control" id="endDateModal" required></div>
          </div>
          <div class="mb-3"><label for="reasonModal" class="form-label">Reason</label><textarea class="form-control" id="reasonModal" rows="3" required></textarea></div>
          <div class="alert alert-info"><i class="bi bi-info-circle me-2"></i>Your request will be reviewed by HR.</div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary" id="submitLeaveButton">
            <span class="submit-text">Submit Request</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Success Modal (Ensure this is in your modals.php or here) -->
<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body text-center p-5">
        <h4 class="text-success mb-3">Request Submitted!</h4>
        <p class="mb-4">Your leave request has been received and your credits have been updated.</p>
        <button type="button" class="btn btn-success px-4" data-bs-dismiss="modal">Continue</button>
      </div>
    </div>
  </div>
</div>