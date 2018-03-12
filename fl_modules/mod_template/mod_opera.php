<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
	


if(isset($_POST['template'])) {

$template_info = GRD('fl_msg_template',check($_POST['template']));
$template_info['messaggio'] = converti_txt($template_info['messaggio']);


echo json_encode($template_info); 
@mysql_close(CONNECT);
exit;

}


mysql_close(CONNECT);
header("location: ./?esito=".$esito);
exit;




?>
