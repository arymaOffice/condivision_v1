<?php 

require_once('../../fl_core/autentication.php');

$id = check($_GET['id']);


include('fl_settings.php'); // Variabili Modulo 
$id = check($_GET['id']);
if($id != 1){
$persona = @GRD('fl_persone',$id); 
$profilo_funzione_d = @GRD('fl_profili_funzione',$persona['profilo_funzione']); 
$processo_d = @GRD('fl_processi',$profilo_funzione_d['processo_id']); 
}
include("../../fl_inc/headers.php");
include("../../fl_inc/testata_mobile.php");
 ?>




<body style=" background: rgb(241, 241, 241) none repeat scroll 0% 0%;">
<div id="container" >



<div id="content_scheda">
<?php 
if($id != 1){
$telefono = phone_format($persona['telefono'],'39');
$cellulare = phone_format($persona['cellulare'],'39');
echo '<div class="info_dati"><h1><strong>'.$persona['nome'].' '.$persona['cognome'].'</strong></h1>';
echo '<h3><span class="msg blue">'.@$processo_d['processo'].'</span>'.@$profilo_funzione_d['funzione'].'</h3>';
echo '<p>Tel: <a href="tel:'.@$cellulare.'">'.@$cellulare.'</a><a href="tel:'.@$telefono.'">'.@$telefono.'</a> mail: <a href="mailto:'.@$persona['emails'].'">'.@$persona['emails'].'</a></p></div>';
} ?>



<?php if(isset($_GET['esito'])) { $class = (isset($_GET['success'])) ? 'green' : 'red'; echo '<p class="esito '.$class.'">'.check($_GET['esito']).'</p>'; }  ?>

<div id="map-canvas"></div>
<form id="scheda" action="../mod_basic/action_modifica.php" method="post" enctype="multipart/form-data">



<?php include('../mod_basic/action_estrai.php');  ?>

<input type="hidden" name="dir_upfile" value="icone_articoli" />


</form>



</div></body></html>
