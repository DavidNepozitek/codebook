if ($(".js-filter")) {

    $(document).on("change", ".js-filter select, .js-filter input", function (e) {
        $(".js-filter").find("input[type=submit]").click();
    });


    $.nette.ext({
        start: function () {
            $(".filter__result *").css("opacity", 0);
            $(".filter__loader").css({"display" : "block", "opacity" : "0.8"});
        },
        success: function () {
            loader.end();
        }
    });
}


