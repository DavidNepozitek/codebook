/*------------------------------------*\
 #Tutorial
\*------------------------------------*/


$(document).on("click", ".js-tutorial-delete", function (e) {
    e.preventDefault();

    var name = $(this).data("title");
    
    var isOk = confirm("Opravdu chcete smazat návod " + name + " ?");

    if (isOk == true) {
        $(this).netteAjax(e);
    }
});