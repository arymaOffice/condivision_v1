<?php 
require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 

include("../../fl_inc/headers.php"); 
include("../../fl_inc/testata_mobile.php");


$id =  check($_GET['id']);
$query1 = "INSERT INTO `fl_conferme` (`modulo`, `contenuto`, `proprietario`, `conferma`, `data_apertura`) VALUES ('1', '$id', '".$_SESSION['number']."', '0', NOW());";
mysql_query($query1, CONNECT);	



?>



<body style=" background: #FFFFFF;">
<div id="container" >


<div id="content_scheda">

<style>

.messaggio { 
border: 5px solid rgb(233, 233, 233);
padding: 20px;
margin-bottom: 40px;
}
</style>


<h1>Contenuti da leggere  <a href="#" class="noprint" onClick=" window.print();"><i class="fa fa-print"></i></a></h1>
<div style="  font-size: 110%;">
<?php include('../mod_basic/action_visualizza.php'); ?>

<p>Clicca su Ho capito per confermare che hai letto e compreso questo messaggio.</p>
<a href="mod_opera.php?modulo=1&contenuto=<?php echo $id; ?>" class="button green">  Ho Capito!  </a></p>


</div>

</div></div></body></html>
