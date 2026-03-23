
document.querySelectorAll('.dropdown-toggle').forEach(function (toggle) {
    toggle.addEventListener('mouseover', function (e) {
        e.preventDefault();
        var dropdownMenu = this.nextElementSibling;
        if (dropdownMenu && dropdownMenu.classList.contains('dropdown-menu')) {
            dropdownMenu.classList.toggle('show');
        }
    });
});

function setActiveMenu() {
    const currentPath = location.pathname.replace(/\/$/, "") || "/";

    // ✅ Handle all nav links including dropdown items
    document.querySelectorAll(".nav-link, .dropdown-item").forEach(link => {
        let linkPath = link.getAttribute("href");
        if (!linkPath) return;

        linkPath = linkPath.replace(/\/$/, "") || "/";

        if (linkPath === currentPath) {
            link.classList.add("active");

            // ✅ If active link is inside a dropdown, also highlight the parent toggle
            const parentDropdown = link.closest(".dropdown");
            if (parentDropdown) {
                parentDropdown.querySelector(".dropdown-toggle").classList.add("active");
            }
        }
    });
}

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
