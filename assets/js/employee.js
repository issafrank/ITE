document.addEventListener('DOMContentLoaded', function() {
    const leaveView = document.getElementById('leave-view');
    const attendanceView = document.getElementById('attendance-view');
    if (!leaveView && !attendanceView) return;

    const api = {
        attendance: '/Employee/api/employee/get_attendance.php',
        leaveData: '/Employee/api/employee/leave_data.php',
        fileLeave: '/Employee/api/employee/leave.php?action=file_leave'
    };

    const elements = {
        leaveForm: document.getElementById('leaveForm'),
        submitLeaveButton: document.getElementById('submitLeaveButton'),
        leaveHistoryTableBody: document.querySelector('#leave-view .table tbody'),
        leaveCreditsContainer: document.getElementById('leave-credits-container'),
        attendanceTableBody: document.querySelector('#attendance-view .table tbody'),
        monthFilter: document.getElementById('month-filter')
    };

    // Assumes a global showAlert function is available from another script like app.js
    const showAlert = window.showAlert || function(message, type) { alert(`${type}: ${message}`); };

    function renderLeaveCredits(credits) {
        if (!elements.leaveCreditsContainer || !credits) return;
        elements.leaveCreditsContainer.innerHTML = ''; // Clear spinner/old data

        const creditColors = { 'Vacation': '#237ab7', 'Sick': '#ffc107', 'Paternity': '#0dcaf0', 'Maternity': '#0dcaf0', 'Bereavement': '#6c757d' };

        for (const [leaveType, credit] of Object.entries(credits)) {
            const balance = parseFloat(credit.balance);
            const total = parseFloat(credit.total);
            const percentage = total > 0 ? (balance / total) * 100 : 0;
            const creditHTML = `
                <div class="col-md-6 mb-3">
                    <div class="d-flex justify-content-between">
                        <span>${leaveType}</span>
                        <span class="fw-bold">${balance}/${total}</span>
                    </div>
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar" role="progressbar" 
                             style="width: ${percentage}%; background-color: ${creditColors[leaveType] || '#6c757d'};" 
                             aria-valuenow="${balance}" aria-valuemin="0" aria-valuemax="${total}"></div>
                    </div>
                </div>`;
            elements.leaveCreditsContainer.insertAdjacentHTML('beforeend', creditHTML);
        }
    }

    function renderLeaveHistory(history) {
        if (!elements.leaveHistoryTableBody) return;
        elements.leaveHistoryTableBody.innerHTML = '';
        if (!history || history.length === 0) {
            elements.leaveHistoryTableBody.innerHTML = '<tr><td colspan="5" class="text-center">No leave requests found.</td></tr>';
            return;
        }
        history.forEach(request => {
            let statusBadge;
            switch (request.status.toLowerCase()) {
                case 'approved': statusBadge = 'bg-success'; break;
                case 'declined': statusBadge = 'bg-danger'; break;
                default: statusBadge = 'bg-warning text-dark';
            }
            const row = `
                <tr>
                    <td>${request.leave_type}</td>
                    <td>${request.date_from} to ${request.date_to}</td>
                    <td>${request.days}</td>
                    <td><span class="badge ${statusBadge}">${request.status}</span></td>
                    <td>${request.reason}</td>
                </tr>`;
            elements.leaveHistoryTableBody.insertAdjacentHTML('beforeend', row);
        });
    }

    async function renderAttendance() {
        // This function can be filled out similarly if needed for the attendance view
    }

    function setupEmployeeHandlers() {
        if (elements.leaveForm) {
            elements.leaveForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                const submitButton = elements.submitLeaveButton;
                const submitText = submitButton.querySelector('.submit-text');
                
                // Create and add spinner
                const spinner = document.createElement('span');
                spinner.className = 'spinner-border spinner-border-sm me-2';
                submitButton.disabled = true;
                submitButton.prepend(spinner);

                const formData = {
                    leaveTypeId: document.getElementById('leaveTypeModal').value,
                    startDate: document.getElementById('startDateModal').value,
                    endDate: document.getElementById('endDateModal').value,
                    reason: document.getElementById('reasonModal').value,
                };

                try {
                    const response = await fetch(api.fileLeave, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(formData)
                    });
                    const data = await response.json();
                    if (!response.ok) throw new Error(data.message || 'Submission failed');

                    showAlert(data.message, 'success');
                    bootstrap.Modal.getInstance(document.getElementById('leaveRequestModal')).hide();
                    elements.leaveForm.reset();
                    initializeEmployeeViews(); // Refresh credits and history
                } catch (error) {
                    showAlert(error.message, 'danger');
                } finally {
                    submitButton.disabled = false;
                    spinner.remove();
                }
            });
        }
        if (elements.monthFilter) {
            // ... month filter handler logic ...
        }
    }

    async function initializeEmployeeViews() {
        try {
            const response = await fetch(api.leaveData);
            if (!response.ok) throw new Error('Failed to fetch data');
            const data = await response.json();
            
            renderLeaveCredits(data.leave_credits);
            renderLeaveHistory(data.leave_history);
        } catch (error) {
            console.error('Error initializing employee views:', error);
            showAlert('Could not load your leave data.', 'danger');
        }
    }
    
    document.addEventListener('viewChanged', function(e) {
        if (e.detail.viewId === 'leave-view' || e.detail.viewId === 'attendance-view') {
            initializeEmployeeViews();
        }
    });

    // Initial load check
    if ((leaveView && leaveView.classList.contains('active')) || (attendanceView && attendanceView.classList.contains('active'))) {
        initializeEmployeeViews();
        setupEmployeeHandlers();
    }
});