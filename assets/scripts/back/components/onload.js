/*------------------------------------*\
 #On load
\*------------------------------------*/

var onLoad = function () {

    $(function(){

        if ($(".grido").length > 0 && $(".grido").data("loaded") !== "true") {

            $(".grido").grido();

            $(".grido").data("loaded", "true");
        }

    });

    $(function(){

        if ($(".js-editor-add").length > 0 && $(".js-editor").data("loaded") !== "true") {

            var simplemde = new SimpleMDE({
                element: $(".js-editor-add")[0] ,
                spellChecker: false,
                renderingConfig: {
                    codeSyntaxHighlighting: true
                },
                showIcons: [
                    "code",
                    "table"
                ],
                forceSync: true
            });

            $(".js-editor").data("loaded", "true");


        } else if ($(".js-editor").length > 0 && $(".js-editor").data("loaded") !== "true") {

            var simplemde = new SimpleMDE({
                element: $(".js-editor")[0] ,
                spellChecker: false,
                renderingConfig: {
                    codeSyntaxHighlighting: true
                },
                showIcons: [
                    "code",
                    "table"
                ],
                forceSync: true
            });

            $(".js-editor").data("loaded", "true");
        }

    });

    $(function(){
        var tags = $(".tags");

        if (tags.length > 0 && tags.data("loaded") !== "true") {
            tags.makeTags();
            tags.data("loaded", "true");
        }

    });

    $(".js-tutorialForm").netteFormValidate();
    $(".js-passwordChange").netteFormValidate();

    /** Input image ids to TutorialForm */

    $(function () {
        if($(".js-imageList")) {
            var images = [];

            $(".js-imageList").find(".js-image").each(function (i, element) {
                var id = $(element).data("image-id");
                images.push(id);
            });

            $(".js-imagesJSON").val(JSON.stringify(images));
        }
    });
};


$.nette.ext({
    load: function () {
        onLoad();
    }
});


$(window).on("load", function () {
    onLoad();
});



