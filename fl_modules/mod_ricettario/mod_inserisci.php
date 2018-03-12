<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 

if(check($_GET['id']) != 1) {
	/*Se esite questa array, la scheda modifica viene suddivisa all'occorenza del campo specificato o si possono aggiungere sotto schede */
	$tab_div_labels['./mod_diba.php?record_id=[*ID*]'] = 'Distinta Base';
}

include("../../fl_inc/headers.php");
if(!isset($_GET['backToMenu'])) include("../../fl_inc/testata_mobile.php");


 ?>


<body>

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
margin: 31px auto 0 auto;
width: 50%;
float: none;}

.tumb { width: 150px; }
</style>


<?php if(isset($_GET['backToMenu'])) { ?><h2><a href="javascript:history.back();">&lt; Indietro </a></h2> <?php } ?>
<div id="container" >

	<?php
	if(check($_GET['id']) != 1) {

	$load = $foto = (!file_exists($folder.$_GET['id'].'.jpg')) ? '' : '<img src="'.$folder.$_GET['id'].'.jpg" class="tumb" /> ';
	
	if(file_exists($folder.$_GET['id'].'.jpg')) $foto .= '<br><a onclick="return conferma(\'Eliminare la foto?\');" href="../mod_basic/elimina.php?delFile='.urldecode($folder.$_GET['id']).'.jpg">Elimina Foto</a>';
	$load = '
	<form action="mod_opera.php" method="post" class="dropzone"  id="my-awesome-dropzone" enctype="multipart/form-data">
    <input type="hidden" name="id" value="'.$_GET['id'].'">
	'.$foto.'
	</form>';
echo $load ;

}
	?>
<div id="content_scheda">



<form id="scheda" action="../mod_basic/action_modifica.php" method="post" enctype="multipart/form-data">
<?php if(isset($_GET['esito'])) { $class = (isset($_GET['success'])) ? 'green' : 'red'; echo '<p class="'.$class.'">'.check($_GET['esito']).'</p>'; }  ?>
<h1>Scheda</h1>
<?php   include('../mod_basic/action_estrai.php');  ?>
<?php if(check($_GET['id']) == 1) { ?><input type="hidden" name="reload" value="../mod_ricettario/mod_inserisci.php?t=<?php echo base64_encode(2); ?>&id=" /><?php } ?>
<?php if(isset($_GET['backToMenu'])) { ?><input type="hidden" name="reload" value="<?php echo $_SERVER['HTTP_REFERER']; ?>&id=" /><?php } ?>
</form>



</div>
</div>
</body></html>
