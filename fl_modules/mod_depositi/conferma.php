<?php 
require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 

include("../../fl_inc/headers.php"); ?>


<body style=" background: #FFFFFF;">
<div id="print_container">


<form method="get" action="mod_opera.php">
<input type="hidden" name="set" value="1">
<input type="hidden" name="id" value="<?php echo check($_GET['id']); ?>">

<input type="text" value="<?php echo date('d/m/Y'); ?>" class="calendar" name="data_valuta" />
<a href="#" onclick="form.submit();" class="button">Conferma</a>
</form>
</div></body></html>
