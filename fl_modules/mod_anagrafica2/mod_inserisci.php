<?php 

require_once('../../fl_core/autentication.php');
$loadSelectComuni = 1;
$id = ($_SESSION['usertype'] > 1) ? $_SESSION['anagrafica'] : check($_GET['id']);
if($_SESSION['usertype'] > 1) $force_id = $_SESSION['anagrafica']; 

if($id > 1) {
$profilo = @GRD('fl_anagrafica',@$id); 
//$user_photo = "../../../set/img/photo_cv/".$profilo['id'].".jpg";	
//$user_photo = (@!file_exists($user_photo)) ? '<p style="font-size: 300%; padding: 20px; color: #CACACC"><i class="fa fa-smile-o"></i></p>' : '<p class="user_photo"><span class="user_corda"><img  data-file="'.$user_photo.'" src="'.$user_photo.'" alt="Img" /></span></p>';
}


include('fl_settings.php'); // Variabili Modulo 

include("../../fl_inc/headers.php");
if(!isset($_GET['view'])) include("../../fl_inc/testata_mobile.php");

$tab_div_labels = array('marchio' => 'Profilo',  'tipo_documento' => "Dati Documento", 'forma_giuridica' => "Dati Fiscali", 'tipologia_attivita' => $etichette_anagrafica['tipologia_attivita'], 'telefono' => "Contatti", 'note' => "Note");
 ?>




<body style=" background: rgb(241, 241, 241) none repeat scroll 0% 0%;">


<div id="container" >



<div id="content_scheda">

<div class="info_dati">
<?php if($id > 1) { 
$telefono = phone_format($profilo['telefono'],'39');
echo '<h1><strong>'.$profilo['ragione_sociale'].'</strong> ('.$profilo['nome'].' '.$profilo['cognome'].')</h1>';
if(ALERT_DOCUMENTO_SCADUTO == 1)  echo '<h2>Tipo Delega: <span class="msg gray">'.@$pagamenti_f24[@$profilo['pagamenti_f24']].'</span></h2>';
echo '<p>Telefono: <a href="tel:'.@$telefono.'">'.@$telefono.'</a> mail: <a href="mailto:'.@$profilo['email'].'" >'.@$profilo['email'].'</a></h2>';
} else { echo '<h1>Nuovo '.$tipo_profilo[ $tipo_profilo_id].'</h1>'; }

?>




</div>

<?php if(isset($_GET['esito'])) { $class = (isset($_GET['success'])) ? 'green' : 'red'; echo '<p class="esito '.$class.'">'.check($_GET['esito']).'</p>'; }  ?>

<div id="map-canvas"></div>
<form id="scheda" action="../mod_basic/action_modifica.php" method="post" enctype="multipart/form-data">



<?php include('../mod_basic/action_estrai.php');  ?>
<input type="hidden" name="info" value="1" />

<input type="hidden" name="dir_upfile" value="icone_articoli" />

<!--<input type="hidden" name="goto" value="../mod_account/mod_inserisci.php?id=1" />-->

</form>


</div></div></body></html>
