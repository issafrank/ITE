document.addEventListener('DOMContentLoaded', function() {
    const leaveView = document.getElementById('leave-view');
    if (!leaveView) return;

    // API endpoint now points to the new unified file
    const api = {
        leave: '/Employee/api/employee/leave-api.php' 
    };

    const elements = {
        leaveForm: document.getElementById('leaveForm'),
        submitLeaveButton: document.getElementById('submitLeaveButton'),
        leaveHistoryTableBody: document.querySelector('#leave-view .table tbody'),
        leaveCreditsContainer: document.getElementById('leave-credits-container')
    };

    // Make sure a success modal with this ID exists in your modals file
    const successModal = new bootstrap.Modal(document.getElementById('successModal')); 

    function renderLeaveCredits(credits) {
        if (!elements.leaveCreditsContainer || !credits) return;
        elements.leaveCreditsContainer.innerHTML = ''; // Clear previous content

        const creditColors = { 'Vacation': '#237ab7', 'Sick': '#ffc107', 'Paternity': '#0dcaf0', 'Maternity': '#0dcaf0', 'Bereavement': '#6c757d' };
        const displayOrder = ['Vacation', 'Sick', 'Paternity', 'Maternity', 'Bereavement'];

        displayOrder.forEach(leaveType => {
            if (credits[leaveType]) {
                const credit = credits[leaveType];
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
        });
        
        // Add AWOL/Suspended section
        elements.leaveCreditsContainer.insertAdjacentHTML('beforeend', `
            <div class="col-md-6 mb-3 d-flex align-items-center">
                <div class="row w-100">
                    <div class="col-6"><p class="mb-0"><span class="fw-bold">AWOL:</span> 0 days</p></div>
                    <div class="col-6"><p class="mb-0"><span class="fw-bold">Suspended:</span> 0 days</p></div>
                </div>
            </div>
        `);
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

    async function initializeLeaveView() {
        try {
            // Fetch data from the API with the 'get_data' action
            const response = await fetch(`${api.leave}?action=get_data`);
            if (!response.ok) throw new Error('Failed to fetch leave data');
            const data = await response.json();
            
            renderLeaveCredits(data.leave_credits); 
            renderLeaveHistory(data.leave_history);
        } catch (error) {
            console.error('Error initializing leave view:', error);
            alert('Could not load your leave data.');
        }
    }

    function setupLeaveHandlers() {
        if (elements.leaveForm) {
            elements.leaveForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                const submitButton = elements.submitLeaveButton;
                submitButton.disabled = true;
                submitButton.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Submitting...`;

                const formData = {
                    leaveTypeId: document.getElementById('leaveTypeModal').value,
                    startDate: document.getElementById('startDateModal').value,
                    endDate: document.getElementById('endDateModal').value,
                    reason: document.getElementById('reasonModal').value,
                };

                try {
                    // Post data to the API with the 'file_leave' action
                    const response = await fetch(`${api.leave}?action=file_leave`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(formData)
                    });
                    const result = await response.json();
                    if (!response.ok || !result.success) throw new Error(result.message || 'Submission failed');

                    bootstrap.Modal.getInstance(document.getElementById('leaveRequestModal')).hide();
                    successModal.show();
                    elements.leaveForm.reset();
                    
                    // Refresh data after success modal is closed
                    document.getElementById('successModal').addEventListener('hidden.bs.modal', initializeLeaveView, { once: true });

                } catch (error) {
                    alert(error.message);
                } finally {
                    submitButton.disabled = false;
                    submitButton.innerHTML = 'Submit Request';
                }
            });
        }
    }
    
    // Custom event listener to trigger data loading when view becomes active
    document.addEventListener('viewChanged', function(e) {
        if (e.detail.viewId === 'leave-view') {
            initializeLeaveView();
        }
    });

    // Initial load if the view is already active on page load
    if (leaveView && leaveView.classList.contains('active')) {
        initializeLeaveView();
        setupLeaveHandlers();
    }
});
