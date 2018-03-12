<?php 
	// Variabili Modulo
	$active = 6;
	$tab_id = 88;
	$tabella = $tables[$tab_id];
	$select = "*";
	$step = 50; 
	$sezione = 0;
	$jorel = 0;
	$accordion_menu = 1;
	$jquery = 1;
	$calendar = 1;
	$fancybox = 1;
	$text_editor = 0;
	$tabs_div = 0;
	$tab_div_labels = array('id'=>"Dettagli",'scheda_obiettivo'=>"Scheda Obiettivo",);

	if(!isset($_SESSION['proprietario_id'])) $_SESSION['proprietario_id'] = 0;
	if(isset($_GET['cmy'])) $_SESSION['proprietario_id'] = check(base64_decode($_GET['cmy']));
	$proprietario_id = ($_SESSION['usertype'] > 1) ? $_SESSION['anagrafica'] : $_SESSION['proprietario_id'];



    $module_menu = '
	';
	
	/* Tipologie di ordinamento disponobili */
   	$ordine_mod = array("numero_obiettivo ASC","id ASC","id ASC");
	$ordine = $ordine_mod[0];	
	
	
	/* RICERCA */
	$tipologia_main = "WHERE id != 1 AND anagrafica_id = $proprietario_id ";
	if(isset($_GET['cerca'])) $vars = "cerca=".check($_GET['cerca'])."&";	
	$where = ricerca_semplice('descrizione_obiettivo','descrizione_obiettivo');
	
	if(@$sezione != "") $tipologia_main .= $sezione;	
	if(@$where != "") $tipologia_main .= $where;
	
	

	
	/* Inclusioni Oggetti Categorie */	
	require('../../fl_core/dataset/array_statiche.php');
	require('../../fl_core/class/ARY_dataInterface.class.php');
	$data_set = new ARY_dataInterface();
	$fornitore_informazioni  = $data_set->data_retriever('fl_profili_funzione','funzione',"WHERE id != 1 AND anagrafica_id = $proprietario_id",'funzione ASC');
	$anagrafica = $anagrafica_id = $data_set->data_retriever('fl_anagrafica','ragione_sociale',"WHERE id != 1",'ragione_sociale ASC');
	$persona_id = $data_set->data_retriever('fl_persone','nome, cognome',"WHERE id != 1 AND anagrafica_id = $proprietario_id",'nome ASC');
	$processo_id = $data_set->data_retriever('fl_processi','processo',"WHERE id != 1 AND anagrafica_id = $proprietario_id",'processo ASC');
	$tipo_obiettivo = $data_set->get_items_key("tipo_obiettivo");	

	function select_type($who){
	$textareas = array('descrizione_obiettivo','misurazione','scheda_obiettivo','descrizione_premio'); 
	$select = array('tipo_obiettivo','processo_id',"anagrafica_id",'fornitore_informazioni','persona_id');
	$disabled = array();
	$hidden = array("id",'operatore','data_creazione','marchio','data_aggiornamento','proprietario',"photo");
	$radio  = array();	
	$multi_selection = array('sedi_id');	
	$checkbox = array();	
	$calendario = array('scadenza_obiettivo');	
	$selectbtn  = array();	
	$file = array();

	$type = 1;

	
	if(in_array($who,$select)) { $type = 2; }
	if(in_array($who,$textareas)){ $type = 3; }
	if(in_array($who,$disabled)){ $type = 4; }
	if(in_array($who,$hidden)){ $type = 5; }
	if(in_array($who,$multi_selection)){ $type = 6; }
	if(in_array($who,$calendario)){ $type = 20; }
	if(in_array($who,$radio)){ $type = 8; }
	if(in_array($who,$selectbtn)){ $type = 22; }
	if(in_array($who,$file)){ $type = 18; }

	if(in_array($who,$multi_selection)){ $type = 23; }
		
	return $type;
	}
	
		$module_title = 'Obiettivi '.$anagrafica[$proprietario_id];
?>
