/*------------------------------------*\
 #Tags input
\*------------------------------------*/


$.fn.makeTags = function () {

    var tags = $(this);
    var newtag = tags.children(".tags__new");
    var resultInput = tags.children(".tags__result");
    var result = [];
    var isRemovable = false;

    if (resultInput.val()) {
        result =  $.parseJSON(resultInput.val());

        result.forEach(function (content) {
            newtag.before("<span class='tags__tag' data-value='" + content + "'>" + content + "<i class='fa fa-trash tags__delete'></i></span>");
        });

    }

    function createTag(value){

        var content = value.toLowerCase().trim();
        
        if (content == "" || tagExist(content)) {

            newtag.val("");
            return;
        }

        result.push(content);
        updateResultInput();
        newtag.before("<span class='tags__tag' data-value='" + content + "'>" + content + "<i class='fa fa-times tags__delete'></i></span>");
        newtag.val("");
    }

    function deleteTag(content) {

        if (tagExist(content)) {
            result.splice($.inArray(content, result), 1);
        }

        tags.children("[data-value='" + content + "']").remove();
        updateResultInput();
    }

    function updateResultInput() {
        resultInput.val(JSON.stringify(result));
    }

    function tagExist(content) {

        return $.inArray(content, result) != -1;

    }

    newtag.keydown(function (event) {

        switch (event.which) {
            case 13:
            case 188:
                event.preventDefault();
                createTag(newtag.val());
                break;
        }

    });


    newtag.keyup(function (event) {

        switch (event.which) {
            case 8:
                if (newtag.val() == "") {
                    if (isRemovable) {
                        event.preventDefault();
                        deleteTag(tags.children(".tags__tag:last-of-type").text());
                        tags.children(".tags__tag:last-of-type").removeClass("tags__tag--removeable");
                        isRemovable = false;
                    } else {
                        event.preventDefault();
                        tags.children(".tags__tag:last-of-type").addClass("tags__tag--removeable");
                        isRemovable = true;
                    }
                }
                break;
            default:
                tags.children(".tags__tag:last-of-type").removeClass("tags__tag--removeable");
                isRemovable = false;

        }
    });

    $(document).on("click", ".tags__delete", function (event) {

        if ($(event.target).parents(this).length == 0) {
            return;
        }

        var content = $(event.target).parent("span").data("value");
        deleteTag(content);

    });

    tags.click(function () {
        newtag.focus();
    });
    
    return this;
};