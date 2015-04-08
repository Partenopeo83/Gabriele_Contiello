<img src='<?php echo plugins_url('TrovaImmobile/images/trova_icon.png'); ?>'/>
<h2>Trova L'immobile</h2>
<p>
    Di seguito inserisci la mail alla quale Trova l'immobile notificherà le occorrenze trovate tra le richieste degli
    utenti e gli immobili già inseriti a sistema !
</p>
<form action="#" method="post" enctype="multipart/form-data">
    <?php
    if (isset($_POST['mail_notification'])) {
        $new_mail = $_POST['mail_notification'];
        $wpdb->query("UPDATE trovaimm_options SET valore_opzione = '$new_mail' WHERE  nome_opzione = 'mail_notifiche'");
    }
    $fivesdrafts = $wpdb->get_results("SELECT * FROM trovaimm_options WHERE nome_opzione='mail_notifiche' ");
    foreach ($fivesdrafts as $mail) {
        $mail = $mail->valore_opzione;
    }
    ?>
    <table style="width:1000px;">
        <tr valign="top">
            <th scope="row" style="width:200px;"><label for="add-nome" title="Nome" class="label">Mail a cui inviare le
                    notifiche</label></th>
            <td><input type="text" name="mail_notification" value="<?php echo $mail ?>" class="mail_notification"
                       style='width:300px; text-align:center;'/></td>
        </tr>
        <tr valign="top">
            <td>
                <button id="submit" class="button-primary" type="submit" name="save_options">Salva</button>
            </td>
        </tr>
    </table>
</form>