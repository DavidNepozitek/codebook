/*------------------------------------*\
 #Markdown editor settings
\*------------------------------------*/

$.nette.ext({
    load: function () {

        $(function(){
            
            if ($('.grido')) {
                $('.grido').grido();
            }

        });
        
        $(function(){

            //TODO: content on error

            if ($("#frm-tutorialForm-form-source").length > 0) {

                var simplemde = new SimpleMDE({
                    element: $("#frm-tutorialForm-form-source")[0] ,
                    spellChecker: false,
                    renderingConfig: {
                        codeSyntaxHighlighting: true
                    },
                    showIcons: [
                        "code",
                        "table"
                    ]
                });
            }

        });

        $(function(){

            if ($(".tags").length > 0) {


                $(".tags").makeTags();
            }

        });

        $(function () {
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


                $(document).on("click", ".js-removeImage", function (e) {

                    $(this).netteAjax(e).done(function () {
                        //$(e.target).closest(".js-image").remove();
                    });
                });


            }
        });
    }
});




