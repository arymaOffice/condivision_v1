<?php 
require_once('../../../../fl_core/autentication.php');
include('../../../../fl_core/category/cms.php');
require_once('../../../../fl_core/class/ARY_dataInterface.class.php');
if($_SESSION['anagrafica'] < 2) { 
echo "<h2 class=\"red\">Modulo non accessibile.</h2> <p>Non esiste un profilo anagrafica te</p>"; exit; 
} 

$data_set = new ARY_dataInterface();
$secretKey = 'f34KlhYt67H4sGKQad3654FIlLY';
$pin = 6666;

$time = time();
$prodotto_id_id = check($_SESSION['product']); 
$prodotto = $data_set->get_record('fl_sottoprodotti',$prodotto_id_id);
$linea = $data_set->get_record('fl_prodotti',$prodotto['prodotto_id']);
$commissione_netto = ($prodotto['profilo'.$_SESSION['profilo_commissione']]*$prodotto['ricavo_netto'])/100;
$commissione_genitore_netto = ($prodotto['profilo_agente']*$prodotto['ricavo_netto'])/100;
$ricavo_gestore = $prodotto['ricavo_netto']-$commissione_netto-$commissione_genitore_netto;
$aliquota_iva =  $prodotto['aliquota_iva'];
$genitore = $_SESSION['profilo_genitore'];

$encryptedData = sha1('u='.$_SESSION['number'].'&p='.$pin.$secretKey); 

$credito_utente = get_saldo($_SESSION['number'])+get_fido($_SESSION['anagrafica']);
if($credito_utente < ($prodotto['amount']/100)) { echo '<h2 class="c-red">ERRORE G009, Credito insufficente. ('.numdec($credito_utente,2).')</h2><p><a class="big-button gray" href="index.php">TORNA INDIETRO</a></p>'; exit; }

$balanceurl = 'https://vpn.goservizi.it/ws/balance/?checkSum='.$encryptedData;
$fields	= array('checkSum'=>$encryptedData,'user'=>$_SESSION['number']);
$balance = get_data($balanceurl,$fields);
if($balance->RcDs <= 0) { echo '<h2 class="c-red">ERRORE G001, Prego riprovare pi&ugrave; tardi. ('.$balance->RcDs.')</h2><p><a class="big-button gray" href="index.php">TORNA INDIETRO</a></p>'; exit; }

$url = 'https://vpn.goservizi.it/ws/pin/?checkSum='.$encryptedData;
$fields	= array('ricavo_gestore'=>$ricavo_gestore,'genitore'=>$genitore,'aliquota_iva'=>$aliquota_iva,'commissione_genitore_netto'=>$commissione_genitore_netto,'commissione_netto'=>$commissione_netto,'prodotto_id'=>$prodotto_id_id ,'checkSum'=>$encryptedData,'user'=>$_SESSION['number'],'srv'=>$linea['codice'],'amount'=>$prodotto['amount'],'offercode'=>$prodotto['offercode']);



$data = get_data($url,$fields);



$results = 'data='.date('d/m/Y H:i',$time).'&srv='.$linea['codice'].'&offercode='.$prodotto['offercode'];
foreach($data as $chiave => $valore) {  $results .='&'.$chiave.'='.$valore; }

if($data->Rc == '000') {
unset($_SESSION['product']);
echo '<h2>CONFERMARE OPERAZIONE?</h2>';
action_record('RICARICA PIN','TRANSAZIONI',$data->OrigId,base64_encode(json_encode($data)));
$_SESSION['OrigId'] = $data->OrigId;
$_SESSION['Results'] = $results;
echo '<a id="confirm" class="button green button_height" href="./conferma.php?'.$results.'">Conferma</a>';
//echo '<h1>'.$data->payment.'</h1>';
echo '<div class="action"><p><a id="annulla" class="button red button_height" href="./conferma.php?annulla=1&'.$_SESSION['Results'].'">Annulla</a></p></div>';

} else { 

echo '<h2 class="c-red">'.$data->Rc." - ".$data->RcDs.'</h2>';
echo '<div class="action">
<p><a class="button orange button_height" href="index.php">ESEGUI ALTRA OPERAZIONE</a></p>

</div>';

}


mysql_close(CONNECT);

?>
