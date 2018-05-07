<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 

include("../../fl_inc/headers.php");
include("../../fl_inc/testata_mobile.php");


?>


<body style="background: #FFFFFF;">


<div id="container" >


<div id="content_scheda">

<h1>Aggiorna i dettagli per questo FAX</h1>
<p class="msg red">DA ADESSO PUOI ASSOCIARE IL FAX AD UN ACCOUNT E AGGIUGERE DELLE NOTE</p>
<form id="scheda" action="../mod_basic/action_modifica.php" method="post" enctype="multipart/form-data">
<?php include('../mod_basic/action_estrai.php');  ?>
</form>

</div>
</div></body></html>
