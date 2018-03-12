<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
$tab_id = 91;//Inserimento Calendario
$tabella = $tables[$tab_id];

include("../../fl_inc/headers.php");
include("../../fl_inc/testata_mobile.php");
 ?>




<body style=" background: rgb(241, 241, 241) none repeat scroll 0% 0%;">


<div id="container" >



<div id="content_scheda">
<?php if(isset($_GET['esito'])) { $class = (isset($_GET['success'])) ? 'green' : 'red'; echo '<p style="padding: 13px; width: 56%;"  class="'.$class.'">'.check($_GET['esito']).'</p>'; }  ?>

<form id="scheda" action="../mod_basic/action_modifica.php" method="post" enctype="multipart/form-data">
<?php include('../mod_basic/action_estrai.php');  ?>
</form>

</div></body></html>
</div></div></body></html>