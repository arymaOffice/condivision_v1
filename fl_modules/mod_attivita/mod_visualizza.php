<?php 
require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 

include("../../fl_inc/headers.php"); 
include("../../fl_inc/testata_mobile.php");
?>



<body style=" background: #FFFFFF;">
<div id="container" >


<div id="content_scheda" style="max-width: 600px;">

<br><br>

<br><br>
<h1>Dettaglio Attivit&agrave; |  <a href="mod_inserisci.php?id=<?php echo check($_GET['id']); ?>" class="noprint"><i class="fa fa-edit"></i></a> | <a href="#" class="noprint" onClick=" window.print();"><i class="fa fa-print"></i></a></h1>

<?php include('../mod_basic/action_visualizza.php'); ?>


<?php if(isset($_GET['mode'])) { ?><input type="hidden" name="mode" value="1" /><?php } ?>
<br><br>
<a href="mod_opera.php?pubblica=1&id=<?php echo check($_GET['id']); ?>" class="button noprint" >FATTO</a>

</form>
<?php if(isset($_GET['mode'])) { ?>
</div></body></html>
<?php } ?>