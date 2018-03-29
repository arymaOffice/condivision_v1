<?php

require_once '../../fl_core/autentication.php';

include "../../fl_inc/headers.php";

$module_title = 'connessioni attuali';
$new_button = '';

include '../../fl_inc/testata.php';
include '../../fl_inc/menu.php';
include '../../fl_inc/module_menu.php';
?>


 <div style="font-size: 100%; text-align: left; padding: 10px; color: gray;">
  <?php

$query = "SELECT * FROM `fl_one_session` WHERE attivo = 1 ORDER BY id DESC;";
$risultato = mysql_query($query, CONNECT);
if (mysql_affected_rows() == 0) {echo "<h2>Nessun cliente connesso!</h2>";} else {

    echo '<p style="font-weight:bold;">Clienti con sessione aperta  <span style="color:green;" >' . mysql_num_rows($risultato) . '</span></p>';
    echo '<table>';
    while ($riga = mysql_fetch_array($risultato)) {
        echo '<tr><td><p>' . $riga['utente'] . ' </p></td><td><a href="mod_opera.php?kill&mail='. $riga['utente'].'" class="button">Termina sessione</a></td>';
    }
    echo '</table>';
    

}
?>
    </div>