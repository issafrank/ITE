document.addEventListener('DOMContentLoaded', function() {
    // ======================
    // DOM Element References
    // ======================
    const elements = {
        navLinks: document.querySelectorAll('.sidebar .nav-link, .offcanvas .nav-link'),
        views: document.querySelectorAll('.main-content .view'),
        mobileSidebar: document.getElementById('mobile-sidebar'),
        leaveForm: document.getElementById('leaveForm'),
        changePhotoForm: document.getElementById('changePhotoForm'),
        photoFileInput: document.getElementById('photoFile'),
        imagePreview: document.getElementById('imagePreview'),
        uploadPlaceholder: document.getElementById('uploadPlaceholder'),
        fileInfo: document.getElementById('fileInfo'),
        fileNameDisplay: document.getElementById('fileNameDisplay'),
        fileSizeDisplay: document.getElementById('fileSizeDisplay'),
        navbarProfileImg: document.getElementById('navbarProfileImg'),
        modals: {
            leaveRequest: new bootstrap.Modal(document.getElementById('leaveRequestModal')),
            changePhoto: new bootstrap.Modal(document.getElementById('changePhotoModal')),
            photoSuccess: new bootstrap.Modal(document.getElementById('photoSuccessModal')),
            profile: new bootstrap.Modal(document.getElementById('profileModal'))
        },
        clock: document.getElementById('clock'),
        logoutButton: document.getElementById('logoutButton'),
        leaveHistoryTableBody: document.querySelector('#leave-view .table tbody'),
        submitLeaveButton: document.getElementById('submitLeaveButton')
    };

    // =================
    // Helper Functions
    // =================
    const helpers = {
        formatFileSize: (bytes) => {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        },
        updateClock: () => {
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-US', { hour: 'numeric', minute: 'numeric', second: 'numeric', hour12: true });
            const dateString = now.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
            if (elements.clock) {
                elements.clock.innerHTML = `${dateString} · ${timeString}`;
            }
        },
        showAlert: (message, type = 'info') => {
            const alertId = `alert-${Date.now()}`;
            const alertHtml = `
                <div id="${alertId}" class="alert alert-${type} alert-dismissible fade show m-3" role="alert" style="position: fixed; top: 10px; right: 20px; z-index: 1056; cursor: pointer;">
                    ${message}
                </div>
            `;
            document.body.insertAdjacentHTML('beforeend', alertHtml);
            const newAlert = document.getElementById(alertId);
            const bsAlert = new bootstrap.Alert(newAlert);
            newAlert.addEventListener('click', () => bsAlert.close());
            setTimeout(() => bsAlert.close(), 5000);
        },
        fetchAndRenderLeaveHistory: () => {
            // This absolute path is reliable. It starts from the domain root (localhost)
            // and goes through your project folder (Employee).
            fetch('/Employee/api/employee/leave.php?action=get_leave_history')
                .then(response => {
                    if (!response.ok) throw new Error(`Network response was not ok: ${response.statusText}`);
                    return response.json();
                })
                .then(data => {
                    if (!elements.leaveHistoryTableBody) return;
                    elements.leaveHistoryTableBody.innerHTML = '';
                    if (data.length === 0) {
                        elements.leaveHistoryTableBody.innerHTML = '<tr><td colspan="5" class="text-center">No leave requests found.</td></tr>';
                        return;
                    }
                    data.forEach(request => {
                        let statusBadge;
                        switch (request.status.toLowerCase()) {
                            case 'approved': statusBadge = 'bg-success'; break;
                            case 'rejected': statusBadge = 'bg-danger'; break;
                            default: statusBadge = 'bg-warning text-dark';
                        }
                        const row = `
                            <tr>
                                <td>${request.leave_type}</td>
                                <td>${request.start_date} to ${request.end_date}</td>
                                <td>${request.days}</td>
                                <td><span class="badge ${statusBadge}">${request.status}</span></td>
                                <td>${request.reason}</td>
                            </tr>`;
                        elements.leaveHistoryTableBody.insertAdjacentHTML('beforeend', row);
                    });
                })
                .catch(error => {
                    console.error('Error fetching leave history:', error);
                    helpers.showAlert('Error loading leave history.', 'danger');
                });
        }
    };

    // ================
    // Event Listeners
    // ================
    const setupEventListeners = () => {
        elements.navLinks.forEach(link => {
            if (link.getAttribute('data-bs-toggle') === 'modal') return;
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('data-bs-target');
                if (!targetId) return;
                elements.navLinks.forEach(l => l.classList.remove('active'));
                document.querySelectorAll(`.nav-link[data-bs-target="${targetId}"]`).forEach(activeLink => activeLink.classList.add('active'));
                elements.views.forEach(v => v.classList.remove('active'));
                const targetView = document.querySelector(targetId);
                if (targetView) targetView.classList.add('active');
                const mobileSidebarInstance = bootstrap.Offcanvas.getInstance(elements.mobileSidebar);
                if (mobileSidebarInstance) mobileSidebarInstance.hide();
            });
        });

        if (elements.leaveForm) {
            elements.leaveForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const submitButton = elements.submitLeaveButton;
                const submitText = submitButton.querySelector('.submit-text');
                const spinner = submitButton.querySelector('.spinner-border');
                submitText.textContent = 'Submitting...';
                spinner.classList.remove('d-none');
                submitButton.disabled = true;

                const formData = {
                    leaveType: document.getElementById('leaveTypeModal').value,
                    startDate: document.getElementById('startDateModal').value,
                    endDate: document.getElementById('endDateModal').value,
                    reason: document.getElementById('reasonModal').value,
                };

                fetch('/Employee/api/employee/leave.php?action=file_leave', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(formData)
                })
                .then(response => {
                    if (!response.ok) throw new Error(`Network response was not ok: ${response.statusText}`);
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        elements.modals.leaveRequest.hide();
                        elements.leaveForm.reset();
                        helpers.showAlert(data.message || 'Request submitted successfully!', 'success');
                        helpers.fetchAndRenderLeaveHistory();
                    } else {
                        helpers.showAlert(data.message || 'An unknown error occurred.', 'danger');
                    }
                })
                .catch(error => {
                    console.error('Error submitting form:', error);
                    helpers.showAlert('Could not submit request. Check connection.', 'danger');
                })
                .finally(() => {
                    submitText.textContent = 'Submit Request';
                    spinner.classList.add('d-none');
                    submitButton.disabled = false;
                });
            });
        }

        if (elements.changePhotoForm) {
            elements.changePhotoForm.addEventListener('submit', function(e) {
                e.preventDefault();
                elements.modals.changePhoto.hide();
                setTimeout(() => elements.modals.photoSuccess.show(), 500);
            });
        }

        if (elements.photoFileInput) {
            elements.photoFileInput.addEventListener('change', function(e) {
                const file = this.files[0];
                if (file) {
                    const validTypes = ['image/jpeg', 'image/png'];
                    if (!validTypes.includes(file.type)) {
                        alert('Only JPG or PNG images are allowed.');
                        this.value = ''; return;
                    }
                    if (file.size > 2 * 1024 * 1024) {
                        alert('File must be less than 2MB.');
                        this.value = ''; return;
                    }
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        elements.imagePreview.src = event.target.result;
                        elements.imagePreview.style.display = 'block';
                        elements.uploadPlaceholder.style.display = 'none';
                        elements.fileInfo.style.display = 'flex';
                        elements.fileNameDisplay.textContent = file.name;
                        elements.fileSizeDisplay.textContent = helpers.formatFileSize(file.size);
                    }
                    reader.readAsDataURL(file);
                }
            });
        }

        if (elements.logoutButton) {
            elements.logoutButton.addEventListener('click', function(e) {
                e.preventDefault();
                window.location.href = 'logout.php';
            });
        }
    };

    // =============
    // Initialization
    // =============
    const init = () => {
        setInterval(helpers.updateClock, 1000);
        helpers.updateClock();
        setupEventListeners();
        helpers.fetchAndRenderLeaveHistory();
    };

    init();
});