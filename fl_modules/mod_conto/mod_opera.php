<?php 


// Controllo Login
session_start(); 
if(!isset($_SESSION['user'])){ header("Location: ../../login.php"); exit; }
//require('../../fl_core/core.php'); 

$baseref = explode('?', $_SERVER['HTTP_REFERER']);
$rct = $baseref[0]; 
$val = (count($baseref) > 1) ? $baseref[1] : "";
$valb = explode('#',$val);
$vars = $valb[0];





if(isset($_GET['causale'])){
	
	$customer_id = check($_GET['customer_id']);
	$causale = check($_GET['causale']);
	$data_scadenza = convert_data(check($_GET['data_scadenza']),1);
	$metodo_di_pagamento = check($_GET['metodo_di_pagamento']);
	$importo = check($_GET['importo']);
	$status_pagato = (isset($_GET['status_pagamento'])) ? check($_GET['status_pagamento']) : 3;
	if($causale == 0)  $status_pagato = 3;
	if($causale == 1)  $status_pagato = 1;
	if($causale == 2)  $status_pagato = 1;
	if($causale == 4)  $status_pagato = 3;
	$query = "INSERT INTO `fl_pagamenti` (`id`, `status_pagamento`, `proprietario`, `marchio`, `customer_rel`, `causale`, `rif_ordine`, `scadenza_pagamento`, `metodo_di_pagamento`, `data_operazione`, `importo`, `estremi_del_pagamento`, `note`, `data_creazione`, `data_aggiornamento`, `operatore`, `ip`) 
	VALUES (NULL, '$status_pagato', '".$_SESSION['number']."', '0', '$customer_id', '$causale', '', '$data_scadenza', '$metodo_di_pagamento', '', '$importo', '', '', '".date('Y-m-d H:i')."', '".date('Y-m-d H:i')."','".$_SESSION['number']."', '$ip');";
	
	if($causale > -1 && $importo != '') mysql_query($query,CONNECT);
	
	}
	

if(isset($_GET['change_status'])){
	
	$pagamento_id = check($_GET['pagamento_id']);
	$change_status = check($_GET['change_status']);

	$data_operazione = (isset($_GET['data_operazione'])) ? ", data_operazione = '".convert_data($_GET['data_operazione'],1)."'" : '' ;
	$metodo = (isset($_GET['metodo_di_pagamento'])) ? ", metodo_di_pagamento = '".check($_GET['metodo_di_pagamento'])."'" : '' ;
		
	$query = "UPDATE `fl_pagamenti` SET data_aggiornamento = '".date('Y-m-d H:i')."', status_pagamento = '$change_status' $metodo $data_operazione, operatore = '".$_SESSION['number']."' WHERE id = '$pagamento_id';";
	
	if($pagamento_id > 1) mysql_query($query,CONNECT);
	
	}
	

if(isset($_GET['archivia'])){
	
	$pagamento_id = check($_GET['pagamento_id']);
	
	$change_status = check($_GET['change_status']);
	$proprietario = check($_GET['proprietario']);
	$importo = check($_GET['importo']);
	
	$anno_imposta = date('Y');


	$query = "UPDATE `fl_pagamenti` SET data_aggiornamento = '".date('Y-m-d H:i')."', status_pagamento = '$change_status', data_operazione = '".date('Y-m-d H:i')."', operatore = '".$_SESSION['number']."' WHERE id = '$pagamento_id';";
	
	if($pagamento_id > 1) { 
	mysql_query($query,CONNECT); 
	$conto = "INSERT INTO `fl_conto` (`id`, `marchio`, `proprietario`, `anno_di_imposta`, `data_operazione`, `causale_operazione`, `metodo_di_pagamento`, `dare`, `avere`, `annotazione`, `fatturato`, `operatore`, `data_creazione`, `data_aggiornamento`) 
	VALUES (NULL, '0', '$proprietario', '$anno_imposta', '".date('Y-m-d H:i')."', '1', '".check($_GET['metodo_di_pagamento'])."', '', '$importo', 'Payment ID ".$pagamento_id."', '1', '".$_SESSION['number']."', '".date('Y-m-d H:i')."', '".date('Y-m-d H:i')."');";
	mysql_query($conto,CONNECT);
	
	if(isset($_GET['customer_rel'])) {
	$customer_rel = check($_GET['customer_rel']);
	$attiva = "UPDATE fl_customers_cv SET status_profilo = 2 WHERE id = $customer_rel AND status_profilo = 0";
	mysql_query($attiva,CONNECT);
	}
	}
	
	}
	
	

mysql_close(CONNECT);
header("location: ".$_SERVER['HTTP_REFERER']);
exit;

					
?>  
