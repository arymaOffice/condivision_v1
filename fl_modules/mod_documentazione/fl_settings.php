<?php 
	// Variabili Modulo
	// Variabili Modulo
    // Variabili Modulo
	$tab_id = 18;
	$tabella = $tables[$tab_id];
	$modulo_id = 0;
	
	$fancybox = 1;
	$select = "*";
	$where = "WHERE id = 1 ";
    $ordine_mod = array("titolo ASC","data DESC","anno DESC, mese DESC");
        
    if(isset($_GET['mese'])) { $mese_sel = check($_GET['mese']); } else { $mese_sel = date("m");}
	if(isset($_GET['anno'])) { $anno_sel = check($_GET['anno']); } else { $anno_sel = date("Y");}
	if(isset($_GET['operatore']) && $_SESSION['usertype'] == 0) { $proprietario_id = check($_GET['operatore']);}
	if(isset($_GET['modulo'])) { $modulo_id = check($_GET['modulo']);} else {$modulo_id = 0;}
	if(isset($_GET['contenuto'])) { $contenuto_id = check($_GET['contenuto']);}else { $contenuto_id = 0;}
    if(!isset($_SESSION['last_referrer'])) $_SESSION['last_referrer'] = 'index.php';

	
	$ordine = $ordine_mod[0];

	$cat = 0;
	if(isset($_GET['cat'])) $cat = check($_GET['cat']);	
	if(isset($_POST['cat'])) $cat = check($_POST['cat']);	
	if(isset($_GET['contenuto'])) $contenuto_id = check($_GET['contenuto']);	
	if(isset($_POST['contenuto'])) $contenuto_id = check($_POST['contenuto']);	
	
	$tipologia = 0;	
	if ($cat == 2 || $cat == 3 || $cat == 4){ $ordine = $ordine_mod[2]; }
	$step = 10; 
	$modulo_suffix = modulo_suffix();
	
		
	if ($cat == 10){ $doc_dir = "gallery/".$modulo_suffix[$modulo_id].$contenuto_id."/"; }
	if ($cat == 12){ $doc_dir = "download/".$modulo_suffix[$modulo_id].$contenuto_id."/"; }
	if ($cat == 17){ $doc_dir = "mobilmat/"; }
	if ($cat == 18){ $doc_dir = "anagrafica/"; }
	
	include('../../fl_core/category/cms.php');
	include('../../fl_core/category/proprietario.php');
	

	
?>
