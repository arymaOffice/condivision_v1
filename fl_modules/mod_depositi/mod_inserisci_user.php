<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 

include("../../fl_inc/headers.php");

 ?>


<body style=" background: #FFFFFF;">

<div id="up_menu" class="noprint">
<a href="javascript:history.back();"> <i class="fa fa-angle-left"></i> INDIETRO </a></div>

<div style="text-align: left;">
<style>
.salva { width: 100%; }
.form_row, .form_row:hover {
    width: 98%;
}
</style>

<?php if(isset($_GET['action'])) { include($pagine[$_GET['action']]); } else { ?>
<form id="scheda" action="../mod_basic/action_modifica.php" method="post" enctype="multipart/form-data">
<input type="hidden" name="goto" value="../mod_depositi/mod_estrattoconto.php?proprietario=<?php echo check($_GET['proprietario']); ?>" />

<?php include('../mod_basic/action_estrai.php');  ?>
<input type="hidden" name="dir_upfile" value="ricevute" />


</form>
<?php } ?>
</div></body>
