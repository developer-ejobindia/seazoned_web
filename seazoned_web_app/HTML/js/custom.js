$(function () {

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

});
