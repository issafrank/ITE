<!-- My Profile Modal -->
<div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="profileModalLabel">Employee Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row">
                    <!-- Personal Information -->
                    <div class="col-md-8">
                        <h6 class="text-muted">Personal Information</h6>
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <th scope="row" style="width: 120px;">Full Name:</th>
                                    <td>Juan Dela Cruz</td>
                                </tr>
                                <tr>
                                    <th scope="row">Position:</th>
                                    <td>Pharmacist</td>
                                </tr>
                                <tr>
                                    <th scope="row">Location:</th>
                                    <td>Manila</td>
                                </tr>
                                <tr>
                                    <th scope="row">Email:</th>
                                    <td>juan@nordlich.com</td>
                                </tr>
                                <tr>
                                    <th scope="row">Status:</th>
                                    <td><span class="badge bg-success">Active</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Employee Photo -->
                    <div class="col-md-4 text-center">
                        <img src="https://placehold.co/150x150/e1f0e5/237ab7?text=Photo" alt="Employee Photo" class="img-thumbnail mb-2">
                        <p class="text-muted small">Employee Photo</p>
                        <button class="btn btn-sm btn-secondary mt-2" data-bs-toggle="modal" data-bs-target="#changePhotoModal"><i class="bi bi-camera me-1"></i> Change Photo</button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Change Photo Modal -->
<div class="modal fade" id="changePhotoModal" tabindex="-1" aria-labelledby="changePhotoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #14db6eff; color: white;">
                <h5 class="modal-title" id="changePhotoModalLabel">Update Profile Photo</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="changePhotoForm" action="upload_photo.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="employee_id" id="modalEmployeeId" value="1">

                    <div class="text-center mb-4">
                        <div class="position-relative d-inline-block">
                            <img id="imagePreview" src="https://placehold.co/200x200/e1f0e5/237ab7?text=Preview"
                                class="img-thumbnail rounded-circle"
                                style="width: 200px; height: 200px; object-fit: cover; display: none; border-color: #237ab7 !important;">
                            <div id="uploadPlaceholder" class="d-flex flex-column align-items-center justify-content-center bg-light rounded-circle"
                                style="width: 200px; height: 200px; margin: 0 auto; border: 2px dashed #237ab7;">
                                <i class="bi bi-cloud-arrow-up fs-1" style="color: #237ab7;"></i>
                                <small style="color: #237ab7;">Image Preview</small>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="photoFile" class="form-label" style="color: #237ab7;">Select Image</label>
                        <input class="form-control" type="file" id="photoFile" name="photo"
                            accept="image/jpeg, image/png" required>
                        <div class="form-text" style="color: #237ab7;">JPG or PNG format, maximum 2MB</div>
                    </div>

                    <div id="fileInfo" class="d-flex justify-content-between small mt-2" style="display: none; color: #237ab7;">
                        <span id="fileNameDisplay">No file selected</span>
                        <span id="fileSizeDisplay">0 KB</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn" id="uploadButton" style="background-color: #237ab7; color: white;">
                        <span class="upload-text">Upload Photo</span>
                        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Photo Upload Success Modal -->
<div class="modal fade" id="photoSuccessModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center p-5">
                <div class="mb-4">
                    <div class="checkmark-circle">
                        <div class="checkmark"></div>
                    </div>
                </div>
                <h4 class="text-success mb-3">Success!</h4>
                <p class="mb-4">Your profile photo has been updated successfully.</p>
                <button type="button" class="btn btn-success px-4" data-bs-dismiss="modal">Continue</button>
            </div>
        </div>
    </div>
</div>

<!-- My Payslip Modal -->
<div class="modal fade" id="payslipModal" tabindex="-1" aria-labelledby="payslipModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="payslipModalLabel">Payslip Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <!-- Company and Payroll Info -->
                <div class="d-flex justify-content-between mb-4">
                    <div>
                        <h6 class="fw-bold">Nordlich Pharma Inc.</h6>
                        <p class="text-muted mb-0">Main Branch</p>
                    </div>
                    <div>
                        <p class="mb-0"><span class="fw-bold">Pay Period:</span> <input type="month" class="form-control form-control-sm d-inline-block" style="width: 150px;" value="2025-08"></p>
                    </div>
                </div>
                <!-- Employee Info -->
                <table class="table table-bordered table-sm mb-4">
                    <tbody>
                        <tr>
                            <th class="bg-light">Employee</th>
                            <td>Juan Dela Cruz</td>
                            <th class="bg-light">Work Period</th>
                            <td>1st Half (15 days)</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Position</th>
                            <td>Accountant</td>
                            <th class="bg-light">Pay Date</th>
                            <td>Aug 15, 2025</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Employee ID</th>
                            <td>EMP-001</td>
                            <th class="bg-light">Worked Days</th>
                            <td>12 / 12</td>
                        </tr>
                    </tbody>
                </table>
                <!-- Earnings and Deductions -->
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-success">Earnings</h6>
                        <table class="table table-sm">
                            <tbody>
                                <tr>
                                    <td>Basic Salary</td>
                                    <td class="text-end">₱ 25,000.00</td>
                                </tr>
                                <tr>
                                    <td>Overtime</td>
                                    <td class="text-end">₱ 1,200.00</td>
                                </tr>
                                <tr>
                                    <td>Allowance</td>
                                    <td class="text-end">₱ 800.00</td>
                                </tr>
                                <tr class="fw-bold border-top">
                                    <td>Total Earnings</td>
                                    <td class="text-end">₱ 27,000.00</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-danger">Deductions</h6>
                        <table class="table table-sm">
                            <tbody>
                                <tr>
                                    <td>SSS</td>
                                    <td class="text-end">₱ 600.00</td>
                                </tr>
                                <tr>
                                    <td>PhilHealth</td>
                                    <td class="text-end">₱ 400.00</td>
                                </tr>
                                <tr>
                                    <td>Pag-IBIG</td>
                                    <td class="text-end">₱ 200.00</td>
                                </tr>
                                <tr>
                                    <td>Withholding Tax</td>
                                    <td class="text-end">₱ 1,000.00</td>
                                </tr>
                                <tr>
                                    <td>Other Deductions</td>
                                    <td class="text-end">₱ 250.00</td>
                                </tr>
                                <tr class="fw-bold border-top">
                                    <td>Total Deductions</td>
                                    <td class="text-end">₱ 2,450.00</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <hr>
                <!-- Net Pay -->
                <div class="d-flex justify-content-end">
                    <div class="text-end bg-light p-2 rounded">
                        <h5 class="fw-bold mb-0">Net Pay: ₱ 24,550.00</h5>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-danger"><i class="bi bi-file-earmark-pdf me-1"></i>Download PDF</button>
            </div>
        </div>
    </div>
</div>
<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center p-5">
                <div class="mb-4">
                    <div class="checkmark-circle">
                        <div class="checkmark"></div>
                    </div>
                </div>
                <h4 class="text-success mb-3">Request Submitted!</h4>
                <p class="mb-4">Your leave request has been received. You'll be notified once processed.</p>
                <button type="button" class="btn btn-success px-4" data-bs-dismiss="modal">Continue</button>
            </div>
        </div>
    </div>
</div>