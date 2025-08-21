document.addEventListener('DOMContentLoaded', function() {
    const attendanceView = document.getElementById('attendance-view');
    if (!attendanceView) return; // Exit if the attendance view isn't on the page

    const api = {
        attendance: '/Employee/api/employee/get_attendance.php'
    };

    const elements = {
        attendanceTableBody: document.querySelector('#attendance-view .table tbody'),
        monthFilter: document.getElementById('month-filter')
    };

    const showAlert = window.showAlert || function(message, type) { alert(`${type}: ${message}`); };

    async function renderAttendance(month = null) {
        if (!elements.attendanceTableBody) return;
        const url = month ? `${api.attendance}?month=${month}` : api.attendance;
        
        try {
            const response = await fetch(url);
            if (!response.ok) throw new Error('Network response failed');
            const data = await response.json();

            elements.attendanceTableBody.innerHTML = '';
            if (data.length === 0) {
                elements.attendanceTableBody.innerHTML = '<tr><td colspan="4" class="text-center">No attendance logs found.</td></tr>';
                return;
            }

            data.forEach(log => {
                const statusBadge = log.status === 'On Time' ? 'bg-success' : (log.status === 'Late' ? 'bg-danger' : 'bg-secondary');
                const row = `
                    <tr>
                        <td>${log.log_date}</td>
                        <td>${log.time_in || 'N/A'}</td>
                        <td>${log.time_out || 'N/A'}</td>
                        <td><span class="badge ${statusBadge}">${log.status}</span></td>
                    </tr>`;
                elements.attendanceTableBody.insertAdjacentHTML('beforeend', row);
            });
        } catch (error) {
            console.error('Error fetching attendance:', error);
            showAlert('Error loading attendance.', 'danger');
        }
    }

    function setupAttendanceHandlers() {
        if (elements.monthFilter) {
            elements.monthFilter.addEventListener('change', () => renderAttendance(elements.monthFilter.value));
        }
    }

    document.addEventListener('viewChanged', function(e) {
        if (e.detail.viewId === 'attendance-view') {
            renderAttendance(elements.monthFilter.value);
        }
    });

    if (attendanceView && attendanceView.classList.contains('active')) {
        renderAttendance(elements.monthFilter.value);
        setupAttendanceHandlers();
    }
});