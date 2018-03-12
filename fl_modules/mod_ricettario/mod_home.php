<?php 
// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit; }
$_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];
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
			
			

			if((select_type($chiave) == 2 || select_type($chiave) == 23 || select_type($chiave) == 19 || select_type($chiave) == 9 || select_type($chiave) == 8 || select_type($chiave) == 12) && $chiave != 'id') {
				
								
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
    
<?php
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main);
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	$risultato = mysql_query($query, CONNECT);
?>



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
    <th></th>
    <th>Ricetta/Portata</th>
    <th>Food Cost</th>
    <th>Vendita</th>
    <th></th>
  </tr>
  <?php 
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"5\">Nessun Elemento</td></tr>";		}
	
	while ($riga = mysql_fetch_array($risultato)) 
	{
	
	$attivo = ($riga['attivo'] == 1) ? 'tab_green' : 'tab_red';

	$foto = (!file_exists($folder.$riga['id'].'.jpg')) ? '' : '<img src="'.$folder.$riga['id'].'.jpg" class="tumb" /> ';
	if(file_exists($folder.$riga['id'].'.jpg')) $foto .= '<br><a onclick="return conferma(\'Eliminare la foto?\');" href="../mod_basic/elimina.php?delFile='.urldecode($folder.$riga['id']).'.jpg">Elimina Foto</a>';
	$load = '
	<form action="mod_opera.php" method="post" class="dropzone"  id="my-awesome-dropzone" enctype="multipart/form-data">
    <input type="hidden" name="id" value="'.$riga['id'].'">
	'.$foto.'
	</form>';



	$famiglia_ricettaId = explode(',',$riga['famiglia_ricetta']);
	$famiglia_ricettaLabels = '';
	foreach ($famiglia_ricettaId as $value) {
		$famiglia_ricettaLabels  .= '<span class="msg gray">'.@$famiglia_ricetta[$value].'</span>';
	}


	$query = "SELECT * FROM `fl_ricettario_diba` WHERE `ricetta_id` = ".$riga['id']." ORDER BY id DESC";
	$risultato1 = mysql_query($query, CONNECT);
	$foodCost = 0;
	while ($componenti = mysql_fetch_assoc($risultato1)) 
	{ 
	$materiaprima = GRD('fl_materieprime',$componenti['materiaprima_id']);
  	//$quotazione = GQD('fl_listino_acquisto','valuta, prezzo_unitario, data_validita',' id_materia = '.$materiaprima['id'].' ORDER BY data_validita DESC,data_creazione DESC LIMIT 1');
  	$costo =  ($materiaprima['ultimo_prezzo']*$componenti['quantita'])/$materiaprima['valore_di_conversione'];
  	$foodCost += $costo;
    } 
    $foodSell = numdec($foodCost*$riga['prezzo_vendita'],2);
    $foodCost = numdec($foodCost,2);
    $categoria_msg = ($riga['categoria_ricetta'] > 1) ? "<a href=\"\" class=\"msg orange\">".$categoria_ricetta[$riga['categoria_ricetta']]."</a>" : '';

			echo "<tr>"; 				
			echo "<td class=\"$attivo\"></td>";
			echo "<td> $load </td>";

			echo "<td><h2>".$riga['codice_portata']." ".converti_txt($riga['nome'])."</h2>
			<a href=\"\" class=\"msg green\">".$portata[$riga['portata']]."<a/>$categoria_msg".$famiglia_ricettaLabels." ".mydatetime($riga['data_aggiornamento'])."<br>".strip_tags(converti_txt($riga['note']))."</td>";	
			echo "<td><h2 class=\"c-green\">&euro; $foodCost </h2></td>";	
			echo "<td><h2>&euro; ".$foodSell."</h2></td>";
			echo "<td><a href=\"mod_inserisci.php?id=".$riga['id']."\" title=\"Modifica\" > <i class=\"fa fa-search\"></i> </a>
						<a href=\"mod_inserisci.php?copy_record&amp;id=".$riga['id']."\" title=\"Copia\"><i class=\"fa fa-files-o\"></i></a>

			<a  href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=".$riga['id']."\" title=\"Elimina\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i></a></td>"; 
		    echo "</tr>";
	}

	
	

?>
</table>

<?php $start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0); ?>
