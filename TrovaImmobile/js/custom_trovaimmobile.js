jQuery(document).ready(function () {
    /***********************************Add user trova immobili backend**********************************/
    jQuery(".add-user-trovaimmobili").submit(function () {
        var arrayElementi = [];
        var elementi_input = jQuery("input").size();

        jQuery(".error-check").hide();
        jQuery(".message-ok").hide();
        jQuery(".message-duplicate").hide();

        jQuery("input").each(function () {
            var elemento = jQuery(this).val();
            var title_elemento = jQuery(this).attr('title');
            if (elemento == "" || elemento == title_elemento) {
                jQuery(this).next(".error-check").css("display", "inline-block").show();
            }
            else {
                arrayElementi.push(elemento);
                var conto_array = jQuery(arrayElementi).size();
                if (elementi_input == conto_array) {

                    var elementi = jQuery("input").serialize();
                    var mq_da = jQuery("#add_MQ_da").val();
                    var mq_a = jQuery("#add_MQ_a").val();
                    var citta_reference = jQuery(".add_city").val();
                    var frazione = jQuery(".frazione").val();
                    var regione = jQuery(".add_region").val();
                    var comune = jQuery(".add_comune").val();
                    var trattativa = jQuery(".add_trattativa").val();
                    var valuta = jQuery(".add_valuta").val();

                    jQuery.ajax({
                        type: "GET",
                        url: "/wp-content/plugins/TrovaImmobile/elab/elab-add-user.php",
                        data: "sub_user&" + elementi + "&mq_da=" + mq_da + "&mq_a=" + mq_a + "&add_valuta=" + valuta + "&regione=" + regione + "&city_reference=" + citta_reference + "&comune=" + comune + "&frazione=" + frazione + "&trattativa=" + trattativa,
                        success: function (response) {
                            var result = response.split(',');
                            var risposta = response[0];
                            var id_user_creato = result[1];
                            if (risposta == 0) {
                                location.href = "/wp-admin/admin.php?page=TrovaImmobile-edit-users&action=%s&movie=" + id_user_creato;
                            } else {
                                jQuery(".message-duplicate").show();
                            }

                        }
                    });
                    return false;
                }
                /*fine if*/
            }
            /*fine else*/


        })/*fine each*/;
        return false;

    })
    /*fine submit*/

    /***************************************Mostra Province*******************/

    jQuery(".add_region").change(function () {
        var valore_select = jQuery(this).val();
        var selezione = jQuery(this);
        if (valore_select == "") {

            selezione.parents(".region-box").next(".city-box").find(".add_city").html(""),
                selezione.parents(".region-box").next(".city-box").hide(100),

                selezione.parents(".region-box").next(".city-box").next(".comune-box").hide(100),
                selezione.parents(".region-box").next(".city-box").next(".comune-box").find(".add_comune").html(""),

                selezione.parents(".region-box").next(".city-box").next(".comune-box").next(".frazione-box").hide(100),
                selezione.parents(".city-box").next(".comune-box").next(".frazione-box").find(".frazione").html("<option value=''></option>");

        }

        else {
            jQuery.ajax({
                type: "GET",
                url: "/wp-content/plugins/TrovaImmobile/elab/elab-add-user.php",
                data: "add_city&city=" + valore_select,
                success: function (response) {
                    selezione.parents(".region-box").next(".city-box").find(".add_city").html(response),
                        selezione.parents(".region-box").next(".city-box").show(100);
                }
            });
            return false;

        }
    });

    /***************************************Mostra comuni******************/

    jQuery(".add_city").change(function () {
        var valore_select = jQuery(this).val();
        var selezione = jQuery(this);
        if (valore_select == "") {
            selezione.parents(".city-box").next(".comune-box").find(".add_comune").html(""),
                selezione.parents(".city-box").next(".comune-box").hide(100);
            selezione.parents(".city-box").next(".comune-box").next(".frazione-box").hide(100),
                selezione.parents(".city-box").next(".comune-box").next(".frazione-box").find(".frazione").html("<option value=''></option>");
        }
        else {
            jQuery.ajax({
                type: "GET",
                url: "/wp-content/plugins/TrovaImmobile/elab/elab-add-user.php",
                data: "add_comune&comune=" + valore_select,
                success: function (response) {
                    selezione.parents(".city-box").next(".comune-box").find(".add_comune").html(response),
                        selezione.parents(".city-box").next(".comune-box").show(100);
                }
            });
            return false;

        }
    });

    /***************************************Mostra frazione se Napoli***************/

    jQuery(".add_comune").change(function () {
        var selezione = jQuery(this);
        var valore_select = jQuery(this).val();
        if (valore_select == "") {
            jQuery(this).parents(".comune-box").next(".frazione-box").hide(100);
            selezione.parents(".comune-box").next(".frazione-box").find(".frazione").html("<option value=''></option>");
        } else {
            jQuery.ajax({
                type: "GET",
                url: "/wp-content/plugins/TrovaImmobile/elab/elab-add-user.php",
                data: "add_zone&zona=" + valore_select,
                success: function (response) {
                    selezione.parents(".comune-box").next(".frazione-box").find(".frazione").html(response),
                        selezione.parents(".comune-box").next(".frazione-box").show(100);
                }
            });
            return false;
        }
    });

    /***************************************Edit dati utente trovaimmobile******************************/

    jQuery(".edit-user-trovaimmobile").submit(function () {
        var arrayElementi = [];
        var elementi_input = jQuery("input").size();
        var id_user = jQuery(".id_user").val();

        jQuery(".error-check").hide();
        jQuery(".message-ok").hide();
        jQuery(".message-duplicate").hide();
        jQuery("input").each(function () {
            var elemento = jQuery(this).val();
            if (elemento == "") {
                jQuery(this).next(".error-check").css("display", "inline-block").show();
            }
            else {
                arrayElementi.push(elemento);
                var conto_array = jQuery(arrayElementi).size();
                if (elementi_input == conto_array) {
                    var elementi = jQuery("input").serialize();
                    jQuery.ajax({
                        type: "GET",
                        url: "/wp-content/plugins/TrovaImmobile/elab/elab-add-user.php",
                        data: "edit_user&" + elementi,
                        success: function (response) {
                            location.href = "/wp-admin/admin.php?page=TrovaImmobile-edit-users&action=%s&movie=" + id_user;

                        }
                    });
                    return false;

                }
                /*fine if*/
            }
            /*fine else*/
        })/*fine each*/;
        return false;

    })
    /*fine submit*/


    /********************************Edit parametri di ricerca utente trovaimmobile*************************/

    jQuery(".edit-search").live("submit", function () {

        var mq_da = jQuery(this).find(".add_MQ_da").val();
        var mq_a = jQuery(this).find(".add_MQ_a").val();
        var citta = jQuery(this).find(".add_city").val();
        var frazione = jQuery(this).find(".frazione").val();
        var id_user = jQuery(this).find(".id_user").val();
        var id_param = jQuery(this).find(".id_param").val();
        var categoria = jQuery(this).find("input[name=category_choise]:checked").val();
        var regione = jQuery(this).find(".add_region").val();
        var comune = jQuery(this).find(".add_comune").val();
        var trattativa = jQuery(this).find(".add_trattativa").val();
        var budget = jQuery(this).find(".add_budget").val();
        var valuta = jQuery(this).find(".add_valuta").val();
        var note = jQuery(this).find("#add-note").val();

        var olds_input = jQuery(this).find(".input_olds input").serialize();

        var array_verifiche = [];
        var array_non_verificati = [];
        jQuery(".verifica_user", this).each(function () {

            var valore = jQuery(this).val();
            var post_verificato = jQuery(this).is(':checked');

            if (post_verificato == true) {
                array_verifiche.push(valore);
            }
            else {
                array_non_verificati.push(valore);
            }
        });

        jQuery.ajax({
            type: "GET",
            url: "/wp-content/plugins/TrovaImmobile/elab/elab-add-user.php",
            data: "edit_param=0&mq_da=" + mq_da + "&mq_a=" + mq_a + "&add_valuta=" + valuta + "&add_budget=" + budget + "&regione=" + regione + "&comune=" + comune + "&provincia=" + citta + "&frazione=" + frazione + "&id_user=" + id_user + "&id_param=" + id_param + "&category_choise=" + categoria + "&verifiche=" + array_verifiche + "&non_verificati=" + array_non_verificati + "&trattativa=" + trattativa + "&note=" + note + "&" + olds_input,
            success: function (response) {

                if (response == 0) {
                    location.href = "/wp-admin/admin.php?page=TrovaImmobile-edit-users&action=%s&movie=" + id_user;
                }

                else {
                    alert(response);
                }

            }

        });
        /*fine ajax*/
        return false;

    });

    /********************************Delete User*************************/

    jQuery(".delete-item").click(function () {
        if (confirm("Sicuro di voler cancellare l'elemento?")) {
        } else {
            return false;
        }
    });


    /********************************Delete Parametri*******************/

    jQuery(".delete_param").click(function () {
        var id_param = jQuery(this).val();
        var id_user = jQuery(".id_user").val();
        if (confirm("Sicuro di voler cancellare l'elemento?")) {

            jQuery.ajax({
                type: "GET",
                url: "/wp-content/plugins/TrovaImmobile/elab/elab-add-user.php",
                data: "delete_param=" + id_param,
                success: function (response) {
                    if (response == 0) {
                        location.href = "/wp-admin/admin.php?page=TrovaImmobile-edit-users&action=TrovaImmobile-list-users&movie=" + id_user;
                    }
                    else {
                    }
                }
            });
            return false;

        } else {
            return false;
        }

    });


    /***********************************Add Parametro ricerca**********************************/

    jQuery(".add-param-trovaimmobili").submit(function () {

        var mq_da = jQuery("#add_MQ_da").val();
        var mq_a = jQuery("#add_MQ_a").val();
        var citta_reference = jQuery(".add_city").val();
        var frazione = jQuery(".frazione").val();
        var id_user = jQuery(".id_user").val();
        var regione = jQuery(".add_region").val();
        var comune = jQuery(".add_comune").val();
        var categoria = jQuery(this).find("input[name=category_choise]:checked").val();
        var trattativa = jQuery(".add_trattativa").val();
        var budget = jQuery(".add_budget").val();
        var valuta = jQuery(".add_valuta").val();

        jQuery.ajax({
            type: "GET",
            url: "/wp-content/plugins/TrovaImmobile/elab/elab-add-user.php",
            data: "add_param=" + id_user + "&mq_da=" + mq_da + "&mq_a=" + mq_a + "&add_valuta=" + valuta + "&add_budget=" + budget + "&regione=" + regione + "&comune=" + comune + "&provincia=" + citta_reference + "&frazione=" + frazione + "&category_choise=" + categoria + "&trattativa=" + trattativa,
            success: function (response) {

                if (response == 0) {
                    location.href = "/wp-admin/admin.php?page=TrovaImmobile-edit-users&action=TrovaImmobile-list-users&movie=" + id_user;
                }
            }/*fine function success*/

        });
        return false;
    })
    /*fine submit*/

    /***********************************Slide add parametri ricerca********************/

    jQuery(".add_param_link").live("click", function () {
        jQuery('.add-param-box').show(200);
    });

    /************************************Riscrivo url per ricerca utente trova imm**********/

    jQuery(".search-user").live("click", function () {

        var value_search = jQuery("#search_user").val();
        location.href = "/wp-admin/admin.php?page=TrovaImmobile-list-users&user_search=" + value_search

        return false;

    });


    /**********************************Nascondere etichette form trovaimmobile fronend*************************/
    jQuery(".custom_form input[type=text]").each(function () {
        var title_text = jQuery(this).attr("title");
        jQuery(this).val(title_text);
    });

    jQuery(".custom_form input[type=text]").focus(function () {
        if (jQuery(this).val() == jQuery(this).attr("title"))
            jQuery(this).val("");
    });

    jQuery(".custom_form input[type=text]").focusout(function () {
        var valore = jQuery(this).val();
        jQuery(this).css("border", "1px solid #ccc");
        if (valore == "") {
            var title_text = jQuery(this).attr("title");
            jQuery(this).val(title_text);
        } else {
            jQuery(this).addClass("blur");
        }
    });


    jQuery(".custom_form select").focusout(function () {

        jQuery(this).css("border", "1px solid #ccc");


    })

    /********************************Mostro form in base alla select****************/

    jQuery(".nazione_select").change(function () {
        var valore = jQuery(this).val();
        if (valore == "Italia") {
            jQuery(".hidden_form").hide(100);
            jQuery("#trova-italia").show(100);
        }
        else if (valore == "Resto del Mondo") {
            jQuery(".hidden_form").hide(100);
            jQuery("#trova-mondo").show(100);
        }
        else {
            jQuery(".hidden_form").hide(100)
        }
    });
    /***************************MOstro le province************************/

    jQuery(".custom_form .add_region").change(function () {
        var valore_select = jQuery(this).val();
        var selezione = jQuery(this);
        if (valore_select == "") {
            jQuery(".add_city").html("<option value=''>Provincia</option>");
            jQuery(".add_comune").html("<option value=''>Comune</option>");
            jQuery(".frazione").html("<option value=''>Zona /Quartiere</option>");
        }
        else {
            jQuery.ajax({
                type: "GET",
                url: "/wp-content/plugins/TrovaImmobile/elab/elab-add-user.php",
                data: "add_city&city=" + valore_select,
                success: function (response) {
                    jQuery(".add_city").html(response)
                }
            });
            return false;
        }
    });

    /***************************************Mostra comuni******************/

    jQuery(".custom_form .add_city").change(function () {
        var valore_select = jQuery(this).val();
        var selezione = jQuery(this);

        if (valore_select == "") {
            jQuery(".add_comune").html("<option value=''>Comune</option>");
            j
            Query(".frazione").html("<option value=''>Zona /Quartiere</option>");
        }

        else {
            jQuery.ajax({
                type: "GET",
                url: "/wp-content/plugins/TrovaImmobile/elab/elab-add-user.php",
                data: "add_comune&comune=" + valore_select,
                success: function (response) {
                    jQuery(".add_comune").html(response)
                }
            });
            return false;
        }
    });

    /***************************************Mostra frazione se Napoli***************/

    jQuery(".add_comune").change(function () {
        var selezione = jQuery(this);
        var valore_select = jQuery(this).val();
        if (valore_select == "") {
            jQuery(".frazione").html("<option value=''>Zona /Quartiere</option>");
        }
        else {
            jQuery.ajax({
                type: "GET",
                url: "/wp-content/plugins/TrovaImmobile/elab/elab-add-user.php",
                data: "add_zone&zona=" + valore_select,
                success: function (response) {
                    jQuery(".frazione").html(response)
                }
            });
            return false;
        }
    });

    /***********************************Add user trova immobili frontend**********************************/
    jQuery(".add-user-frontend").submit(function () {
        type_form = jQuery(this).parent('div').attr("id");
        if (jQuery(".trattamento").is(':checked')) {
        } else {
            alert("Devi accettare il trattamento dei dati prima di proseguire !");
            return false;
        }
        ;
        if (jQuery("#" + type_form + " form #add_MQ_da").val() == "") {
            jQuery("#" + type_form + " form #add_MQ_da").css("border", "1px solid red");
            return false;
        }
        if (jQuery("#" + type_form + " form #add_MQ_a").val() == "") {
            jQuery("#" + type_form + " form #add_MQ_a").css("border", "1px solid red");
            return false;
        }
        if (jQuery("#" + type_form + " form .anti_spam").val() != "") {
            alert("MAmmt");
            return false;
        }
        //do un valore al campo anti-spam per superare i controlli
        jQuery("#" + type_form + " form .anti_spam").val("ok");
        var arrayElementi = [];
        var elementi_input = jQuery('input', this).size();
        jQuery('input', this).each(function () {
            var elemento = jQuery(this).val();
            var title_elemento = jQuery(this).attr('title');
            if (elemento == "" || elemento == title_elemento) {
                jQuery(this).css("border", "1px solid red");
            }
            else {
                arrayElementi.push(elemento);
                var conto_array = jQuery(arrayElementi).size();
                if (elementi_input == conto_array) {

                    var elementi = jQuery('#' + type_form + ' form input').serialize();
                    var mq_da = jQuery("#" + type_form + " form #add_MQ_da").val();
                    var mq_a = jQuery("#" + type_form + " form #add_MQ_a").val();
                    var citta_reference = jQuery("#" + type_form + " form .add_city").val();
                    var frazione = jQuery("#" + type_form + " form .frazione").val();
                    var regione = jQuery("#" + type_form + " form .add_region").val();
                    var comune = jQuery("#" + type_form + " form .add_comune").val();
                    var trattativa = jQuery("#" + type_form + " form .trattativa_type").val();

                    jQuery.ajax({
                        type: "GET",
                        url: "/wp-content/plugins/TrovaImmobile/elab/elab-add-user.php",
                        data: "sub_user&" + elementi + "&mq_da=" + mq_da + "&mq_a=" + mq_a + "&regione=" + regione + "&city_reference=" + citta_reference + "&comune=" + comune + "&frazione=" + frazione + "&trattativa=" + trattativa,
                        success: function (response) {
                            var result = response.split(',');
                            var risposta = response[0];
                            var id_user_creato = result[1];
                            if (risposta == 0) {
                                location.href = "http://www.gruppoeuropeo.it/trova-l-immobile/?registration=ok";
                            } else {
                                alert(response);
                            }
                        }
                    });
                    return false;

                }
                /*fine if*/
            }
            /*fine else*/
        })/*fine each*/;
        return false;

    })
    /*fine submit*/

})
/*fine document ready*/

/*****************************Funzione per i numeri del budget***********************/
function ControlloNumero(obj) {
    valore = obj.value.replace(/[^\d]/g, '').replace(/^0+/g, '');
    nuovovalore = '';
    while (valore.length > 3) {
        nuovovalore = '.' + valore.substr(valore.length - 3) + nuovovalore;
        valore = valore.substr(0, valore.length - 3);
    }
    obj.value = valore + nuovovalore;
}