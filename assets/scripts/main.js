/**
 * CONTENTS
 *
 * Loader...............Reveals the page, when loaded.
 * Form validation......Validates form with data-rules
 *
 */


/*------------------------------------*\
 #Loader
\*------------------------------------*/

$(window).on("load", function () {
    $("body").removeClass("preload"); //Reveals the page
});






/*------------------------------------*\
 #Nette form validation function
\*------------------------------------*/


$.fn.netteFormValidate = function () {

    var form = $(this);
    var inputs = form.find("input");

    form.submit(function(e){

        //If there is an error -> do not submit the form
        if ( form.find(".has-error").length > 0 ) {
            e.preventDefault();
        }

        //Validates each input with rules
        inputs.each(function () {
            if($(this).data("nette-rules")){
                validateInput($(this));
            }
        });


    });

    //Validates an input on focusout
    inputs.focusout(function (e) {
        validateInput($(e.target));
    });

    function validateInput(input) {

        var rules = input.data("nette-rules");
        var error = false;

        if ( rules ) {

            rules.forEach(function(rule) { //Goes through all of the rules stated in data-nette-rules

                var inputError = input.parent().find(".input-error").find("[data-rule=" + rule.op.substr(1) + "]");

                //If has a rule and the rule is violated
                if (( rule.op == ":filled" && input.val() == "" ) ||
                    ( rule.op == ":minLength" &&  input.val().length < rule.arg) ||
                    ( rule.op == ":email" &&  !validateEmail(input.val()))) {

                    input.parent().addClass("has-error");

                    //If there is no list prepared for errors -> create it
                    if ( input.parent().find(".input-error").length == 0 ) {
                        input.parent().append("<ul class='input-error'></ul>");
                    }


                    //If the particular error message is not displayed yet -> create it with data-rule
                    if (inputError.length == 0) {
                        input.next(".input-error").append("<li data-rule='" + rule.op.substr(1) + "'>" + rule.msg + "</li>");
                    }

                    error = true;
                }

                //If has a rule, but follows the rule
                if (( rule.op == ":filled" && input.val() != "" ) ||
                    ( rule.op == ":minLength" &&  input.val().length >= rule.arg) ||
                    ( rule.op == ":email" &&  validateEmail(input.val()))) {

                    //If there is the particular error message -> removes it
                    if (inputError.length > 0) {
                        inputError.remove();
                    }
                }
            });
        }

        //If the input follows all of the rules -> removes list of errors
        if ( error == false ) {
            input.parent().find(".input-error").remove();
            input.parent().removeClass("has-error");
        }
    }

    return this;
};
