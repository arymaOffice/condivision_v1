<?php 

if(!isset($_GET['record_id'])) $_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];

?>
<style >

#content h2.dms_links {
    margin: 0px 0px 10px 0px;
    font-size: 140%;
	position: absolute;
top: 10px;
right: 17px;
}#content h2.dms_links  a { display: inline-block; margin: 2px 2px 2px 0; }
</style>

<h2 class="dms_links">
<?php if(!isset($_GET['record_id']) ) { ?>
<?php if($folder > 0  || isset($_GET['cerca'])) { ?>
<a href="./"><i class="fa fa-home"></i></a>
<a href="../mod_dms/?c=<?php echo base64_encode($folder_info['parent_id']); ?>" title=""><i class="fa fa-arrow-up"></i> Livello Superiore </a>
<?php  } ?> 
<?php } else { echo '<a href="'.$_SESSION['POST_BACK_PAGE'].'"><i class="fa fa-angle-left"></i> Indietro</a>'; } ?>
</h2>

<p style="margin: 10px 20px; color: gray;"><?php echo @$folder_info['descrizione'];  ?></p>
 <?php  
 
 
 if(1) { // $_SESSION['workflow_id'] < 2 ?>

<?php if(!isset($_GET['record_id']) &&  $_SESSION['usertype'] < 2 && $folder == 3) {  ?>
<div class="col_sx_content">

<form method="get" action="" id="dms_account_sel">

<?php 			$selected = ($proprietario_id == 0) ? ' checked="checked"' : '';
?>
<input id="0" onClick="form.submit();" type="radio" name="proprietario" value="0" <?php echo $selected; ?> />
		
 <?php
			
			 foreach($account_id as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($proprietario_id == $valores) ? ' checked="checked"' : '';
			if($valores != 0){ echo '
			<input id="'.$valores.'" onClick="form.submit();" type="radio" name="proprietario" value="'.$valores.'" '.$selected.' />
			<label for="'.$valores.'" id="label_'.$valores.'"><i class="fa fa-user"></i><br>'.$label.'</label>'; }
			}
		 ?>
      
     <input type="hidden" name="d" value="ZG9jdW1lbnRfZGFzaGJvYXJk">
      <input type="hidden" name="c" value="Mw==">

   
</form>
</div>
<div class="col_dx_content">
<?php } else { ?>
<!--<form method="get" action="" id="dms_account_sel">

<?php 

$selected = ($folder == 2) ? ' checked="checked"' : '';
echo 		
'<input id="'.base64_encode(2).'" onClick="form.submit();" type="radio" name="c" value="'.base64_encode(2).'" '.$selected.' />
<label for="'.base64_encode(2).'"><i class="fa fa-user"></i><br>Folder Personale</label>'; 

$selected = ($folder == 3) ? ' checked="checked"' : '';
echo 		
'<input id="'.base64_encode(3).'" onClick="form.submit();" type="radio" name="c" value="'.base64_encode(3).'" '.$selected.' />
<label for="'.base64_encode(3).'"><i class="fa fa-user"></i><br>Folder Condiviso</label>'; 

$selected = ($folder == 4) ? ' checked="checked"' : '';
echo 		
'<input id="'.base64_encode(4).'" onClick="form.submit();" type="radio" name="c" value="'.base64_encode(4).'" '.$selected.' />
<label for="'.base64_encode(4).'"><i class="fa fa-user"></i><br>Repository</label>'; 

?>
			
<input type="hidden" name="a" value="dashboard">
<input type="hidden" name="d" value="ZG9jdW1lbnRfZGFzaGJvYXJk">

</form>
-->
<?php }  ?>




  <?php } else { echo '<div>'; } ?>

<?php if(@$folder == 3 && $proprietario_id < 2 && $_SESSION['usertype'] < 2) { ?><img src="../../fl_set/lay/intro1.png" alt="Intro"/><?php } else { ?>


<?php if($folder > 0 && $folder != 4) { ?>
<script src="<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/dropzone.js"></script> 
<script type="text/javascript">
$( document ).ready(function() {
	
	 $("#my-awesome-dropzone").dropzone({
               addRemoveLinks: true
			  
			  });



});
</script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/dropzone.css">
<form action="mod_opera.php" method="post" class="dropzone"  id="my-awesome-dropzone" enctype="multipart/form-data">
<input type="hidden" name="AiD" value="<?php echo base64_encode($proprietario_id); ?>">
<input type="hidden" name="PiD" value="<?php echo base64_encode($folder); ?>">
<input type="hidden" name="WiD" value="<?php echo base64_encode($workflow_id); ?>">
<p>
<?php if($proprietario_id > 1) { 
$DMS_AUTO_ALERT_MAIL = (defined('DMS_AUTO_ALERT_MAIL')) ? 'checked="checked"' : ''; ?>

<input type="checkbox" name="notifica" id="notifica" value="1" <?php echo $DMS_AUTO_ALERT_MAIL; ?>  onclick="if(!$(this).is(':checked')) { $('#allega_invio').attr('checked',false); }"  /><label for="notifica"><i class="fa fa-check-square"></i><i class="fa fa-square-o"></i></label>
<input type="text" style="display: inline;" name="descrizione_invio" value="" placeholder="Descrizione caricamento" onclick="$('#notifica').attr('checked',true);" /> Invia notifica all'utente

<?php if(defined('DMS_ALLEGA_FILES') && DMS_ALLEGA_FILES == true) { ?>

<input type="checkbox" name="allega_invio" id="allega_invio" value="1" <?php echo $DMS_AUTO_ALERT_MAIL; ?>  onclick="if($(this).is(':checked')) { $('#notifica').attr('checked',true); }"  />
<label for="allega"><i class="fa fa-check-square"></i><i class="fa fa-square-o"></i></label>
Allega file via mail</p>
<?php }} ?></p>
</form>

<p class="small">Nota: L'invio di una notifica viene eseguito per ogni file caricato. Se inviate più di un file, selezionate l'invio della notifica solo per il primo caricamento, descrivendo la serie di file che state caricando.</p>
<?php } ?>

<?php

if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}

if(isset($_GET['ordine'])) { if(!is_numeric($_GET['ordine'])){ exit; } else { $ordine = $ordine_mod[$_GET['ordine']]; }}
	
$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main);
						
$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	
$risultato = mysql_query($query, CONNECT);
			
?>

<?php if (!isset($_GET['record_id']) && $folder == 3 || !isset($_GET['record_id']) && ($folder > 2 && $_SESSION['usertype'] < 2)) { ?>
<span class="new_folder"><a class="button" href="mod_inserisci.php?n&AiD=<?php echo base64_encode($proprietario_id); ?>&PiD=<?php echo base64_encode($folder); ?>"><i class="fa fa-plus-circle"></i> Crea Cartella</a></span>
<?php } ?>

<br class="clear" />
<br clear="all" />
<?php 
	
	if(mysql_affected_rows() == 0) {}
	
	while ($riga = mysql_fetch_array($risultato)) 
	{
	
	
	if($riga['resource_type'] == 1) { 
			$icona_tipo = '<i class="fa fa-file-text-o"></i>' ;
			$filename = DMS_ROOT.$riga['parent_id'].'/'.$riga['file'];
			if(function_exists('finfo_open')){
			$finfo = @finfo_open(FILEINFO_MIME_TYPE); 
			$type = @finfo_file($finfo,$filename);
			} else {
			$finfo = ''; 
			$type  = ''; 
			}
			if(strstr($type,'image') && !strstr($type,'tiff')) $icona_tipo = '<img src="apri.php?d='.base64_encode($riga['parent_id']).'&f='.base64_encode($riga['file']).'" class="tumb" alt="'.$type.'"> ';
			$open_link = 'apri.php?d='.base64_encode($riga['parent_id']).'&f='.base64_encode($riga['file']) ;
			$apri_inbrowser = (strstr($type,'image') && !strstr($type,'tiff')) ? ' data-fancybox-type="iframe" class="fancybox_view" ' : 'target="_blank"';
	} else {
		   $icona_tipo = '<i class="fa fa-folder"></i>';
	 	   $open_link =  './?c='.base64_encode($riga['id']);
		   $apri_inbrowser = '';
	}
				
			echo '<div class="dashboard_div"><div class="col_sx_content" style=" text-align: center; width: 25%; float: left; padding:0;">';
			
			echo '<p class="folder_icon">
			<a '.$apri_inbrowser.' href="'.$open_link.'" title="Aggiornato da: '. @$proprietario[$riga['operatore']].' il '.mydatetime($riga['data_aggiornamento']).'">'.$icona_tipo.'</a><br><span class="folder_info">'. @$proprietario[$riga['account_id']].'</span></p>';
				echo '</div>
			<div class="col_dx_content" style="width: 70%; float: right; padding:0;">';	
			echo '<h2 style="margin: 5px 0;"><a '.$apri_inbrowser.' href="'.$open_link.'" title="Aggiornato da: '. @$proprietario[$riga['operatore']].' il '.mydatetime($riga['data_aggiornamento']).'">'.ucfirst($riga['label']).'</a></h2>';
			if($riga['descrizione'] != '') echo $riga['descrizione'].'<br>';
			

			
			
			if($riga['resource_type'] == 1) { 
			echo '<a href="'.$open_link.'" title="'.ucfirst($riga['label']).'" '.$apri_inbrowser.' > <i class="fa fa-desktop"></i> Apri nel browser </a><br />
			<a href="scarica.php?d='.base64_encode($riga['parent_id']).'&f='.base64_encode($riga['file']).'" title="Download Risorsa"> <i class="fa fa-cloud-download"></i> Download</a><br>';
			if($type == 'application/zip') { 
			echo '<a href="estrai.php?d='.base64_encode($riga['parent_id']).'&f='.base64_encode($riga['file']).'" > <i class="fa fa-desktop"></i> Estrai </a><br />
			';
			}			

			} else {
			echo '<a href="'.$open_link.'" title="Apri" > <i class="fa fa-folder-open"></i> Apri cartella</a><br/>';
			}
			if(!isset($_GET['record_id']) && $_SESSION['usertype'] == 0 || $_SESSION['usertype'] >= 0 && $_SESSION['number'] == $riga['account_id']) {
			if($riga['id'] > 5) echo "<a href=\"mod_inserisci.php?id=".$riga['id']."\" title=\"Gestisci\" > <i class=\"fa fa-pencil-square-o\"></i> Gestisci proprietà </a><br>
			"; 
			}
			
				
			
				
		    echo "</div></div>";
	}

	
	

?>
<?php $start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0); ?>
<?php }  ?>
</div>