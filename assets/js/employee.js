document.addEventListener('DOMContentLoaded', function() {
    const leaveView = document.getElementById('leave-view');
    const attendanceView = document.getElementById('attendance-view');
    if (!leaveView && !attendanceView) return;

    const api = {
        attendance: '/Employee/api/employee/get_attendance.php',
        leaveHistory: '/Employee/api/employee/leave.php?action=get_leave_history',
        fileLeave: '/Employee/api/employee/leave.php?action=file_leave'
    };

    const elements = {
        leaveForm: document.getElementById('leaveForm'),
        submitLeaveButton: document.getElementById('submitLeaveButton'),
        leaveHistoryTableBody: document.querySelector('#leave-view .table tbody'),
        attendanceTableBody: document.querySelector('#attendance-view .table tbody'),
        monthFilter: document.getElementById('month-filter')
    };

    async function renderLeaveHistory() {
        // ... your fetch and render logic for leave history
    }

    async function renderAttendance() {
        // ... your fetch and render logic for attendance
    }

    function setupEmployeeHandlers() {
        if (elements.leaveForm) {
            elements.leaveForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                // ... your leave form submission logic
            });
        }

        if (elements.monthFilter) {
            elements.monthFilter.addEventListener('change', renderAttendance);
        }
    }

    function initializeEmployeeViews() {
        renderLeaveHistory();
        renderAttendance();
        setupEmployeeHandlers();
    }
    
    // Listen for the custom event from app.js to initialize when needed
    document.addEventListener('viewChanged', function(e) {
        if (e.detail.viewId === 'leave-view' || e.detail.viewId === 'attendance-view') {
            // You might want to re-fetch data every time the view is shown
            initializeEmployeeViews(); 
        }
    });

    // Also run once on initial load if one of these views is active
    if ( (leaveView && leaveView.classList.contains('active')) || (attendanceView && attendanceView.classList.contains('active')) ) {
        initializeEmployeeViews();
    }
});