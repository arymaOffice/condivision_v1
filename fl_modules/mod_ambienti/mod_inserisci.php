<?php 
require_once('../../fl_core/autentication.php');
$id = check($_GET['id']);


include('fl_settings.php'); // Variabili Modulo 

include("../../fl_inc/headers.php");
include("../../fl_inc/testata_mobile.php");
 ?>




<body style=" background: rgb(241, 241, 241) none repeat scroll 0% 0%;">

<div id="container" >



<div id="content_scheda">


<form id="scheda" action="../mod_basic/action_modifica.php" method="post" enctype="multipart/form-data">
<?php include('../mod_basic/action_estrai.php');  ?>
</form>



</div></div>


</body></html>
