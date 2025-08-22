<div id="dashboard-view" class="view active">
    <div class="container-fluid py-4">
        <div class="row mb-3 align-items-center">
            <div class="col-12">
                <h4 class="fw-bold mb-0">My Dashboard</h4>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4" style="background: linear-gradient(to right, #237ab7, #1c6aa1); color: white;">
                    <div class="card-body d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h5 class="fw-bold mb-2">Welcome Back!</h5>
                            <p class="mb-0 small">Here is a summary of your activity for today, .</p>
                        </div>
                        <img src="https://i.imgur.com/example.png" alt="Welcome" style="width:100px; height:100px; object-fit:contain;">
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h6 class="fw-bold mb-0">My Recent Attendance</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Time In</th>
                                        <th>Time Out</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Aug 18, 2025</td>
                                        <td>08:00 AM</td>
                                        <td>05:05 PM</td>
                                        <td><span class="badge bg-success">On Time</span></td>
                                    </tr>
                                    <tr>
                                        <td>Aug 17, 2025</td>
                                        <td>09:15 AM</td>
                                        <td>06:02 PM</td>
                                        <td><span class="badge bg-danger">Late</span></td>
                                    </tr>
                                    <tr>
                                        <td>Aug 16, 2025</td>
                                        <td colspan="2" class="text-center text-warning">On Leave</td>
                                        <td><span class="badge bg-warning text-dark">On Leave</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <h6 class="fw-bold mb-0">My Leave Credits</h6>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-between mb-2">
                <span>Vacation</span>
                <span class="fw-bold">10 / 10</span>
            </div>
            <div class="progress mb-3" style="height: 10px;">
                <div class="progress-bar" role="progressbar" style="width: 83%;" aria-valuenow="10" aria-valuemin="0" aria-valuemax="12"></div>
            </div>

            <div class="d-flex justify-content-between mb-2">
                <span>Sick</span>
                <span class="fw-bold">10 / 10</span>
            </div>
            <div class="progress" style="height: 10px;">
                <div class="progress-bar bg-warning" role="progressbar" style="width: 80%;" aria-valuenow="8" aria-valuemin="0" aria-valuemax="10"></div>
            </div>
        </div>
    </div>
                
                <div class="col-lg-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <h6 class="fw-bold mb-3">Upcoming Company Holidays</h6>
            <div id="holidays-container-employee" style="max-height: 250px; overflow-y: auto;">
                           <div class="mb-3">
                                <div class="small text-muted">August 2025</div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <div><span class="fw-semibold me-2">26</span>National Heroes Day</div>
                                    <span class="badge bg-info bg-opacity-25 text-info">Holiday</span>
                                </div>
                           </div>
                           <div class="mb-3">
                                <div class="small text-muted">December 2025</div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <div><span class="fw-semibold me-2">25</span>Christmas Day</div>
                                    <span class="badge bg-info bg-opacity-25 text-info">Holiday</span>
                                </div>
                           </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>