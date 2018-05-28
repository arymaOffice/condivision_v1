<?php 

$_SESSION['POST_BACK_PAGE'] = $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];

if($folder > 0) { ?>
<h2><a href="../mod_dms/?c=<?php echo base64_encode($folder_info['parent_id']); ?>" title=""><i class="fa fa-arrow-up"></i> Livello Superiore </a> </h2>
<?php  } ?>
<h1><?php echo @$folder_info['label'].' '.$proprietario[$proprietario_id];  ?></h1>
<p style="margin: 10px 20px;
color: gray;"><?php echo @$folder_info['descrizione'];  ?></p>
 <?php  
 
 
 if($_SESSION['workflow_id'] < 2) { ?>

<?php if($_SESSION['usertype'] < 2 && $folder == 3) {  ?>
<div class="col_sx_content">

<form method="get" action="" id="dms_account_sel">

<?php 			$selected = ($proprietario_id == 0) ? ' checked="checked"' : '';
?>
<input id="0" onClick="form.submit();" type="radio" name="proprietario" value="0" <?php echo $selected; ?> />
		
 <?php
			
			 foreach($account_id as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($proprietario_id == $valores) ? ' checked="checked"' : '';
			if($valores >= 0){ echo '
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


<?php if(($folder > 0 && $_SESSION['usertype'] < 2) || ($folder > 0 && $folder < 4 && $_SESSION['usertype'] > 1) ) { ?>
<script src="<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/dropzone.js"></script> 
<script type="text/javascript">
$( document ).ready(function() {
Dropzone.options.dropzone = {
    maxFilesize: 100, 
    init: function() {
      this.on("uploadprogress", function(file, progress) { console.log("File progress", progress); });
	  this.on("queuecomplete", function(file) { alert("Added file."); }
  	},
    }
};
});
</script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/dropzone.css">
<form action="mod_opera.php" method="post" class="dropzone"  id="my-awesome-dropzone" enctype="multipart/form-data">
<input type="hidden" name="AiD" value="<?php echo base64_encode($proprietario_id); ?>">
<input type="hidden" name="PiD" value="<?php echo base64_encode($folder); ?>">
<input type="hidden" name="WiD" value="<?php echo base64_encode($workflow_id); ?>">
</form>
<?php } ?>

<?php

if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}

if(isset($_GET['ordine'])) { if(!is_numeric($_GET['ordine'])){ exit; } else { $ordine = $ordine_mod[$_GET['ordine']]; }}
	
$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main);
						
$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	
$risultato = mysql_query($query, CONNECT);
			
?>

<?php if ($folder == 3 || ($folder > 2 && $_SESSION['usertype'] < 2)) { ?>
<span class="new_folder"><a class="button" href="mod_inserisci.php?n&AiD=<?php echo base64_encode($proprietario_id); ?>&PiD=<?php echo base64_encode($folder); ?>"><i class="fa fa-plus-circle"></i> Crea Cartella</a></span>
<?php } ?>
<table class="dati" summary="Dati">
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
			if(strstr($type,'image')) $icona_tipo = '<img src="apri.php?d='.base64_encode($riga['parent_id']).'&f='.base64_encode($riga['file']).'" class="tumb" alt="Anteprima"> ';
			$open_link = 'apri.php?d='.base64_encode($riga['parent_id']).'&f='.base64_encode($riga['file']) ;
			$apri_inbrowser = (strstr($type,'image')) ? ' data-fancybox-type="iframe" class="fancybox_view" ' : 'target="_blank"';
	} else {
		   $icona_tipo = '<i class="fa fa-folder"></i>';
		   $open_link = './?c='.base64_encode($riga['id']);
		   $apri_inbrowser = '';
	}

			
			echo "<tr>";
			echo '<td class="folder_icon"><a '.$apri_inbrowser.' href="'.$open_link.'" title="Aggiornato da: '. @$proprietario[$riga['operatore']].' il '.mydatetime($riga['data_aggiornamento']).'"><span>'.$icona_tipo.'</span></a></td>';
			echo '<td><a '.$apri_inbrowser.' href="'.$open_link.'" title="Aggiornato da: '. @$proprietario[$riga['operatore']].' il '.mydatetime($riga['data_aggiornamento']).'">'.ucfirst($riga['label']).'</a><br>
			<span class="folder_info">'. @$proprietario[$riga['account_id']].' '.$riga['descrizione'];
			if(trim($riga['tags']) != '') echo '<br><i class="fa fa-tags"></i>'.$riga['tags'].'</span>';
			echo '</td>';		
			
			echo "<td>";
			if($riga['resource_type'] == 1) { 
			echo '<span class="larger">
			<a href="'.$open_link.'" title="'.ucfirst($riga['label']).'" '.$apri_inbrowser.'  > <i class="fa fa-desktop"></i> Apri nel browser </a><br>
			<a href="scarica.php?d='.base64_encode($riga['parent_id']).'&f='.base64_encode($riga['file']).'" title="Download Risorsa" > <i class="fa fa-cloud-download"></i>Download</a></span>';
			if($type == 'application/zip') {echo '<a href="estrai.php?d='.base64_encode($riga['parent_id']).'&f='.base64_encode($riga['file']).'" > <i class="fa fa-desktop"></i> Estrai </a><br />'; }			
			} else {
			echo '<a href="'.$open_link.'" title="Apri" > <i class="fa fa-folder-open"></i> Apri cartella</a>';
			}
			echo "</td>"; 
			
			echo "<td>";			
			if($_SESSION['usertype'] == 0 || $_SESSION['usertype'] >= 0 && $_SESSION['number'] == $riga['account_id']) {
			echo "<a href=\"mod_inserisci.php?id=".$riga['id']."\" title=\"Gestisci\" > <i class=\"fa fa-pencil-square-o\"></i> Gestisci proprietà </a><br>
			<a href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=".$riga['id']."\" title=\"Elimina\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i> Elimina </a>"; 
			}
			echo "</td>"; 
				
			
				
		    echo "</tr>";
	}

	
	

?>
</table>
<?php $start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0); ?>
<?php }  ?>
</div>