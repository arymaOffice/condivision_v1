<?php 
require_once('../../fl_core/autentication.php');

include('fl_settings.php'); // Variabili Modulo 

include("../../fl_inc/headers.php"); 
include("../../fl_inc/testata_mobile.php");


$id =  check($_GET['id']);

?>



<body style=" background: #FFFFFF;">
<div id="container" >


<div id="content_scheda">




<h1>Informazione  <a href="#" class="noprint" onClick=" window.print();"><i class="fa fa-print"></i></a></h1>

<div style="  font-size: 110%;">
<?php include('../mod_basic/action_visualizza.php'); ?>

</div>

</div></div></body></html>
