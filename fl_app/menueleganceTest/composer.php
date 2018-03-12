<?php 
//if($_SERVER['HTTP_HOST'] != 'menutranslate.condivision.biz') header("Location: http://menutranslate.condivision.biz/'.$folderContent.'/?".$_SERVER['QUERY_STRING']);
session_start();
header('Content-Type: text/html; charset=UTF-8');
ini_set('default_charset', 'utf-8');



require_once("../../fl_core/core.php"); 

$synapsy_id = check($_GET['synapsy_id']);

require('../../fl_core/class/ARY_dataInterface.class.php'); //Classe di gestione dei dati 
$data_set = new ARY_dataInterface();

$finger_food =  $data_set->get_items_key("finger_food");



?>

<!DOCTYPE html>
<html lang="it">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Digital Menù Composer 1.0</title>
<meta name="description" content="App aziendale con traduzione in tempo reale dei contenuti">

<!-- Smarthphone -->
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="format-detection" content="telephone=no">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<link rel="icon" href="<?php echo ROOT.$cp_admin.$cp_set; ?>css/lay/a.ico" type="image/x-icon" /> 
<link rel="shortcut icon" href="<?php echo ROOT.$cp_admin.$cp_set; ?>css/lay/a.ico" type="image/x-icon" />
<link rel="apple-touch-icon" href="<?php echo ROOT.$cp_admin.$cp_set; ?>lay/a.png" />

<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Arimo:400,700,400italic">
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT.$cp_admin.$cp_set; ?>css/condivision4-jquery.css" media="screen, projection, tv" />
<link rel="stylesheet" type="text/css" href="<?php echo ROOT.$cp_admin.$cp_set; ?>css/condivision4.css" media="screen, projection, tv" />
<link rel="stylesheet" type="text/css" href="<?php echo ROOT.$cp_admin.$cp_set; ?>css/fl_print.css" media="print" />
<link rel="stylesheet" type="text/css" href="<?php echo ROOT.$cp_admin.'fl_config/'.HTTP_HOST.'/'; ?>css/custom.css" media="screen, projection, tv" />


<script src="<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/jquery-1.8.3.js" type="text/javascript"></script>
<script src="<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/jquery-ui.js" type="text/javascript"></script>


<!-- Add fancyBox main JS and CSS files -->


<script>


$(document).ready(function(){

<?php if(isset($_GET['close'])) echo 'parent.$.fancybox.close();'; ?>

/*PRELOAD CONTENT */
$("#preloader").hide();
$("#app_container").show(); 


});


</script>

<style type="text/css">
	
input, .button {
width: 100% !important;
margin: 5px 0px 5px 5px;
padding: 8px 8px;
}

.button { margin: 10px 0 20px 5px !important; }
</style>

</head>
<body>

<div id="preloader"><img src="<?php echo ROOT.$cp_admin; ?>fl_set/lay/preloader.png" /><a href="#" onClick="location.reload();" style="font-size: smaller; display: block; text-align: center;">Aggiornamento del Menù</a></div>

<div id="app_container">

<form action="mod_opera.php" method="post" style="width: auto;
max-width: 400px;
margin: 0 auto;
" >

<input type="hidden" name="synapsy_id" value="<?php echo $synapsy_id; ?>">

<h1>Opzioni portata</h1>
<?php
	foreach($finger_food as $key => $value) { if($key > 1) echo '<input   type="checkbox" id="'.$value.'" name="opzione'.$key.'" value="'.$value.'"><label style="width: 100%;" for="'.$value.'">'.$value.'</label><br>
	<input  type="text" id="'.$value.'" name="note_'.$key.'" id="note_allestimento_torta'.$key.'" placeholder="Descrizione">'; }
?>


<input type="submit" value="Aggiungi" class="button" style="">
</form>


<div id="scroll-up" class="hideMobile"><i class="fa fa-chevron-circle-up"></i></div>

</div>

</body></html>

