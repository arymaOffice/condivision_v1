<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 

include("../../fl_inc/headers.php");
include("../../fl_inc/testata_mobile.php");

 ?>


<body>
<div id="container" >

<div id="content_scheda">


<?php if(isset($_GET['esito'])) { $class = (isset($_GET['success'])) ? 'green' : 'red'; echo '<p class="'.$class.'">'.check($_GET['esito']).'</p>'; }  ?>


<form id="scheda" action="../mod_basic/action_modifica.php" method="post" enctype="multipart/form-data">
<h1>Scheda Modifica</h1>

<?php include('../mod_basic/action_estrai.php');  ?>

<input type="hidden" name="dir_upfile" value="icone_articoli" />
<?php if(isset($_GET['external'])) { ?><input type="hidden" name="external" value="1" /><?php } ?>

</form>

</div></body></html>
