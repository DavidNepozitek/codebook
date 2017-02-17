$(function () {

    function init() {
        list.find(".js-motto").each(function (i, element) {
            $(element).attr("data-motto-index", i);
            lastIndex = i;
        })
    }

    function rotate() {
        var active = list.find(".motto__bottom--active");
        var activeIndex = active.data("motto-index");
        var nextIndex;

        if (activeIndex === lastIndex) {
            nextIndex = 0;
        } else {
            nextIndex = activeIndex + 1;
        }

        list.find(".motto__bottom--last").removeClass("motto__bottom--last");

        active.addClass("motto__bottom--last");
        active.removeClass("motto__bottom--active");

        list.find(".js-motto[data-motto-index=" + nextIndex + "]").addClass("motto__bottom--active");
    }

    function startRotation() {
        setTimeout(function () {
            rotate();
            startRotation();
        }, timeout);
    }

   if($(".js-mottoList").length > 0) {

       var list = $(".js-mottoList");

       var lastIndex;

       var timeout = 4500;

       init();

       startRotation();
   }
});