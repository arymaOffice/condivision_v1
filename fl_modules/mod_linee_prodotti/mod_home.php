<div class="filtri" id="filtri"> 


<form method="get" action="" id="fm_filtri">
<h2> Filtri</h2>  
<?php if(isset($_GET['action'])) echo '<input type="hidden" value="'.check($_GET['action']).'" name="action" />'; ?>

<?php 
 
	foreach ($campi as $chiave => $valore) 
	{		
			if(in_array($chiave,$basic_filters)){
			
			

			if((select_type($chiave) == 2 || select_type($chiave) == 19) && $chiave != 'id') {
				echo '<div class="filter_box">';
				echo '  <label>'.$valore.'</label>';
				echo '<select name="'.$chiave.'" class="select"><option value="-1">Tutti</option>';
				foreach($$chiave as $val => $label) { $selected = (isset($_GET[$chiave]) && check(@$_GET[$chiave]) == $val) ? 'selected' : ''; echo '<option '.$selected.' value="'.$val.'">'.$label.'</option>'; }
				echo '</select>';
				echo '</div>';
			} else if( $chiave != 'id') { $cont = (isset($_GET[$chiave])) ? check($_GET[$chiave]) : ''; 
			echo '<div class="filter_box">';
			echo '<label>'.$valore.'</label><input type="text" name="'.$chiave.'" value="'.$cont.'" />'; echo '</div>';}

			
			
			} 
		
	}
	 ?>    


<input type="submit" value="Mostra" class="salva" id="filter_set" />
  
</form>

     



</div>


<?php
// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}

	if(isset($_GET['ordine'])) { if(!is_numeric($_GET['ordine'])){ exit; } else { $ordine = $ordine_mod[$_GET['ordine']]; }}
	
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

.tumb { width: 250px; max-height: 110px; }
.dashboard_div { width:  48%; min-height: 220px; background: #F4F4F4; }
</style>


<br class="clear" />
<br clear="all" />
	  
	<?php 
	
	while ($riga = mysql_fetch_array($risultato)) 
	{
	
	
	$prodotti_count = mk_count('fl_prodotti',' id > 1 AND prodotto_id = '.$riga['id']);


	$foto = (!file_exists($folder.$riga['id'].'.jpg')) ? '<i class="fa fa-camera" style="padding: 20px; font-size: 200%;"></i>' : '<img src="'.$folder.$riga['id'].'.jpg" class="tumb" /> ';
	if(file_exists($folder.$riga['id'].'.jpg')) $foto .= '<br><a  onclick="conferma(\'Eliminare la foto?\');" href="../mod_basic/elimina.php?delFile='.urldecode($folder.$riga['id']).'.jpg">Elimina Foto</a>';
	$load = '
	<form action="mod_opera.php" method="post" class="dropzone"  id="my-awesome-dropzone" enctype="multipart/form-data">
    <input type="hidden" name="id" value="'.$riga['id'].'">
	'.$foto.'
	</form>';	
	
	 
			
	

		    echo '<div class="dashboard_div">
		    <div class="col_sx_content" style=" text-align: center; width: 25%; float: left; padding:0;">';
			echo '<p class="folder_icon">'.$load.'</p>';
			echo '</div>
			<div class="col_dx_content" style="width: 70%; float: right; padding:0;">';	

			echo '<h2 style="margin: 5px 0;"><a title="'.$riga['codice'].'" href="../mod_prodotti/?categoria_id='.$riga['categoria_prodotto'].'&prodotto_id='.$riga['id'].'">'.ucfirst($riga['label']).'</a></h2>';
			echo '<span class="msg blue">'.$categoria_prodotto[$riga['categoria_prodotto']].'</span><span class="msg gray">'.$prodotti_count.' PRODOTTI </span><br>';

			echo "<br clear=\"all\" />
			<a href=\"../mod_prodotti/?categoria_id=".$riga['categoria_prodotto']."&prodotto_id=".$riga['id']."\" title=\"Prodotti\" > <i class=\"fa fa-list\"></i> Mostra prodotti  </a>
			<a href=\"mod_inserisci.php?item_rel=0&amp;action=1&amp;id=".$riga['id']."\" title=\"Modifica\" > <i class=\"fa fa-search\"></i> Modifica Linea  </a>";
			echo "<a href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=".$riga['id']."\" title=\"Elimina\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i> Elimina </a><br>"; 
		
		    echo "</div></div>";

	}

	



?>	


<?php $start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0); ?>
