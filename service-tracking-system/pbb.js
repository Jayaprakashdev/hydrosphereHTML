// ==========================
// Prevent browser back navigation
// ==========================
(function() {
    if (window.history && window.history.pushState) {
        window.history.pushState('forward', null, window.location.href);
        window.onpopstate = function() {
            window.history.go(1); // forces forward if back button pressed
        };
    }
})();