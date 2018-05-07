<?php 
if($_SESSION['usertype'] > 0)  { 



$didi = $_SESSION['anagrafica']; 
if($_SESSION['anagrafica'] < 2) { echo "<h2 class=\"red\">Modulo non accessibile.</h2> <p>Non esiste un profilo anagrafica te</p>"; exit; } 


} else {

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 

include("../../fl_inc/headers.php");   ?>


<body style=" background: #FFFFFF;">
<div id="print_container">
<h1>Scheda Anagrafica <a href="#" class="noprint" onClick=" window.print();"><i class="fa fa-print"></i></a></h1>

<?php } ?>



<?php include('../mod_basic/action_visualizza.php'); ?>


<?php if(isset($_GET['mode'])) { ?><input type="hidden" name="mode" value="1" /><?php } ?>

</form>
<?php if(isset($_GET['mode'])) { ?>
</div></body></html>
<?php } ?>