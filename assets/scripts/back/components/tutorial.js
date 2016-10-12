/*------------------------------------*\
 #Markdown editor settings
 \*------------------------------------*/


if ($("#frm-tutorialForm-form-source").lenght > 0) {
    var simplemde = new SimpleMDE({
        element: $("#frm-tutorialForm-form-source")[0] ,
        spellChecker: false,
        renderingConfig: {
            codeSyntaxHighlighting: true
        }
    });
}
