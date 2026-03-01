
document.addEventListener("DOMContentLoaded", () => {
    loadPartial("header", "assets/component/header.html", setActiveMenu);
    loadPartial("footer", "assets/component/footer.html");
});

function loadPartial(id, file, callback) {
    const el = document.getElementById(id);
    if (!el) return;

    fetch(file)
        .then(res => res.text())
        .then(html => {
            el.innerHTML = html;
            if (callback) callback();
        })
        .catch(err => console.error(`${id} load error:`, err));
}

function setActiveMenu() {
    const currentPath = location.pathname.replace(/\/$/, "") || "/";

    document.querySelectorAll(".nav-link").forEach(link => {
        let linkPath = link.getAttribute("href");
        if (!linkPath) return;

        linkPath = linkPath.replace(/\/$/, "") || "/";

        if (linkPath === currentPath) {
            link.classList.add("active");
        }
    });
}

function setYear() {
    const y = document.getElementById("year");
    if (y) y.textContent = new Date().getFullYear();
}

document.addEventListener("DOMContentLoaded", () => {
    loadPartial("header", "assets/component/header.html", setActiveMenu);
    loadPartial("footer", "assets/component/footer.html", setYear);
});