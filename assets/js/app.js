// assets/js/app.js
document.addEventListener('DOMContentLoaded', () => {
  // Kunin lahat ng navigation links sa sidebar (desktop at mobile)
  const navLinks = document.querySelectorAll('.sidebar .nav-link, .offcanvas .nav-link');
  const views = document.querySelectorAll('.view');

  // Helper: dispatch the viewChanged event with the view id (without '#')
  function dispatchViewChanged(viewId) {
    if (!viewId) return;
    const id = viewId.startsWith('#') ? viewId.slice(1) : viewId;
    // console.debug for easier debugging
    // (Remove or change to proper logger in production)
    console.debug('Dispatching viewChanged for:', id);
    document.dispatchEvent(new CustomEvent('viewChanged', { detail: { viewId: id } }));
  }

  // Hide mobile offcanvas if open (helper)
  function closeMobileSidebarIfOpen() {
    const offcanvasEl = document.getElementById('mobile-sidebar');
    if (!offcanvasEl) return;
    const bsOffcanvas = bootstrap.Offcanvas.getInstance(offcanvasEl);
    if (bsOffcanvas) bsOffcanvas.hide();
  }

  // Mag-attach ng click event sa bawat navigation link
  navLinks.forEach(link => {
    link.addEventListener('click', function(event) {
      // Kung ang link ay para mag-toggle ng modal, hayaan ang default behavior
      if (this.getAttribute('data-bs-toggle') === 'modal') {
        return;
      }

      // Pigilan ang default na behavior ng link (pag-navigate sa '#')
      event.preventDefault();

      // Kunin ang target ID mula sa data-bs-target attribute ng link
      const targetId = this.getAttribute('data-bs-target');
      if (!targetId) return; // Huwag gawin kung walang target

      // 1. Alisin ang 'active' class sa LAHAT ng links
      navLinks.forEach(nav => nav.classList.remove('active'));

      // 2. Alisin ang 'active' class sa LAHAT ng views
      views.forEach(view => view.classList.remove('active'));

      // 3. Idagdag ang 'active' class sa link na KINI-CLICK
      this.classList.add('active');

      // 4. Hanapin ang target view at idagdag ang 'active' class para ipakita ito
      const targetView = document.querySelector(targetId);
      if (targetView) {
        targetView.classList.add('active');
      }

      // Close mobile sidebar if open (better UX)
      closeMobileSidebarIfOpen();

      // 5. Dispatch custom event so other scripts (leave.js, employee.js) know view changed
      dispatchViewChanged(targetId);
    });
  });

  // On initial load: find active view and dispatch event so page-initialized scripts run
  const initialActive = Array.from(views).find(v => v.classList.contains('active'));
  if (initialActive) {
    // Use setTimeout 0 to let other scripts finish their DOMContentLoaded handlers
    setTimeout(() => {
      dispatchViewChanged('#' + initialActive.id);
    }, 0);
  }
});