<div class="filtri" style="height: auto;"> </div>


<?php
// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}

	if(isset($_GET['ordine'])) { if(!is_numeric($_GET['ordine'])){ exit; } else { $ordine = $ordine_mod[$_GET['ordine']]; }}
	
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main);
						
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	
	$risultato = mysql_query($query, CONNECT);
			
	?>
	    <script src="<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/dropzone.js"></script> 


<link rel="stylesheet" type="text/css" href="<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/dropzone.css">
<style>
.dropzone {
min-height: 0;
border: 1px dashed gray;
border-radius: 0;
background: #FFF none repeat scroll 0% 0%;
padding: 0px 4px;
margin: 0;
width: 100px;
float: left;}

.tumb { width: 150px; }
</style>
 <table class="dati" summary="Dati">
      <tr>
        <th></th>
        <th>Id</th>
         <th>Foto</th>
        <th>Categoria
        </th>
        <th></th>
  		<th></th>

 
      </tr>
	  
	<?php 
	
	$i = 1;
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"7\">Nessun Elemento</td></tr>";		}

	while ($riga = mysql_fetch_array($risultato)) 
	{
	
	$foto = (!file_exists($folder.$riga['id'].'.jpg')) ? '<i class="fa fa-camera" style="float: left;"></i>' : '<img src="'.$folder.$riga['id'].'.jpg" class="tumb" /> ';
		if(file_exists($folder.$riga['id'].'.jpg')) $foto .= '<br><a  onclick="conferma(\'Eliminare la foto?\');" href="../mod_basic/elimina.php?delFile='.urldecode($folder.$riga['id']).'.jpg">Elimina Foto</a>';
	$load = '
	<form action="mod_opera.php" method="post" class="dropzone"  id="my-awesome-dropzone" enctype="multipart/form-data">
    <input type="hidden" name="id" value="'.$riga['id'].'">
	'.$foto.'
	</form>';

	$genitore = ($riga['parent_id'] < 2) ? '' : ucfirst($parent_id[$riga['parent_id']])." &gt;";
	
	 echo "<tr>"; 		
			
			$colore = ($riga['attivo'] == 0) ? "class=\"tab_red\"" : "class=\"tab_green\"";  
			echo "<td $colore><span class=\"Gletter\"></span></td>"; 
			echo "<td>".$riga['id']."  </td>";
			echo "<td> $load </td>";
			echo "<td>".$genitore." <a href=\"../mod_linee_prodotti/?categoria_prodotto=".$riga['id']."\">".ucfirst($riga['label'])."</a></td>";	
		
			echo "<td><a href=\"mod_inserisci.php?item_rel=0&amp;action=1&amp;id=".$riga['id']."\" title=\"Modifica\" > <i class=\"fa fa-search\"></i> </a></td>"; 
			echo "<td><a  href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=".$riga['id']."\" title=\"Elimina\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i></a></td>"; 
		    echo "</tr>";
	}

	

?>	

</table>

<?php $start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0); ?>
