document.addEventListener('DOMContentLoaded', function() {
    // --- 1. Show 2FA Modal if the PHP flag was passed ---
    if (document.body.dataset.showModal === 'true') {
        const sweetModal = new bootstrap.Modal(document.getElementById('sweetModal'));
        sweetModal.show();
    }

    // --- 2. Add client-side validation for the 6-digit code form ---
    const codeForm = document.getElementById('codeForm');
    if (codeForm) {
        codeForm.addEventListener('submit', function(e) {
            const codeInput = document.getElementById('sweetCode');
            const codeError = document.getElementById('codeError');
            
            // Check if the input is exactly 6 digits
            if (!/^\d{6}$/.test(codeInput.value.trim())) {
                e.preventDefault(); // Stop the form from submitting
                if (codeError) {
                    codeError.textContent = 'Please enter a valid 6-digit code.';
                    codeError.style.display = 'block';
                }
            }
        });
    }
});