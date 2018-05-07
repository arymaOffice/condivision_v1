<?php

// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
$_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];
?>


   
 <div id="filtri" class="filtri"> 
<form method="get" action="" id="sezione_select">
 <h2>Filtri</h2>
 
       <label> Stato: </label>
       <select name="status_pagamento" id="status_pagamento">
            <option value="0">Mostra Tutti</option>
			<?php 
              
		     foreach($status_pagamento as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($status_pagamento_id == $valores) ? " selected=\"selected\"" : "";
			if($valores != 0){ echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; }
			}
		 ?>
       </select>
      <label> creato tra il </label>
      <input type="text" name="data_da" onFocus="this.value='';" value="<?php  echo $data_da_t;  ?>"  class="calendar" size="10" /> 
      <label>e il</label>
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
    
     
        <th scope="col">Utente</th>
        
        <th scope="col">Stato</th>
        
        <th scope="col">Data</th>
          <th scope="col">Importo</th>
         <th scope="col">Ricevuta</th>
         <th scope="col">Modifica</th>
        <th scope="col">Agg./Creaz.</th>   
      </tr>
	<?php 
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"9\">Nessun Record Inserito</td></tr>";		}

	while ($riga = mysql_fetch_array($risultato)) 
	{
			
			$query_doc = "SELECT * FROM fl_dms WHERE workflow_id = $workflow_id AND record_id = ".$riga['id']." ORDER BY label ASC";
 			$risultato2 = mysql_query($query_doc, CONNECT);	
			$documenti_count = mysql_affected_rows();

			$status_pagamento_txt = ($riga['status_pagamento'] == 2) ? "<span class=\"green msg\">".$status_pagamento[$riga['status_pagamento']]."</span>" : "<span class=\"orange msg\">".$status_pagamento[$riga['status_pagamento']]."</span>"; 
		    $note = ($riga['note'] != "") ?  "<span class=\"c-red\"><a href=\"?action=1&amp;sezione=".@$riga['sezione']."&amp;id=".$riga['id']."\" title=\"".convert_note($riga['note'])."\"><i class=\"fa fa-exclamation-triangle fa-lg\"></i></a></span>" : "";
			$files = "../mod_dms/uploader.php?PiD=".base64_encode(11)."&NAme[]=Ricevuta pagamento&workflow_id=$workflow_id&record_id=".$riga['id'];

					
			echo "<tr><td>".$proprietario[$riga['proprietario']]."</td>";		
			echo "<td>".$status_pagamento_txt."</td>"; 
			echo "<td>".mydate($riga['data_pagamento'])."</td>"; 
			echo "<td> &euro; ".$riga['importo']."</td>"; 
			echo "<td><a data-fancybox-type=\"iframe\" class=\"fancybox_view\" href=\"$files\" title=\"Files per questo elemento\"><i class=\"fa fa-file\"></i>($documenti_count)</a></td>"; 
			echo "<td><a href=\"mod_inserisci.php?id=".$riga['id']."\" title=\"Modifica\"> <i class=\"fa fa-search\"></i> </a></td>";
		    echo "<td  style=\"font-size: 8px;\" title=\"Creato da: ". @$proprietario[$riga['proprietario']]." - ultimo intevento di: ". @$proprietario[$riga['operatore']]." il ".mydatetime($riga['data_aggiornamento'])."\">C ".mydatetime($riga['data_creazione'])."<br />A ".mydatetime($riga['data_aggiornamento'])."</td>";
			if($riga['status_pagamento'] < 2) echo "<td class=\"tasto\"><a href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=".$riga['id']."\" title=\"Elimina\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i></a></td>"; 
				
		    echo "</tr>";
			
			
			
	}

	echo "</table>";
	

?>	


<p>* Ci sono delle note per questo nominativo</p>
