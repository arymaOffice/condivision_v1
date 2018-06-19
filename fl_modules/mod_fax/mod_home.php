<?php

// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
$_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];
?>

<div class="filtri" id="filtri"> 

<form method="get" action="" id="fm_filtri">
<h2> Filtri</h2>  
<label>Partner: </label>
  

 <select name="proprietario" id="proprietario" class="select2">
      <option value="-1">Tutti</option>
      <?php 
              
		     foreach($proprietario as $valores => $label){ // Recursione Indici di Categoria
			$selected = (@$proprietario_id == $valores) ? " selected=\"selected\"" : "";
			if($valores != 0){ echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; }
			}
		 ?>
    </select> 

<label>  Note:</label>
<input type="text" id="note" name="note" value="<?php if(isset($_GET['note'])){ echo check($_GET['note']);} else {  } ?>" onFocus="this.value='';"  maxlength="200" class="txt_cerca" />

  <div style="width: 50%; margin: 0; float: left;"> 
  <label>  da</label> <input type="text" name="data_da" onFocus="this.value='';" value="<?php  echo $data_da_t;  ?>"  class="calendar" size="8" /> 
   </div>
   <div style="width: 50%; margin: 0; float: left;"> 
  <label>  a</label>  <input type="text" name="data_a" onFocus="this.value='';" value="<?php  echo $data_a_t;  ?>" class="calendar" size="8" /> 
      </div>  
          <input type="submit" value="Cerca" class="button" />

</form>
</div>
<?php
	

	if(isset($_GET['ordine'])) { if(!is_numeric($_GET['ordine'])){ exit; } else { $ordine = $ordine_mod[$_GET['ordine']]; }}
	
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0);
						
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	
	$risultato = mysql_query($query, CONNECT);
	?>
<table class="dati" >
<tr>
  <th><a href="./?ordine=1">Account</a></th>
  <th>Mittente</th>
   <th>Note</th>
  <th>Stato</th>
  <th>Documenti</th>
  <th>Elimina</th>
  <th>Ricevuto/Archiviato</th>
</tr>
<?php 
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"9\">Nessun Record Inserito</td></tr>";		}
	while ($riga = mysql_fetch_array($risultato)) 
	{
			$query_doc = "SELECT * FROM fl_dms WHERE record_id = ".$riga['id']." AND workflow_id = 42 ";
 			$risultato2 = mysql_query($query_doc, CONNECT);	
			$documenti_count = mysql_affected_rows();
			
			$colore = ($riga['identificato'] == 4) ? 'class="orange"' : 'class="green"' ;
		  	
			echo "<tr>"; 				
			echo "<td>".ucfirst($riga['account'])."</td>";		
			echo "<td>".$proprietario[$riga['proprietario']]."</td>"; 
			echo "<td>".$riga['note']."</td>"; 

			echo "<td $colore>".$identificato[$riga['identificato']]."</td>"; 
			echo "<td><a href=\"../mod_dms/?c=MTY=&record_id=".$riga['id']."\" title=\"Gestisci Files\" class=\"button\">Doc: ($documenti_count)</a></td>"; 
			echo "<td><a href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=".$riga['id']."\" title=\"Delete\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i></a></td>"; 
			echo "<td title=\"Aggiornato da: ". @$proprietario[$riga['operatore']]."\">R ".mydatetime($riga['data_ricezione'])."<br />A ".mydatetime($riga['data_creazione'])."</td>";
		
				
		    echo "</tr>";
		
	}

	echo "</table>";


?>
<?php $start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main); ?>
