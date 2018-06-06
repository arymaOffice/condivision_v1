<?php 

require_once('../../fl_core/autentication.php');

$id = check($_GET['id']);


include('fl_settings.php'); // Variabili Modulo 

 ?>








<div id="content_scheda">

<?php if(isset($_GET['esito'])) { $class = (isset($_GET['success'])) ? 'green' : 'red'; echo '<p class="esito '.$class.'">'.check($_GET['esito']).'</p>'; }  ?>

<div id="map-canvas"></div>
<form id="scheda" class="loadData" action="<?php echo ROOT . $cp_admin; ?>fl_modules/mod_basic/action_modifica.php" method="post" enctype="multipart/form-data">

<input type="hidden" name="dir_upfile" value="icone_articoli" />

<?php include('../mod_basic/action_estrai.php');  ?>

</form>

<script>

</script>


