/*------------------------------------*\
 #Markdown editor settings
 \*------------------------------------*/


var simplemde = new SimpleMDE({
    element: $("#frm-tutorialForm-form-source")[0] ,
    spellChecker: false,
    renderingConfig: {
        codeSyntaxHighlighting: true
    }
});