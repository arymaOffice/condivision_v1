<?php 
	
	/*Class Load */
	require('../../fl_core/class/ARY_dataInterface.class.php');
	$data_set = new ARY_dataInterface();
	$tabelle = array('fl_parco_veicoli');
	$tab_id = 78;
	// Variabili Modulo
	$select = "*";
	$step = 100; 
	$jorel = 0;
	$accordion_menu = 1;
	$formValidator = 1;
	$text_editor = 2;
	$jquery = 1;
	
	$calendar = 1;
	$fancybox = 1;
	$filtri = 1;
	$searchbox = 'Cerca...';
	$tabella = (isset($_GET['data'])) ? base64_decode(check($_GET['data'])) : 'fl_parco_veicoli';
	$hidden = array('aging','proprietario','managed_by','operatore','data_creazione','data_aggiornamento','FullTransmission','DestFiscale','');
	$select = array();
	$module_menu = '';
	foreach($tabelle as  $chiave => $valore) {    $module_menu .= '<li class=""><a href="./?data='.base64_encode($valore).'">'.ucfirst(str_replace('bk_','',$valore)).' </a></li>'; }
   
   

  

	$campi = "SHOW COLUMNS FROM `$tabella`";
	$risultato = mysql_query($campi, CONNECT);
	$x = 0;
	$campi = array();
	while ($riga = mysql_fetch_assoc($risultato)) 
	{					
	if(!in_array($riga['Field'],$hidden)) { $campi[$x] = record_label($riga['Field'],CONNECT,1); $x++; }
	}
	$ordine_tipo = (isset($_GET['ordine'])) ? check($_GET['ordine']) : 0;
	$ordine = $campi[$ordine_tipo];
	$tipologia_main =' WHERE 1 ';
	
	
	/*Cerca in tutti i campi visibili */
	if(isset($_GET['cerca'])) { 
	$vars = "cerca=".check($_GET['cerca'])."&";		
	$tipologia_main .= 'AND (0 ';
	foreach ($campi as $chiave => $valore) 
	{					
	if(!in_array($valore,$hidden)) $tipologia_main .= ricerca_avanzata($valore,$valore);  
	}
	$tipologia_main .= ")";
	}

    $module_menu = '';
	$categoria_veicolo = $data_set->get_items_key('categorie_veicoli_interni');

	foreach($categoria_veicolo as $valores => $label){ // Recursione Indici di Categoria
			$module_menu .= "<li><a href=\"./?categoria_veicolo=$valores\">".ucfirst($label)."</a></li>\r\n"; 
	}
?>