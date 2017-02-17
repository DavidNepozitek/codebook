$(function(){
    var nav = $(".main-navigation");

    function handleCollapsed() {
        $(".main-navigation__subitem--active").parent(".main-navigation__submenu").css("display", "block");
        $(".main-navigation__subitem--active").parents(".main-navigation__item").removeClass("collapsed");
    }

    if (nav.length > 0) {

        $(document).on("click", ".main-navigation__item--sub > a",function (e) {

            var thisSub =  $(e.target).parent().find(".main-navigation__submenu");
            var thisParent = $(e.target).parent();

            thisSub.slideToggle();

            if (thisParent.hasClass("collapsed")) {
                thisParent.removeClass("collapsed");
            } else {
                thisParent.addClass("collapsed");
            }
        });

        $(document).on("click", ".js-ajax-menu", function (e) {

            $(this).netteAjax(e);
        });

        $.nette.ext({
            success: function () {
                nav.find(".main-navigation__item").each(function (i, element) {
                    $(element).removeClass("main-navigation__item--active");
                });

                nav.find(".main-navigation__subitem").each(function (i, element) {
                    $(element).removeClass("main-navigation__subitem--active");
                });

                nav.find("a").each(function (i, element) {
                    var path = window.location.pathname;
                    var href = $(element).attr("href");

                    if (path.indexOf(href) === 0) {

                        if(href.match(/admin\/$/)) {
                            if (path !== href) {
                                return true;
                            }
                        }

                        $(element).closest(".main-navigation__item").addClass("main-navigation__item--active");
                        $(element).closest(".main-navigation__subitem").addClass("main-navigation__subitem--active");
                    }
                });

                handleCollapsed();
            }
        });

        handleCollapsed();

    }



    $(window).on("load resize", function () {

            if(
                ($(".main-navigation").height() +
                $(".nav-bottom").height())
                >
                $(".navigation__background").height()) {


                $(".nav-bottom").css("position", "relative");
            }

            else{
                $(".nav-bottom").css("position", "absolute");
            }
    });
});