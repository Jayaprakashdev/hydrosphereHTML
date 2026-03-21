$('.dropdown-toggle').on('click', function (e) {
    e.preventDefault();
    var $dropdownMenu = $(this).siblings('.dropdown-menu');
    $dropdownMenu.toggleClass('show');
});



