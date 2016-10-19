/*------------------------------------*\
 #Markdown editor settings
\*------------------------------------*/

$(function(){

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