<?php

function aggiungiuser_call_trovaimmobile()
{ ?>

    <?php include("elab/avvisi.php") ?>

    <h3>Aggiungi Utente</h3>
    <div class="add-user-box">
        <form action="<?php echo plugin_dir_url(__FILE__); ?>elab/elab-add-user.php" method="get"
              class='add-user-trovaimmobili' enctype="multipart/form-data">
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><label for="add-nome" title="Nome" class="label">Nome</label></th>
                    <td><input name="add_user_name" type="text" id="add-nome" value="" class="regular-text"/>

                        <div class="error-check"><em>Campo obbligatorio</em></div>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="add-cognome" title="Cognome">Cognome</label></th>
                    <td><input name="add_user_surname" type="text" id="add-cognome" value="" class="regular-text"/>

                        <div class="error-check"><em>Campo obbligatorio</em></div>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="add-mail" title="Email">Email</label></th>
                    <td><input name="add_user_mail" type="text" id="add-mail" value="" class="regular-text"/>

                        <div class="error-check"><em>Campo obbligatorio</em></div>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="add-phone" title="Email">Phone</label></th>
                    <td><input name="add_user_phone" type="text" id="add-phone" value="" class="regular-text"/>

                        <div class="error-check"><em>Campo obbligatorio</em></div>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="add-activation" title="Activation">Attivo ?</label></th>
                    <td>
                        <input name="add_user_activation" type="radio" id="add-activation" value="1" checked="checked"/>
                        Attivo<br/>
                        <input name="add_user_activation" type="radio" id="add-activation" value="0"/> Non Attivo
                        <div class="error-check"><em>Campo obbligatorio</em></div>
                    </td>
                </tr>
            </table>
            <table class="form-table small-table">
                <tr valign="top">
                    <th scope="row"><label for="add-MQ" title="MQ">MQ</label></th>
                    <td>
                        Da : <select name="add_MQ" id="add_MQ_da">
                            <option value="">-</option>
                            <?php for ($i = 50; $i <= 1000; $i += 50) { ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option> <?php } ?>
                        </select>

                        A :
                        <select name="add_MQ" id="add_MQ_a">
                            <option value="">-</option>
                            <?php for ($i = 50; $i <= 1000; $i += 50) { ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option> <?php } ?>
                        </select>

                        <div class="error-check"><em>Campo obbligatorio</em></div>
                    </td>
                </tr>
                <tr valign="top" class="trattativa-box">
                    <th scope="row"><label for="add-trattativa" title="Trattativa">Tipo Trattativa</label></th>
                    <td>
                        <select name="add_trattativa" class="add_trattativa">
                            <option value="">-</option>
                            <option value="Vendita">Vendita</option>
                            <option value="Affitto">Affitto</option>
                        </select>
                    </td>
                </tr>
                <tr valign="top" class="valuta-box">
                    <th scope="row"><label for="add-valuta" title="Budget">Valuta</label></th>
                    <td>
                        <select name="add_valuta" class="add_valuta">
                            <option value="euro">€</option>
                            <option value="dollaro">$</option>
                        </select>
                    </td>
                </tr>
                <tr valign="top" class="budget-box">
                    <th scope="row"><label for="add-budget" title="Budget">Budget</label></th>
                    <td>
                        <input type="text" name="add_budget" value="0" onkeyup="ControlloNumero(this)"/> €
                    </td>
                </tr>
                <tr valign="top" class="region-box">
                    <th scope="row"><label for="add-regione" title="Regione">Regione</label></th>
                    <td>
                        <select name="add_region" class="add_region">
                            <option value="">-</option>
                            <?php
                            global $wpdb;
                            $fivesdrafts = $wpdb->get_results("SELECT * FROM regioni ORDER BY nome ASC");
                            foreach ($fivesdrafts as $fivesdraft) {
                                ?>
                                <option value="<?php echo $fivesdraft->id; ?>"><?php echo $fivesdraft->nome; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr valign="top" class="city-box">
                    <th scope="row"><label for="add-zona" title="Città">Provincia</label></th>
                    <td class="province-select">
                        <select name="add_city" class="add_city">
                        </select>
                    </td>
                </tr>
                <tr valign="top" class="comune-box">
                    <th scope="row"><label for="add-comune" title="Comune">Comune</label></th>
                    <td class="comune-select">
                        <select name="add_comune" class="add_comune">
                        </select>
                    </td>
                </tr>
                <tr valign="top" class="frazione-box">
                    <th scope="row"><label for="add-zona" title="Zona / Quartiere">Frazione</label></th>
                    <td>
                        <select name="frazione" class="frazione">
                        </select>
                    </td>
                </tr>
            </table>
            <div class="floating"><?php $category_check = "5";
                $exclude = "";
                category_list_trovaimmobile($category_check, $exclude); ?></div>
            <div class="clear"></div>
            <p class="submit">
                <button id="submit" class="button-primary" type="submit" name="sub_user">Salva utente</button>
            </p>
        </form>
    </div>
<?php } ?><?php
function tt_render_list_page_trovaimmobile()
{

    //Create an instance of our package class...
    $testListTable = new TT_Example_List_Table();
    //Fetch, prepare, sort, and filter our data...
    $testListTable->prepare_items();

    ?>
    
    <h2>Lista Utenti registrati</h2>
    <form action="#" name="search_user" method="post" enctype="multipart/form-data" style="float:right;margin-right:20px;">
           <?php if (isset($_GET['user_search'])) {
    $valore = $_GET['user_search'];
    global $valore;
} ?>
          <label class="screen-reader-text" for="search_user">Cerca articoli:</label>
          <input type="search" class="" id='search_user' name="search_user" value=""  style='width:250px;' />
          <input id="search-submit" class="button search-user" type="submit" value="Cerca Utente" name="">
    </form>
    <!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
        <form id="movies-filter" method="get">
            <!-- For plugins, we also need to ensure that the form posts back to our current page -->
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
            <!-- Now we can render the completed list table -->
            <?php $testListTable->display($valore) ?>
        </form>
    </div>
<?php } ?><?php
function edituser_call_trovaimmobile()
{ ?>

    <?php
    include("elab/avvisi.php");

    global $wpdb;
    $id = $_GET['movie'];

    $query = "select * from  trovaimm_user where id='$id'";
    $data = $wpdb->get_row($query, 'ARRAY_A');

    $query_reference = "select * from  trovaimm_references where id_user='$id' order by data_inserimento,id DESC";
    $data_reference = $wpdb->get_results($query_reference, 'ARRAY_A');

    ?>

    <h3>Edit Utente</h3>
    <form method="get" action="#" class='edit-user-trovaimmobile' enctype="multipart/form-data">
        <table class="form-table">
            <tr valign="top">
                <th scope="row"><label for="add-nome" title="Nome" class="label">Nome</label></th>
                <td><input name="add_user_name" type="text" id="add-nome" value="<?php echo $data['nome']; ?>"
                           class="regular-text"/>

                    <div class="error-check"><em>Campo obbligatorio</em></div>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="add-cognome" title="Cognome">Cognome</label></th>
                <td><input name="add_user_surname" type="text" id="add-cognome" value="<?php echo $data['cognome']; ?>"
                           class="regular-text"/>

                    <div class="error-check"><em>Campo obbligatorio</em></div>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="add-mail" title="Email">Email</label></th>
                <td><input name="add_user_mail" type="text" id="add-mail" value="<?php echo $data['mail']; ?>"
                           class="regular-text"/>

                    <div class="error-check"><em>Campo obbligatorio</em></div>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="add-phone" title="Email">Phone</label></th>
                <td><input name="add_user_phone" type="text" id="add-phone" value="<?php echo $data['phone']; ?>"
                           class="regular-text"/>

                    <div class="error-check"><em>Campo obbligatorio</em></div>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="add-activation" title="Activation">Attivo ?</label></th>
                <td>
                    <input name="add_user_activation" type="radio" id="add-activation"
                           value="1" <?php if ($data['stato'] == "1") {
                        echo "checked='checked'";
                    } ?>/> Attivo<br/>
                    <input name="add_user_activation" type="radio" id="add-activation"
                           value="0" <?php if ($data['stato'] == "0") {
                        echo "checked='checked'";
                    } ?>  /> Non Attivo
                    <div class="error-check"><em>Campo obbligatorio</em></div>
                </td>
                <input type="hidden" name="id_user" id="id_user" value="<?php echo $data['id']; ?>"/>
            </tr>
        </table>
        <div class="clear"></div>
        <p class="submit">
            <button id="submit" class="button-primary" type="submit" name="edit_user">Salva utente</button>
            <button id="submit" class="button-primary add_param_link" type="button" name="add_param_link">Aggiungi
                Ricerca
            </button>
        </p>
    </form>
    <!----------------------------------------Box Nascosto----------------------------->
    <div class="add-param-box">
        <form method="post" action="#" class='add-param-trovaimmobili' enctype="multipart/form-data">
            <table class="form-table small-table">
                <tr valign="top">
                    <th scope="row"><label for="add-MQ" title="MQ">MQ</label></th>
                    <td>

                        Da : <select name="add_MQ" id="add_MQ_da">

                            <option value="">-</option>
                            <?php for ($i = 50; $i <= 1000; $i += 50) { ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option> <?php } ?>

                        </select>

                        A :
                        <select name="add_MQ" id="add_MQ_a">

                            <option value="">-</option>
                            <?php for ($i = 50; $i <= 1000; $i += 50) { ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option> <?php } ?>

                        </select>

                        <div class="error-check"><em>Campo obbligatorio</em></div>
                    </td>

                </tr>

                <tr valign="top" class="trattativa-box">
                    <th scope="row"><label for="add-trattativa" title="Trattativa">Tipo Trattativa</label></th>
                    <td>
                        <select name="add_trattativa" class="add_trattativa"
                        <option value="">-</option>
                        <option value="Vendita">Vendita</option>
                        <option value="Affitto">Affitto</option>
                        </select>
                    </td>
                </tr>
                <tr valign="top" class="valuta-box">
                    <th scope="row"><label for="add-valuta" title="Valuta">Valuta</label></th>
                    <td>
                        <select name="add_valuta" class="add_valuta">
                            <option value="euro">€</option>
                            <option value="dollaro">$</option>
                        </select>
                    </td>
                </tr>
                <tr valign="top" class="budget-box">
                    <th scope="row"><label for="add-budget" title="Budget">Budget</label></th>
                    <td>
                        <input type="text" class="add_budget" name="add_budget" value="0"
                               onkeyup="ControlloNumero(this)"/> €
                    </td>
                </tr>
                <tr valign="top" class="region-box">
                    <th scope="row"><label for="add-regione" title="Regione">Regione</label></th>
                    <td>
                        <select name="add_region" class="add_region">
                            <option value="">-</option>
                            <?php
                            global $wpdb;
                            $fivesdrafts = $wpdb->get_results("SELECT * FROM regioni ORDER BY nome ASC");
                            foreach ($fivesdrafts as $fivesdraft) {
                                ?>
                                <option value="<?php echo $fivesdraft->id; ?>"><?php echo $fivesdraft->nome; ?></option>
                            <?php
                            }
                            ?>

                        </select>
                    </td>
                </tr>
                <tr valign="top" class="city-box">
                    <th scope="row"><label for="add-zona" title="Città">Provincia</label></th>
                    <td class="province-select">
                        <select name="add_city" class="add_city">
                        </select>
                    </td>
                </tr>
                <tr valign="top" class="comune-box">
                    <th scope="row"><label for="add-comune" title="Comune">Comune</label></th>
                    <td class="comune-select">
                        <select name="add_comune" class="add_comune">
                        </select>
                    </td>
                </tr>
                <tr valign="top" class="frazione-box">
                    <th scope="row"><label for="add-zona" title="Zona / Quartiere">Frazione</label></th>
                    <td>
                        <select name="frazione" class="frazione">
                        </select>
                    </td>
                </tr>
            </table>
            <div class="floating"><?php $category_check = "5";
                category_list_trovaimmobile($category_check, $exclude); ?></div>
            <div class="clear"></div>
            <input type="hidden" name="id_user" class="id_user" value="<?php echo $data['id']; ?>"/>

            <p class="submit">
                <button id="submit" class="button-primary" type="submit" name="add-param">Salva</button>
            </p>
        </form>
    </div>
    <!--------------------------Ricerche per l'utente---------------------------------->
    <?php foreach ($data_reference as $dato) { ?>

    <form class="edit-search" enctype="multipart/form-data" method="post" action="#">
        <table class="form-table small-table">
            <tr valign="top" style="display:table-row;">
                <th scope="row"><label for="add-MQ" title="MQ">MQ</label></th>
                <td>
                    Da :
                    <select name="add_MQ_da" class="add_MQ_da">
                        <option value="">-</option>
                        <?php for ($i = 50; $i <= 1000; $i += 50) { ?>
                            <option value="<?php echo $i; ?>" <?php if ($dato['mq_da'] == $i) {
                                echo 'selected="selected"';
                            } ?>><?php echo $i; ?></option> <?php } ?>
                    </select>
                    A :
                    <select name="add_MQ_a" class="add_MQ_a">
                        <option value="">-</option>
                        <?php for ($i = 50; $i <= 1000; $i += 50) { ?>
                            <option value="<?php echo $i; ?>" <?php if ($dato['mq_a'] == $i) {
                                echo 'selected="selected"';
                            } ?>><?php echo $i; ?></option> <?php } ?>
                    </select>

                    <div class="error-check"><em>Campo obbligatorio</em></div>
                </td>
            </tr>
            <tr valign="top" class="trattativa-box">
                <th scope="row"><label for="add-trattativa" title="Trattativa">Tipo Trattativa</label></th>
                <td>
                    <select name="add_trattativa" class="add_trattativa">
                        <option value="">-</option>
                        <option
                            value="Vendita" <?php if ($dato['tipo_trattativa'] == "Vendita") { ?> selected="selected" <?php } ?>>
                            Vendita
                        </option>
                        <option
                            value="Affitto" <?php if ($dato['tipo_trattativa'] == "Affitto") { ?> selected="selected" <?php } ?>>
                            Affitto
                        </option>
                    </select>
                </td>
            </tr>
            <tr valign="top" class="valuta-box">
                <th scope="row"><label for="add-valuta" title="Budget">Valuta</label></th>
                <td>
                    <select name="add_valuta" class="add_valuta">
                        <option value="euro" <?php if ($dato['valuta'] == "euro") { ?> selected="selected" <?php } ?>>
                            €
                        </option>
                        <option
                            value="dollaro" <?php if ($dato['valuta'] == "dollaro") { ?> selected="selected" <?php } ?>>
                            $
                        </option>
                    </select>
                </td>
            </tr>
            <tr valign="top" class="budget-box">
                <th scope="row"><label for="add-budget" title="Budget">Budget</label></th>
                <td>
                    <input type="text" class="add_budget" name="add_budget"
                           value="<?php echo number_format($dato['budget'], 0, ',', '.'); ?>"
                           onkeyup="ControlloNumero(this)"/>
                </td>
            </tr>
            <tr valign="top" class="region-box" style="display:table-row;">
                <th scope="row"><label for="add-regione" title="Regione">Regione</label></th>
                <td>
                    <select name="add_region" class="add_region">
                        <option value="">-</option>
                        <?php
                        global $wpdb;
                        $fivesdrafts = $wpdb->get_results("SELECT * FROM regioni ORDER BY nome ASC");
                        foreach ($fivesdrafts as $fivesdraft) {
                            ?>
                            <option
                                value="<?php echo $fivesdraft->id; ?>" <?php if ($fivesdraft->id == $dato['regione']) { ?> selected="selected"
                                <?php $nome_regione_ricercata = $fivesdraft->nome;
                                $id_regione_ricercata = $fivesdraft->id;
                            } ?>><?php echo $fivesdraft->nome; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr valign="top" class="city-box" style="display:table-row;">
                <th scope="row"><label for="add-zona" title="Città">Provincia</label></th>
                <td class="province-select">
                    <select name="add_city" class="add_city">
                        <option value="">-</option>
                        <?php $fivesdrafts_province = $wpdb->get_results("SELECT * FROM province WHERE id_regione='$dato[regione]' ORDER BY nome ASC");
                        foreach ($fivesdrafts_province as $province) {
                            ?>
                            <option
                                value="<?php echo $province->id; ?>" <?php if ($province->id == $dato['provincia']) { ?> selected="selected"
                                <?php $nome_province_ricercata = $province->nome;
                                $id_province_ricercata = $province->id;
                            } ?> > <?php echo $province->nome; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr valign="top" class="comune-box" style="display:table-row;">
                <th scope="row"><label for="add-comune" title="Comune">Comune</label></th>
                <td class="comune-select">
                    <select name="add_comune" class="add_comune">
                        <option value="">-</option>
                        <?php $fivesdrafts_comune = $wpdb->get_results("SELECT * FROM comuni WHERE id_provincia = '$dato[provincia]' ORDER BY nome ASC");
                        foreach ($fivesdrafts_comune as $comune) {
                            ?>
                            <option
                                value="<?php echo $comune->id; ?>" <?php if ($comune->id == $dato['comune']) { ?> selected="selected"
                                <?php $nome_comune_ricercata = $comune->nome;
                                $id_comune_ricercata = $comune->id;
                            } ?>><?php echo $comune->nome; ?></option>
                        <?php
                        } ?>
                    </select>
                </td>
            </tr>
            <tr valign="top" class='frazione-box' <?php if ($dato['comune'] == "63049"){ ?>
                style="display:table-row; <?php } ?>">
                <th scope="row"><label for="add-zona" title="Zona / Quartiere">Frazione</label></th>
                <td>
                    <select name="frazione" class="frazione">
                        <option value="">-</option>
                        <?php $fivesdrafts_frazioni = $wpdb->get_results("SELECT * FROM zone WHERE id_comune = '$dato[comune]' ORDER BY nome ASC");
                        foreach ($fivesdrafts_frazioni as $frazione) {
                            ?>
                            <option
                                value="<?php echo $frazione->id; ?>" <?php if ($frazione->id == $dato['frazione']) { ?> selected="selected" <?php $nome_frazione_ricercata = $frazione->nome;
                                $id_frazione_ricercata = $frazione->id;
                            } ?>><?php echo $frazione->nome; ?></option>
                        <?php
                        } ?>
                    </select>
                </td>
            </tr>
            <tr valign="top">
                <input type="hidden" name="id_user" class="id_user" value="<?php echo $data['id']; ?>"/>
                <input type="hidden" value="<?php echo $dato['id']; ?>" class='id_param' name="id_param"/>
        </table>
        <div class="floating"><?php category_list_trovaimmobile($dato['id_category'], $exclude); ?></div>
        <div class="input_olds">
            <input type="hidden" class="category_old" name='category_old' value="<?php echo $dato['id_category']; ?>"/>
            <input type="hidden" class="frazione_old" name="frazione_old" value="<?php echo $dato['frazione']; ?>"/>
            <input type="hidden" class="comune_old" name='comune_old' value="<?php echo $dato['comune']; ?>"/>
            <input type="hidden" class="provincia_old" name='provincia_old' value="<?php echo $dato['provincia']; ?>"/>
            <input type="hidden" class="regione_old" name='regione_old' value="<?php echo $dato['regione']; ?>"/>
            <input type="hidden" class="trattativa_old" name='trattativa_old'
                   value="<?php echo $dato['tipo_trattativa']; ?>"/>
            <input type="hidden" class="mq_a_old" name='mq_a_old' value="<?php echo $dato['mq_a']; ?>"/>
            <input type="hidden" class="mq_da_old" name='mq_da_old' value="<?php echo $dato['mq_da']; ?>"/>
            <input type="hidden" class="budget_old" name='budget_old'
                   value="<?php echo number_format($dato['budget'], 0, ',', '.'); ?>"/>
            <input type="hidden" class="valuta_old" name='valuta_old' value="<?php echo $dato['valuta']; ?>"/>
        </div>
        <div class="report-user">
            <h4>Potrebbe interessare all'utente &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                Verificato?</h4>

            <div class="report-table-box">
                <table>
                    <?php
                    global $post;

                    if (!empty($nome_regione_ricercata) or !empty($id_regione_ricercata)) {
                        $region = array('key' => 'imm_regione', 'value' => array($nome_regione_ricercata, $id_regione_ricercata, false), 'compare' => 'IN',);
                    }

                    if (!empty($nome_province_ricercata) or !empty($id_province_ricercata)) {
                        $provincia = array('key' => 'imm_provincia', 'value' => array($nome_province_ricercata, $id_province_ricercata, false), 'compare' => 'IN');
                    }

                    if (!empty($nome_comune_ricercata) or !empty($id_comune_ricercata)) {
                        $comune = array('key' => 'imm_comune', 'value' => array($nome_comune_ricercata, $id_comune_ricercata, false), 'compare' => 'IN');
                    }

                    if (!empty($nome_frazione_ricercata) or !empty($id_frazione_ricercata)) {
                        $frazione = array('key' => 'imm_quartiere_list', 'value' => array($nome_frazione_ricercata, $id_frazione_ricercata, false), 'compare' => 'IN');
                    }

                    if (!empty($dato['tipo_trattativa'])) {
                        $trattativa = array('key' => 'imm_trattativa', 'value' => array($dato['tipo_trattativa']), 'compare' => 'IN',);
                    }

                    if ($dato['budget'] > 0) {
                        $cerca_budget = array('key' => 'imm_prezzo', 'value' => $dato['budget'], 'type' => 'NUMERIC', 'compare' => '<=',);
                    }


                    $args = array(
                        'post_type' => 'post',
                        'meta_query' => array(
                            'relation' => 'AND', $region, $provincia, $comune, $frazione, $trattativa, $cerca_budget,

                            array(
                                'key' => 'imm_metriquadri',
                                'value' => $dato['mq_da'],
                                'type' => 'NUMERIC',
                                'compare' => '>=',
                            ),
                            array(
                                'key' => 'imm_metriquadri',
                                'value' => $dato['mq_a'],
                                'type' => 'NUMERIC',
                                'compare' => '<=',
                            ),
                            array(
                                'key' => 'imm_valuta',
                                'value' => $dato['valuta'],
                                'type' => 'CHAR',
                                'compare' => 'LIKE',
                            )
                        ),
                        'orderby' => '',
                        'order' => 'DESC',
                        'cat' => $dato['id_category'],
                        'posts_per_page' => -1,
                    );

                    $query = new WP_Query($args);

                    if ($query->have_posts()) {

                        while ($query->have_posts()) :
                            $query->the_post();

                            $post_categories = wp_get_post_categories($post->ID);
                            $metriquadri = get_post_meta($post->ID, 'imm_metriquadri', true);


                            $mylink = $wpdb->get_row("SELECT * FROM trovaimm_verifiche WHERE id_post = '$post->ID' AND id_user = '$data[id]' AND stato='1' ");
                            if ($mylink == null) {
                                $check = "";

                            } else {

                                $check = "checked='checked'";

                            }

                            ?>
                            <tr>
                                <td><a href='<?php echo get_permalink($post->ID);?>'
                                       target="_blank"><?php echo the_title(); ?></a></td>
                                <td style="text-align:center;"><input type="checkbox" <?php echo $check; ?>
                                                                      value='<?php echo $post->ID; ?>'
                                                                      name="verifica_user" class="verifica_user"/></td>
                            </tr>

                        <?php endwhile;

                    } ?>
                </table>
            </div>
        </div>
        <div class="clear"></div>
        <textarea style='width:630px; height:100px;margin-bottom:20px;' name="add-note"
                  id="add-note"><?php if (!empty($dato['note'])) {
                echo $dato['note'];
            } else {
                echo 'Note';
            }; ?></textarea>

        <div class="clear"></div>
        <button style="margin-right:50px;" class="button-primary edit-param" type="submit" name="edit_param">Salva i
            Parametri
        </button>
        <button class="button-primary delete_param" type="button" value="<?php echo $dato['id']; ?>"
                name="delete_param">Cancella
        </button>
    </form>
<?php }/*fine foreach*/ ?>
<?php } ?>