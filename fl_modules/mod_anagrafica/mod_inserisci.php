<?php 

require_once('../../fl_core/autentication.php');

$id = check($_GET['id']);
$anagrafica_dati = get_anagrafica($id ); 


include('fl_settings.php'); // Variabili Modulo 

include("../../fl_inc/headers.php");
include("../../fl_inc/testata_mobile.php");
 ?>





<div id="container" style=" text-align: left;">

<div class="info_dati">
<p>
<?php 
if($id > 1) echo 'User: <strong>'.@$anagrafica_dati['user'].'</strong> - Nickname: <strong><span id="userbox">'.@$anagrafica_dati['nominativo'].'</span></strong> - <a href="#" class="delete" data-uid="'.$id.'" title="Elimina cliente e disattiva tutti gli accessi">[Disattiva Cliente]</a>';

?></p>
</div>


<?php if(isset($_GET['esito'])) { $class = (isset($_GET['success'])) ? 'green' : 'red'; echo '<p class="esito '.$class.'">'.check($_GET['esito']).'</p>'; }  ?>

<div id="map-canvas"></div>
<form id="scheda" action="../mod_basic/action_modifica.php" method="post" enctype="multipart/form-data">



<?php include('../mod_basic/action_estrai.php');  ?>

<input type="hidden" name="info" value="1" />
<input type="hidden" name="dir_upfile" value="icone_articoli" />


</form>



</div></body></html>
