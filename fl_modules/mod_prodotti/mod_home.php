<a href="../mod_linee_prodotti/?categoria_prodotto=<?php echo check($_GET['categoria_id']); ?>" title=""><i class="fa fa-arrow-up"></i> Torna alle linee di prodotto </a>


<?php
// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}

	if(isset($_GET['ordine'])) { if(!is_numeric($_GET['ordine'])){ exit; } else { $ordine = $ordine_mod[$_GET['ordine']]; }}
	
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main);
						
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	
	$risultato = mysql_query($query, CONNECT);
			
	?>


	<div class="filtri" id="filtri"> 
<form method="get" action="" id="fm_filtri">
<h2> Filtri</h2>  

<?php if(isset($_GET['action'])) echo '<input type="hidden" value="'.check($_GET['action']).'" name="action" />'; ?>
<?php if(isset($_GET['start'])) echo '<input type="hidden" value="'.check($_GET['start']).'" name="start" />'; ?>

   <?php 
 
	foreach ($campi as $chiave => $valore) 
	{		
			if(in_array($chiave,$basic_filters)){
			
			

			if((select_type($chiave) == 2 || select_type($chiave) == 19 || select_type($chiave) == 9 || select_type($chiave) == 8 || select_type($chiave) == 12) && $chiave != 'id') {
				
								
				echo '<div class="filter_box">';
				echo '  <label>'.$valore.'</label>';
				echo '<select name="'.$chiave.'" class="select"><option value="-1">Non impostato</option>';
				foreach($$chiave as $val => $label) { $selected = (isset($_GET[$chiave]) && check(@$_GET[$chiave]) == $val) ? 'selected' : ''; echo '<option '.$selected.' value="'.$val.'">'.$label.'</option>'; }
				echo '</select>';
				echo '</div>';
			} else if( $chiave != 'id') { $valtxt = (isset($_GET[$chiave])) ? check($_GET[$chiave]) : ''; 
			echo '<div class="filter_box">';
			echo '<label>'.$valore.'</label><input type="text" name="'.$chiave.'" value="'.$valtxt.'" />'; echo '</div>';}

			
			
			} 
		
	}
	 ?>    

    <input type="submit" value="Mostra" class="salva" id="filter_set" />
  
</form>

     
    </div>


<script src="<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/dropzone.js"></script> 
<script type="text/javascript">
$( document ).ready(function() {
Dropzone.options.dropzone = {
    maxFilesize: 100, 
    init: function() {
      this.on("uploadprogress", function(file, progress) { console.log("File progress", progress); });
	  this.on("queuecomplete", function(file) { alert("Added file."); });
  	},
    }
});
</script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/dropzone.css">
<style>
.dropzone {
min-height: 0;
border: 1px dashed gray;
border-radius: 0;
background: #FFF none repeat scroll 0% 0%;
padding: 0px 4px;
margin: 0;
width: 100%;
float: left;}

.tumb { width: 150px; }
</style>


	 <table class="dati" summary="Dati">
      <tr>
        <th></th>
        <th>Id</th>
        <th>Foto</th>
   <?php if(defined('DISEGNO_PRODOTTI')) { ?>  <th>Disegno</th> <?php } ?>
   
 <th>Prodotto/Linea</th>
		<th>Produttore</th>
        <th></th>
  		<th></th>
      </tr>


	  
	<?php 
	
	$i = 1;
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"7\">Nessun Elemento</td></tr>";		}
	
	while ($riga = mysql_fetch_array($risultato)) 
	{
	
	
	$prodotto_da = explode(',',$riga['produttore']);
	$produttori = '';
	foreach ($prodotto_da as $key => $value) {
		$produttori .= '<span class="msg green">'.$produttore[$value].'</span>';
	}

	$foto = (!file_exists($folder.$riga['id'].'.jpg')) ? '<i class="fa fa-camera" style="float: left;"></i>' : '<img src="'.$folder.$riga['id'].'.jpg" class="tumb" /> ';
		if(file_exists($folder.$riga['id'].'.jpg')) $foto .= '<br><a onclick="return conferma(\'Eliminare la foto?\');" href="../mod_basic/elimina.php?delFile='.urldecode($folder.$riga['id']).'.jpg">Elimina Foto</a>';
	$load = '
	<form action="mod_opera.php" method="post" class="dropzone"  id="my-awesome-dropzone" enctype="multipart/form-data">
    <input type="hidden" name="id" value="'.$riga['id'].'">
	'.$foto.'
	</form>';

	if(defined('DISEGNO_PRODOTTI')) { $foto2 = (!file_exists($folder2.$riga['id'].'.jpg')) ? '<i class="fa fa-camera" style="float: left;"></i>' : '<img src="'.$folder2.$riga['id'].'.jpg" class="tumb" /> ';
		if(file_exists($folder2.$riga['id'].'.jpg')) $foto .= '<br><a onclick="return conferma(\'Eliminare la foto?\');" href="../mod_basic/elimina.php?delFile='.urldecode($folder2.$riga['id']).'.jpg">Elimina Disegno</a>';
	$load2 = '
	<form action="mod_opera.php" method="post" class="dropzone"  id="my-awesome-dropzone" enctype="multipart/form-data">
    <input type="hidden" name="id" value="'.$riga['id'].'">
    <input type="hidden" name="folder" value="'.$folder2.'">
	'.$foto2.'
	</form>';}


	 echo "<tr>"; 		
			
			$colore = ($riga['attivo'] == 0) ? "class=\"tab_red\"" : "class=\"tab_green\"";  
			
			echo "<td $colore><span class=\"Gletter\"></span></td>"; 
			echo "<td>".$riga['id']."  </td>";
			echo "<td> $load </td>";
			if(defined('DISEGNO_PRODOTTI')) { echo "<td> $load2 </td>"; }
			echo "<td>
				<a href=\"mod_inserisci.php?item_rel=0&amp;action=1&amp;id=".$riga['id']."\" title=\"Modifica\">".ucfirst($riga['label'])."</a>
				<br><span class=\"msg blue\">".$prodotto_id[$riga['prodotto_id']]."</span></td>";		

			echo "<td>".$produttori."</td>";			

			
			echo "<td><a href=\"mod_inserisci.php?item_rel=0&amp;action=1&amp;id=".$riga['id']."\" title=\"Modifica\"> <i class=\"fa fa-search\"></i> </a></td>"; 
			echo "<td><a  href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=".$riga['id']."\" title=\"Elimina\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i></a></td>"; 
	
			
				
		    echo "</tr>";
	}

	

?>	

</table>

<?php $start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0); ?>
