<?php

// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
echo "<h2>".ucfirst(@$proprietario[$_SESSION['number']])."</h2>";  ?>

     <a href="./?new&amp;auto" class="button" style=" float: right; padding: 4px 4px;">Nuova Richiesta </a> </p>

<form method="get" action="" id="sezione_select">
   
 <div style="position: relative; background:  #F4F4F4; padding: 1px 5px;"> 
  
         Stato: <select name="status_assistenza" id="status_assistenza">
            <option value="0">Mostra Tutti</option>
			<?php 
              
		     foreach($status_assistenza as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($status_assistenza_id == $valores) ? " selected=\"selected\"" : "";
			if($valores != 0){ echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; }
			}
		 ?>
       </select>
       creato tra il <input type="text" name="data_da" onFocus="this.value='';" value="<?php  echo $data_da_t;  ?>" size="10" class="calendar" /> e il <input type="text" name="data_a" onFocus="this.value='';" value="<?php  echo $data_a_t;  ?>" class="calendar" size="10" /> 
        
       <input type="submit" value="Mostra" class="button" />

    </div>
      
     
       
       </form>
<?php
	

	if(isset($_GET['ordine'])) { if(!is_numeric($_GET['ordine'])){ exit; } else { $ordine = $ordine_mod[$_GET['ordine']]; }}
	
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0);
						
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	//echo $query;
	$risultato = mysql_query($query,CONNECT);
	
		
	?>
       

  
<table class="dati" summary="Dati">
      <tr>
        <th>Oggetto | <a href="./?ordine=1">Operatore</a></th>
        <th><a href="./?ordine=5">Status</a></th>
        <th><a href="./?ordine=0">Data Apertura</a></th>
        <th>Data ultima attivit&agrave;</th>
      
 
      </tr>
	  
	<?php 
	
	$i = 1;
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"7\">Nessun Record Inserito</td></tr>";		}
	
	$entrate = 0;
	$uscite = 0;
	$saldo = 0;
	$saldo_parziale = 0;
	

	
	while ($riga = mysql_fetch_array($risultato)) 
	{
			
		$manute = "SELECT * FROM `$tabella` WHERE jorel = ".$riga['id']." AND operatore != ".$_SESSION['number']." AND letto = 0;";
		mysql_query($manute,CONNECT);
		$new_mail = (mysql_affected_rows() > 0) ? "not_read" : "read";
		
 			echo "<tr>";
			
			echo "<td><a href=\"?action=8&amp;proprietario=".$riga['proprietario']."&amp;jorel=$jorel&amp;id=".$riga['id']."\" title=\"".$riga['descrizione']."\">".$riga['oggetto']."</a><br />".$proprietario[$riga['proprietario']]."</td>";
		    echo "<td>".$status_assistenza[$riga['status_assistenza']]."</td>";
			echo "<td style=\"font-size: 8px;\" title=\"Creato da: ". @$proprietario[$riga['proprietario']]."\">".date("d/m/y H:i",$riga['data_creazione'])."</td>";
			echo "<td  class=\"$new_mail\" style=\"font-size: 8px;\" title=\"Aggiornato da: ". @$proprietario[$riga['operatore']]."\"><a href=\"?action=8&amp;jorel=$jorel&amp;id=".$riga['id']."\" title=\"".$riga['descrizione']."\">".date("d/m/y H:i",$riga['data_aggiornamento'])."</a></td>";
	
			
		
		
		    echo "</tr>";
	}
	

	echo "<tr></tr>";
	
		if($i==1){ $i=0; echo "<tr>"; } else { $i=1; echo "<tr class=\"alternate\">"; }		
			
			
			echo "<td class=\"codice\" colspan=\"4\">Elementi in lista: ".mysql_affected_rows()."</td>";
			
		
				
		    echo "</tr>";
	

	

?>
</table>
