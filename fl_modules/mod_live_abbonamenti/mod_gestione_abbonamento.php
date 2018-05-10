<?php

// Controlli di Sicurezza
require_once '../../fl_core/autentication.php';
$_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];
include 'fl_settings.php';
include "../../fl_inc/headers.php";

$user_id = check($_GET['id']);
include '../../fl_inc/testata_mobile.php';

$query = "SELECT *,DATE_FORMAT(data_avvio,'%d/%m/%Y %H:%i:%s ') as data_format_start,DATE_FORMAT(data_fine,'%d/%m/%Y %H:%i:%s ') as data_format_end  FROM fl_abb_user  abb_us JOIN fl_abbonamenti abb ON abb.id = abb_us.id_abb WHERE abb_us.id_user = '" . $user_id . "'";
$risultato = mysql_query($query, CONNECT);

echo '<br><br><br><br>';

if (mysql_affected_rows() == 0) {
    //selezione abbonamenti
    $select_free = "SELECT * FROM fl_abbonamenti WHERE free = 1 AND attivo = 1";
    $risultato = mysql_query($select_free, CONNECT);
    echo '<table>';
    while ($riga = mysql_fetch_array($risultato)) { ?>

        <tr><th><?php echo $riga['nome']; ?></th></tr>

<?php }
    echo '</table>';
    

} else {

    while ($riga = mysql_fetch_array($risultato)) {

        print_r($riga);

    }
}

?>


