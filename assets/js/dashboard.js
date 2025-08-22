document.addEventListener('DOMContentLoaded', function() {
    const dashboardView = document.getElementById('dashboard-view');
    if (!dashboardView) return;

    const api = {
        dashboard: '/Employee/api/employee/dashboard.php'
    };

    const elements = {
        dashboardUsername: document.getElementById('dashboard-username'),
        membersList: document.getElementById('membersList'),
        inOutTabs: document.getElementById('inOutTabs')
    };

    let teamMembers = [];

    function renderTeamStatus(type = 'in') {
        const filtered = teamMembers.filter(m => m.status === type);
        elements.membersList.innerHTML = '';

        if (filtered.length === 0) {
            elements.membersList.innerHTML = `<div class="text-center text-muted py-3 small">No members are currently '${type}'.</div>`;
            return;
        }

        filtered.forEach(member => {
            const timeHTML = member.time ? `<div class="text-muted small">${member.status === 'in' ? 'In' : 'Out'}: ${member.time}</div>` : '';
            elements.membersList.innerHTML += `
                <div class="d-flex align-items-center mb-3">
                    <img src="${member.img}" class="rounded-circle me-3" width="44" height="44">
                    <div>
                        <div class="fw-semibold">${member.name}</div>
                        ${timeHTML}
                    </div>
                </div>`;
        });
    }

    function setupDashboardHandlers() {
        if (elements.inOutTabs) {
            elements.inOutTabs.addEventListener('click', function(e) {
                if (e.target.tagName === 'BUTTON') {
                    elements.inOutTabs.querySelector('.active').classList.remove('active');
                    e.target.classList.add('active');
                    const activeType = e.target.getAttribute('data-type');
                    renderTeamStatus(activeType);
                }
            });
        }
    }

    async function initializeDashboard() {
        try {
            const response = await fetch(api.dashboard);
            if (!response.ok) throw new Error('Failed to fetch dashboard data');
            const data = await response.json();
            
            teamMembers = data.team_members || [];
            if (elements.dashboardUsername) elements.dashboardUsername.textContent = data.username || 'Employee';
            
            renderTeamStatus('in');
            setupDashboardHandlers();
        } catch (error) {
            console.error('Error initializing dashboard:', error);
            if (elements.membersList) elements.membersList.innerHTML = `<div class="text-center text-danger py-3 small">Could not load data.</div>`;
        }
    }

    document.addEventListener('viewChanged', function(e) {
        if (e.detail.viewId === 'dashboard-view') {
            initializeDashboard();
        }
    });
});