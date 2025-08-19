document.addEventListener('DOMContentLoaded', function() {
    // ======================
    // DOM Element References
    // ======================
    const elements = {
        // Navigation elements
        navLinks: document.querySelectorAll('.sidebar .nav-link, .offcanvas .nav-link'),
        views: document.querySelectorAll('.main-content .view'),
        mobileSidebar: document.getElementById('mobile-sidebar'),
        
        // Forms
        leaveForm: document.getElementById('leaveForm'),
        changePhotoForm: document.getElementById('changePhotoForm'),
        
        // Profile Image Elements
        photoFileInput: document.getElementById('photoFile'),
        imagePreview: document.getElementById('imagePreview'),
        uploadPlaceholder: document.getElementById('uploadPlaceholder'),
        fileInfo: document.getElementById('fileInfo'),
        fileNameDisplay: document.getElementById('fileNameDisplay'),
        fileSizeDisplay: document.getElementById('fileSizeDisplay'),
        navbarProfileImg: document.getElementById('navbarProfileImg'),
        
        // Modals
        modals: {
            leaveRequest: new bootstrap.Modal(document.getElementById('leaveRequestModal')),
            success: new bootstrap.Modal(document.getElementById('successModal')),
            changePhoto: new bootstrap.Modal(document.getElementById('changePhotoModal')),
            photoSuccess: new bootstrap.Modal(document.getElementById('photoSuccessModal')),
            profile: new bootstrap.Modal(document.getElementById('profileModal'))
        },
        
        // Clock
        clock: document.getElementById('clock'),
        
        // Logout
        logoutButton: document.getElementById('logoutButton')
    };

    // =================
    // Helper Functions
    // =================
    const helpers = {
        formatFileSize: (bytes) => {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        },
        updateClock: () => {
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-US', { 
                hour: 'numeric', 
                minute: 'numeric', 
                second: 'numeric', 
                hour12: true 
            });
            const dateString = now.toLocaleDateString('en-US', { 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            });
            if (elements.clock) {
                elements.clock.innerHTML = `${dateString} · ${timeString}`;
            }
        },
        updateProfileImages: (imageUrl) => {
            const timestamp = '?t=' + new Date().getTime();
            if (elements.navbarProfileImg) {
                elements.navbarProfileImg.src = imageUrl + timestamp;
            }
            const profileModalImg = document.querySelector('#profileModal .img-thumbnail');
            if (profileModalImg) {
                profileModalImg.src = imageUrl + timestamp;
            }
        }
    };

    // ================
    // Event Listeners
    // ================
    const setupEventListeners = () => {
        // Navigation
        elements.navLinks.forEach(link => {
            if (link.getAttribute('data-bs-toggle') === 'modal') return;
            
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('data-bs-target');
                if (!targetId) return;

                // Update active state for all nav links
                elements.navLinks.forEach(l => l.classList.remove('active'));
                document.querySelectorAll(`.nav-link[data-bs-target="${targetId}"]`).forEach(activeLink => activeLink.classList.add('active'));

                // Show the correct view
                elements.views.forEach(v => v.classList.remove('active'));
                const targetView = document.querySelector(targetId);
                if (targetView) targetView.classList.add('active');
                
                // Close mobile sidebar if open
                const mobileSidebarInstance = bootstrap.Offcanvas.getInstance(elements.mobileSidebar);
                if (mobileSidebarInstance) mobileSidebarInstance.hide();
            });
        });

        // Photo Upload Form (NO backend, just preview)
        if (elements.changePhotoForm) {
            elements.changePhotoForm.addEventListener('submit', function(e) {
                e.preventDefault();
                // No backend handler, just simulate upload success modal
                elements.modals.changePhoto.hide();
                setTimeout(() => {
                    elements.modals.photoSuccess.show();
                }, 500);
            });
        }

        // File Input Preview
        if (elements.photoFileInput) {
            elements.photoFileInput.addEventListener('change', function(e) {
                const file = this.files[0];
                if (file) {
                    const validTypes = ['image/jpeg', 'image/png'];
                    if (!validTypes.includes(file.type)) {
                        alert('Only JPG or PNG images are allowed.');
                        this.value = ''; return;
                    }
                    if (file.size > 2 * 1024 * 1024) {
                        alert('File must be less than 2MB.');
                        this.value = ''; return;
                    }

                    const reader = new FileReader();
                    reader.onload = function(event) {
                        elements.imagePreview.src = event.target.result;
                        elements.imagePreview.style.display = 'block';
                        elements.uploadPlaceholder.style.display = 'none';
                        elements.fileInfo.style.display = 'flex';
                        elements.fileNameDisplay.textContent = file.name;
                        elements.fileSizeDisplay.textContent = helpers.formatFileSize(file.size);
                    }
                    reader.readAsDataURL(file);
                } else {
                    elements.imagePreview.style.display = 'none';
                    elements.uploadPlaceholder.style.display = 'flex';
                    elements.fileInfo.style.display = 'none';
                }
            });
        }

        // Logout
        if (elements.logoutButton) {
            elements.logoutButton.addEventListener('click', function(e) {
                e.preventDefault();
                window.location.href = 'logout.php';
            });
        }
    };

    // =============
    // Initialization
    // =============
    const init = () => {
        // Start clock
        setInterval(helpers.updateClock, 1000);
        helpers.updateClock();
        
        // Set up event listeners
        setupEventListeners();
    };

    // Start the application
    init();
});