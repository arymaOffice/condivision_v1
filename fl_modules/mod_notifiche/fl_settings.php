<?php 
	
	$module_uid = 17;
	check_auth($module_uid);
	$tab_id = 14;
	$tabella = $tables[$tab_id];
	$select = "*";
	$step = 20; 
	$sezione_id = -1;
	$jorel = 0;
	$jquery = 1;
	$calendar = 1;
	$fancybox = 1;
	$text_editor = 2;
	$searchbox = 'Cerca..';
	if(isset($_GET['id'])) $tab_div_labels = array('id'=>"Notifica",'mod_conferme.php?id=[*ID*]' =>'Conferme di Lettura');
  	
	
	if(isset($_GET['proprietario']) && check(@$_GET['proprietario']) != -1 && check(@$_GET['proprietario']) != '') { $proprietario_id = check($_GET['proprietario']); } else {  $proprietario_id = -1; }
	if(isset($_GET['sezione']) && check(@$_GET['sezione']) != -1 && check(@$_GET['sezione']) != '') { $sezione_id = check($_GET['sezione']); } else {  $sezionee_id = -1; }
	
	 if(isset($_GET['data_da']) && check($_GET['data_da']) != "" && check($_GET['data_a']) != "") { 
	 $data_da = convert_data($_GET['data_da'],1); $data_a = convert_data($_GET['data_a'],1); 
	 $data_da_t = check($_GET['data_da']); $data_a_t = check($_GET['data_a']); 
	
	 } else {
	 }
	
	/* Tipologie di ordinamento disponobili */
    $ordine_mod = array("id DESC","sezione ASC","categoria ASC","visite DESC");
	$ordine = $ordine_mod[0];	
	
	
	/* RICERCA */
	$tipologia = 0;
	$tipologia_main = "WHERE id != 1 ";
	
	//if(isset($sezione_id) && @$sezione_id != -1) { $sezione = " AND sezione = $sezione_id "; } else { $sezione = " AND sezione > 1 ";}
	if(isset($data_da_t) && !isset($_GET['cerca']) && @check($_GET['action'] != 12) ) 	$tipologia_main .= " AND `data_invio`  BETWEEN '$data_da' AND '$data_a' ";
	if(isset($proprietario_id) && @$proprietario_id > -1) {  $tipologia_main .= " AND destinatario = $proprietario_id ";	 }
	if($_SESSION['usertype'] > 0) $tipologia_main = "WHERE id != 1 AND (destinatario = 0 OR destinatario = ".$_SESSION['number'].")";
	
	/* Filtro Ricerca */
	if(isset($_GET['cerca'])) {
	$vars = "cerca=".check($_GET['cerca'])."&";
	$tipologia_main .= "AND (id = 0 ".ricerca_avanzata('titolo','titolo');
    $tipologia_main .= ricerca_avanzata('articolo','articolo')." )";
	}
		
	if(@$sezione != "") $tipologia_main .= $sezione;	
	if(@$where != "") $tipologia_main .= $where;
	if(@$home_items != "") $tipologia_main .= $home_items;
	
	
	/* Inclusioni Oggetti Categorie */
	include('../../fl_core/dataset/sezione.php');
	include('../../fl_core/dataset/categoria.php');
	include('../../fl_core/dataset/array_statiche.php');
	include('../../fl_core/dataset/proprietario.php');
	
		$categories = '';
	
	  
		foreach($sezione as $hj => $label){ // Recursione Indici di Categoria
			$selected = ($sezione_id == $hj) ? 'class="selected"' : '';
			$categories .=   '<li '.$selected.'><a href="?sezione='.$hj.'">'.ucfirst($label).'</a></li>';
			}

	
	$module_menu = '';
	
	
		
	function select_type($who){
	/* Gestione Oggetto Statica */
	
	$textareas = array("messaggio"); 
	$select = array("lang","proprietario","destinatario",'mittente');
	$disabled = array("visite");
	$hidden = array("alert","obbligatorio","data_invio","proprietario","modulo","categoria","id","ip","operatore","data_aggiornamento");
	$radio  = array();	
	$calendario = array();	
	$multi_selection = array();	
	$file = array();
	$id_tags = array();
	

	
	$type = 1;
	
	if(in_array($who,$select)) { $type = 2; }
	if(in_array($who,$textareas)){ $type = 3; }
	if(in_array($who,$disabled)){ $type = 4; }
	if(in_array($who,$hidden)){ $type = 5; }
	if(in_array($who,$radio)){ $type = 8; }
	if(in_array($who,$calendario)){ $type = 19; }
	if(in_array($who,$id_tags)){ $type = 23; }
	if(in_array($who,$file)){ $type = 18; }
	if(in_array($who,$multi_selection)){ $type = 22; }		

	
	return $type;
	}
	
	
?>