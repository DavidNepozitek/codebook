/**
 * CONTENTS
 *
 * Ajax init............Nette ajax initialization
 * Highlight init.......Highligh.js initialization
 * Loader...............Reveals the page, when loaded.
 * Form validation......Validates form with data-rules
 *
 */


/*------------------------------------*\
 #Ajax init
\*------------------------------------*/


$.nette.ext('history').cache = false;
$.nette.init();





/*------------------------------------*\
 #Loader
\*------------------------------------*/


hljs.initHighlightingOnLoad();





/*------------------------------------*\
 #Loader
\*------------------------------------*/

var loader = new CDloader(".js-loader", 200, "2px");

$.nette.ext({
    start: function () {
        loader.start();
    },
    success: function () {
        loader.end();
    }
});


function CDloader(selector, transitionDur, height) {
    var loader = $(selector);

    this.status = 0;

    this.start = function () {
        loader.css({"height": height, "transform": "translateX(-100%) translateZ(0)"});
        loader.css("transition", transitionDur + "ms");
        loader.css("transform", "translateX(-95%) translateZ(0)");
        this.status = 0.1;
        var _this = this;

        var work = function () {

            setTimeout(function () {
                if (_this.status == 1) {
                    return;
                }
                _this.inc();

                work();
            }, transitionDur)
        };

        work();

    };

    this.end = function () {
        loader.css("transform", "translateX(0) translateZ(0)");

        setTimeout(function () {
            loader.css({"height": 0});

            setTimeout(function () {
                loader.css({"transition": "0s"});
                loader.css({"transform": "translateX(-100%) translateZ(0)", "height": height});
            }, transitionDur);

        }, transitionDur);

        this.status = 1;
    };
    
    this.inc = function () {


        var incAmount;

        if (this.status < 0.5) {
            incAmount = Math.random() * (10 - 5 + 1) + 5;
        } else if (this.status >= 0.5 && this.status < 0.7) {
            incAmount = Math.random() * (6 - 3 + 1) + 3;
        } else if (this.status >= 0.7 && this.status < 0.8) {
            incAmount = Math.random() * (2 - 1 + 1) + 1;
        } else if (this.status >= 0.8 && this.status < 0.9) {
            incAmount = Math.random() * (0.5 - 0.2 + 1) + 0.2;
        } else if (this.status >= 0.9) {
            incAmount = 0;
        }

        this.status = this.status + (incAmount / 100);
        var transX = -100 + (this.status * 100);
        loader.css("transform", "translateX(" + transX + "%) translateZ(0)");

    }
}






/*------------------------------------*\
 #Nette form validation function
\*------------------------------------*/


$.fn.netteFormValidate = function () {

    var form = $(this);
    var inputs = form.find("input, textarea, select");
    var button = form.find("button[type=submit]");

    form.submit(function(e){
        checkIfValid(e);
    });

    button.click(function (e) {
        checkIfValid(e);
    });

    function checkIfValid(e) {
        //If there is an error -> do not submit the form
        if ( form.find(".has-error").length > 0 ) {
            e.preventDefault();
            e.stopImmediatePropagation();
        }

        //Validates each input with rules
        inputs.each(function () {
            if($(this).data("nette-rules")){
                validateInput($(this));
            }
        });
    }
    

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





/*------------------------------------*\
 #Force redirect
\*------------------------------------*/


$.nette.ext({
    success: function (payload) {
        if (payload.forceRedirect) {
            window.location.href = payload.forceRedirect;
        }
    }
});
