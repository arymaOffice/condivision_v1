<?php

// Controlli di Sicurezza
require_once '../../fl_core/autentication.php';
include 'fl_settings.php';
include "../../fl_inc/headers.php";

$user_id = check($_GET['id']);
include '../../fl_inc/testata_mobile.php';

$query = "SELECT *,DATE_FORMAT(data_avvio,'%d/%m/%Y %H:%i:%s ') as data_format_start,DATE_FORMAT(data_fine,'%d/%m/%Y %H:%i:%s ') as data_format_end  FROM fl_abb_user  abb_us JOIN fl_abbonamenti abb ON abb.id = abb_us.id_abb JOIN fl_periodi p ON p.id = abb.periodo  WHERE abb_us.id_user = '" . $user_id . "'";
$risultato = mysql_query($query, CONNECT);

echo '<br><br><br><br><table class="dati">';

if (mysql_affected_rows() == 0) {
    //selezione abbonamenti
    $select_free = "SELECT * FROM fl_abbonamenti WHERE free = 1 AND attivo = 1";
    $risultato = mysql_query($select_free, CONNECT);
    echo '<h1>Assegna abbonamento free</h1>';
    while ($riga = mysql_fetch_array($risultato)) { ?>

        <tr><th><?php echo $riga['nome']; ?></th><td><?php echo $riga['durata']; ?></td><td><?php echo $riga['label']; ?></td><td><a href="" class="button">Attiva</a></td></tr>

<?php }
   
    

} else {
    echo '<h1>Abbonamento attualmente attivo</h1>';
    
    while ($riga = mysql_fetch_array($risultato)) { ?>

        <tr><th><?php echo $riga['nome']; ?></th><td><?php echo $riga['durata']; ?></td><td><?php echo $riga['label']; ?></td>
        <td><?php echo $riga['data_format_start']; ?></td>
        <td><?php echo $riga['data_format_end']; ?></td>
        <td><a href="" class="button">Disattiva</a></td></tr>

<?php    }
}

?>

</table>

