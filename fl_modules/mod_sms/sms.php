<?php 
require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
$_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];


$autorefresh = 0;

$jquery = 1;
$fancybox = 1;


include("../../fl_inc/headers.php");
?>
<form id="sms" action="mod_opera.php" method="post" enctype="multipart/form-data">

<div id="results"></div>
<div style="padding: 10px; text-align: right;">
<div class="ui-widget-content" style="padding: 20px 10px;"><h1>Invia SMS</h1>
<div class="form_row" id="box_from">
<p class="input_text"><label for="from">Da</label><input  disabled type="text" name="from" id="from"  value=""  /></p>
</div><div class="form_row" id="box_to"><div class="form_row" id="box_to"><p class="input_text"><label for="to">Destinatario  </label>
<input   class="" type="text" name="to" id="to"  value=""  /> </p>
</div>
</div>
<div class="form_row" id="box_body"><div class="form_row" id="box_body"><p class="input_text"><label for="body">Messaggio  </label>
<textarea   class="" type="text" name="body" id="body"></textarea> </p>
</div></div></div></div><p class="savetabs"><input type="submit"  value="Invia" class="button salva" /></p></form>

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


</body>
</html>