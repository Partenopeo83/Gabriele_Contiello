$(document).ready(function () {

    var page = {}

    //Inizializzazione pagina
    page.init = function (json) {
        page.generate_menu(json);
        page.events(json);
    }

    //Eventi
    page.events = function (json) {
        //Titolo pagina
        $("header").text(name_catalog);

        //Evento su click categoria
        $(document).on("click", ".link-category", function () {
            var category = $(this).data("category");
            $(".wait").fadeIn(400, function () {
                page.table_generator(json, category);
                $(".second-page").show(function () {
                    $("th").each(function () {
                        var width = $(this).width();
                        $(this).css({width: width});
                    });
                });
                $(".wait").fadeOut(400);
                //Scroll alla tabella
                var offset = $(".second-page").offset().top - 90;
                $('html, body').animate({
                    scrollTop: offset
                }, 2000);
            });
            return false;
        });

        //Menù sticky
        $(window).scroll(function () {
            var thead_offset = $(".second-page").offset().top;
            var scrolling = $(document).scrollTop();
            if (scrolling >= thead_offset) {
                $(".category-table thead").addClass("fixed");
            } else if (scrolling < thead_offset) {
                $(".category-table .fixed").removeClass("fixed");
            }
        });

        //Evento filtro
        $(document).on("change", ".search", function () {
            $(".search").val($(this).val());
            $(".wait").fadeIn(400, function () {
                $.when(page.filter_table()).then(function () {
                    $("th").each(function () {
                        var width = $(this).css("width");
                        $(this).css({width: width});
                    });
                    $(".wait").fadeOut(400);
                });
            });
        });
    }

    //Genero il menù iniziale
    page.generate_menu = function (json) {
        $.each(json, function (key, value) {
            $(".category-list-box").append("<li><a class='link-category' href='#' data-category='" + key + "'><p>" + key + "</p></a></li>");
        });
    }

    //Genero tabella della categoria
    page.table_generator = function (json, data_category) {
        $(".category-table tbody").html("");
        var array_prod = json[data_category];
        $.each(array_prod, function (key, value) {
            var row = "<tr>";
            row += "<td>" + data_category + "</td>";
            row += "<td>" + array_prod[key].nome + "</td>";
            row += "<td>" + array_prod[key].stato_conservazione + "</td>";
            row += "<td>" + array_prod[key].referrer + "</td>";
            row += "<td>" + array_prod[key].quantity + "</td>";
            row += "<td>" + array_prod[key].stock + "</td>";
            row += "<td>" + array_prod[key].price + "</td>";
            row += "<td>" + array_prod[key].cost + "</td>";
            row += "<td>" + array_prod[key].fornitore + "</td>";
            row += "</tr>";

            $(".category-table .body-table").append(row);
        });
    }

    //Funzione per filtri
    page.filter_table = function () {
        var value = $(".search").val();
        var rows = $('.body-table tr');
        if (value.toUpperCase().replace(/ /g, '').length > 2) {
            var val = $.trim(value).replace(/ +/g, ' ').toLowerCase();
            rows.show()
            rows.filter(function () {
                var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
                return !~text.indexOf(val);
            }).hide();
        }
        else if (value.replace(/ /g, '').length <= 2) {
            rows.show();
        }
    }

    //Avvio pagina
    page.init(db);
});
