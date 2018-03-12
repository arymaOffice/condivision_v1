<?php 
require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 

include("../../fl_inc/headers.php"); ?>


<body style=" background: #FFFFFF;">
<div id="print_container">




<h2>Scheda profilo <a href="#" class="noprint" onClick=" window.print();"><i class="fa fa-print"></i></a></h2>

<?php include('../mod_basic/action_visualizza.php'); ?>


<?php if(isset($_GET['mode'])) { ?><input type="hidden" name="mode" value="1" /><?php } ?>

</form>
<?php if(isset($_GET['mode'])) { ?>
</div></body></html>
<?php } ?>