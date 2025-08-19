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
          <div class="row">

            <div class="col-md-6 mb-3">
              <div class="d-flex justify-content-between"><span>Vacation</span><span>10/10</span></div>
              <div class="progress" style="height: 10px;">
                <div class="progress-bar" role="progressbar" style="width: 100%; background-color: #237ab7;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>

            <div class="col-md-6 mb-3">
              <div class="d-flex justify-content-between"><span>Sick</span><span>10/10</span></div>
              <div class="progress" style="height: 10px;">
                <div class="progress-bar bg-warning" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>

            <div class="col-md-6 mb-3">
              <div class="d-flex justify-content-between"><span>Paternity</span><span>7/7</span></div>
              <div class="progress" style="height: 10px;">
                <div class="progress-bar bg-info" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>

            <div class="col-md-6 mb-3">
              <div class="d-flex justify-content-between"><span>Maternity</span><span>105/105</span></div>
              <div class="progress" style="height: 10px;">
                <div class="progress-bar bg-info" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>

            <div class="col-md-6 mb-3">
              <div class="d-flex justify-content-between"><span>Bereavement</span><span>3/3</span></div>
              <div class="progress" style="height: 10px;">
                <div class="progress-bar bg-secondary" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>

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
              <tbody>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="leaveRequestModal" tabindex="-1" aria-labelledby="leaveRequestModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="leaveRequestModalLabel">New Leave Request</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="leaveForm">
        <div class="modal-body">
          <div class="mb-3">
            <label for="leaveTypeModal" class="form-label">Leave Type</label>
            <select class="form-select" id="leaveTypeModal" name="leaveType" required>
              <option value="" disabled selected>Select leave type...</option>
              <option value="Vacation">Vacation Leave</option>
              <option value="Sick">Sick Leave</option>
              <option value="Bereavement">Bereavement Leave</option>
              <option value="Maternity">Maternity Leave</option>
              <option value="Paternity">Paternity Leave</option>
            </select>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="startDateModal" class="form-label">Date From</label>
              <input type="date" class="form-control" id="startDateModal" name="startDate" required>
            </div>
            <div class="col-md-6 mb-3">
              <label for="endDateModal" class="form-label">Date To</label>
              <input type="date" class="form-control" id="endDateModal" name="endDate" required>
            </div>
          </div>

          <div class="mb-3">
            <label for="reasonModal" class="form-label">Reason</label>
            <textarea class="form-control" id="reasonModal" name="reason" rows="3" placeholder="Briefly explain the reason for your leave..." required></textarea>
          </div>

          <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>
            Your request will be reviewed by HR. You'll receive a notification once processed.
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary" id="submitLeaveButton">
            <span class="submit-text">Submit Request</span>
            <span class="spinner-border spinner-border-sm d-none" role="status"></span>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>