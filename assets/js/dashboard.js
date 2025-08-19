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

    let teamMembers = []; // Store data locally for this module

    function renderTeamStatus(type = 'in') {
        const filtered = teamMembers.filter(m => m.status === type);
        elements.membersList.innerHTML = '';

        if (filtered.length === 0) {
            elements.membersList.innerHTML = `<div class="text-center text-muted py-3 small">No members are currently '${type}'.</div>`;
            return;
        }

        filtered.forEach(member => {
            // ... your team status rendering logic
        });
    }

    function setupDashboardHandlers() {
        elements.inOutTabs.addEventListener('click', function(e) {
            if (e.target.tagName === 'BUTTON') {
                elements.inOutTabs.querySelector('.active').classList.remove('active');
                e.target.classList.add('active');
                const activeType = e.target.getAttribute('data-type');
                renderTeamStatus(activeType);
            }
        });
    }

    async function initializeDashboard() {
        try {
            const response = await fetch(api.dashboard);
            if (!response.ok) throw new Error('Failed to fetch dashboard data');
            const data = await response.json();
            
            teamMembers = data.team_members || [];
            elements.dashboardUsername.textContent = data.username || 'Employee';
            
            renderTeamStatus('in');
            setupDashboardHandlers();
        } catch (error) {
            console.error('Error initializing dashboard:', error);
            elements.membersList.innerHTML = `<div class="text-center text-danger py-3 small">Could not load data.</div>`;
        }
    }

    // Listen for the custom event from app.js
    document.addEventListener('viewChanged', function(e) {
        if (e.detail.viewId === 'dashboard-view') {
            initializeDashboard();
        }
    });
});