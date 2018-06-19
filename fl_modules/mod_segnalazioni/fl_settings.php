<?php 
	// Variabili Modulo
	$sezione_tab = 1;
	$tab_id = 44;
	$modulo_uid = 25;
	$tabella = $tables[$tab_id];
	$select = "*";
	$step = 1000; 
	$text_editor = 2;
	$jquery = 1;
	$fancybox = 1;
	$calendar = 1;
	$searchbox = 'Ricerca nome,user,mail';

    $module_menu = '
	<ul>
  	   <li class=""><a href="#" onclick="display_toggle(\'#menu_modulo\');"><i class="fa fa-th-large"></i></a></li>
    </ul>';

		
	if(isset($_GET['tab_id'])) { $tab_id = check($_GET['tab_id']); }
	if(isset($_GET['operatore']) && check(@$_GET['operatore']) != -1 && check(@$_GET['operatore']) != '') { $operatore_id = check($_GET['operatore']); } else {  $operatore_id = -1; }
	if(isset($_GET['status_segnalazione']) && check(@$_GET['status_segnalazione']) != 0) { $status_segnalazione_id = check($_GET['status_segnalazione']);  } else {    $status_segnalazione_id = 0; }
	if(isset($_GET['tipo_segnalazione']) && check(@$_GET['tipo_segnalazione']) != 0) { $tipo_segnalazione_id = check($_GET['tipo_segnalazione']);  } else {    $tipo_segnalazione_id = 0; }
	
  	 if(isset($_GET['data_da']) && check($_GET['data_da']) != "" && check($_GET['data_a']) != "") { 
	
	 $data_da = convert_data($_GET['data_da'],1); $data_a = convert_data($_GET['data_a'],1); 
	 $data_da_t = check($_GET['data_da']); $data_a_t = check($_GET['data_a']); 
	
	 } else {
	 
	 $data_da_t = date('d/m/Y',time()-9204800); 
	 $data_a_t = date('d/m/Y'); 
	 }
	 
	
	/* Tipologie di ordinamento disponobili */
    $ordine_mod = array("nome_e_cognome ASC","user_id ASC","proprietario ASC","id DESC","data_aggiornamento DESC");
	$ordine = $ordine_mod[4];	
	
	
	/* RICERCA */
	$tipologia = 0;
	$tipologia_main = "WHERE id != 1 ";
	if(isset($data_da)) 	$tipologia_main .= " AND (data_creazione BETWEEN $data_da AND $data_a )";
	if(isset($operatore_id) && @$operatore_id != -1) {  $tipologia_main .= " AND proprietario = $operatore_id ";	 }
	if(isset($status_segnalazione_id) && @$status_segnalazione_id != 0) {  $tipologia_main .= " AND status_segnalazione = $status_segnalazione_id ";	 }
	if(isset($tipo_segnalazione_id) && @$tipo_segnalazione_id != 0) {  $tipologia_main .= " AND tipo_segnalazione = $tipo_segnalazione_id ";	 }
	if(@$sezione != "") $tipologia_main .= $sezione;	
	if(@$where != "") $tipologia_main .= $where;

	
	
	if(isset($_GET['cerca'])) { 
	$vars = "cerca=".check($_GET['cerca'])."&";		
	$tipologia_main .= ricerca_semplice('nome_e_cognome','nome_e_cognome');
	$tipologia_main .= ricerca_avanzata('user_id','user_id');
	$tipologia_main .= ricerca_avanzata('email','email');
	$tipologia_main .= ")";
	}
	
	
	
	/* Inclusioni Oggetti Categorie */	
	include('../../fl_core/dataset/array_statiche.php');
	include('../../fl_core/dataset/proprietario.php');

	
		
	function select_type($who){
	/* Gestione Oggetto Statica */
	
	$textareas = array("segnalazione"); 
	$select = array("marchio","tipo_segnalazione");
	$disabled = array("visite");
	$hidden = array("proprietario","data_creazione","ip","operatore","data_aggiornamento");
	$radio = array();
	$text = array();
	$checkbox = array("status_segnalazione");
	$calendario = array('data_chiusura');	
	$file = array("upfile");
	
	$type = 1;
	
	if(in_array($who,$select)) { $type = 2; }
	if(in_array($who,$textareas)){ $type = 3; }
	if(in_array($who,$disabled)){ $type = 4; }
	if(in_array($who,$hidden)){ $type = 5; }
	if(in_array($who,$radio)){ $type = 8; }
	if(in_array($who,$calendario)){ $type = 20; }
	if(in_array($who,$file)){ $type = 18; }
	if(in_array($who,$text)){ $type = 24; }
		if(in_array($who,$checkbox)){ $type = 19; }
	
	return $type;
	}
	
	
?>