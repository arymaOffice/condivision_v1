<?php 
	$module_uid = 9;
	check_auth($module_uid);

	$tab_id = 8;
	$tabella = $tables[$tab_id];
	$select = "*";
	$fancybox = 1;
	
	if($_SESSION['usertype'] == 0) { 
    $search = "Cerca account";
	$module_menu = '
    ';
	} 

	$module_title = "Account";
	$ordine_mod = array("nominativo ASC","marchio DESC","tipo ASC","attivo DESC","gruppo ASC","user DESC");
	$ordine = $ordine_mod[5];
	
	
	
  
	$step = 30; 

	
	$txt_operazione = array('Inserisci Nuova ','Modifica Informazioni ','Modifica Password'); 
	$time = time();
	$operatore = $_SESSION['number'];
	$ip = $_SERVER['REMOTE_ADDR'];
	$usertype = $_SESSION['usertype'];
	
	$tipologia_main = "WHERE id != 1 ";
	if(isset($_GET['cerca'])) { 
	$vars = "cerca=".check($_GET['cerca'])."&";	
	$tipologia_main .= ricerca_semplice('nominativo','nominativo');
	$tipologia_main .= ricerca_avanzata('email','email');
	$tipologia_main .= ricerca_avanzata('user','user');
	$tipologia_main .= ")";
	}
	
	
	/* Inclusioni Oggetti Categorie */
	include('../../fl_core/data_manager/array_statiche.php');
	include('../../fl_core/category/anagrafica.php');
	include('../../fl_core/category/proprietario.php');
	if($_SESSION['usertype'] > 0 && check(@$_GET['action'] > 0)) { unset($tipo[0]);  unset($tipo[1]); }
			
	function select_type($who){
	$textareas = array("descrizione","note"); 
	$select = array("status");
	$selectbtn = array('attivazione_cassa',"alert",'attivo');
	$disabled = array("user","total_scooring");
	$hidden = array("anagrafica","sede","data_creazione","aggiornamento_password",'tipo',"proprietario","marchio","foto","data","password","id","codice","categoria_struttura","sottocategoria_struttura","type","ip","continente","operatore","data_aggiornamento","visite");
	$checkbox = array();	
	$radio = array('');	
	$multi_selection = array("giorni_lavorativi");	
	$calendario = array('data_scadenza','data_emissione','data_nascita');	
	
	$type = 1;

	
	if(in_array($who,$select)) { $type = 2; }
	if(in_array($who,$textareas)){ $type = 3; }
	if(in_array($who,$disabled)){ $type = 4; }
	if(in_array($who,$hidden)){ $type = 5; }
	if(in_array($who,$checkbox)){ $type = 6; }
	if(in_array($who,$calendario)){ $type = 20; }
	if(in_array($who,$radio)){ $type = 8; }
	if(in_array($who,$selectbtn)){ $type = 22; }
	if(in_array($who,$multi_selection)){ $type = 23; }
		
	return $type;
	}
	
?>