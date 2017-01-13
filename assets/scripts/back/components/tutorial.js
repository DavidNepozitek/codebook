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

    if (isOk == true) {
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



