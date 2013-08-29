/* 
 * Fixes HTML5 placeholder attribute
 * 
 */
$.fn.placeholder = function(){
    $(this).attr('origval', $(this).attr('placeholder')).removeAttr('placeholder');
    $(this).focus(function() {
        var input = $(this);
        if (input.val() == input.attr("origval")) {
            input.val("");
            input.removeClass("placeholder");
        }
    }).blur(function() {
        var input = $(this);
        if (input.val() == "" || input.val() == input.attr("origval")) {
            input.addClass("placeholder");
            input.val(input.attr("origval"));
        }
    }).blur().parents("form").submit(function() {
        $(this).find("[origval]").each(function() {
            var input = $(this);
            if (input.val() == input.attr("origval")) {
                input.val("");
            }
        })
    });
};
$(document).ready(function() {

    $('[placeholder]:not([type="password"])').each(function() { //passwords shoul be handeled specialy
        $(this).placeholder();
    });
});
