$(function(){

    if ($(".main-navigation").length > 0) {

        function handleCollapsed() {
            $(".main-navigation__subitem--active").parent(".main-navigation__submenu").css("display", "block");
            $(".main-navigation__subitem--active").parents(".main-navigation__item").removeClass("collapsed");
        }

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

            $(this).netteAjax(e).done(function () {
                handleCollapsed();
            });

        });

        handleCollapsed();
        
    }

});