<!-- My Attendance View -->
<div id="attendance-view" class="view active">
  <h1 class="h2 mb-4">My Attendance</h1>
  <div class="card shadow-sm">
    <div class="card-header bg-white d-flex flex-wrap justify-content-between align-items-center">
      <h5 class="mb-0 py-2">Attendance Logs</h5>
      <div class="d-flex gap-2 py-2">
        <input type="month" id="month-filter" class="form-control form-control-sm" style="width: 150px;" value="<?php echo date('Y-m'); ?>">
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>Date</th>
              <th>Time In</th>
              <th>Time Out</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <!-- JavaScript will populate this -->
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>