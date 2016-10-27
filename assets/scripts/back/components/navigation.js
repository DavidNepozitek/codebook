$(function(){
    var nav = $(".main-navigation");

    if (nav.length > 0) {

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
                
                nav.find(".main-navigation__item").each(function (i, element) {
                    $(element).removeClass("main-navigation__item--active");
                });

                nav.find(".main-navigation__subitem").each(function (i, element) {
                    $(element).removeClass("main-navigation__subitem--active");
                });
                
                $(e.target).closest(".main-navigation__subitem").addClass("main-navigation__subitem--active");
                $(e.target).closest(".main-navigation__item").addClass("main-navigation__item--active");

                console.log($(e.target).closest(".main-navigation__item"));
                
                handleCollapsed();
            });

        });

        handleCollapsed();
        
    }

});