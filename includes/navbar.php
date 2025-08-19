<nav class="top-navbar shadow-sm">
  <div class="container-fluid d-flex justify-content-between align-items-center px-3">
    <div class="d-flex align-items-center">
      <button class="btn btn-link text-white d-lg-none me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobile-sidebar">
        <i class="bi bi-list fs-3"></i>
      </button>
      <div class="brand d-flex align-items-center">
        <img src="assets/images/logo.png" alt="Nordlich Pharma Logo" style="height:50px;">
      </div>
    </div>

    <div class="d-flex align-items-center">
      <div id="clock" class="navbar-clock me-3 d-none d-md-block"></div>

      <div class="dropdown me-3">
        <a href="#" class="text-white position-relative" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bi bi-bell fs-5"></i>
          <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6em;">
            1
            <span class="visually-hidden">unread messages</span>
          </span>
        </a>

        <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="min-width: 350px; max-height: 400px; overflow-y: auto;">
          <li class="px-3 py-2">
            <h6 class="mb-0">Notifications</h6>
          </li>
          <li><hr class="dropdown-divider"></li>
          
          <li>
            <a href="#" class="dropdown-item">
              <div class="d-flex w-100 justify-content-between">
                <h5 class="mb-1 fs-6">Leave Request Approved</h5>
                <small class="text-muted">1 day ago</small>
              </div>
              <p class="mb-1 small">Your sick leave for Aug 06 has been approved.</p>
            </a>
          </li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item text-center text-muted small" href="#">View all notifications</a></li>
        </ul>
      </div>
      <div class="dropdown">
        <div class="profile dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown" style="cursor:pointer;">
          <img id="navbarProfileImg" src="https://placehold.co/40x40/e1f0e5/237ab7?text=E" alt="profile" class="profile-img me-2">
          <span class="profile-name fw-bold d-none d-sm-inline">Employee</span>
        </div>
        <ul class="dropdown-menu dropdown-menu-end">
          <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#profileModal"><i class="bi bi-person"></i> Profile</a></li>
          <li><a class="dropdown-item" href="#"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
        </ul>
      </div>
    </div>
  </div>
</nav>