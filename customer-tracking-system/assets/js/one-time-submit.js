document.addEventListener("DOMContentLoaded", function () {

    const form = document.querySelector("form");
    const btn = document.getElementById("submitBtn");

    if (!form || !btn) return;

    form.addEventListener("submit", function (e) {

        // Prevent double click
        if (btn.disabled) {
            e.preventDefault();
            return false;
        }

        // Disable button
        btn.disabled = true;

        // Save original text
        const originalText = btn.innerText;

        // Show loading
        btn.innerText = "Saving... Please wait";

        // Optional: restore if something fails (rare case)
        setTimeout(() => {
            if (btn.disabled) {
                btn.disabled = false;
                btn.innerText = originalText;
            }
        }, 10000); // 10 sec fallback
    });

});