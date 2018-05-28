<?php 

require_once('../../fl_core/autentication.php');

$id = check($_GET['id']);


include('fl_settings.php'); // Variabili Modulo 

include("../../fl_inc/headers.php");
include("../../fl_inc/testata_mobile.php");
 ?>




<body style=" background: rgb(241, 241, 241) none repeat scroll 0% 0%;">
<div id="container" >



<div id="content_scheda">

<body style=" background: #FFFFFF;">
<div id="container" style=" text-align: left;">


<form id="scheda" action="../mod_basic/action_modifica.php" method="post" enctype="multipart/form-data">gfdg
<?php include('../mod_basic/action_estrai.php');  ?>

<input type="hidden" name="dir_upfile" value="ricevute" />
<?php if(isset($_GET['external'])) { ?><input type="hidden" name="external" value="1" /><?php } ?>

</form>

