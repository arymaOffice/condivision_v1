<?php if($proprietario_id < 2 || isset($_GET['intro'])) {  ?>
<h2>Seleziona un account</h2>
<form method="get" action="" id="dms_account_sel">

<?php 			$selected = ($proprietario_id == 0) ? ' checked="checked"' : '';
?>
<input id="0" onClick="form.submit();" type="radio" name="cmy" value="0" <?php echo $selected; ?> />
		
 <?php
			
			 foreach($anagrafica as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($proprietario_id == $valores) ? ' checked="checked"' : '';
			if($valores > 1){ echo '
			<input id="'.$valores.'" onClick="form.submit();" type="radio" name="cmy" value="'.base64_encode($valores).'" '.$selected.' />
			<label for="'.$valores.'"><i class="fa fa-user"></i><br>'.$label.'</label>'; }
			}
		 ?>
      
     <input type="hidden" name="a" value="amministrazione">
     <input type="hidden" name="d" value="ZG9jdW1lbnRfZGFzaGJvYXJk">
   
</form>
<?php } else { 

if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
$_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];
?>



<div id="filtri" class="filtri"> 

<form method="get" action="" id="fm_filtri">
<h2 class="filter_header"> Filtri</h2>  
  
    <label> creato tra il</label>
    <input type="text" name="data_da" onFocus="this.value='';" value="<?php  echo $data_da_t;  ?>" class="calendar" size="10" />
    <label> e il</label>
    <input type="text" name="data_a" onFocus="this.value='';" value="<?php  echo $data_a_t;  ?>" class="calendar" size="10" />
    <input type="submit" value="Mostra" class="salva" id="filter_set" />
  
</form>

     
    </div>
<?php
	

	if(isset($_GET['ordine'])) { if(!is_numeric($_GET['ordine'])){ exit; } else { $ordine = $ordine_mod[$_GET['ordine']]; }}
	
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,1);
						
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	
	$risultato = mysql_query($query, CONNECT);
	

 		
	?>
<table class="dati" summary="Dati" style=" width: 100%;">
<tr>
  <th style="width: 1%;"></th>
 <th><a href="./?ordine=1">Nominativo/Azienda</a></th>
  <th>Profilo</th>
  <th>Riferimenti</th>
   <th></th> <th></th>
  <th></th>
  <th  class="hideMobile"></th>
</tr>
<?php 
	
	$i = 1;
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"9\">Nessun Record Inserito</td></tr>";		}
	$tot_res = 0;
	$deleted = 0;
	$incomplete = 0;
	
	while ($riga = mysql_fetch_array($risultato)) 
	{					
			
			$sedis =  getMultiItems($riga['sedi_id'],$sedi_id);
	
			
			$elimina = "<a href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=".$riga['id']."\" title=\"Elimina\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i></a>";
			
			
				
			echo "<tr>"; 
			$nominativo = ucfirst($riga['nome']).' '.ucfirst($riga['cognome']);		
			echo "<td ><span class=\"Gletter\"></span></td>"; 
			echo "<td><span class=\"color\">$nominativo</span><br> $sedis ".$anagrafica_id[$riga['anagrafica_id']]."</td>";
			echo "<td>".ucfirst($profilo_funzione[$riga['profilo_funzione']])." </td>";
			echo "<td><i class=\"fa fa-envelope-o\"></i> <a href=\"mailto:".$riga['emails']."\">".$riga['emails']."</a></td>
			<td><i class=\"fa fa-phone\"> </i> ".$riga['telefono']."</td><td> <i class=\"fa fa-mobile\"></i> ".$riga['cellulare']."</td>"; 
			echo "<td  class=\"strumenti\">";
			echo "<a href=\"mod_inserisci.php?id=".$riga['id']."\" title=\"Gestione Cliente \"> <i class=\"fa fa-search\"></i> </a>
			$elimina </td>";
			echo "<td  class=\"hideMobile\" style=\"font-size: smaller;\" title=\"Aggiornato da: ". @$proprietario[$riga['operatore']]."\">Agg. ".$riga['data_aggiornamento']."<br />Creaz. ".$riga['data_creazione']."</td>";
		
		    echo "</tr>"; 
			}
		

	echo "</table>";
	

?>
<?php } ?>