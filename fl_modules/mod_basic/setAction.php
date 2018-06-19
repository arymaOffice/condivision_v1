<?php 

require_once('../../fl_core/autentication.php');

if(isset($_POST['azione'])){
	
	$tab_id = base64_decode(check($_POST['gtx']));
	$id =  base64_decode(check($_POST['id']));
	$azione =  check($_POST['azione']);
	$esito =  check($_POST['esito']);
	$note =  check($_POST['note']);
	actionTracer($tab_id,$id,$azione,$esito,$note);
	echo json_encode(array('action'=>'info','class'=>'red','url'=>'','esito'=>$azione)); 

	}


mysql_close(CONNECT);
exit;




?>
