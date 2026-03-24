
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


