/*------------------------------------*\
 #Login form validation
\*------------------------------------*/


$(".form-login input").focusout(function () {

    var elem = $(this);
    var rules = elem.data("nette-rules");

    var error = false;
    if ( rules ) {
        rules.forEach(function(rule) { //Goes through all of the rules stated in data-nette-rules

            var inputError = elem.parent().find(".input-error").find("[data-rule=" + rule.op.substr(1) + "]");

            //If has a rule and the rule is violated
            if (( rule.op == ":filled" && elem.val() == "" ) ||
                ( rule.op == ":minLength" &&  elem.val().length < rule.arg) ||
                ( rule.op == ":email" &&  !validateEmail(elem.val()))) {

                elem.parent().addClass("has-error");

                //If there is no list prepared for errors -> create it
                if ( elem.parent().find(".input-error").length == 0 ) {
                    elem.parent().append("<ul class='input-error'></ul>");
                }

                //If the particular error message is not displayed yet -> create it with data-rule
                if (inputError.length == 0) {
                    elem.next(".input-error").append("<li data-rule='" + rule.op.substr(1) + "'>" + rule.msg + "</li>");
                }

                error = true;
            }

            //If has a rule, but follows the rule
            if (( rule.op == ":filled" && elem.val() != "" ) ||
                ( rule.op == ":minLength" &&  elem.val().length >= rule.arg) ||
                ( rule.op == ":email" &&  validateEmail(elem.val()))) {

                //If there is the particular error message -> removes it
                if (inputError.length > 0) {
                    inputError.remove();
                }
            }
        });
    }

    //If the input follows all of the rules -> removes list of errors
    if ( error == false ) {
        elem.parent().find(".input-error").remove();
        elem.parent().removeClass("has-error");
    }
});

$(".form-login").submit(function(e){

    var form = $(this);

    //Focusout all of the inputs of the form
    form.find("input").focusout();

    //If there is an error -> do not submit the form
    if ( form.find(".has-error").length > 0 ) {
        e.preventDefault();
    }

});