<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
$file_name = (isset($_GET['NAme'])) ? $_GET['NAme'] : array();
$PiD = (isset($_GET['PiD'])) ? base64_decode(check($_GET['PiD'])) : 0;
$workflow_id = (isset($_GET['workflow_id'])) ? check($_GET['workflow_id']) : $_SESSION['workflow_id'];
$record_id = (isset($_GET['record_id'])) ? check($_GET['record_id']) : $_SESSION['record_id'];
include("../../fl_inc/headers.php");

$folder = GRD('fl_dms',$PiD);
if(!isset($folder['label'])) { echo "<p>Folder ".$PiD." non trovato. Crea il folder.</p>"; exit;  }

?>


<h1><?php if(isset($_GET['title'])) { echo check($_GET['title']); } else { echo @$modulo[$_SESSION['workflow_id']].$folder['label'].' '.$proprietario[$proprietario_id]; } ?></h1>

<?php
 


if(!isset($_GET['view']) && $_SESSION['workflow_id'] > 1) { ?>


 <style>
.dsh_panel { text-align: left;} 
.dsh_panel input, .dsh_panel input .calendar { width: 90%; margin: 2px; }
.dsh_panel select { width: 80%; }
 </style> 
 <div class="dsh_panel big">
<h1 onClick="$('.dsh_panel_content').show();">Carica Files <a href="#" class="" style="color: gray"> <i class="fa fa-plus-circle"></i>  </a></h1><span class="open-close"><a href="#"><i class="fa fa-angle-up" aria-hidden="true"></i></a></span>
<div class="dsh_panel_content" style="display: none;">


<script src="<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/dropzone.js"></script> 
<script type="text/javascript">
$( document ).ready(function() {
	
	 $("#my-awesome-dropzone").dropzone({
               addRemoveLinks: true
			  
			  });



});
</script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/dropzone.css">
<style>
.dropzone {
    min-height: 70px;
    border: 1px solid #326636;
    border-radius: 5px;
    background: #FFF none repeat scroll 0% 0%;
    padding: 0px;
    margin: 1%;
    width: 48%;
    float: left;
}
</style>

<?php foreach($file_name as $chiave => $valore){ ?>
<form action="mod_opera.php" method="post" class="dropzone"  id="my-awesome-dropzone<?php echo $chiave; ?>" enctype="multipart/form-data">

<div class="dz-message" data-dz-message><span><h2>Clicca qui e carica <?php echo @$valore;  ?></h2></span></div>
<input type="hidden" name="AiD" value="<?php echo base64_encode($proprietario_id); ?>">
<input type="hidden" name="PiD" value="<?php echo base64_encode($PiD); ?>">
<input type="hidden" name="WiD" value="<?php echo base64_encode($workflow_id); ?>">
<input type="hidden" name="RiD" value="<?php echo base64_encode($record_id); ?>">
<?php if(!isset($_GET['multifile'])) { ?><input type="hidden" name="NAme" value="<?php echo base64_encode($valore); ?>"><?php } ?>

</form>

<?php }  ?>
<br class="clear" />

<p>NOTA BENE: Attendere il caricamento del file fino a che non scompare la barra di caricamento.</p>

</div></div>

<?php }  ?>

<br class="clear" />

<div class="content" style=" text-align: left; padding: 10px;">
<?php
$query = "SELECT $select FROM `$tabella` WHERE resource_type = 1 AND  workflow_id = ".$workflow_id." AND record_id = ".$record_id." ORDER BY $ordine;";
$risultato = mysql_query($query, CONNECT);
echo mysql_error();
if(mysql_affected_rows() < 1) echo "<p>Nessun elemento</p>";
?>

  <?php 
	
	
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
			if(strstr($type,'image')) $icona_tipo = '<img src="apri.php?d='.base64_encode($riga['parent_id']).'&f='.base64_encode($riga['file']).'" class="tumb"  style="max-width: 100%; "  alt="Anteprima"> ';
			$open_link = 'apri.php?d='.base64_encode($riga['parent_id']).'&f='.base64_encode($riga['file']) ;
			$apri_inbrowser = (strstr($type,'image')) ? ' data-fancybox-type="iframe" class="fancybox_view" ' : 'target="_blank"';
	} else {


	}

			
			echo '<div class="dashboard_div"><div class="col_sx_content" style=" text-align: center; width: 25%; float: left; padding:0;">';
			
			echo '<p class="folder_icon">
			<a '.$apri_inbrowser.' href="'.$open_link.'" title="Aggiornato da: '. @$proprietario[$riga['operatore']].' il '.mydatetime($riga['data_aggiornamento']).'">'.$icona_tipo.'</a></p>';
				echo '</div>
			<div class="col_dx_content" style="width: 70%; float: right; padding:0;">';	
			echo '<h2 style="margin: 5px 0;"><a '.$apri_inbrowser.' href="'.$open_link.'" title="Aggiornato da: '. @$proprietario[$riga['operatore']].' il '.mydatetime($riga['data_aggiornamento']).'" style="color: #528354;">'.ucfirst($riga['label']).'</a></h2>';
			if($riga['descrizione'] != '') echo $riga['descrizione'].'<br>';
			

			
			
			if($riga['resource_type'] == 1) { 
			echo '<a href="'.$open_link.'" title="'.ucfirst($riga['label']).'" '.$apri_inbrowser.' > <i class="fa fa-desktop"></i> Apri nel browser </a><br />
			<a href="scarica.php?d='.base64_encode($riga['parent_id']).'&f='.base64_encode($riga['file']).'" title="Download Risorsa"> <i class="fa fa-cloud-download"></i> Download</a><br>';
			

			} else {
			echo '<a href="'.$open_link.'" title="Apri" > <i class="fa fa-folder-open"></i> Apri cartella</a><br/>';
			}
			if($_SESSION['usertype'] == 0 || $_SESSION['usertype'] >= 0 && $_SESSION['number'] == $riga['account_id']) {
			echo "<a href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=".$riga['id']."\" title=\"Elimina\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i> Elimina </a>"; 
			}
			
				
			
				
		    echo "</div></div>";
	}


	

?>

	</div>