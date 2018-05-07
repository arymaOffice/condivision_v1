<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
$noback = 1;
$time = time();
$_SESSION['checksum'] = sha1($_SESSION['number'].'-'.$_SERVER['REMOTE_ADDR'].$time);
if(isset($_SESSION['product'])){
$prodotto_id_id = check($_SESSION['product']); 
$riga = $data_set->get_record('fl_sottoprodotti',$prodotto_id_id);
$line = $data_set->get_record('fl_prodotti',$riga['prodotto_id']);
$_SESSION['product_label'] = $riga['label']." - &euro; ".numdec($riga['valore_facciale'],2);
}
include("../../fl_inc/headers.php");
include("../../fl_inc/testata_mobile.php");
?>





<div id="container" style=" margin: 55px auto; max-width: 800px;">

<?php 	if($_SESSION['anagrafica'] < 2 || !isset($_SESSION['profilo_commissione'])) { echo "<h2 class=\"red\">Modulo non accessibile.</h2> <p>Non esiste un profilo anagrafica te</p>"; exit; } ?>
<div id="left_box" style="float: none; margin: 0 auto; padding-top: 10px; ">

<?php if(isset($_SESSION['product'])){ ?>
<h1 class="product_line" style="margin: 20px 20px 70px 20px; padding: 20px;">
<?php 	echo (file_exists('../../fl_set/lay/prodotti/'.$riga['prodotto_id'].'.jpg')) ?  '<img src="../../fl_set/lay/prodotti/'.$riga['prodotto_id'].'.jpg" alt="" />' : $riga['label']; ?>
	
	<br><br><?php echo $riga['label']; ?> (<?php echo numdec($riga['valore_facciale'],2); ?> &euro;)<br>
</h1>

<div id="res">
<p id="loading" style=" display: none; clear: both;"> Elaborazione in corso...<br><img src="<?php echo ROOT; ?>condivision/fl_set/lay/preloader_oriz.png" alt="Caricamento in corso"/></p>
<a class="button green loadres button_height" href="#" style="width: auto; display: block; margin: 20px" id="<?php echo $riga['id']; ?>">Conferma</a>
</div>

<div class="action"><p>
<a id="indietro" class="button red button_height" href="index.php">TORNA INDIETRO</a>
</p></div>

</div>

<br>
<?php } else { ?>
<h2>Hai gia avviato un operazione o devi riselezionare il prodotto.</h2>

<?php 
if(isset($_SESSION['OrigId'])) {
	 echo '<div id="main_service" style=" margin: 55px auto; max-width: 800px;">

<div id="left_box" style="float: none; margin: 0 auto;  padding: 20px;"><h2>OPERAZIONE IN SOSPESO</h2><p>'.$_SESSION['product_label'].'</p>
	 <p><a id="confirm" class="button green button_height " href="./conferma.php?'.$_SESSION['Results'].'">Conferma operazione</a>
    <a id="annulla" class="button red button_height" href="./conferma.php?annulla=1&'.$_SESSION['Results'].'">Annulla operazione</a></p></div></div>';

} else { echo '<p><a class="big-button orange button_height" href="index.php">ESEGUI ALTRA OPERAZIONE</a></p>'; }

} ?>

</div>

</div>


</body>
</html><?php @mysql_close(CONNECT); ?>