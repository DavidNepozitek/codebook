/*------------------------------------*\
 #On load
\*------------------------------------*/

var onLoad = function () {
    $(function(){

        if ($('.grido')) {
            $('.grido').grido();
        }

    });

    $(function(){

        //TODO: 

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
                /*autosave: {
                    enabled: true,
                    uniqueId: "addTut"
                },*/
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

    /*$(function () {
        var imgForm = $(".js-imageUpload");
        var tutForm = $(".js-tutorialForm");


        if (imgForm.length > 0 && tutForm.length > 0) {

            var result = [];
            var resultInput = tutForm.find(".js-imageInput");


            imgForm.submit(function (e) {
                var form = $(e.target);


                $(e.target).netteAjax(e).complete(function (e) {
                    form.find(".js-image").each(function (i, image) {
                        var id = $(image).data("image-id");

                        if ($.inArray(id, result) == -1) {
                            result.push(id);
                        }

                    });

                    resultInput.val(JSON.stringify(result));
                });


            });


        }
    });*/

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



