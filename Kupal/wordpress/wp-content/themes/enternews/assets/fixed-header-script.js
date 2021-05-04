jQuery(document).ready(function ($) {
    var lastScrollTop = 0;
    $(window).scroll(function (event) {
        var current_pos = $(this).scrollTop();
        var header_height = $('#masthead').outerHeight();

        if (current_pos > lastScrollTop) {
            $('#main-navigation-bar').removeClass('nav-down');
            // downscroll code
            if (lastScrollTop > current_pos) {

                if (current_pos <= header_height - 45) {
                    $('#main-navigation-bar').removeClass('nav-down');
                    $('#main-navigation-bar').addClass('nav-up');
                }

            }
        } else {

            if (current_pos <= header_height - 45) {
                $('#main-navigation-bar').removeClass('aft-sticky-navigation nav-down');

            } else {
                $('#main-navigation-bar').addClass('aft-sticky-navigation nav-down');
                $('#main-navigation-bar').removeClass('nav-up');
            }

        }
        lastScrollTop = current_pos;
    });

});
