$(function () {

    if ($(window).width() < 768) {
        if ($('.header-bottom-menu-scroll').length > 0) {
            if ($('.header-bottom-menu-scroll ul li').hasClass('active')) {
                var p = $('.header-bottom-menu-scroll ul li.active').offset();
                var scrollAmt = p.left - 40;
                $('.header-bottom-menu-scroll').scrollLeft(scrollAmt);

            }
        }
    }

    /* Show/Hide Sidebar Menu Start */
    $('.menu-collapse-btn').click(function () {
        $('body').addClass('show-sidebar');
    });
    $('.close-sidebar').click(function () {
        $('body').removeClass('show-sidebar');
    });

    $(document).mouseup(function (e) {
        var container = $(".sidenav");

        if (!container.is(e.target) && container.has(e.target).length === 0)
        {
            $('body').removeClass('show-sidebar');
        }
    });
    /* Show/Hide Sidebar Menu End */

    $('.job-btn-group li a').click(function () {
        $(this).parent().addClass('active').siblings().removeClass('active');
    });

    $(".header-collapse").click(function () {
        $(".search-service-list").slideToggle();
    });
    $(".question").click(function(){
        $(".answer").hide();
        $(this).next(".answer").slideDown(500);        
    });  

});
(function ($) {
    $(window).on("load", function () {
        $(".notification-scroll").mCustomScrollbar({
        });
    });     
})(jQuery);

