// ✅ Show form and smooth scroll on button click
document.querySelector('.scroll-to-form').addEventListener('click', function (e) {
    e.preventDefault();
    const formSection = document.getElementById('waterTestForm');
    formSection.classList.remove('d-none');
    setTimeout(function () {
        formSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }, 100);
});

// ✅ Form validation on submit
document.getElementById('waterTestFormFields').addEventListener('submit', function (e) {
    e.preventDefault();
    if (!this.checkValidity()) {
        e.stopPropagation();
        this.classList.add('was-validated');
        return;
    }

    // ✅ Success - you can replace this with a fetch/AJAX call to your PHP backend
    this.innerHTML = `
        <div class="text-center py-4">
            <i class="fa-solid fa-circle-check text-success" style="font-size: 3rem;"></i>
            <h5 class="mt-3 fw-bold">Request Submitted!</h5>
            <p class="text-muted">Thank you! Our team will contact you soon for the free water test.</p>
        </div>
    `;
});


// Contact Input Validation
document.getElementById('contactNumber').addEventListener('input', function () {
    this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);
    clearError(this);
});
