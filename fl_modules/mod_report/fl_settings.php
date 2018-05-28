<?php 
	
	// Variabili Modulo
	$active = 5;
	
  	 if(isset($_GET['data_da']) && check(@$_GET['data_da']) != "") { 
	 $data_da = convert_data($_GET['data_da'],1); 
	 $data_da_t = check($_GET['data_da']); 
	 $data_a = convert_data($_GET['data_a'],1); 
	 $data_a_t = check($_GET['data_a']); 
	 } else {
	 $data_a = date('Y-m-d',time()); 	 
	 $data_a_t = date('d/m/Y',time());
	 $data_da = date('Y-m-d',time());
	 $data_da_t =date('d/m/Y',time());
 
	 }
	 $module_menu = '
	
	 <ul>
     </ul>';
	
	if(isset($_GET['operatore']) && check(@$_GET['operatore']) != -1 && check(@$_GET['operatore']) != '') { $userid = check($_GET['operatore']); } else {  $userid = 0; }
    $user = ($userid > 0) ? "AND `callcenter` = $userid" : '';
	$opt = ($userid > 0) ? "AND `operatore` = $userid" : '';
	$prop = ($userid > 0) ? "AND `proprietario` = $userid" : '';

	/* Inclusioni Oggetti Categorie */
	include('../../fl_core/dataset/array_statiche.php');
	include('../../fl_core/dataset/proprietario.php');
	


	
?>