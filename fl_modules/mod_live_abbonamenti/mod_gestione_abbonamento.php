<?php

// Controlli di Sicurezza
require_once '../../fl_core/autentication.php';
include 'fl_settings.php';
include "../../fl_inc/headers.php";

$user_id = check($_GET['id']);
include '../../fl_inc/testata_mobile.php';

$query = "SELECT *,abb_us.id as abus,DATE_FORMAT(data_avvio,'%d/%m/%Y %H:%i:%s ') as data_format_start,DATE_FORMAT(data_fine,'%d/%m/%Y %H:%i:%s ') as data_format_end  FROM fl_abb_user  abb_us JOIN fl_abbonamenti abb ON abb.id = abb_us.id_abb JOIN fl_periodi p ON p.id = abb.periodo  WHERE abb_us.id_user = '" . $user_id . "'";
$risultato = mysql_query($query, CONNECT);

echo '<br><br><br><br><div id="content_scheda"><table class="dati">';

if (mysql_affected_rows() == 0) {
    //selezione abbonamenti
    $select_free = "SELECT *,abb.id as abbId FROM fl_abbonamenti abb JOIN fl_periodi p ON p.id = abb.periodo  WHERE free = 1 AND attivo = 1";
    $risultato = mysql_query($select_free, CONNECT);
    echo '<h1>Assegna abbonamento free</h1>';
    while ($riga = mysql_fetch_array($risultato)) { ?>

        <tr><th><?php echo $riga['nome']; ?></th><td><?php echo $riga['durata']; ?></td><td><?php echo $riga['label']; ?></td><td><a href='mod_opera.php?abb=<?php echo base64_encode($riga['abbId']) ?>&user=<?php echo $user_id; ?>' class="button">Attiva</a></td></tr>

<?php }
   
    

} else {
    echo '<h1>Abbonamento attualmente attivo</h1>';
    
    while ($riga = mysql_fetch_array($risultato)) { ?>

        <tr><th><?php echo $riga['nome']; ?></th><td><?php echo $riga['durata']; ?></td><td><?php echo $riga['label']; ?></td>
        <td><?php echo $riga['data_format_start']; ?></td>
        <td><?php echo $riga['data_format_end']; ?></td>
        <td><a href="mod_opera.php?DELabb=<?php echo $riga['abus']; ?>" class="button">Disattiva</a></td></tr>

<?php    }
}

?>
</div>
</table>

