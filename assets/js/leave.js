document.addEventListener('DOMContentLoaded', function() {
    const leaveView = document.getElementById('leave-view');
    if (!leaveView) return;

    const api = {
        leaveData: '/Employee/api/employee/leave_data.php',
        fileLeave: '/Employee/api/employee/leave.php?action=file_leave'
    };

    const elements = {
        leaveForm: document.getElementById('leaveForm'),
        submitLeaveButton: document.getElementById('submitLeaveButton'),
        leaveHistoryTableBody: document.querySelector('#leave-view .table tbody')
    };

    const showAlert = window.showAlert || function(message, type) { alert(`${type}: ${message}`); };
    const successModal = new bootstrap.Modal(document.getElementById('successModal'));

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

    async function initializeLeaveView() {
        try {
            // We only fetch history, as credits are now loaded by PHP.
            const response = await fetch(api.leaveData);
            if (!response.ok) throw new Error('Failed to fetch leave data');
            const data = await response.json();
            
            renderLeaveHistory(data.leave_history);
        } catch (error) {
            console.error('Error initializing leave view:', error);
            showAlert('Could not load your leave history.', 'danger');
        }
    }

    function setupLeaveHandlers() {
        if (elements.leaveForm) {
            elements.leaveForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                const submitButton = elements.submitLeaveButton;
                const submitText = submitButton.querySelector('.submit-text');
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

                    bootstrap.Modal.getInstance(document.getElementById('leaveRequestModal')).hide();
                    successModal.show();
                    elements.leaveForm.reset();
                    
                    // Reload the page to show the updated credits and history
                    successModal._element.addEventListener('hidden.bs.modal', function () {
                        location.reload();
                    });

                } catch (error) {
                    showAlert(error.message, 'danger');
                } finally {
                    submitButton.disabled = false;
                    spinner.remove();
                }
            });
        }
    }
    
    document.addEventListener('viewChanged', function(e) {
        if (e.detail.viewId === 'leave-view') {
            initializeLeaveView();
        }
    });

    if (leaveView.classList.contains('active')) {
        initializeLeaveView();
        setupLeaveHandlers();
    }
});