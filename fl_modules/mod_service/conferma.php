<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
$noback = 1;
include("../../fl_inc/headers.php");
include("../../fl_inc/testata_mobile.php");
?>





<div id="main_service" style=" margin: 55px auto; max-width: 800px;">

<div id="left_box" style="float: none; margin: 0 auto;  padding: 20px;">
<h1>ESITO OPERAZIONE</h1>

<div id="res">
<?php
$results = (!isset($_GET['annulla'])) ? 'confirm=1' : 'confirm=0';
$secretKey = 'f34KlhYt67H4sGKQad3654FIlLY';
$pin = 6666;
$encryptedData = sha1('u='.$_SESSION['number'].'&p='.$pin.$secretKey); 
$OrigId = check(@$_GET['OrigId']);
$Trid = (isset($_GET['Trid'])) ? check($_GET['Trid']) : 0;


$url = 'https://vpn.goservizi.it/ws/conferma/?'.$results.'&checkSum='.$encryptedData;
$fields	= array('checkSum'=>$encryptedData,'user'=>$_SESSION['number'],'OrigId'=>$OrigId,'Time'=>check(@$_GET['Time']),'offercode'=>check(@$_GET['offercode']),'Trid'=>$Trid);

$data = get_data($url,$fields);

foreach($_GET as $chiave => $valore) {  $results .='&'.$chiave.'='.check($valore); }


if($data->Rc == '000') {
	
if(!isset($_GET['annulla'])){ 

echo '<h1 class="green button_height" style="padding: 50px;">Operazione eseguita con successo.<h1>';
action_record('CONFERMA OP','TRANSAZIONI',$OrigId,base64_encode(json_encode($data)));
} else { 
echo '<h1 class="red button_height" style="padding: 50px;">Operazione annullata.<h1>';
action_record('ANULLA OP','TRANSAZIONI',$OrigId,base64_encode(json_encode($data)));
}

if(!isset($_GET['annulla'])) echo '<a data-fancybox-type="iframe" class="fancybox_view button button_height" href="../mod_ricevute/?'.$results.'"><i class="fa fa-print" style="font-size: larger; color: white;"></i> Stampa Scontrino</a>';



} else {
	
echo '<h1 class="red button_height">'.$data->RcDs.' '.$data->Rc.'</h1>';
@mail('michelefazio@aryma.it',"Errore client: ".$data->Rc,$data);

}


echo '<div class="action"><p><a class="orange big-button" href="index.php">ESEGUI ALTRA OPERAZIONE</a></p></div>';

unset($_SESSION['OrigId']);
unset($_SESSION['Results']);
unset($_SESSION['product_label']);

?>

</div>



</div>

</div>


</body>
</html><?php @mysql_close(CONNECT); ?>