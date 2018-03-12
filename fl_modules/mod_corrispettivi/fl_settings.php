<?php 
	// Variabili Modulo
	$active = 3;
	$tab_id = 27;
	$tabella = $tables[$tab_id];
	$text_editor = 0;
	$calendar = 1;
	$operatore_check = 1;
	$tabs_div = 0;
	$calendar = 1;
	$fancybox = 1;
	$filtri = 1;
	$searchbox = 'Cerca..';
	$tab_div_labels = array('id'=>"Corrispettivi",'condizioni_meteo'=>"Dettagli");

	
	if(isset($_GET['mese'])) { $mese_sel = check($_GET['mese']); }  else { $mese_sel = date('m'); }
	if(isset($_GET['anno'])) { $anno_sel = check($_GET['anno']); } else { $anno_sel = date('Y'); }
	$proprietario_id = (isset($_GET['operatore']) && $_SESSION['usertype'] == 0) ? check($_GET['operatore']) : $_SESSION['number'];
	if(isset($_GET['condizioni_meteo'])) { $condizioni_meteo_id = check($_GET['condizioni_meteo']);}
	
	if(isset($_GET['data_da']) && check($_GET['data_da']) != "" && check($_GET['data_a']) != "") { 
	 $data_da = convert_data($_GET['data_da'],1); $data_a = convert_data($_GET['data_a'],1); 
	 $data_da_t = check($_GET['data_da']); $data_a_t = check($_GET['data_a']); 
	 } else {
	
	 $data_da_t = date('1/m/Y',time()); 
	 $data_a_t = date('d/m/Y'); 
	}

	$select = "*";

   
    $ordine_mod = array("data_corrispettivo ASC","id ASC","tipologia_sezione ASC","riepilogo DESC","lang ASC");
	$ordine = $ordine_mod[0];	

	$tipologia_main = "WHERE id != 1 ";
	if($_SESSION['usertype'] > 0) $tipologia_main = "WHERE id != 1  AND proprietario = ".$_SESSION['number']." ";
	if(isset($proprietario_id) && @$proprietario_id > -1) 	$tipologia_main .= " AND proprietario = ".$proprietario_id." ";
	if(isset($data_da) && !isset($_GET['cerca'])) {	$tipologia_main .= " AND `data_corrispettivo`  BETWEEN '$data_da' AND '$data_a' ";
	} else { $tipologia_main .= " AND (MONTH(`data_corrispettivo`) = $mese_sel AND YEAR(`data_corrispettivo`)  =  $anno_sel)";	 }	

	if(isset($condizioni_meteo_id) && check(@$condizioni_meteo_id) >= 0 ) { $tipologia_main .= " AND condizioni_meteo = $condizioni_meteo_id "; }
	
	if(isset($_GET['cerca'])) { 
	$vars = "cerca=".check($_GET['cerca'])."&";		
	$tipologia_main .= ricerca_semplice('note','note');
	$tipologia_main .= ricerca_avanzata('note','note');
	$tipologia_main .= ")";
	}
	
	$step = 60;
	
	
	/* Inclusioni Oggetti Categorie */
	include('../../fl_core/dataset/array_statiche.php');
	require('../../fl_core/class/ARY_dataInterface.class.php');
	$data_set = new ARY_dataInterface();
	$proprietario =  $data_set->data_retriever('fl_account','nominativo',"WHERE id != 1 AND attivo = 1 AND tipo = 2   ",'nominativo ASC');


		
	function select_type($who){
	$textareas = array("note","articolo_di_sezione"); 
	$select = array("condizioni_meteo","lang","percorso");
	$hidden = array('operatore','sede_id','',"mese","anno","proprietario","relation","cat","riepilogo","visite","jorel","id","codice","type","ip","data_aggiornamento");
	$radio  = array("homepage");
	$radioicons  = array();	
	$disabled = array();
	$calendario = array('data_corrispettivo','data_versamento');	
	$type = 1;
	if(isset($_GET['xmod'])) $disabled = array('data_corrispettivo','euro','buoni_pasto_battuto','buoni_pasto_facciale','totale_scontrini','pos','delivery');
	if(isset($_GET['vrs'])) array_push($disabled,'totale_versato','data_versamento');
	
	if(in_array($who,$select)) { $type = 2; }
	if(in_array($who,$radioicons)) { $type = 9; }
	if(in_array($who,$textareas)){ $type = 3; }
	if(in_array($who,$hidden)){ $type = 5; }
	if(in_array($who,$radio)){ $type = 8; }
	if(in_array($who,$calendario)){ $type = 20; }
	if(in_array($who,$disabled)){ $type = 4; }

	return $type;
	}
	
	
?>
