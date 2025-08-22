<?php
// Leave view (pure view file).
// NOTE: index.php already starts the session and includes db_connect.php.
// Do NOT call session_start() here to avoid "session already started" warnings.
?>
<!-- My Leave View -->
<div id="leave-view" class="view">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2 mb-0">My Leave</h1>
    <button type="button" class="btn btn-primary" style="background-color: #237ab7; border-color: #237ab7;" data-bs-toggle="modal" data-bs-target="#leaveRequestModal">
      File a New Leave Request
    </button>
  </div>
  <div class="row">
    <div class="col-12 mb-4">
      <div class="card shadow-sm">
        <div class="card-header bg-white">
          <h5 class="mb-0">Leave Credits</h5>
        </div>
        <div class="card-body">
          <!-- JavaScript will populate this container -->
          <div id="leave-credits-container" class="row">
             <!-- Example:
                <div class="col-md-6 mb-3">
                  <div class="d-flex justify-content-between"><span>Vacation</span><span>7/10</span></div>
                  <div class="progress" style="height: 10px;">
                    <div class="progress-bar" role="progressbar" style="width: 70%; background-color: #237ab7;"></div>
                  </div>
                </div>
             -->
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
              <!-- JavaScript will populate this table body -->
              <tbody id="leaveHistoryTableBody">
                <!-- Example Row:
                    <tr>
                      <td>Vacation</td>
                      <td>Jul 10-12, 2025</td>
                      <td>3</td>
                      <td><span class="badge bg-success">Approved</span></td>
                      <td>Family Trip</td>
                    </tr>
                -->
              </tbody>
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
            <select class="form-select" id="leaveTypeModal" name="leave_type_id" required>
              <option value="" disabled selected>Select...</option>
              <option value="1">Vacation</option>
              <option value="2">Sick</option>
              <option value="5">Bereavement</option>
              <option value="4">Maternity</option>
              <option value="3">Paternity</option>
            </select>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="startDateModal" class="form-label">Date From</label>
              <input type="date" class="form-control" id="startDateModal" name="date_from" required>
            </div>
            <div class="col-md-6 mb-3">
              <label for="endDateModal" class="form-label">Date To</label>
              <input type="date" class="form-control" id="endDateModal" name="date_to" required>
            </div>
          </div>
          <div class="mb-3">
            <label for="reasonModal" class="form-label">Reason</label>
            <textarea class="form-control" id="reasonModal" name="reason" rows="3" required></textarea>
          </div>
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