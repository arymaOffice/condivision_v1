<?php 
	
	
    // Variabili Modulo
	$tab_id = 13;
	$tabella = $tables[$tab_id];
	$select = "*";
	$step = 10; 
	$where = "WHERE id = 1 ";
    $ordine_mod = array("id ASC","aggiornato DESC");
	$_SESSION['active'] = 'centrodati';
	$ordine = $ordine_mod[0];	
	$cat = 0;
	
	$relation = (isset($_GET['relation'])) ? check($_GET['relation']) : -1;
	$tipologia_quota = (isset($_GET['tipologia']) && $_SESSION['relation_link'] == $relation) ? check($_GET['tipologia']) : -1;
	$periodo_quota_id =(isset($_GET['periodo_quota'])) ? check($_GET['periodo_quota']) : -1;
	$sezione_link_id =(isset($_GET['sezione_link_id'])) ? check($_GET['sezione_link_id']) : -1;
	$disponibilita_link_id =(isset($_GET['disponibilita_link']) ) ? check($_GET['disponibilita_link']) : -1;
	$tipo_link_id = (isset($_GET['tipo_link']) && $_SESSION['relation_link'] == $relation) ? check($_GET['tipo_link']) : -1;
	if($_SESSION['usertype'] == 3) $disponibilita_link_id = $_SESSION['usertype'];
	$_SESSION['relation_link'] = $relation;

	$where = "WHERE id != 1  ";
	if(isset($relation) && $relation > 1) {	$where .= " AND relation = $relation "; } else if($sezione_link_id != 46){ $where .= " AND (relation < 2 OR relation = 17)  "; }
	if(isset($tipologia_quota) && $tipologia_quota > 1) {	$where .= " AND cat = $tipologia_quota "; }
	if(isset($tipo_link_id) && $tipo_link_id > 1) {	$where .= " AND tipo_link = $tipo_link_id "; }
	if(isset($disponibilita_link_id) && $disponibilita_link_id > 1) {	$where .= " AND disponibilita_link = $disponibilita_link_id "; }
	if(isset($periodo_quota_id) && $periodo_quota_id > 1) {	$where .= " AND periodo_quota = $periodo_quota "; }
	if(isset($sezione_link_id) && $sezione_link_id > 1) {	$where .= " AND sezione_link = $sezione_link_id "; }
	if($_SESSION['usertype'] > 0 && $sezione_link_id == 42 && !isset($_GET['pricarica'] )) {	$where .= " AND tipo = ".$_SESSION['usertype']; }
	if(isset($_GET['pricarica'])) {	$where .= " AND tipo = 3"; }


	include("../../fl_core/dataset/items_rel.php");
	include("../../fl_core/dataset/array_statiche.php");
	$categorie_link = get_items(5);
	$tipologia_quote = get_items(6);
	$periodo_quote = get_items(11);
	$sezione_link  = get_items(15);
	$sottocategoria  = get_items(16);
	$tipo_link = get_items(18);
	$disponibilita_link = $tipo_affiliazione;
	unset($categorie_link[0]); 	
	unset($tipologia_quote[0]); 	
	unset($tipo_link[0]); 	
	$tipo_link[1] = $tipologia_quote[1] = $categorie_link[1] = "Tutto";

	$module_title = (isset($sezione_link_id)) ? @$sezione_link[$sezione_link_id] : 'Links';
	$module_title = ($disponibilita_link_id > 1 ) ? $module_title.' '.@$disponibilita_link[$disponibilita_link_id] : $module_title;
	
	$module_menu = '';
	$new_button = ($_SESSION['usertype'] > 1) ? '' : '<a href="#" onclick="display_toggle(\'#filtri\');" style="color: gray"> <i class="fa fa-plus-circle"></i></a>';
	

	
?>