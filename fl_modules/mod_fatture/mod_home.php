<?php

// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
$_SESSION['POST_BACK_PAGE'] = "https://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];;
?>
<h1>Fatture</h1>   

 
 <div class="filtri" id="filtri">

<h2>Filtri</h2>
<form method="get" action="" id="fm_filtri">
  
    
   <select name="proprietario" id="proprietario" class="select2" onChange="form.submit();">
            <option value="-1">Seleziona affiliato</option>
			<?php 
              
		     foreach($proprietario as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($proprietario_id == $valores) ? " selected=\"selected\"" : "";
		    if($valores > 1)  echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; 
			}
		 ?>
       </select>
       
  
       emessa tra il <br>
       <input type="text" name="data_da" onFocus="this.value='';" value="<?php  echo $data_da_t;  ?>"  class="calendar" size="10"  />
 
       <br>e  il <br><input type="text" name="data_a" onFocus="this.value='';" value="<?php  echo $data_a_t;  ?>" class="calendar" size="10"  /> 

       <input type="submit" value="Mostra" class="button" />

       </form>
   

    
    
      </div>
      

<?php
	

	if(isset($_GET['ordine'])) { if(!is_numeric($_GET['ordine'])){ exit; } else { $ordine = $ordine_mod[$_GET['ordine']]; }}
	
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0);
						
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	
	$risultato = mysql_query($query, CONNECT);
	//echo $query;

 		
	?>

 
     <script type="text/javascript">
function checkAllFields(ref)
{
var chkAll = document.getElementById('checkAll');
var checks = document.getElementsByName('prints[]');
var boxLength = checks.length;
var allChecked = false;
var totalChecked = 0;
	if ( ref == 1 )
	{
		if ( chkAll.checked == true )
		{
			for ( i=0; i < boxLength; i++ )
			checks[i].checked = true;
		}
		else
		{
			for ( i=0; i < boxLength; i++ )
			checks[i].checked = false;
		}
	}
	else
	{
		for ( i=0; i < boxLength; i++ )
		{
			if ( checks[i].checked == true )
			{
			allChecked = true;
			continue;
			}
			else
			{
			allChecked = false;
			break;
			}
		}
		if ( allChecked == true )
		chkAll.checked = true;
		else
		chkAll.checked = false;
	}
	for ( j=0; j < boxLength; j++ )
	{
		if ( checks[j].checked == true )
		totalChecked++;
	}
	countFields(1);
}
  
  
  function countFields(ref)
{
var checks = document.getElementsByName('prints[]');
var boxLength = checks.length;
var totalChecked = 0;
	for ( j=0; j < boxLength; j++ )
	{
		if ( checks[j].checked == true )
		totalChecked++;
	}
	$('#counter').val('Stampa ' + totalChecked + ' documenti');
}

    </script>
<form action="stampa.php" method="post">
  	 <table class="dati" summary="Dati" style=" width: 100%;">
        <tr>
    
       <th scope="col" style="width: 1%;"></th>
       <th scope="col">Numero</th>

       <th scope="col">Cliente</th>
       <th scope="col">Imponibile</th>
       <th scope="col">IVA</th>
       <th scope="col">Totale</th>
        <th scope="col">Commissioni</th>
      <th scope="col">Fattura </th>          
       <th scope="col">
       <input onclick="checkAllFields(1);" id="checkAll"  name="checkAll" type="checkbox" checked  />
       <label for="checkAll" style="margin: 0;"><i class="fa fa-print"></i></label></th>
         </tr>
	<?php 
	
	$i = 1;
	$dare = 0;
	$avere = 0;
	$count = 0;
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"9\">Nessun Record Inserito</td></tr>";		}
	$tot_res = 0;
	$totale_foglio = 0;
	while ($riga = mysql_fetch_array($risultato)) 
	{
		
			$fattura = "<a data-fancybox-type=\"iframe\" class=\"fancybox_view\" href=\"scarica.php?dir=fatture&file=".$riga['filename']."\" title=\"Visualizza\"> <i class=\"fa fa-file-text\"></i></a>"; 
			$stampare = '<input id="'.$riga['filename'].'" onClick="countFields(1);" type="checkbox" name="prints[]" value="'.$riga['filename'].'" checked="checked" /><label for="'.$riga['filename'].'" style="margin: 0;"><i class="fa fa-print"></i></label>';
			$count++;
			
			if($riga['pagato'] == 0){
			$elimina_id = "<a href=\"../mod_basic/elimina.php?gtx=$tab_id&unset=".$riga['id']."\" title=\"Elimina\" onclick=\"conferma_del();\"><i class=\"fa fa-trash-o\"></i></a>";   
			$modifica_id = "<a href=\"?action=1&amp;sezione=".@$riga['sezione']."&amp;id=".$riga['id']."\" title=\"Modifica\"> <i class=\"fa fa-search\"></i> </a>"; 
			$colore = "class=\"gray\"";
			if($riga['totale_fattura'] > 0 ) $conferma = "<a class=\"button green\" href=\"./?action=21&set=1&amp;id=".$riga['id']."\" title=\"Conferma\"> Invia </a>";
			$cred = 'c-gray';
			$cgreen = 'c-gray';
			$colore = "class=\"gray\"";
			} else if($riga['pagato'] == 1){
			$colore = "class=\"green\"";
			$elimina_id = "";  
			$modifica_id = "<a data-fancybox-type=\"iframe\" class=\"fancybox_view\" href=\"mod_visualizza.php?&amp;id=".$riga['id']."\" title=\"Visualizza\"> <i class=\"fa fa-search\"></i> </a>"; 
			$cgreen = 'c-green';
			} else {
			$elimina_id = "";  
			$modifica_id = "<a data-fancybox-type=\"iframe\" class=\"fancybox_view\" href=\"mod_visualizza.php?&amp;id=".$riga['id']."\" title=\"Visualizza\"> <i class=\"fa fa-search\"></i> </a>"; 
			$colore = "class=\"gray\"";
			$cred = 'c-gray';
			$cgreen = 'c-gray';

			}
	
			echo "<tr><td $colore></td>"; 
			echo "<td style=\"\" title=\"Aggiornato da: ". @$proprietario[$riga['operatore']]." aggiornato il ".mydatetime($riga['data_aggiornamento'])."\">
			<span style=\"font-size: 160%;\"><strong>".$riga['numero_documento']."</strong>-".mydate($riga['data_documento'])."</span>
			<br>Periodo dal ".mydate($riga['periodo_competenze_inizio'])." al ".mydate($riga['periodo_competenze_fine'])."</td>";	
			
			
			echo "<td>".$proprietario[$riga['proprietario']]."</td>";	
			echo "<td>&euro; ".$riga['imponibile']."</td>";	
			echo "<td>&euro; ".$riga['iva']."</td>";
			echo "<td>&euro; ".$riga['totale_fattura']."</td>";
			echo "<td>&euro; ".numdec($riga['commissione'],2)."</td>";

			echo "<td style=\"padding: 0;\"> $fattura </td>";
		   echo "<td> $stampare </td>"; 
		    echo "</tr>";
			
			
			
	}
	echo "</table>";
	
echo '<p style="text-align: right; padding: 20px 0;">
<input type="submit" id="counter" value="Stampa '.$count.' documenti " class="button">
</p></form>';

?>	



<?php $start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,1);  ?>
