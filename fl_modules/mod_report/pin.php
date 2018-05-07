<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 

$time = time();
$_SESSION['checksum'] = sha1($_SESSION['number'].'-'.$_SERVER['REMOTE_ADDR'].$time);
if(isset($_SESSION['product'])){
$prodotto_id_id = check($_SESSION['product']); 
$riga = $data_set->get_record('fl_sottoprodotti',$prodotto_id_id);
$line = $data_set->get_record('fl_prodotti',$riga['prodotto_id']);
$_SESSION['product_label'] = $riga['label']." - &euro; ".numdec($riga['valore_facciale'],2);
}
include("../../fl_inc/headers.php");
?>





<div id="main_service" style=" margin: 0 auto; max-width: 800px;">

<?php 	if($_SESSION['anagrafica'] < 2 || !isset($_SESSION['profilo_commissione'])) { echo "<h2 class=\"red\">Modulo non accessibile.</h2> <p>Non esiste un profilo anagrafica te</p>"; exit; } ?>
<div id="left_box" style="width: 100%">

<?php if(isset($_SESSION['product'])){ ?>
<h1 class="product_line"><?php 	echo (file_exists('../../fl_set/lay/prodotti/'.$riga['prodotto_id'].'.jpg')) ?  '<img src="../../fl_set/lay/prodotti/'.$riga['prodotto_id'].'.jpg" alt="" />' : $riga['label']; ?></h1>

<p>Dettaglio ricarica</p>
<ul>
<li><?php echo $riga['label']; ?></li>
<li><a href="#">Importo <?php echo numdec($riga['valore_facciale'],2); ?> &euro;</a></li>
</ul>
<br><div id="res">
<p><a id="indietro" class="button red button_height" href="index.php">TORNA INDIETRO</a>
<a class="button green loadres button_height" href="#" id="<?php echo $riga['id']; ?>">AVANTI &gt;</a></p>
<p id="loading" class="c-red" style=" display: none; clear: both;"> Elaborazione in corso...<br><img src="<?php echo ROOT; ?>condivision/fl_set/lay/preloader.gif" alt="Caricamento in corso"/></p>

</div>

<br>
<?php } else { ?>
<h2>Hai gia avviato un operazione o devi riselezionare il prodotto.</h2>

<?php 
if(isset($_SESSION['OrigId'])) {
	 echo '<h2>OPERAZIONE IN SOSPESO</h2><p>'.$_SESSION['product_label'].'</p>
	 <p><a id="confirm" class="button green button_height " href="./conferma.php?'.$_SESSION['Results'].'">Conferma operazione</a>
    <a id="annulla" class="button red button_height" href="./conferma.php?annulla=1&'.$_SESSION['Results'].'">Annulla operazione</a></p></div>';

} else { echo '<p><a class="big-button orange button_height" href="index.php">ESEGUI ALTRA OPERAZIONE</a></p>'; }

} ?>

</div>

</div>


</body>
</html>