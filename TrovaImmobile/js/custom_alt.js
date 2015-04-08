jQuery(document).ready(function () {
    jQuery(".add-user-esperimenti").submit(function () {
        var arrayElementi = [];
        var elementi_input = jQuery("input").size();
        jQuery(".error-check").hide();
        jQuery("input").each(function () {
            var elemento = jQuery(this).val();
            if (elemento == "") {
                jQuery(this).next(".error-check").css("display", "inline-block").show();
            }
        })/*fine each*/;
        return false;
    })
    /*fine submit*/
});