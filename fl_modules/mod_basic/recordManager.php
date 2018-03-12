<?php 
require_once('../../fl_core/autentication.php');

$text_editor = 2;
$jquery = 1;

include("../../fl_inc/headers.php"); 


$tab_id = base64_decode(check($_GET['gtx']));
$tabella = $tables[$tab_id];
//include("../../fl_inc/testata_mobile.php");


	function select_type($who){
	/* Gestione Oggetto Statica */
	
	$textareas = array("content"); 
	$select = array();
	$disabled = array();
	$hidden = array("workflow_id","account_id","record_id","operatore","module_type",'data_creazione','data_aggiornamento','main_icon');
	$radio = array('active');
	$text = array();
	$calendario = array();	
	$file = array();
	

	$type = 1;
	
	if(in_array($who,$select)) { $type = 2; }
	if(in_array($who,$textareas)){ $type = 3; }
	if(in_array($who,$disabled)){ $type = 4; }
	if(in_array($who,$hidden)){ $type = 5; }
	if(in_array($who,$radio)){ $type = 8; }
	if(in_array($who,$calendario)){ $type = 20; }
	if(in_array($who,$file)){ $type = 18; }
	if(in_array($who,$text)){ $type = 24; }
	
	return $type;
	}


?>



<div id="container">

<form id="scheda" action="../mod_basic/action_modifica.php" method="post" enctype="multipart/form-data">
<h1>Modifica</h1>
<?php include('../mod_basic/action_estrai.php');  ?>
</form>

</div>

</body></html>
<?php mysql_close(CONNECT); ?>