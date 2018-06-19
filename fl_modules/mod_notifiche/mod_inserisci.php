<?php 

require_once('../../fl_core/autentication.php');


include('fl_settings.php'); // Variabili Modulo 

include("../../fl_inc/headers.php");
include("../../fl_inc/testata_mobile.php");
 ?>




<body style=" background: rgb(241, 241, 241) none repeat scroll 0% 0%;">
<div id="container" >



<div id="content_scheda">
<h1>Scheda Notifica</h1>

<form id="scheda" action="../mod_basic/save_data.php" method="post" enctype="multipart/form-data">

<?php include('../mod_basic/action_estrai.php');  ?>
<input type="hidden" name="info" value="1" />
<input type="hidden" name="dir_upfile" value="../../../set/files/" />
</form>



</div></div></body></html>

