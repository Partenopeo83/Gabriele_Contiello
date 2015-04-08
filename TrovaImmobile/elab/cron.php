<?php
/************************/
//Script che invia una mail se i parametri salvati dagli utenti soddisfano i requisiti
/*************************/
include_once('../../../../wp-config.php');
include_once('../../../../wp-load.php');
include_once('../../../../wp-includes/wp-db.php');

global $wpdb;
$query = mysql_query("SELECT* FROM trovaimm_verifiche WHERE stato = '0'");
while ($temp = mysql_fetch_array($query)) {
    $id_user = $temp['id_user'];
    $array_user[] = $id_user;
}

if (count($array_user) > 0) {

    foreach ($array_user as $user) {
        $mylink = $wpdb->get_row("SELECT * FROM trovaimm_user WHERE id = '$user' ");
        $nome_user = $mylink->nome . " " . $mylink->cognome;
        if ($mylink->stato == "1") {
            $array_nomi_user[] = $nome_user;
            $array_unico = array_unique($array_nomi_user);
        }
    }

    $nomi = "";
    foreach ($array_unico as $nome) {
        $nomi .= $nome . "<br/>";
    }

    $testo = "
<div style='width:100%;height:50px; background-color:#3F6CB2;margin-bottom:25px;'><img src='http://www.gruppoeuropeo.it/wp-content/uploads/2012/12/europeo_immobiliare.png' style='height:50px; width:auto;' /></div>
<div style='width:50%; margin:0 auto;color:#666;font-size:15px;'>
I seguenti utenti registrati al portale, possono essere interessati a 1 o più immobili, verifica e contattali:
<br/><br/><br/>
<span style='color:#999;'>$nomi</span>
</div>
";

    $fivesdrafts = $wpdb->get_results("SELECT * FROM trovaimm_options WHERE nome_opzione='mail_notifiche' ");
    foreach ($fivesdrafts as $mail) {

        $mail_notifica = $mail->valore_opzione;

    }


    if ($nomi != "") {
        $headers = 'From: Gruppo Europeo <info@gruppoeuropeo.it>';
        add_filter('wp_mail_content_type', create_function('', 'return "text/html";'));
        if (wp_mail($mail_notifica, "Trova Immobile - Utenti interessati", $testo, $headers)) {
        } else {
        }
    }
}

?>