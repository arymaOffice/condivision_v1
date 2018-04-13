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

<?php

	if(check($_GET['id'] == 11) && $_SESSION['number'] > 1) { echo "<h2>AUTORIZZAZIONE ALLA MODIFICA NEGATA.</h1><p> Questo contenuto è modificabile solo dall'amministratore di sistema. </p><p>Invia una richiesta di modifica al DPO.</p>"; } else{  include('../mod_basic/action_estrai.php'); }  ?>
</form>


</div>
</div>
</body></html>
