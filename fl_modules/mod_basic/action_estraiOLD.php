<?php 

if(isset($_GET['copy_record'])) { echo '<input type="hidden" name="copy_record" value="1" />
<div class="msg orange">ATTENZIONE! Stai creando una copia di questo elemento</div>' ;
} 


if(isset($_GET['id'])) { $id = (is_numeric($_GET['id'])) ? check($_GET['id']) : exit;  } 
if(isset($force_id)){ $id = check($force_id); } 
if(isset($_GET['n'])) $id = 1;

$id = (isset($id)) ? $id : 1;

$where = "WHERE id = $id ";

if(!isset($_GET['label']) && $_SESSION['nome'] == 'Sistema') echo "<p><a href=\"$thispage&label\">Modifica Campi</a> </p>";

if(isset($newid)) { $id = (is_numeric($newid)) ? check($newid) : exit; $where = "WHERE id = $id ";}	
	
	$counter = 0;
	
	if(!isset($codice_scheda)){
	if(!isset($tosave)){
	echo "<p><input type=\"hidden\" name=\"id\" id=\"id\" value=\"$id\" /></p>"; 
	} else { echo "<input type=\"hidden\" name=\"id\" id=\"id\" value=\"$tosave\" />";}
	}
	echo "<p><input type=\"hidden\" name=\"gtx\" id=\"gtx\" value=\"$tab_id\" /></p>";	
	
	if(isset($_GET['label'])) {	$x = 0; echo '</form><form method="post" enctype="application/x-www-form-urlencoded" action="../mod_basic/mod_save_labels.php" name="creazione_labels">'; }
	
	$query = "SELECT * FROM `$tabella` $where LIMIT 1;";
	
	/* Box Esito post Ajax*/
	echo '<div id="results"></div>';

	if(!$risultato = mysql_query($query, CONNECT) )  //echo "<p>".mysql_error()."</p><p>$query</p>"; } // Errore mysql
	if(mysql_affected_rows() < 1) { echo "<h2>Manca l'origine dati</h2>"; } // Se manca la riga 1 per form_maker
	
	/* Generazione automatica tabs scheda */
	if(isset($tab_div_labels)) { 
	$tabs_div = array();
	$tabs_ext = array();
	echo '<div id="tabs"><ul>';
	$ext = 0;
	
	foreach($tab_div_labels as $chiave => $valore) { 
	if(strstr($chiave,'[*ID*]')) { 
	echo '<li><a href="#tb_'.$ext.'">'.$valore.'</a></li>';
	$tabs_ext['tb_'.$ext] = $chiave;
	$ext++;
	} else {
	array_push($tabs_div,$chiave);
	echo '<li><a href="#tb_'.$chiave.'">'.$valore.'</a></li>'; }
    }
	echo '</ul>
	<div id="tb_'.$tabs_div[0].'">';
	unset($tabs_div[0]);
	}  else {  echo '<div id="tabs"><div class="ui-widget-content">';}

	$campi = array();
	$campi_anno = array('anno_di_imposta','anno');
	$campi_today = array('start_meeting','end_meeting','data_fine_evento','data_evento','data_preventivo','data_pubblicazione','data_prenotazione','data_pagamento','data_richiesta','data_fattura','data_apertura','data_avvio','data_corrispettivo','data_versamento','data_scadenza_pec','data_sottoscrizione','data_inizio','data_fine','data_rinnovo','data_documento','data_operazione','data_scadenza');
	
	while ($riga = mysql_fetch_array($risultato)) 
	{
	    $dafault_disable = "";
		foreach($riga as $chiave => $valore){
		$campi[$chiave] = $valore;
		
		if($id == 1 && in_array($chiave,$campi_anno)) $valore = date('Y');
		if($id == 1 && in_array($chiave,$campi_today)) $valore = date('Y-m-d H:i');
		if(isset($tabs_div) && is_array($tabs_div)) { if(in_array($chiave,$tabs_div) && !is_numeric($chiave)) echo '</div><div id="tb_'.$chiave.'">'; }
		
		
		if(is_to_use($chiave) == 1){
	
			$size = (is_numeric($valore)) ? 6 : 255;
			


			if($chiave == "sezione"){ $rel = $valore; }		
			if($chiave == "email"){ $email = $valore; }		
			if($chiave == "aggiornamento_password"){ $aggiornamento_password = $valore; }		
			$disabled = $dafault_disable;
			$obbligatorio = '';
			if($chiave == "sezione" && $valore > 1){ $disabled = "disabled";  }
			if($chiave == "categoria" && $rel == 0){ $disabled = "disabled";  }
			if($chiave == "anagrafica") $anagrafica_id = $valore; 
			if($chiave == "marchio") $marchio_id = $valore; 
			if($chiave == "tipo") $tipo_id = $valore; 
			if(($chiave == "anno_fiscale" || $chiave == "anno") && $id == 1) $valore = date('Y');
			if($chiave == "mese" && $id == 1) $valore = date('m');
			if($chiave == "data_creazione" && $id == 1) $valore = date('Y-m-d H:i:s');
			if($chiave == "data_creazione_asset" && $id == 1) $valore = date('Y-m-d');
			if($chiave == "proprietario" && $id == 1) $valore = $_SESSION['number']; 
			if($chiave == "item_rel" && isset($_GET['item_rel'])) $valore = check($_GET['item_rel']);
			if($chiave == "menu_id" && isset($_GET['menu_id'])  && $id == 1) $valore = check($_GET['menu_id']); 
			if($chiave == "modulo" && isset($_GET['modulo_id'])  && $id == 1) $valore = check($_GET['modulo_id']); 
			if($chiave == "modalita_pagamento" && isset($_GET['ANiD']) && $id == 1) $valore =  (isset($pagamenti_f24_id)) ? $pagamenti_f24_id : 0; //
			if($chiave == "modulo_id" && isset($_GET['mId']) && $id == 1) $valore = base64_decode(check($_GET['mId']));
			if($chiave == "course_id" && isset($_GET['cId']) && $id == 1) $valore = base64_decode(check($_GET['cId']));
			if($chiave == "quesito_id" && isset($_GET['qId']) && $id == 1) $valore = base64_decode(check($_GET['qId']));
			if($chiave == "test_id" && isset($_GET['tId']) && $id == 1) $valore = base64_decode(check($_GET['tId']));
			if($chiave == "numero_documento" && isset($_GET['numero_documento']) && $id == 1) $valore = check($_GET['numero_documento']);
			if($chiave == "dipendenza" && isset($_GET['dipendenza']) && $id == 1) $valore = check($_GET['dipendenza']);
			if($chiave == "anagrafica_id" && isset($_GET['ANiD']) && $id == 1) $valore =  (is_numeric($_GET['ANiD'])) ? check($_GET['ANiD']) : base64_decode(check($_GET['ANiD'])); //
			if($chiave == "anagrafica_id" && $_SESSION['usertype'] > 1 && $id == 1) $valore =  @$_SESSION['anagrafica'];
			if($chiave == "account_id") $valore = $_SESSION['number']; // Account id delle risorse
			if($chiave == "account_id" && isset($_GET['AiD']) && $id == 1) $valore = base64_decode(check($_GET['AiD'])); // Account id delle risorse
			if($chiave == "workflow_id" && isset($_GET['WiD']) && $id == 1) $valore = base64_decode(check($_GET['WiD']));
			if($chiave == "parent_id" && isset($_GET['PiD']) && $id == 1) $valore = base64_decode(check($_GET['PiD'])); 
			if($chiave == "conto" && isset($_GET['cId']) && $id == 1) $valore = base64_decode(check($_GET['cId'])); 
			if($chiave == "prodotto_id" && isset($_GET['prodotto_id']) && $id == 1) $valore = check($_GET['prodotto_id']); 
			if($chiave == "anagrafica_cliente" && isset($_GET['anagrafica_cliente'])) $valore = check($_GET['anagrafica_cliente']); 
			if(isset($_GET[$chiave]) && $id == 1) $valore = check($_GET[$chiave]); 
			if(isset($$chiave) && $id == 1 && !is_array($$chiave)) $valore = $$chiave; 
			$SEL_val = 'SEL_'.$chiave;
			if(isset($$SEL_val) && $$SEL_val > -1 && $id == 1 && !is_array($$SEL_val)) $valore = $$SEL_val; 
			if($chiave == "status_potential" && isset($_GET['status_potential']) && $id == 1 && check($_GET['status_potential']) < 1 ) $valore = 1; 
	
			
			$disabilitati = array('pincode','aggiornameto_pin','aggiornamento_password','data_creazione','account_id','data_inizio','data_fine','datac_inizio','datac_fine','account_affiliato','user','anagrafica');		
			if(in_array($chiave,$disabilitati)){ $disabled = "disabled";  }
			
			
			/*Imposta Elementi Form Label */
			if(isset($_GET['label']) && select_type($chiave) != 5 && $chiave != "status" && $chiave != "note_aggiuntive"){
 				$x++;
				$tipi_input = array('Non gestire','Testo','Selezione','Area di Testo','Disabilitato','Invisibile','Checkbox','Orario','Radio SI/NO','Radio Selezione');	
				$madatory = record_label($chiave,CONNECT,3);
				$cheking = ($madatory == 1) ? "checked=\"checked\"" : "";
				$cheking_no = ($madatory == 0) ? "checked=\"checked\"" : "";	
				echo "<div class=\"input_text modify_div\"><h2>$chiave</h2>
				<input type=\"hidden\" name=\"fl_campo_$x\" value=\"$chiave\" />				
				<p class=\"modify_input\"><label>Obbligatorio</label>
				<input style=\"width: 10px;\" name=\"fl_richiesto_$x\" id=\"fl_richiesto_".$x."_si\"  value=\"1\" type=\"radio\" $cheking />
				<label for=\"fl_richiesto_".$x."_si\">Si</label>
				<input style=\"width: 10px;\" name=\"fl_richiesto_$x\" id=\"fl_richiesto_".$x."_no\"   value=\"0\" type=\"radio\" $cheking_no />
				<label for=\"fl_richiesto_".$x."_no\">No</label>
				Ricerca &nbsp; 
				<input style=\"width: 10px;\" name=\"fl_filtro_$x\" id=\"fl_filtro_".$x."_si\"  value=\"1\" type=\"radio\" $cheking />
				<label for=\"fl_filtro_".$x."_si\">Si</label>
				<input style=\"width: 10px;\" name=\"fl_filtro_$x\" id=\"fl_filtro_".$x."_no\"   value=\"0\" type=\"radio\" $cheking_no />
				<label for=\"fl_filtro_".$x."_no\">No</label></p>
				
				<p class=\"modify_input\"><label>Label</label><input name=\"fl_label_$x\" value=\"".record_label($chiave,CONNECT,1)."\" /></p>
				
				<p class=\"\"><label>Tipo di input</label><select name=\"fl_tipo_$x\" class=\"\">";
				foreach($tipi_input as $num => $labl){
				$selected = (record_label($chiave,CONNECT,4) == $num) ? 'selected' : '';
				echo "<option value=\"$num\" $selected>$labl</option>";
				}				
				echo "</select>   
				</p>
				<p class=\"modify_input\"><label>Testatina</label><input name=\"fl_header_$x\" type=\"text\" value=\"".record_label($chiave,CONNECT,0)."\" /></p>
				<p class=\"modify_input\"><label>Help</label> <input name=\"fl_help_$x\" value=\"".record_label($chiave,CONNECT,2)."\" /></p>";
			 
			} else {
			if(record_label($chiave,CONNECT,0) != "") echo "<h3 class=\"testata\">".record_label($chiave,CONNECT,0)."</h3>\r\n";
			
			echo '<div class="form_row" id="box_'.$chiave.'">';
			
			$info ='';
			if(record_label($chiave,CONNECT,2) != "") $info = "<a href=\"#\" title=\"".strip_tags(record_label($chiave,CONNECT,2))."\"><i class=\"fa fa-question\"></i></a>\r\n";
			if(record_label($chiave,CONNECT,3) == 1) $obbligatorio =  "validate[required]";
			}
			

			$campi[$chiave] = $valore;
			
			
			switch(select_type($chiave)){
							
			case 1:		// Input Text	
			$extra = '';
			$tasto = '';
			$onblur = '';
			if($chiave == "partita_iva"){ $extra = '<a href="https://telematici.agenziaentrate.gov.it/VerificaPIVA/Scegli.do?parameter=verificaPiva" target="new">Verifica</a>'; }
		    if($chiave == "codice_fiscale" || $chiave == "codice_fiscale_legale"){ $extra = '<a href="https://telematici.agenziaentrate.gov.it/VerificaCF/Scegli.do?parameter=verificaCf" target="new">Verifica</a>'; }
			if($chiave == "sito_web"){ $extra = "<a href=\"#\" onclick=\"window.open(document.getElementById('scheda').sito_web.value); return false;\">Controlla Sito</a>"; }
			
			
			if($chiave == "cap_punto" && isset($ajax))  $extra =  " <a href=\"#\" onClick=\"fl_get_coordinate('indirizzo_punto','comune_punto','cap_punto','provincia_punto','lat','lon'); return false;\"><i class=\"fa fa-map-marker\"></i></a>";
			if($chiave == "indirizzo_punto" && isset($ajax))  $onblur = "onfocusout=\"fl_get_coordinate('indirizzo_punto','comune_punto','cap_punto','provincia_punto','lat','lon');\"";
			
			if($chiave == "cap_sede" && isset($ajax))  $extra =  " <a href=\"#\" onClick=\"fl_get_coordinate('sede_legale','comune_sede','cap_sede','provincia_sede','',''); return false;\" ><i class=\"fa fa-map-marker\"></i></a>";
			if($chiave == "sede_legale" && isset($ajax))  $onblur = "onfocusout=\"fl_get_coordinate('sede_legale','comune_sede','cap_sede','provincia_sede','',''); \"";

			if($chiave == "cap_residenza" && isset($ajax))  $extra =  " <a href=\"#\" onClick=\"fl_get_coordinate('indirizzo_residenza','comune_residenza','cap_residenza','provincia_residenza','',''); return false;\"><i class=\"fa fa-map-marker\"></i></a>";
			if($chiave == "indirizzo_residenza" && isset($ajax))  $onblur = "onfocusout=\"fl_get_coordinate('indirizzo_residenza','comune_residenza','cap_residenza','provincia_residenza','',''); \"";
	

  		   	if($valore == '0.000') $onblur = "onclick=\"this.value=''\"";
			if($chiave == 'ricavo_netto') $onblur = "onfocus=\"this.value=Math.round((vendita_imponibile.value-costo_imponibile.value) * 1000) / 1000;\"";

			
  		    echo "<p class=\"input_text\"><label for=\"$chiave\">".record_label($chiave,CONNECT,1)." $info $extra</label><input  $onblur class=\"$obbligatorio\" type=\"text\" name=\"$chiave\" id=\"$chiave\"  value=\"".$valore."\" $disabled /> </p>\r\n";
		
			break;
			
			
			case 2:		 // Select Box	
			$onChange = '';
			if($chiave == "anagrafica_id" && isset($ajax)) $onChange = "onChange=\"load_cliente()\"";
			echo "<p class=\"select_text $chiave\" ><label for=\"$chiave\">".record_label($chiave,CONNECT,1)."</label>\r\n
			<select class=\"select2 $obbligatorio\" name=\"$chiave\" id=\"$chiave\" $disabled $onChange>\r\n";
			echo "<option value=\"-1\">Seleziona...</option>\r\n";
			
			foreach($$chiave as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($valore == $valores) ? " selected=\"selected\"" : "";
			if($chiave == 'proprietario' && $id == 1 && $valores == $_SESSION['number']) { $selected = " selected=\"selected\""; }
			echo "<option value=\"$valores\"$selected>".ucfirst($label)."</option>\r\n";
			}
			echo "</select>\r\n";
			echo "</p>\r\n";
			if(!isset($$chiave) || !is_array($$chiave)) { echo "<p> ATTENZIONE: ".$chiave." non trovata o non e un array.</p>";  }	
			
			break;
			
					
			
			case 3:      // Text Area
			if($chiave != "messaggio" || $chiave != "note") echo "<h3><label for=\"$chiave\">".record_label($chiave,CONNECT,1)."</label></h3>";
			echo "<p class=\"input_text\"><br /><textarea  name=\"$chiave\"  class=\"mceEditor\"  id=\"$chiave\" rows=\"20\" cols=\"100\" $disabled onkeyup=\"$('#info$chiave').html(this.value.length+' caratteri');\">".$valore."</textarea><span id=\"info$chiave\"></span></p>\r\n";
			break;
			
			
			case 4:			
			echo "<p class=\"input_text\"><label for=\"$chiave\">".record_label($chiave,CONNECT,1)."</label><input  disabled type=\"text\" name=\"$chiave\" id=\"$chiave\"  value=\"".$valore."\"  /></p>\r\n";
			break;
			
			case 5:
			if($chiave == "ip"){ $valore = $_SERVER['REMOTE_ADDR'];}
			if($chiave == "operatore"){ $valore = $_SESSION['number'];}
			if($chiave == "id"){ $file_id = $valore; }
			if($id == 1 && $chiave == 'data_creazione') $valore = date("Y-m-d");
			
			echo "<input type=\"hidden\" name=\"$chiave\" id=\"$chiave\" value=\"$valore\"  />\r\n";
			break;
			
			case 6:
			echo "<p class=\"pbox\"><label class=\"labelbox\">".record_label($chiave,CONNECT,1)."</label>";
			foreach($$chiave as $valores => $label){ // Recursione Indici di Categoria
			$scelti = explode(",",$valore);
			$selected = (in_array("[".$valores."]",$scelti)) ? " checked=\"checked\"" : "";
	
		   	echo "
			<input type=\"checkbox\" id=\"".$chiave.$valores."\" name=\"$chiave"."[]"."\" value=\"".$valores."\" $selected />
			<label for=\"".$chiave.$valores."\">".$label."</label>";
			}
			if(!isset($$chiave) || !is_array($$chiave)) { echo "<p> ATTENZIONE: ".$chiave." non trovata o non e un array.</p>";  }	
			echo "</p>";
			break;
			
			
			case 7:
			if($chiave == "ip"){ $valore = $_SERVER['REMOTE_ADDR'];}
			if($chiave == "operatore"){ $valore = $_SESSION['number'];}
			if($chiave == "id"){ $file_id = $valore; }
			if($id == 1 && $chiave == 'data_creazione') $valore = date("Y-m-d");
			
			echo "<input type=\"hidden\" name=\"$chiave\" id=\"$chiave\" value=\"$valore\"  />\r\n";
			break;
								
			case 8:
			echo "<p class=\"pbox\"><label class=\"labelbox\">".record_label($chiave,CONNECT,1)."</label>";

			$cheking = ($valore == 1) ? "checked=\"checked\"" : "";
			$cheking_no = ($valore == 0) ? "checked=\"checked\"" : "";	
			$counter++;
		   	echo "<span class=\"radio_box\">
			<input type=\"radio\" id=\"".$chiave."1\" name=\"$chiave\" value=\"1\" $cheking />
			<label for=\"".$chiave."1\">".record_label($chiave,CONNECT,1)."</label>
			<input type=\"radio\" id=\"".$chiave."2\" name=\"$chiave\" value=\"0\" $cheking_no />
			<label for=\"".$chiave."2\"> No </label>\r\n";
			echo "</span></p>";
			break;
			
			case 9:
			echo "<p class=\"pbox\"><label class=\"labelbox\">".record_label($chiave,CONNECT,1)."</label>";
			echo "<span class=\"radio_box\">";
			foreach($$chiave as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($valore == $valores) ? "checked=\"checked\"" : "";	
			
		   	echo "
			<input type=\"radio\" id=\"".$chiave.$valores."\" name=\"$chiave\" value=\"".$valores."\" $selected />
			<label for=\"".$chiave.$valores."\">".$label."</label>";
			}
			if(!isset($$chiave) || !is_array($$chiave)) { echo "<p> ATTENZIONE: ".$chiave." non trovata o non e un array.</p>";  }	
			echo "</span></p>";
			break;
		
			case 10:
			$extra = '';
			$tasto = '';
			$onblur = '';
			if($valore == '0.000') $onblur = "onclick=\"this.value=''\"";
			if($chiave == 'ricavo_netto') $onblur = "onfocus=\"this.value=Math.round((vendita_imponibile.value-costo_imponibile.value) * 1000) / 1000;\"";
			echo '<div class="form_row" id="box_'.$chiave.'">';
  		    echo "<p class=\"input_text\"><label for=\"$chiave\">".record_label($chiave,CONNECT,1)."</label><input  $onblur class=\"number\" type=\"text\" name=\"$chiave\" id=\"$chiave\"  value=\"".$valore."\" $disabled /> </p>\r\n";
			echo '</div>';			

			break;

			case 11:
			$datetime = explode(" ",$valore);	
			$data = explode("-",$datetime[0]);		
			$valore_conv = str_replace('//','',@$data[2].'/'.@$data[1].'/'.@$data[0]).' '.substr($datetime[1],0,5);
			echo "<p class=\"input_text\"><label for=\"$chiave\">".record_label($chiave,CONNECT,1)."</label>
			<input type=\"text\" name=\"$chiave\" id=\"$chiave\" value=\"$valore_conv\" class=\"datetimepicker\" />
			</p>\r\n";
			break;
			
	
			case 12:		 // Select Box	
			echo "<p class=\"select_text $chiave\" ><label for=\"$chiave\">".record_label($chiave,CONNECT,1)."</label>\r\n
			<select class=\"$obbligatorio\" name=\"$chiave\" id=\"$chiave\" $disabled>\r\n";
			if(trim($valore) == '') echo "<option value=\"\">Seleziona precedente...</option>\r\n";
			if(trim($valore) != '') echo "<option value=\"$valore\" selected>$valore</option>\r\n";
			echo "</select>\r\n";
			echo "</p>\r\n";
			
			break;

			case 13:		// Input Text	
			$disabled = ($valore == '') ? 'disabled' : '';
  		    echo "<p class=\"input_text\"><label for=\"$chiave\">".record_label($chiave,CONNECT,1)."</label>
  		    <input type=\"checkbox\" data-control=\"$chiave\" class=\"EnableDisable\" style=\"display: inline; width: auto; margin: 0; border: 1px solid gray;\">
  		    <input type=\"text\" name=\"$chiave\" id=\"$chiave\"  value=\"".$valore."\" $disabled /> </p>\r\n";
		
			break;

		    case 18:
			echo '<div class="form_row" id="box_'.$chiave.'">';
  		    echo "<p class=\"input_text\"><label for=\"$chiave\">".record_label($chiave,CONNECT,1)."</label>".$valore;
			if($valore != "") echo "<input type=\"hidden\" name=\"old_file\" id=\"old_file\" value=\"$valore\" /><input type=\"checkbox\" name=\"del_file\" id=\"del_file\" style=\"display: inline; width: auto;\" value=\"1\" /> [X]</p>\r\n";
			echo '</div>';			
			break;
			
			case 19:
			echo "<p class=\"pbox\"><label class=\"labelbox\">".record_label($chiave,CONNECT,1)."</label>";
			foreach($$chiave as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($valore == $valores) ? "checked=\"checked\"" : "";	
	
		   	echo "
			<input type=\"radio\" id=\"".$chiave.$valores."\" name=\"$chiave\" value=\"".$valores."\" $selected />
			<label for=\"".$chiave.$valores."\">".$label."</label>";
			}
			if(!isset($$chiave) || !is_array($$chiave)) { echo "<p> ATTENZIONE: ".$chiave." non trovata o non e un array.</p>";  }	
			echo "</p>";
			break;
			
			case 20:				

			$data = @explode("-",$valore);		
			$valore_conv = @$data[2].'/'.@$data[1].'/'.@$data[0];
			
			echo "<p class=\"input_text\"><label for=\"$chiave\">".record_label($chiave,CONNECT,1)."</label>
			
			<input type=\"text\" name=\"$chiave\" id=\"$chiave\" value=\"$valore_conv\" size=\"30\" class=\"calendar\" $disabled/></p>\r\n";
					
			break;
			
			case 21:
			echo "<p><input type=\"hidden\" name=\"$chiave\" id=\"$chiave\" value=\"1\" /></p>\r\n";
			break;
			
			if(strlen($valore) > 1) {if($chiave != "messaggio" || $chiave != "note") echo "<h3><label for=\"$chiave\">".record_label($chiave,CONNECT,1)."</label></h3>";
			echo converti_txt($valore);
	        }
			break;

			case 23:
			
			echo "<p class=\"select_text $chiave\"><label>".record_label($chiave,CONNECT,1)."</label><br><span style=\"text-align: right;\">\r\n";
			$valore = explode(',',$valore);
			foreach($$chiave as $key => $label){ // Recursione Indici di Categoria
			$selected = (in_array($key,$valore)) ? "checked=\"checked\"" : "";	
			
		   	echo "
			<input name=\"$chiave"."[".$key."]"."\" type=\"checkbox\" id=\"".$chiave.$key."\" value=\"".$key."\" $selected />
			<label for=\"".$chiave.$key."\">".$label."</label>";
			}
			
			if(!isset($$chiave) || !is_array($$chiave)) { echo "ATTENZIONE: ".$chiave." non trovata o non e un array.";  }	
			echo '</span></p>';
			break;

			case 25:      // Text Area
			echo "<h2>".$valore."</h2>";
	      
			break;
			
			case 26:      // Text Area
			echo "<h2>".date("d/m/Y - H:i",$valore)."</h2>";
	      
			break;
			
			}

			if($chiave == 'mesi_di_interesse') echo '<a href="javascript:return false;"  class="msg orange showCalDisp" ><i class="fa fa-calendar-times-o" aria-hidden="true"></i>
 Calendario Disponibilità</a>';
			echo '</div>';			
			
			}
			
			
			
		}	
	}
	
	if(isset($tabs_div) && @$tabs_div != 0 && count($tabs_ext) > 0) { 
	foreach($tabs_ext as $chiave => $valore) { 
	 $contenutoFrame = str_replace('[*ID*]',$id,$valore);
	 $css_iframe = (!isset($css_iframe)) ? ' width: 100%; border: none; height:1200px; ' : $css_iframe;
     if(!is_numeric($chiave)) echo "</div><div id=\"$chiave\"><iframe style=\"$css_iframe\" src=\"".$contenutoFrame."\"></iframe>";
	}
    }
	
	if(isset($_GET['label'])) { echo '</div></div><p class="savetabs"><input type="submit" value="Salva Configurazione"></p> ';} else {
	if(isset($tabs_div) && @$tabs_div != 0) {  
	echo '</div></div>'; echo '<p class="savetabs"><a href="#" id="invio" class="button salva"> '.$save_form.' <i class="fa fa-check"></i></a></p>';
	} else { echo '</div></div><p class="savetabs"><a href="#" id="invio" class="button salva"> '.$save_form.' <i class="fa fa-check"></i></a></p>'; }
	}
	?>