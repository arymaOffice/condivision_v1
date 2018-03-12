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


       


<div class="filtri" id="filtri"> </div>


<?php
// Controlli di Sicurezza


	if(isset($_GET['ordine'])) { if(!is_numeric($_GET['ordine'])){ exit; } else { $ordine = $ordine_mod[$_GET['ordine']]; }}
	
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main);
						
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	echo mysql_error();
	$risultato = mysql_query($query, CONNECT);
			
	?>
	 <table class="dati" summary="Dati">
      <tr>
        <th></th>
        <th>Tipo</th>
        <th>Persona/Processo</th>
        <th>Descrizione</th>
        <th>Target</th>
        <th>Fornitore Informazioni</th>
        <th>Scadenza</th>
        <th></th>
  		<th></th>

 
      </tr>
	  
	<?php 
	
	
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"7\">Nessun Elemento</td></tr>";		}
	
	while ($riga = mysql_fetch_array($risultato)) 
	{
	
	
	
	echo "<tr>"; 	
			
			echo "<td class=\"tab_green\">".@$riga['numero_obiettivo']."</td>";
			echo "<td>".@$tipo_obiettivo[$riga['tipo_obiettivo']]."</td>";
			echo "<td><strong>".ucfirst($persona_id[$riga['persona_id']])."</strong><br>".ucfirst($processo_id[$riga['processo_id']])." - ".$anagrafica_id[$riga['anagrafica_id']]."</td>";
			echo "<td>".ucfirst(strip_tags(converti_txt($riga['descrizione'])))." </td>";
			echo "<td>&euro; ".@numdec($riga['valore_target'],2)."</td>";
			echo "<td>".@$fornitore_informazioni[$riga['fornitore_informazioni']]."</td>";
			echo "<td><span title=\"Ultima Revisione: ".mydate($riga['data_aggiornamento'])."\">".mydate($riga['scadenza_obiettivo'])."</td>";
			echo "<td><a href=\"mod_inserisci.php?item_rel=0&amp;action=1&amp;id=".$riga['id']."\" title=\"Modifica\" > <i class=\"fa fa-search\"></i> </a>
			<a data-fancybox-type=\"iframe\" class=\"fancybox_view\" href=\"mod_visualizza.php?id=".$riga['id']."\" title=\"Scheda di stampa\"> <i class=\"fa fa-print\"></i> </a></td>"; 
			echo "<td><a  href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=".$riga['id']."\" title=\"Elimina\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i></a></td>"; 
		    echo "</tr>";
	}

	

?>	

</table>

<?php $start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0); ?><?php } ?>
