/*------------------------------------*\
 #On load
\*------------------------------------*/

var onLoad = function () {
    //TODO: Check if loaded

    $(function(){

        if ($('.grido')) {
            $('.grido').grido();
        }

    });

    $(function(){

        if ($(".js-editor-add").length > 0) {

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

        } else if ($(".js-editor").length > 0) {
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
        }

    });

    $(function(){

        if ($(".tags").length > 0) {


            $(".tags").makeTags();
        }

    });

    //TODO: delete if not used
};


$.nette.ext({
    load: function () {
        onLoad();
    }
});


$(window).on("load", function () {
    onLoad();
});



