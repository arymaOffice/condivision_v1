<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 

include("../../fl_inc/headers.php");
include("../../fl_inc/testata_mobile.php");


 ?>


<body style=" background: #FFFFFF;">
<div id="container" >
<div id="content_scheda">

<form id="scheda" action="../mod_basic/action_modifica.php" method="post" enctype="multipart/form-data">
<?php if(isset($_GET['esito'])) { $class = (isset($_GET['success'])) ? 'green' : 'red'; echo '<p class="'.$class.'">'.check($_GET['esito']).'</p>'; }  ?>
<h1>Scheda</h1>
<?php   include('../mod_basic/action_estrai.php');  ?>
</form>


</div>
</div>
</body></html>
