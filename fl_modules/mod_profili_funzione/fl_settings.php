<?php 
	// Variabili Modulo
	$active = 6;
	$tab_id = 83;
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
	$tab_div_labels = array('id'=>"Profilo Di Funzione");//,'mod_competenze.php?reQiD=[*ID*]'=>'Competenze Attese');

	if(!isset($_SESSION['proprietario_id'])) $_SESSION['proprietario_id'] = 0;
	if(isset($_GET['cmy'])) $_SESSION['proprietario_id'] = check(base64_decode($_GET['cmy']));
	$proprietario_id = ($_SESSION['usertype'] > 1) ? $_SESSION['anagrafica'] : $_SESSION['proprietario_id'];


	$module_title = 'Anagrafica Profili di Funzione';
    $module_menu = '
	<ul>
	<!--<li class=""><a href="../mod_organigramma/">  <i class="fa fa-sitemap"></i>  Organigramma </a></li>-->
	<li class=""><a href="./mod_inserisci.php?id=1&ANiD='.base64_encode($proprietario_id).'" class="create_new"><i class="fa fa-plus-circle"></i> Nuovo Profilo</a></li>
	</ul>';
	$new_button = '<a href="./mod_inserisci.php?id=1&ANiD='.base64_encode($proprietario_id).'1" class="" style="color: gray"> <i class="fa fa-plus-circle"></i>  </a>';
    $module_menu = '';
	
	/* Tipologie di ordinamento disponobili */
   	$ordine_mod = array("dipendenza ASC","id ASC","id ASC");
	$ordine = $ordine_mod[0];	
	
	
	/* RICERCA */
	$tipologia_main = "WHERE id != 1 AND anagrafica_id = $proprietario_id ";
	if(isset($_GET['cerca'])) $vars = "cerca=".check($_GET['cerca'])."&";	
	$where = ricerca_semplice('funzione','funzione');
	
	if(@$sezione != "") $tipologia_main .= $sezione;	
	if(@$where != "") $tipologia_main .= $where;
	
	

	
	/* Inclusioni Oggetti Categorie */	
	require('../../fl_core/dataset/array_statiche.php');
	require('../../fl_core/class/ARY_dataInterface.class.php');
	$data_set = new ARY_dataInterface();
	$dipendenza = $data_set->data_retriever('fl_profili_funzione','funzione',"WHERE id != 1 AND anagrafica_id = $proprietario_id",'funzione ASC');
	$dipendenza[1] = 'VERTICE';
	//$sedi_id = $data_set->data_retriever('fl_sedi','sede,citta',"WHERE id != 1 AND anagrafica_id = $proprietario_id",'sede ASC');
	//$sedi_id[0] = 'Tutte';
	$anagrafica = $anagrafica_id = $data_set->data_retriever('fl_anagrafica','ragione_sociale',"WHERE id != 1 AND tipo_profilo = 0",'ragione_sociale ASC');
	$processo_id = $data_set->data_retriever('fl_processi','processo',"WHERE id != 1 AND anagrafica_id = $proprietario_id",'processo ASC');

	function select_type($who){
	$textareas = array('mansioni_e_responsabilita'); 
	$select = array('processo_id',"anagrafica_id",'dipendenza');
	$disabled = array();
	$hidden = array('sedi_id','responisabilita_finanziarie','scopo_posizione','supervisione','relazioni_interne','relazioni_esterne','responsabilita_finanziarie','titoli_di_studio','esperienze_pregresse','revisione','data_revisione',"id",'stumenti_di_gestione','operatore','data_creazione','marchio','data_aggiornamento','proprietario',"photo");
	$radio  = array();	
	$multi_selection = array();	
	$checkbox = array();	
	$calendario = array('');	
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

	
	return $type;
	}
	
	
?>
