<?php 
// Controlli di Sicurezza
require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
 
include("../../fl_inc/headers.php");

?>


<div id="content">

<?php if(isset($_GET['esito'])) { $class = (isset($_GET['success'])) ? 'green' : 'green'; echo '<p class="esito '.$class.'">'.check($_GET['esito']).'</p>'; }  ?>

<form id="scheda" action="../mod_basic/action_modifica.php?sezione=<?php echo @check($_GET['sezione']); ?>" method="post" enctype="multipart/form-data">

<?php 
$tab_id = 60;
$tabella = $tables[$tab_id];

include('../mod_basic/action_estrai.php');   ?>
<input type="hidden" name="info" value="../mod_account/mod_sicurezza.php?esito=Salvataggio Eseguito" />


</form>

</div>
