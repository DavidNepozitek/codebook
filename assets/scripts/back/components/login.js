/*------------------------------------*\
 #Login form validation
\*------------------------------------*/

//TODO: Comment this function

$(".form-login input").focusout(function () {

    var elem = $(this);
    var rules = elem.data("nette-rules");

    var error = false;
    if ( rules ) {
        rules.forEach(function(entry) {

            var inputError = elem.parent().find(".input-error").find("[data-rule=" + entry.op.substr(1) + "]");

            if (( entry.op == ":filled" && elem.val() == "" ) ||
                ( entry.op == ":minLength" &&  elem.val().length < entry.arg) ||
                ( entry.op == ":email" &&  !validateEmail(elem.val()))) {

                elem.parent().addClass("has-error");

                if ( elem.parent().find(".input-error").length == 0 ) {
                    elem.parent().append("<ul class='input-error'></ul>");
                }

                if (inputError.length == 0) {
                    elem.next(".input-error").append("<li data-rule='" + entry.op.substr(1) + "'>" + entry.msg + "</li>");
                }

                error = true;
            }

            if (( entry.op == ":filled" && elem.val() != "" ) ||
                    ( entry.op == ":minLength" &&  elem.val().length >= entry.arg) ||
                    ( entry.op == ":email" &&  validateEmail(elem.val()))) {

                    if (inputError.length > 0) {
                        inputError.remove();
                    }

            }
        });
    }

    if ( error == false ) {
        elem.parent().find(".input-error").remove();
        elem.parent().removeClass("has-error");
    }
});

$(".form-login").submit(function(e){

    var elem = $(this);
    elem.find("input").focusout();

    if ( elem.find(".has-error").length > 0 ) {
        e.preventDefault();
    }

});