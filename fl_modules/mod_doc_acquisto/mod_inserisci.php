<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
$id = check($_GET['id']);
if($id != 1 && defined('FOLDER_DOC_ACQUISTO')) { 
 
 
 $documento = GRD($tabella,$id);
 $tab_div_labels['../mod_dms/uploader.php?PiD='.base64_encode(FOLDER_DOC_ACQUISTO).'&workflow_id='.$tab_id.'&multifile=1&title=Documenti di vendita&NAme[]=Documento&record_id=[*ID*]'] = 'Documenti';
  
  }


include("../../fl_inc/headers.php"); include("../../fl_inc/testata_mobile.php");
 ?>



<body style=" background: rgb(241, 241, 241) none repeat scroll 0% 0%;">
<div id="container" >
<div id="content_scheda">

<?php if($id != 1){ ?>

	<div class="info_dati">
	<?php echo '<h1 style="display: inline-block; margin: 0 0 5px;"><span class="msg blue">'.$tipo_doc_acquisto[$documento['tipo_doc_acquisto']].'</span> no. <strong>'.$documento['numero_documento'].' del '.mydate($documento['data_documento']).'</strong></h1>';
	echo '<p>Oggetto: '.check($documento['oggetto_documento']).'</p>'; ?>
	</div>

<?php } else { echo '<h1>Nuovo <span class="msg blue">'.$tipo_doc_acquisto[check($_GET['tipo_doc_acquisto'])].'</span>  </h1>'; } ?>


<?php if(!defined('FOLDER_DOC_ACQUISTO')) echo "Cartella per documenti di acquisto non definita!"; ?>

<form id="scheda" action="../mod_basic/action_modifica.php" method="post" enctype="multipart/form-data">
<?php if(isset($_GET['esito'])) { $class = (isset($_GET['success'])) ? 'green' : 'red'; echo '<p id="esito" class="'.$class.'">'.check($_GET['esito']).'</p>'; }  ?>

<?php include('../mod_basic/action_estrai.php');  ?>

<?php if(check($_GET['id']) == 1) { ?><input type="hidden" name="reload" value="../mod_doc_acquisto/mod_inserisci.php?id=" /><?php } ?>

</form>
</div>
</div></body></html>
