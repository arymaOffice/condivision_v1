<?php
require_once '../../fl_core/autentication.php';
include 'fl_settings.php'; // Variabili Modulo

//controllo che mi mandi un ambiente solo
$ambienti = explode(',', $_GET['ambiente_id']);
if (count($ambienti) > 1) {echo 'non posso gestire più ambienti insieme';exit;}

$ambiente_id = check($_GET['ambiente_id']);

$templates = GQS('fl_tavoli_layout','id,orientamento,nome_layout',' evento_id = "0" AND ambiente_id  = "'.$ambiente_id.'"'); 



include "../../fl_inc/headers.php";

?>

<div style="margin:10%;">
<h2 style="text-align:center;">Riepilogo templates</h2>

<?php foreach($templates as $riga){ 

    //mod_layout_template.php?orientamento='.$orientamento.'&layout=1&evento_id='.$evento_id.'&ambiente_id='.$ambiente_id.'&template_id='.$last_id

    echo " <a href=\"../mod_tavoli/mod_layout_template.php?orientamento=".$riga['orientamento']."&layout=1&evento_id=0&ambiente_id=".$ambiente_id."&template_id=".$riga['id']."\" class=\"button\"  >".$riga['nome_layout']." </a>";


} ?>

</div>