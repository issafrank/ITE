<!-- My Profile Modal -->
<div class="modal fade" id="profileModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Employee Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div id="profileSpinner" class="text-center">
                    <div class="spinner-border" role="status"></div>
                </div>
                <div id="profileContent" class="d-none">
                    <div class="row">
                        <div class="col-md-8">
                            <h6 class="text-muted">Personal Information</h6>
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <th scope="row" style="width: 120px;">Full Name:</th>
                                        <td id="profileFullName"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Position:</th>
                                        <td id="profilePosition"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Department:</th>
                                        <td id="profileDepartment"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Location:</th>
                                        <td id="profileArea"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Email:</th>
                                        <td id="profileEmail"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Status:</th>
                                        <td><span id="profileStatus" class="badge"></span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-4 text-center">
                            <img id="profilePhoto" src="assets/images/default-avatar.png" alt="Employee Photo" class="img-thumbnail mb-2" style="width: 150px; height: 150px; object-fit: cover;">
                            <button class="btn btn-sm btn-secondary mt-2" data-bs-toggle="modal" data-bs-target="#changePhotoModal"><i class="bi bi-camera me-1"></i> Change Photo</button>
                        </div>
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