<?php 
	
	// Variabili Modulo
	$active = 7;
	$tab_id = 31;
	$tabella = $tables[$tab_id];
	$select = "*";
	$step = 20; 
	$sezione_id = -1;
	$jorel = 0;
	$jquery = 1;
	$calendar = 1;
	$fancybox = 1;
	$text_editor = 1;

    $modulo_suffix = modulo_suffix();
	 

	if(isset($_GET['new']) && isset($_GET['sezione'])){ new_inserimento($tabella); }
		
	if(isset($_GET['sezione']) && check($_GET['sezione']) != "") { 
	
	$sezione_id = check($_GET['sezione']); 
    if($sezione_id > 0) $rel = $sezione_id;  
	/* Contollo tipo di sezione */
	$get_tipo = "SELECT tipologia_sezione,lang FROM cms_sez WHERE id = $sezione_id LIMIT 1";
	@$res = mysql_query($get_tipo,CONNECT);
	@$riga_res = mysql_fetch_array($res);
	@define('sezione_tipo',$riga_res['tipologia_sezione']);  
	@define('sezione_lang',$riga_res['lang']);  
	
	}
	
	
	
	/* Tipologie di ordinamento disponobili */
    $ordine_mod = array("titolo ASC","argomento ASC","categoria ASC","priprietario DESC");
	$ordine = $ordine_mod[0];	
	
	
	/* RICERCA */
	$tipologia = 0;
	$tipologia_main = "WHERE id != 1 ";
	if($_SESSION['usertype'] > 0) $tipologia_main = "WHERE id != 1";
	//if(isset($sezione_id) && @$sezione_id != -1) { $sezione = " AND categoria = $sezione_id "; } else { $sezione = " AND categoria > 1 ";}

	
	/* Filtro Ricerca */
	if(isset($_GET['cerca'])) {
	$vars = "cerca=".check($_GET['cerca'])."&";
	$tipologia_main .= "AND (id = 0 ".ricerca_avanzata('titolo','titolo');
    $tipologia_main .= ricerca_avanzata('testo','testo')." )";
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

	
	$module_menu = '
	<ul>
  
	  <li><a href="'.ROOT.$cp_admin.'fl_modules/mod_cms/">Lista Comunicazioni</a></li>
      <li class=""><a href="#">Categorie <span class="subcolor"></span></a>
  <ul>
        '.$categories.'
        </ul>
      </li>
	       <li><a href="./mod_inserisci.php?external&action=1&id=1" >Nuova Comunicazione</a></li>

    </ul>';
	
	
		
	function select_type($who){
	/* Gestione Oggetto Statica */
	
	$textareas = array("testo"); 
	$select = array("lang","proprietario","status","sezione","categoria");
	$disabled = array();
	$hidden = array("data_creazione","operatore","data_aggiornamento");
	$radio  = array("homepage");	
	$checkbox = array('tipo_profilo');

	$calendario = array('data_inizio_pubblicazione','data_fine_pubblicazione');	
	$file = array();
	$id_tags = array();
	

	
	$type = 1;
	
	if(in_array($who,$select)) { $type = 2; }
	if(in_array($who,$textareas)){ $type = 3; }
	if(in_array($who,$disabled)){ $type = 4; }
	if(in_array($who,$hidden)){ $type = 5; }
	if(in_array($who,$radio)){ $type = 8; }
	if(in_array($who,$calendario)){ $type = 20; }
	if(in_array($who,$id_tags)){ $type = 23; }
	if(in_array($who,$file)){ $type = 18; }
	if(in_array($who,$checkbox)){ $type = 19; }

	return $type;
	}
	
	
?>