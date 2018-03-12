<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 


include("../../fl_inc/headers.php");?>



<?php if(!isset($_GET['external'])) include('../../fl_inc/testata.php'); ?>
<?php if(!isset($_GET['external'])) include('../../fl_inc/menu.php'); ?>
<?php if(!isset($_GET['external'])) include('../../fl_inc/module_menu.php'); ?>

<?php 
$page = (isset($_SESSION['view'])) ? $_SESSION['view'] : "mod_box";

include($page.'.php'); 


if(isset($_SESSION['move'])) {
	$element = GRD('fl_dms',$_SESSION['move']);
	$action = ($_SESSION['action'] == 'copy') ?  ' Copia ': ' Sposta ' ;
	$incolla = (isset($_GET['c'])) ? ' href="mod_opera.php?paste='.check($_GET['c']).'"  onClick="return conferma(\'Incollare '.$element['label'].' in questa catella?\');"' : ' href="#" onClick="alert(\'Entra in una cartella per incollare un file\');" title="Seleziona una cartella ed incolla il file" ';
	echo '<div class="info_alert right"><a '.$incolla.'><i class="fa fa-clipboard"></i>
 '.$action.$element['label'].'</a><a href="mod_opera.php?unset" onClick="return conferma(\'Annullare?\');" class="elimina">x</a></div>';
} 

if(!is_dir(DMS_ROOT)) { echo "Creata cartella Root ".DMS_ROOT." "; mkdir(DMS_ROOT,0777); }

?>


<?php include("../../fl_inc/footer.php"); ?>
