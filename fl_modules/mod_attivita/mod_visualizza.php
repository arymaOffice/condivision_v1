<?php 
require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
$attivita = GRD($tabella,check($_GET['id']));
include("../../fl_inc/headers.php"); 
include("../../fl_inc/testata_mobile.php");
?>




<body style=" background: rgb(241, 241, 241) none repeat scroll 0% 0%;">


<div id="container" >



<div id="content_scheda">



<br><br>
<h1>Dettaglio Attivit&agrave;   

<?php  if($attivita['proprietario'] < 2 || $attivita['proprietario'] == $_SESSION['number'] || $_SESSION['number'] == 1) { echo ' | <a href="mod_inserisci.php?id='.check($_GET['id']).'" class="noprint"> <i class="fa fa-edit"></i></a> '; } ?>
 
| 
<a href="#" class="noprint" onClick=" window.print();"> <i class="fa fa-print"></i></a> </h1>

<?php include('../mod_basic/action_visualizza.php'); ?>


<?php if(isset($_GET['mode'])) { ?><input type="hidden" name="mode" value="1" /><?php } ?>
<br><br>
<?php  if($attivita['proprietario'] > 1) { echo '<a href="mod_opera.php?pubblica=1&id='.check($_GET['id']).'" class="button noprint" >FATTO</a>'; } ?>

</form>

</div></body></html>

