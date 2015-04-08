<?php
include_once('../../../../wp-config.php');
include_once('../../../../wp-load.php');
include_once('../../../../wp-includes/wp-db.php');

global $wpdb;
header('Access-Control-Allow-Origin: *');
/*******In tutti i casi il budget deve essere formattato correttamente**********/
if (isset($_GET['add_budget'])) {
    $budget = str_replace(".", "", $_GET['add_budget']);
}

/***************************************Add User******************************/
if (isset($_GET['sub_user'])) {

    $date = date('Y-m-d');
    $mylink = $wpdb->get_row("SELECT * FROM trovaimm_user WHERE mail = '$_GET[add_user_mail]'");
    if ($mylink == null) {
        $wpdb->insert('trovaimm_user', array('nome' => $_GET['add_user_name'], 'cognome' => $_GET['add_user_surname'], 'mail' => $_GET['add_user_mail'], 'stato' => $_GET['add_user_activation'], 'phone' => $_GET['add_user_phone'], 'data_inserimento' => $date));
        $id_utente = mysql_insert_id();
    } else {
        $id_utente = $mylink->id;
    }
    $category = $_GET['category_choise'];
    $wpdb->insert('trovaimm_references', array('id_user' => $id_utente, 'valuta' => $_GET['add_valuta'], 'budget' => $budget, 'mq_da' => $_GET['mq_da'], 'mq_a' => $_GET['mq_a'], 'regione' => $_GET['regione'], 'comune' => $_GET['comune'], 'provincia' => $_GET['city_reference'], 'frazione' => $_GET['frazione'], 'id_category' => $category, 'tipo_trattativa' => $_GET['trattativa'], 'data_inserimento' => $date));
    $id_ricerca = mysql_insert_id();
    $dati = "0,$id_utente";

    //Cerco e aggiungo eventuali immobili che potrebbero interessare
    global $post;

    if (!empty($_GET['regione'])) {
        $mylink = $wpdb->get_row("SELECT * FROM regioni WHERE id = '$_GET[regione]'");
        $nome_regione = $mylink->nome;
        $region = array('key' => 'imm_regione', 'value' => array($_GET['regione'], $nome_regione, false), 'compare' => 'IN',);
    }

    if (!empty($_GET['city_reference'])) {
        $mylink = $wpdb->get_row("SELECT * FROM province WHERE id = '$_GET[city_reference]'");
        $nome_provincia = $mylink->nome;
        $provincia = array('key' => 'imm_provincia', 'value' => array($_GET['city_reference'], $nome_provincia, false), 'compare' => 'IN');
    }

    if (!empty($_GET['comune'])) {
        $mylink = $wpdb->get_row("SELECT * FROM comuni WHERE id = '$_GET[comune]'");
        $nome_comune = $mylink->nome;
        $comune = array('key' => 'imm_comune', 'value' => array($_GET['comune'], $nome_comune, false), 'compare' => 'IN');
    }

    if (!empty($_GET['frazione'])) {
        $mylink = $wpdb->get_row("SELECT * FROM zone WHERE id = '$_GET[frazione]'");
        $nome_zona = $mylink->nome;
        $frazione = array('key' => 'imm_quartiere_list', 'value' => array($_GET['frazione'], $nome_zona, false), 'compare' => 'IN');
    }

    if (!empty($_GET['trattativa'])) {
        $trattativa = array('key' => 'imm_trattativa', 'value' => array($_GET['trattativa']), 'compare' => 'IN',);
    }

    if ($budget > 0 && !empty($budget)) {
        $cerca_budget = array('key' => 'imm_prezzo', 'value' => $budget, 'type' => 'NUMERIC', 'compare' => '<=',);
    }

    $args = array(
        'post_type' => 'post',
        'meta_query' => array(
            'relation' => 'AND', $region, $provincia, $comune, $frazione, $trattativa, $cerca_budget,

            array(
                'key' => 'imm_metriquadri',
                'value' => $_GET['mq_da'],
                'type' => 'NUMERIC',
                'compare' => '>=',
            ),
            array(
                'key' => 'imm_metriquadri',
                'value' => $_GET['mq_a'],
                'type' => 'NUMERIC',
                'compare' => '<=',
            ),
            array(
                'key' => 'imm_valuta',
                'value' => array($_GET['add_valuta'], false),
                'compare' => 'IN',
            ),

        ),
        'orderby' => '',
        'order' => 'DESC',
        'cat' => $category,
        'posts_per_page' => -1,
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) :
            $query->the_post();
            $wpdb->insert('trovaimm_verifiche', array('id_post' => $post->ID, 'id_user' => $id_utente, 'data_creazione' => $date, 'id_param' => $id_ricerca, 'stato' => '0'));
        endwhile;
    }

    //invio l'array $dai ad ajax
    echo $dati;
}/*fine if*/

/*********************************Aggiunta singolo parametro ricerca************/
elseif (isset($_GET['add_param'])) {
    $date = date('Y-m-d');
    $wpdb->insert('trovaimm_references', array('id_user' => $_GET['add_param'], 'valuta' => $_GET['add_valuta'], 'budget' => $budget, 'id_category' => $_GET['category_choise'], 'mq_da' => $_GET['mq_da'], 'mq_a' => $_GET['mq_a'],
        'regione' => $_GET['regione'], 'comune' => $_GET['comune'], 'provincia' => $_GET['provincia'], 'frazione' => $_GET['frazione'], 'id_category' => $_GET['category_choise'], 'tipo_trattativa' => $_GET['trattativa'], 'data_inserimento' => $date));
    // Cerco e aggiungo eventuali immobili che potrebbero interessare
    global $post;

    if (!empty($_GET['regione'])) {
        $mylink = $wpdb->get_row("SELECT * FROM regioni WHERE id = '$_GET[regione]'");
        $nome_regione = $mylink->nome;
        $region = array('key' => 'imm_regione', 'value' => array($_GET['regione'], $nome_regione, false), 'compare' => 'IN',);
    }

    if (!empty($_GET['city_reference'])) {
        $mylink = $wpdb->get_row("SELECT * FROM province WHERE id = '$_GET[provincia]'");
        $nome_provincia = $mylink->nome;
        $provincia = array('key' => 'imm_provincia', 'value' => array($_GET['provincia'], $nome_provincia, false), 'compare' => 'IN');
    }

    if (!empty($_GET['comune'])) {
        $mylink = $wpdb->get_row("SELECT * FROM comuni WHERE id = '$_GET[comune]'");
        $nome_comune = $mylink->nome;
        $comune = array('key' => 'imm_comune', 'value' => array($_GET['comune'], $nome_comune, false), 'compare' => 'IN');
    }

    if (!empty($_GET['frazione'])) {
        $mylink = $wpdb->get_row("SELECT * FROM zone WHERE id = '$_GET[frazione]'");
        $nome_zona = $mylink->nome;
        $frazione = array('key' => 'imm_quartiere_list', 'value' => array($_GET['frazione'], $nome_zona, false), 'compare' => 'IN');
    }

    if (!empty($_GET['trattativa'])) {
        $trattativa = array('key' => 'imm_trattativa', 'value' => array($_GET['trattativa']), 'compare' => 'IN',);
    }

    if ($budget > 0 && !empty($budget)) {
        $cerca_budget = array('key' => 'imm_prezzo', 'value' => $budget, 'type' => 'NUMERIC', 'compare' => '<=',);
    }

    $args = array(
        'post_type' => 'post',
        'meta_query' => array(
            'relation' => 'AND', $region, $provincia, $comune, $frazione, $cerca_budget,
            array(
                'key' => 'imm_valuta',
                'value' => array(false, $_GET['add_valuta']),
                'compare' => 'IN',
            ),

            array(
                'key' => 'imm_metriquadri',
                'value' => $_GET['mq_da'],
                'type' => 'NUMERIC',
                'compare' => '>=',
            ),
            array(
                'key' => 'imm_metriquadri',
                'value' => $_GET['mq_a'],
                'type' => 'NUMERIC',
                'compare' => '<=',
            ),
            array(
                'key' => 'imm_prezzo',
                'value' => 0,
                'type' => 'NUMERIC',
                'compare' => '>=',
            ),
        ),
        'orderby' => '',
        'order' => 'DESC',
        'cat' => $category,
        'posts_per_page' => -1,
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) {

        while ($query->have_posts()) :
            $query->the_post();

            $wpdb->insert('trovaimm_verifiche', array('id_post' => $post->ID, 'id_user' => $id_utente, 'data_creazione' => $date, 'id_param' => $id_ricerca, 'stato' => '0'));

        endwhile;
    }

    echo 0;

} /*********************************Edit user************************************/
elseif (isset($_GET['edit_user'])) {

    $wpdb->update(
        'trovaimm_user',
        array(
            'nome' => $_GET['add_user_name'],    // string
            'cognome' => $_GET['add_user_surname'],    // integer (number)
            'mail' => $_GET['add_user_mail'],
            'phone' => $_GET['add_user_phone'],
            'stato' => $_GET['add_user_activation']
        ),
        array('id' => $_GET['id_user'])

    );

}/*fine edit user*/

/*********************************Edit sigola ricerca dell'immobile**********************/

elseif (isset($_GET['edit_param'])) {

    $date = date('Y-m-d');

    $wpdb->query("DELETE FROM trovaimm_references WHERE id_user ='$_GET[id_user]' AND id='$_GET[id_param]' ");

    $wpdb->insert('trovaimm_references', array('id_user' => $_GET['id_user'], 'valuta' => $_GET['add_valuta'], 'budget' => $budget, 'id_category' => $_GET['category_choise'], 'mq_da' => $_GET['mq_da'], 'mq_a' => $_GET['mq_a'], 'regione' => $_GET['regione'], 'provincia' => $_GET['provincia'], 'comune' => $_GET['comune'], 'frazione' => $_GET['frazione'], 'tipo_trattativa' => $_GET['trattativa'],
        'note' => $_GET['note'], 'data_inserimento' => $date));

    $id_user = $_GET['id_user'];
    $id_param = $_GET['id_param'];

    $wpdb->query("DELETE FROM trovaimm_verifiche WHERE id_user ='$id_user' AND id_param ='$id_param' ");

    if (
        $_GET['regione'] == $_GET['regione_old'] &&
        $_GET['category_choise'] == $_GET['category_old'] &&
        $_GET['mq_da'] == $_GET['mq_da_old'] &&
        $_GET['mq_a'] == $_GET['mq_a_old'] &&
        $_GET['comune'] == $_GET['comune_old'] &&
        $_GET['frazione'] == $_GET['frazione_old'] &&
        $_GET['trattativa'] == $_GET['trattativa_old'] &&
        $_GET['provincia'] == $_GET['provincia_old'] &&
        $_GET['add_budget'] == $_GET['budget_old'] &&
        $_GET['add_valuta'] == $_GET['valuta_old']
    ) {

//Post verificati

        $verifiche = $_GET['verifiche'];
        $a = explode(',', $verifiche);
        foreach ($a as $verifica) {

            if ($verifica != null) {

                $mylink = $wpdb->get_row("SELECT * FROM trovaimm_verifiche WHERE id_user = '$_GET[id_user]' AND id_post='$verifica'");

                if ($mylink == null) {
                    $wpdb->insert('trovaimm_verifiche', array('id_post' => $verifica, 'id_user' => $_GET['id_user'], 'data_creazione' => $date, 'id_param' => $_GET['id_param'] + 1, 'stato' => '1'));
                } else {
                }

            }/*end if*/

        }/*end foreach*/

//Post non verificati
        $non_verificati = $_GET['non_verificati'];
        $b = explode(',', $non_verificati);
        foreach ($b as $verifica) {
            if ($verifica != null) {
                $mylink = $wpdb->get_row("SELECT * FROM trovaimm_verifiche WHERE id_user = '$_GET[id_user]' AND id_post='$verifica'");
                if ($mylink == null) {
                    $wpdb->insert('trovaimm_verifiche', array('id_post' => $verifica, 'id_user' => $_GET['id_user'], 'data_creazione' => $date, 'id_param' => $_GET['id_param'] + 1, 'stato' => '0'));
                }
            }/*end if*/
        }/*en foreach*/


    }/*fine if*/
    else {
// Cerco e aggiungo eventuali immobili che potrebbero interessare
        global $post;

        if (!empty($_GET['regione'])) {
            $mylink = $wpdb->get_row("SELECT * FROM regioni WHERE id = '$_GET[regione]'");
            $nome_regione = $mylink->nome;
            $region = array('key' => 'imm_regione', 'value' => array($_GET['regione'], $nome_regione, false), 'compare' => 'IN',);
        }

        if (!empty($_GET['city_reference'])) {
            $mylink = $wpdb->get_row("SELECT * FROM province WHERE id = '$_GET[provincia]'");
            $nome_provincia = $mylink->nome;
            $provincia = array('key' => 'imm_provincia', 'value' => array($_GET['provincia'], $nome_provincia, false), 'compare' => 'IN');
        }

        if (!empty($_GET['comune'])) {
            $mylink = $wpdb->get_row("SELECT * FROM comuni WHERE id = '$_GET[comune]'");
            $nome_comune = $mylink->nome;
            $comune = array('key' => 'imm_comune', 'value' => array($_GET['comune'], $nome_comune, false), 'compare' => 'IN');
        }

        if (!empty($_GET['frazione'])) {
            $mylink = $wpdb->get_row("SELECT * FROM zone WHERE id = '$_GET[frazione]'");
            $nome_zona = $mylink->nome;
            $frazione = array('key' => 'imm_quartiere_list', 'value' => array($_GET['frazione'], $nome_zona, false), 'compare' => 'IN');
        }

        if (!empty($_GET['trattativa'])) {
            $trattativa = array('key' => 'imm_trattativa', 'value' => array($_GET['trattativa']), 'compare' => 'IN',);
        }

        if ($budget > 0 && !empty($budget)) {
            $cerca_budget = array('key' => 'imm_prezzo', 'value' => $budget, 'type' => 'NUMERIC', 'compare' => '<=',);
        }

        $args = array(
            'post_type' => 'post',
            'meta_query' => array(
                'relation' => 'AND', $region, $provincia, $comune, $frazione, $trattativa, $cerca_budget,
                array(
                    'key' => 'imm_valuta',
                    'value' => array(false, $_GET['add_valuta']),
                    'compare' => 'IN',
                ),

                array(
                    'key' => 'imm_metriquadri',
                    'value' => $_GET['mq_da'],
                    'type' => 'NUMERIC',
                    'compare' => '>=',
                ),
                array(
                    'key' => 'imm_metriquadri',
                    'value' => $_GET['mq_a'],
                    'type' => 'NUMERIC',
                    'compare' => '<=',
                ),
                array(
                    'key' => 'imm_prezzo',
                    'value' => 0,
                    'type' => 'NUMERIC',
                    'compare' => '>=',
                ),
            ),
            'orderby' => '',
            'order' => 'DESC',
            'cat' => $_GET['category_choise'],
            'posts_per_page' => -1,
        );
        $query = new WP_Query($args);
        if ($query->have_posts()) {
            while ($query->have_posts()) :
                $query->the_post();
                $wpdb->insert('trovaimm_verifiche', array('id_post' => $post->ID, 'id_user' => $id_user, 'data_creazione' => $date, 'id_param' => $id_param + 1, 'stato' => '0'));
            endwhile;
        }
    }/*fine else*/
    echo 0;
}/*fine edit param*/

/********************************Delete singolo parametro*****************************/

elseif (isset($_GET['delete_param'])) {
    $wpdb->query("DELETE FROM trovaimm_references WHERE id='$_GET[delete_param]' ");
    $wpdb->query("DELETE FROM trovaimm_verifiche WHERE id_param='$_GET[delete_param]' ");
    echo 0;
} /********************************Aggiunge select provincia in base a regione selezionata******************/

elseif (isset($_GET['add_city'])) {
    $city = $_GET['city'];
    $mylink = $wpdb->get_results("SELECT * FROM province WHERE id_regione = '$city' ORDER BY nome ASC ");
    echo "<option value=''>-</option>";
    foreach ($mylink as $provincia) {

        ?>
        <option value="<?php echo $provincia->id; ?>"><?php echo $provincia->nome; ?></option>
    <?php
    }
} /********************************Aggiunge select comune in base a provincia selezionata******************/

elseif (isset($_GET['add_comune'])) {

    $comune = $_GET['comune'];
    $mylink = $wpdb->get_results("SELECT * FROM comuni WHERE id_provincia = '$comune' ORDER BY nome ASC ");
    echo "<option value=''>-</option>";
    foreach ($mylink as $comune) {
        ?>
        <option value="<?php echo $comune->id; ?>"><?php echo $comune->nome; ?></option>
    <?php }
} /********************************Aggiunge select zona/frazione in base al comune selezionato******************/

elseif (isset($_GET['add_zone'])) {
    $zona = $_GET['zona'];
    $mylink = $wpdb->get_results("SELECT * FROM zone WHERE id_comune = '$zona' ORDER BY nome ASC ");
    echo "<option value=''>-</option>";
    foreach ($mylink as $zona) {
        ?>
        <option value="<?php echo $zona->id; ?>"><?php echo $zona->nome; ?></option>
    <?php }
}
?>