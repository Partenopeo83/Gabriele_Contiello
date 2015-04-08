$(document).ready(function () {

    //Inizializzo le select box
    $("select").selectBoxIt();

    //get token
    var token = $('.box').attr("data-token");

    //product to category
    $(".catalog-form").live("submit",function () {
        var category_id = $(".category-select option:selected").attr("value");
        var level = $(".level-category option:selected").attr("value");

        if (category_id == "") {
            alert("Seleziona una categoria");
            return false;
        }
        $(".wait").fadeIn(400);
        $.ajax({
            url: 'index.php?route=module/generatorcatalog/products_to_category',
            type: 'get',
            data: "category=" + category_id + "&level=" + level + "&token=" + token,
            success: function (response) {
                console.log(response)
                $(".list-container").html(response);
            },
            error: function (data) {
                alert("error: " + data);
            }
        }).fail(function () {
            alert("Errore nel caricamento");
        }).done(function () {
            //Creo funzioni di step per scroll
            $(window).scroll(function () {
                $(".table-box").each(function () {
                    var topping = parseInt($(document).scrollTop())
                    var offset_top = $(this).offset().top;
                    var element_height = $(this).height() + offset_top;
                    if (topping >= offset_top && topping <= element_height) {
                        var title = $(".category-name", this).text();
                        $(".fixed-header .category-name").text(title);
                    }
                });
            });

            $(".wait").fadeOut(400);
            $(".generate-catalog").show();
        });
        return false;
    });

    //Mostro menù sticky
    $(window).scroll(function () {
        var topping = parseInt($(document).scrollTop());
        if (topping > 200) {
            $(".fixed-header").fadeIn(400);
        }
        else if (topping <= 200) {
            $(".fixed-header").fadeOut(400);
        }
    });

    //Funzione per filtri
    function filter_table() {
        var value = $(".search").val();
        var rows = $('.rows tr');
      if (value.toUpperCase().replace(/ /g, '').length > 2) {
          var val = $.trim(value).replace(/ +/g, ' ').toLowerCase();

          rows.show()
          rows.filter(function() {
              var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
              return !~text.indexOf(val);
          }).hide();
        }
        else if (value.replace(/ /g, '').length <= 2) {
            rows.show();
        }
    }

    //Evento Filtro
    $('.search').live("change",function() {
        $(".search").val($(this).val());
        $(".wait").fadeIn(400, function () {
            $.when(filter_table()).then(function () {
                $(".wait").fadeOut(400);
            });
        });
    });

    //Evento Creazione cartella catalogo
    $(".generate-catalog").click(function () {
        $(".wait").fadeIn(400);
        var name_page = $(".selectboxit-text").eq(0).text();
        var category_id = $(".category-select option:selected").attr("value");
        var level = $(".level-category option:selected").attr("value");
        var category_name = $(".category-select option:selected").text();

        $.ajax({
            url: 'index.php?route=module/generatorcatalog/products_to_category_catalog',
            type: 'GET',
            data: "category_name="+category_name+"&name_page=" + name_page + "&category=" + category_id + "&level=" + level + "&token=" + token,
            success: function (response) {
                console.log(response);
            },
            error: function (data) {
                alert("error: " + data);
            }
        }).fail(function () {
            alert("Errore nel caricamento");
        }).done(function () {
            $(".wait").fadeOut(400);
            archive_catalog();
        });
        return false;
    });

    //Apertura finestra archivio
    $(".archive-label").on("click", function (event) {
        event.stopPropagation();
        if (!$(this).hasClass("open")) {
            $(".archive-container").animate({
                width: "350px"
            });

            $(this).addClass("open");
        } else {
            $(".archive-container").animate({
                width: "27px"
            });

            $(this).removeClass("open");
        }
    });

    $(document).live("click",function(){
        $(".archive-container").animate({
            width: "27px"
        });

        $(".archive-label").removeClass("open");
    });

    //Caricamento file dell'archivio
    archive_catalog();

    function archive_catalog() {
        $.ajax({
            url: 'index.php?route=module/generatorcatalog/catalog_archive',
            type: 'GET',
            data: "token=" + token,
            success: function (response) {
                $(".archive-box").html(response);
            }
        }).done(function(){
            $(".archive-container").fadeIn(400);
        });
    }

    //Cancellazione catalogo
    $(".delete-link").live("click",function () {
        if(confirm("Sicuro di voler cancellare il catalogo?")){
            var file = $(this).data("link-rel");
            console.log(file);
            $.ajax({
                url: 'index.php?route=module/generatorcatalog/delete_catalog',
                type: 'GET',
                data: "file="+file+"&token=" + token,
                success: function (response) {
                    archive_catalog();
                }
            })
        }
        return false;
    });
});