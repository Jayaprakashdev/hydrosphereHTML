window.addEventListener("pageshow", function(event) {
    if (event.persisted) {
        document.forms[0].reset();
    }
});

document.addEventListener("DOMContentLoaded", function() {
    const form = document.querySelector("form");

    form.addEventListener("submit", function(e) {
        // Run existing validation first
        if(!validateForm()) {
            e.preventDefault();
            return false;
        }

        // Disable button to prevent multiple clicks
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerText = "Saving...";
    });
});