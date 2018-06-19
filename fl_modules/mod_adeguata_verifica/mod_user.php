<?php
// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
$_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];
if(isset($_SESSION['subaccount'])) { echo '<h2>Stai operando per conto di: '.$proprietario[$_SESSION['subaccount']].'</h2>'; } else { echo "<h2>".ucfirst(@$proprietario[$_SESSION['number']])."</h2>"; }
?>


<div id="filtri" class="filtri">
<form method="get" action="">
   
  
        <label for="identificato"> Stato: </label>
        <select name="identificato" id="identificato">
            <option value="0">Mostra Tutti</option>
			<?php 
              
		     foreach($identificato as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($identitficato_id == $valores) ? " selected=\"selected\"" : "";
			if($valores != 0){ echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; }
			}
		 ?>
       </select>
       <label for="data_da">creato tra il</label>
       <input type="text" name="data_da" onFocus="this.value='';" value="<?php  echo $data_da_t;  ?>" class="calendar" size="10" />
       <label for="data_a">e il</label>
       <input type="text" name="data_a" onFocus="this.value='';" value="<?php  echo $data_a_t;  ?>" class="calendar" size="10" /> 
        
       <input type="submit" value="Mostra" class="button" />


</form>
</div>

<?php
	

	if(isset($_GET['ordine'])) { if(!is_numeric($_GET['ordine'])){ exit; } else { $ordine = $ordine_mod[$_GET['ordine']]; }}
	
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main);
						
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	
	$risultato = mysql_query($query, CONNECT);
	//echo $query;
	
	?>

 
  	 <table class="dati" summary="Dati" style=" width: 100%;">
      <tr>
        <th scope="col">Nominativo</th>
     
        <th scope="col">Utente</th>
        
        <th scope="col">Identificato</th>
        <th scope="col">Note</th>
         <th scope="col">Documenti</th>
         <th scope="col">Modifica</th>
        <th scope="col">Elimina</th>          
        <th scope="col">Agg./Creaz.</th>   
      </tr>
	  
	<?php 
	
	$i = 1;
	$Doc_nofile = 0;
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"7\">Nessun Record Inserito</td></tr>";		}
	
	while ($riga = mysql_fetch_array($risultato)) 
	{
			$query_doc = "SELECT * FROM fl_dms WHERE workflow_id = $workflow_id AND record_id = ".$riga['id']." ORDER BY label ASC";
 			$risultato2 = mysql_query($query_doc, CONNECT);	
			$documenti_count = mysql_affected_rows();
			
			if(($documenti_count > 1 && !isset($_GET['show_incomplete'])) || ($documenti_count < 1 && isset($_GET['show_incomplete']))){
			
			if($i==1){ $i=0; echo "<tr>"; } else { $i=1; echo "<tr class=\"alternate\">"; }		
			
			$identificato = ($riga['identificato'] == 2) ? "<span class=\"green msg\">".$identificato[$riga['identificato']]."</span>" : "<span class=\"red msg\">".$identificato[$riga['identificato']]."</span>"; 
		    $note = ($riga['note'] != "") ?  "<span class=\"c-red\"><a href=\"?action=1&amp;sezione=".@$riga['sezione']."&amp;id=".$riga['id']."\" title=\"".convert_note($riga['note'])."\"><i class=\"fa fa-exclamation-triangle fa-lg\"></i></a></span>" : "";

            if($documenti_count < 2){$doc_info = "<br /><span class=\"red\">ATTENZIONE: <a href=\"../mod_documentazione/?modulo=0&amp;cat=6&amp;contenuto=".$riga['id']."\" title=\"Gestisci Files\" class=\"button\">CARICA DOCUMENTI</a></span>";  
			} else if(trim($riga['nome_e_cognome']) == "" || trim($riga['user']) == "" || trim($riga['codice_fiscale']) == "") { $doc_info = "<br /><span class=\"red\">ATTENZIONE: Dati Mancanti, clicca su modifica e compila tutti i campi."; 
			} else { $doc_info = ""; }
			
			if($riga['identificato'] < 2){
			$elimina_id = "<a href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=".$riga['id']."\" title=\"Elimina\" onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i> </a></a>";  
			$modifica_id = "<a href=\"./mod_inserisci.php?id=".$riga['id']."\" title=\"Modifica\" > <i class=\"fa fa-search\"></i> </a> </a>"; 
			$file_id = "<a data-fancybox-type=\"iframe\" class=\"fancybox\" href=\"../mod_dms/uploader.php?workflow_id=$workflow_id&record_id=".$riga['id']."&NAme[]=Carta di Identita&NAme[]=Codice Fiscale\" title=\"Carica File\"><i class=\"fa fa-cloud-upload\"></i></a>"; 
			} else { 
			$elimina_id = ""; 
			$modifica_id = ""; 
			$file_id = ""; 
			}

			echo "<td>".ucfirst($riga['nome_e_cognome'])."</td>";			
			echo "<td>".ucfirst($riga['user'])."</td>";		
			if($documenti_count < 2) {  echo "<td class=\"red\">NECESSARI DOCUMENTI</td>"; } else { echo "<td>$identificato</td>"; }
			echo "<td>$note</td>"; 
			echo "<td>$file_id</td>"; 
			echo "<td>$modifica_id</td>";
			echo "<td>$elimina_id</td>"; 
		    echo "<td style=\"font-size: 8px;\" title=\"Creato da: ". @$proprietario[$riga['proprietario']]." - ultimo intevento di: ". @$proprietario[$riga['operatore']]." il ".mydatetime($riga['data_aggiornamento'])."\">A ".mydate($riga['data_creazione'])."<br />C ".mydatetime($riga['data_aggiornamento'])."</td>";
		    echo "</tr>";
			
			} else { $Doc_nofile++; }
			
	}

	

?>	

</table>
<?php if($Doc_nofile > 0 && !isset($_GET['show_incomplete'])) {
echo "ATTENZIONE: Ci sono <span class=\"msg red\" style=\"font-size: larger;\">$Doc_nofile</span> attivazioni senza i documenti necessari!<br /><p><a href=\"../mod_attivazioni/?identificato=1&amp;show_incomplete=yes\" class=\"button\">VISUALIZZA ATTIVAZIONI INCOMPLETE</a></p>"; 
} else { echo "<p><a href=\"../mod_attivazioni/?identificato=1\" class=\"button\">VISUALIZZA ATTIVAZIONI IN ATTESA</a>"; }
?>	

<?php $start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main); ?>
