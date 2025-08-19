document.addEventListener('DOMContentLoaded', function() {
    // ======================
    // Shared Elements & Helpers
    // ======================
    const elements = {
        navLinks: document.querySelectorAll('.sidebar .nav-link, .offcanvas .nav-link'),
        views: document.querySelectorAll('.main-content .view'),
        mobileSidebar: document.getElementById('mobile-sidebar'),
        clock: document.getElementById('clock'),
        logoutButton: document.getElementById('logoutButton')
    };

    const helpers = {
        updateClock: () => {
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-US', { hour: 'numeric', minute: 'numeric', second: 'numeric', hour12: true });
            const dateString = now.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
            if (elements.clock) {
                elements.clock.innerHTML = `${dateString} · ${timeString}`;
            }
        },
        showAlert: (message, type = 'info') => {
            // Your showAlert function code here...
        }
    };

    // =================
    // Event Listeners
    // =================
    function setupEventListeners() {
        // Main Navigation Logic
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
                if (targetView) {
                    targetView.classList.add('active');
                    // Dispatch a custom event to notify modules that the view has changed
                    document.dispatchEvent(new CustomEvent('viewChanged', { detail: { viewId: targetView.id } }));
                }

                const mobileSidebarInstance = bootstrap.Offcanvas.getInstance(elements.mobileSidebar);
                if (mobileSidebarInstance) mobileSidebarInstance.hide();
            });
        });
        
        // ... other global event listeners like logout can go here
    }

    // =============
    // Initialization
    // =============
    function init() {
        setInterval(helpers.updateClock, 1000);
        helpers.updateClock();
        setupEventListeners();

        // Manually trigger the event for the initially active view
        const activeView = document.querySelector('.view.active');
        if (activeView) {
            document.dispatchEvent(new CustomEvent('viewChanged', { detail: { viewId: activeView.id } }));
        }
    }

    init();
});