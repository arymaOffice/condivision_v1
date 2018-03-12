<?php 
require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
include("../../fl_inc/headers.php");

 ?>



<div id="container" style=" min-width: 200px;  text-align: left;">

<?php if(isset($_GET['close'])) { ?><script type="text/javascript"> parent.$.fancybox.close();</script><?php exit; }  ?>
<?php if(isset($_GET['esito'])) { $class = (isset($_GET['success'])) ? 'green' : 'red'; echo '<p style="padding: 13px; width: 56%;"  class="'.$class.'">'.check($_GET['esito']).'</p>'; }  ?>

<div class="info_dati">


</div>



<form id="sms" action="mod_opera.php" method="post" enctype="multipart/form-data">
<?php include('../mod_basic/action_estrai.php');  ?>
</form>


<?php
	echo "<script type=\"text/javascript\">	
	$('#from').val('".from."');	
	</script>";
	
	
if(isset($_GET['to'])) {
	echo "<script type=\"text/javascript\">	
	$('#to').val('".phone_format(check($_GET['to']),'39')."');	
	</script>";
}
?>

<?php

if(isset($_GET['sms'])) {

	echo "<script type=\"text/javascript\">	
	$('#body').val('".check($_GET['sms'])."');	
	</script>";
}
?>


</div></body></html>
