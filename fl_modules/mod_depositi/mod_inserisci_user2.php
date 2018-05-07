<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 

include("../../fl_inc/headers.php");

 ?>


<body style=" background: #FFFFFF;">

<?php include("../../fl_inc/testata_mobile.php"); ?>
<div style="text-align: left;">
<style>

.form_row, .form_row:hover {
    width: 98%;
}
</style>


<form id="scheda" action="../mod_basic/action_modifica.php" method="post" enctype="multipart/form-data">
<input type="hidden" name="goto" value="mod_user.php?proprietario=<?php echo check($_GET['proprietario']); ?>" />

<?php //include('../mod_basic/action_estrai.php');  ?>
<input type="hidden" name="dir_upfile" value="ricevute" />


</form>

</div></body>
