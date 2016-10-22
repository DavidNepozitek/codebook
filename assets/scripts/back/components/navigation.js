$(function(){

    if ($(".main-navigation").length > 0) {
        var withSub =  $(".main-navigation__item--sub");

        $(".main-navigation__subitem--active").parent(".main-navigation__submenu").css("display", "block");
        $(".main-navigation__subitem--active").parents(".main-navigation__item").removeClass("collapsed");


        withSub.children("a").click(function (e) {
            var thisSub =  $(e.target).parent().find(".main-navigation__submenu");
            var thisParent = $(e.target).parent();

           thisSub.slideToggle();

            if (thisParent.hasClass("collapsed")) {
                thisParent.removeClass("collapsed");
            } else {
                thisParent.addClass("collapsed");
            }
        });
        
    }

});