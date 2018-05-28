<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
unset($_SESSION['POST_BACK_PAGE']);

	$tab_id = 1;
	$tabella = $tables[$tab_id];
	$select = "*";
	$force_id = 2;
	$tabs_div = 0;
	$info = GRD($tabella,2);
	$tab_div_labels = array('id'=>"Profilo",'ip_autorizzato'=>"Configurazioni");
	
include("../../fl_inc/headers.php");?>

<?php include('../../fl_inc/testata.php'); ?>
<?php include('../../fl_inc/menu.php'); ?>
<?php include('../../fl_inc/module_menu.php'); ?>


<body style=" background: #FFFFFF;">



<?php 
if($info['ip_autorizzato'] == $_SERVER['REMOTE_ADDR'] || $_SESSION['user'] == 'sistema') { 

if(isset($_GET['esito'])) { $class = (isset($_GET['success'])) ? 'green' : 'red'; echo '<p class="'.$class.'">'.check($_GET['esito']).'</p>'; }  ?>


<form id="scheda" action="../mod_basic/action_modifica.php" method="post" enctype="multipart/form-data">
<h1>Configurazione Sistema</h1>



<?php include('action_estrai.php');   ?>

<input type="hidden" name="dir_upfile" value="icone_articoli" />
<?php if(isset($_GET['external'])) { ?><input type="hidden" name="external" value="1" /><?php } ?>

</form>

<?php } else { echo '<h1>Non puoi configurare condivision da questo computer</h1>'; } include("../../fl_inc/footer.php"); ?>
