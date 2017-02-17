/*------------------------------------*\
 #Tutorial
\*------------------------------------*/


/**
 * Tutorial delete confirmation
 */

$(document).on("click", ".js-tutorial-delete", function (e) {
    e.preventDefault();

    var name = $(this).data("title");
    
    var isOk = confirm("Opravdu chcete smazat návod " + name + " ?");

    if (isOk === true) {
        $(this).netteAjax(e);
    }
});



/**
 * Image markdown copy to clipboard
 */


$(document).on("click", ".js-code-copy", function () {
    var input = $(this).closest(".js-image").find(".js-image-input");

    input.select();

    document.execCommand("copy");

});


/**
 * Attachment size check
 */

$(".js-attachmentInput").on("change", function(e) {
    var rules = $(this).data("nette-rules");
    var msg;
    var maxSize;

    rules.forEach(function (rule) {
        if (rule.op === ":fileSize") {
            maxSize = rule.arg;
            msg = rule.msg;
        }
    });

    for (var i = 0; i < this.files.length; i++) {

        var file = this.files[i];

        if(file.size > maxSize) {
            alert("Soubor " + file.name + " přesáhl maximální povolenou velikost. " + msg);
        }
    }

});


