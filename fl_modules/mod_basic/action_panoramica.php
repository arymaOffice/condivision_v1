<?php 

if(isset($_GET['id'])) { $id = (is_numeric($_GET['id'])) ? check($_GET['id']) : exit;  } 
if(isset($force_id)){ $id = check($force_id); } 
if(isset($_GET['n'])) $id = 1;

$id = (isset($id)) ? $id : 1;

$where = "WHERE id = $id ";

	if(!isset($codice_scheda)){
	if(!isset($tosave)){
	echo "<p><input type=\"hidden\" name=\"id\" id=\"id\" value=\"$id\" /></p>"; 
	} else { echo "<input type=\"hidden\" name=\"id\" id=\"id\" value=\"$tosave\" />";}
	}
	echo "<p><input type=\"hidden\" name=\"gtx\" id=\"gtx\" value=\"$tab_id\" /></p>";	
	

	$query = "SELECT * FROM `$tabella` $where LIMIT 1;";
	

	if(!$risultato = mysql_query($query, CONNECT) )  //echo "<p>".mysql_error()."</p><p>$query</p>"; } // Errore mysql
	if($id == 1 && mysql_affected_rows() < 1) { echo "<h2>Manca l'origine dati</h2>"; } // Se manca la riga 1 per form_maker
	/* Generazione automatica tabs scheda */
	if(isset($tab_div_labels)) { 
	$tabs_div = array();
	$tabs_desc = array();
	$tabs_ext = array();
	//echo '<div id=""><ul>';
	$ext = 0;
	
	
	foreach($tab_div_labels as $chiave => $valore) { 
	if(strstr($chiave,'[*ID*]')) { 
	//echo '<li><a href="#tb_'.$ext.'">'.$valore.'</a></li>';
	$tabs_ext[$valore] = $chiave;
	$ext++;
	} else {
	array_push($tabs_div,$chiave);
	$tabs_desc[$chiave] = $valore;
	//echo '<li><a href="#tb_'.$chiave.'">'.$valore.'</a></li>'; 
	}
    }
	echo '<div class="box_div"><h2>'.@$tabs_desc['id'].'</h2><p class="leggi" style="display: none;"><a data-fancybox-type="iframe" class="fancybox" title="Modifica"  href="mod_inserisci.php?view&id='.$id.'">Modifica</a></p>';
	unset($tabs_div[0]);
	
	}  else {  echo '';}

	$campi = array();
	$campi_anno = array('anno_di_imposta','anno');
	$campi_today = array('data_pagamento','data_richiesta','data_fattura','data_apertura','data_avvio','data_corrispettivo','data_versamento','data_scadenza_pec','data_sottoscrizione','data_inizio','data_fine','data_rinnovo','data_documento','data_operazione','data_scadenza');
	
	while ($riga = mysql_fetch_array($risultato)) 
	{
	    $dafault_disable = "";
		foreach($riga as $chiave => $valore){
		$campi[$chiave] = $valore;
		
		if($id == 1 && in_array($chiave,$campi_anno)) $valore = date('Y');
		if($id == 1 && in_array($chiave,$campi_today)) $valore = date('Y-m-d');
		if(isset($tabs_div)) { if(in_array($chiave,$tabs_div) && !is_numeric($chiave)) echo '</div><div class="box_div" id="tb_'.$chiave.'"><h2>'.$tabs_desc[$chiave].'</h2><p class="leggi" style="display: none;"><a data-fancybox-type="iframe" class="fancybox" href="mod_inserisci.php?view&id='.$id.'&tId=tb_'.$chiave.'">Modifica</a></p>'; }
		
		
		if(is_to_use($chiave) == 1){
	
			$size = (is_numeric($valore)) ? 6 : 255;

			$disabilitati = array('data_creazione','datac_inizio','datac_fine','account_affiliato','anagrafica');		
			if(in_array($chiave,$disabilitati)){ $disabled = "disabled";  }
			if($chiave == 'user' && $tabella == 'fl_account'){ $disabled = "disabled";  }

			echo '<div class="form_row" id="box_'.$chiave.'">';
			if(record_label($chiave,CONNECT,0) != "") echo "<h3>".record_label($chiave,CONNECT,0)."</h3>\r\n";
			$info ='';
	
			
			$campi[$chiave] = $valore;
	
			switch(select_type($chiave)){
			
			case 1:		// Input Text
			$extra = '';
			$onblur = '';
			if($chiave == "partita_iva"){ $extra = '<a href="https://telematici.agenziaentrate.gov.it/VerificaPIVA/Scegli.do?parameter=verificaPiva" target="new"><i class="fa fa-check"></i></a>'; }
		    if($chiave == "codice_fiscale" || $chiave == "codice_fiscale_legale"){ $extra = '<a href="https://telematici.agenziaentrate.gov.it/VerificaCF/Scegli.do?parameter=verificaCf" target="new"><i class="fa fa-check"></i></a>'; }
			if($chiave == "sito_web"){ $extra = '<a href=\"#\" onclick=\"window.open(document.getElementById(\'scheda\').sito_web.value); return false;\"><i class="fa fa-external-link" aria-hidden="true"></i></a>'; }
			
			if($chiave == "cap_punto" && isset($ajax))  $extra =  " <a href=\"#\" onClick=\"fl_get_coordinate('indirizzo_punto','comune_punto','cap_punto','provincia_punto','lat','lon'); return false;\"><i class=\"fa fa-map-marker\"></i></a>";
			if($chiave == "indirizzo_punto" && isset($ajax))  $onblur = "onfocusout=\"fl_get_coordinate('indirizzo_punto','comune_punto','cap_punto','provincia_punto','lat','lon');\"";
			
			if($chiave == "cap_sede" && isset($ajax))  $extra =  " <a href=\"#\" onClick=\"fl_get_coordinate('sede_legale','comune_sede','cap_sede','provincia_sede','',''); return false;\" ><i class=\"fa fa-map-marker\"></i></a>";
			if($chiave == "sede_legale" && isset($ajax))  $onblur = "onfocusout=\"fl_get_coordinate('sede_legale','comune_sede','cap_sede','provincia_sede','',''); \"";

			if($chiave == "cap_residenza" && isset($ajax))  $extra =  " <a href=\"#\" onClick=\"fl_get_coordinate('indirizzo_residenza','comune_residenza','cap_residenza','provincia_residenza','',''); return false;\"><i class=\"fa fa-map-marker\"></i></a>";
			if($chiave == "indirizzo_residenza" && isset($ajax))  $onblur = "onfocusout=\"fl_get_coordinate('indirizzo_residenza','comune_residenza','cap_residenza','provincia_residenza','',''); \"";
	
			echo "<p class=\"print_p\" id=\"txt_$chiave\"><label>".record_label($chiave,CONNECT,1)." $extra:</label > <span><input $onblur id=\"$chiave\" type=\"text\" value=\"$valore\" class=\"updateField_off\" name=\"$chiave\" data-rel=\"$id\" /></span></p>\r\n";
			break;
			
			
			case 2:		 // Select Box	
			echo "<p class=\"print_p\" id=\"txt_$chiave\"><label>".record_label($chiave,CONNECT,1).":</label><span>";			
			echo "<select  id=\"$chiave\" name=\"$chiave\" id=\"$chiave\"  class=\"updateField_off\" value\"$valore\" data-rel=\"$id\">\r\n";
			echo "<option value=\"-1\">Seleziona...</option>\r\n";
			
			foreach($$chiave as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($valore == $valores) ? " selected=\"selected\"" : "";
			if($chiave == 'proprietario' && $id == 1 && $valores == $_SESSION['number']) { $selected = " selected=\"selected\""; }
			echo "<option value=\"$valores\"$selected>".ucfirst($label)."</option>\r\n";
			}
			echo "</select>\r\n";
			echo "</span></p>\r\n";
			break;
			
					
			
			case 3:      // Text Area
			echo "<p class=\"print_p\" id=\"txt_$chiave\"><label>".record_label($chiave,CONNECT,1).":</label></p><textarea class=\"updateField_off\" name=\"$chiave\"  data-rel=\"".$id."\" >".converti_txt($valore)."</textarea>\r\n";
			break;
			case 4:		// Input Text	
			echo "<p class=\"print_p\" id=\"txt_$chiave\"><label>".record_label($chiave,CONNECT,1).":</label> $valore</p>\r\n";
			break;
			

			
			case 6:
		    $cheking = ($valore == 1) ? "checked=\"checked\"" : "";
			echo "<p class=\"print_p\" id=\"txt_$chiave\"><label>".record_label($chiave,CONNECT,1).":</label></p>";
			break;


			case 8:
			
			$cheking = ($valore == 1) ? "checked=\"checked\"" : "";
			$cheking_no = ($valore == 0) ? "checked=\"checked\"" : "";	

			echo "<p class=\"print_p\" id=\"txt_$chiave\"><label>".record_label($chiave,CONNECT,1).":</label><span>";			
		   	echo "
			<input type=\"radio\" id=\"".$chiave."1\" name=\"$chiave\" value=\"1\" $cheking />
			<label for=\"".$chiave."1\">Si</label>
			<input type=\"radio\" id=\"".$chiave."2\" name=\"$chiave\" value=\"0\" $cheking_no />
			<label for=\"".$chiave."2\"> No </label>\r\n";			
			echo "</span></p>\r\n";


			break;


			case 9:
			
			foreach($$chiave as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($valore == $valores) ? "checked=\"checked\"" : "";	
			$disabled = ($valore != $valores) ? "disabled" : "";
		   	echo "
			<input $disabled type=\"radio\" id=\"".$chiave.$valores."\" name=\"$chiave\" value=\"".$valores."\" $selected />
			<label for=\"".$chiave.$valores."\">".$label."</label>";
			}
			if(!isset($$chiave) || !is_array($$chiave)) { echo "<p> ATTENZIONE: ".$chiave." non trovata o non e un array.</p>";  }	

			break;


			case 12:		 // Select Box	
			echo "<p class=\"print_p\" id=\"txt_$chiave\"><label>".record_label($chiave,CONNECT,1).":</label><span>";			
			echo "<select class=\"updateField_off\" data-rel=\"".$id."\" name=\"$chiave\" id=\"$chiave\" >\r\n";
			if(trim($valore) == '') echo "<option value=\"\">Seleziona precedente...</option>\r\n";
			if(trim($valore) != '') echo "<option value=\"$valore\" selected>$valore</option>\r\n";
			echo "<option value=\"$valore\" selected>$valore</option>\r\n";
			echo "</select>\r\n";
			echo "</span></p>\r\n";
			
			break;

			case 19:		 // Select Box	
			echo "<p class=\"print_p\" id=\"txt_$chiave\"><label>".record_label($chiave,CONNECT,1).":</label><span>";			
			foreach($$chiave as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($valore == $valores) ? "checked=\"checked\"" : "";	
	
		   	echo "
			<input type=\"radio\" id=\"".$chiave.$valores."\" name=\"$chiave\" value=\"".$valores."\" $selected />
			<label for=\"".$chiave.$valores."\">".$label."</label>";
			}
			if(!isset($$chiave) || !is_array($$chiave)) { echo "<p> ATTENZIONE: ".$chiave." non trovata o non e un array.</p>";  }	
			echo "</span></p>\r\n";
			break;

			case 20:		// Input Text
			$valore = ($valore != '0000-00-00 00:00:00') ? mydate($valore) : '-';	
			echo "<p class=\"print_p\" id=\"txt_$chiave\"><label>".record_label($chiave,CONNECT,1).":</label> <span><input type=\"text\" class=\"updateField_off calendar\" name=\"$chiave\" data-rel=\"".$id."\"  value=\"$valore\" /></span></p>\r\n";
			break;

									
			case 25:      // Text Area
			echo "<h2>".$valore."</h2>";
	      
			break;
			
			case 26:      // Text Area
			echo "<p class=\"date\"><label>".record_label($chiave,CONNECT,1).":</label> <span>".date("d/m/Y - H:i",$valore)."</span></p>";
	      
			break;
			
			}
			echo '</div>';		
			}
			
			
			
			
		}	
	}
	
	if(isset($tabs_div) && count($tabs_ext) > 0) { 
	$c = 0;
	foreach($tabs_ext as $chiave => $valore) { 
	 $contenutoFrame = str_replace('[*ID*]',$id.'&title',$valore);
     if(!is_numeric($chiave)) echo "</div><div class=\"box_div\" ><h2>".$chiave."</h2><!--<p class=\"leggi\"><a data-fancybox-type=\"iframe\" class=\"fancybox\" href=\"mod_inserisci.php?view&id=$id&tId=tb_".$c."\">Modifica</a></p>--><iframe style=\"width: 100%; border: none; height:400px; \" src=\"".$contenutoFrame."\"></iframe>";
	 $c++; }
	
    }


	
	?>