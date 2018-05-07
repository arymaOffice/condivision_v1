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




<style media="all">
.unselectable > li ,.unselectable, #res {
    -webkit-touch-callout: none;
    -webkit-user-select: none;
    -khtml-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}

</style>
<div id="main_service" style=" margin: 0 auto; max-width: 800px;">
<?php 	if($_SESSION['anagrafica'] < 2 || !isset($_SESSION['profilo_commissione'])) { echo "<h2 class=\"red\">Modulo non accessibile.</h2> <p>Non esiste un profilo anagrafica te</p>"; exit; } ?>
<?php if(isset($_SESSION['product'])){ ?>

<div id="left_box">
<h1 class="product_line"><?php 	echo (file_exists('../../fl_set/lay/prodotti/'.$riga['prodotto_id'].'.jpg')) ?  '<img src="../../fl_set/lay/prodotti/'.$riga['prodotto_id'].'.jpg" alt="" />' : $riga['label']; ?></h1>
<p><?php echo $riga['label']; ?></p>
<ul>

<li><a href="#"><?php echo numdec($riga['valore_facciale'],2); ?> &euro;</a></li>
</ul>
<!--<h2>Commissione </strong>: &euro; <?php echo numdec(($riga['profilo'.@$_SESSION['profilo_commissione']]*$riga['ricavo_netto'])/100,4); ?> (<?php echo $riga['profilo'.$_SESSION['profilo_commissione']]; ?>%) </h2>
-->
<p>Inserisci numero di telefono</p>
<form id="form_ricariche" action="">
<input type="text" name="usercode" id="usercode" class="big-input">
</form>
<br><br>
<div class="action" id="indietro">
<p>Torna indietro e selezionare un altro prodotto oppure procedi.</p>
<p><a class="big-button red" href="index.php">TORNA INDIETRO</a></p>
</div>
<div id="printbox"></div>
</div>

<div id="right_box" style="min-width: 420px; padding: 10px;">
<div id="res">
<!--<script src="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
--><script type="application/javascript">
$(document).ready(function() {
	


    $(document).off('touchend', '.setNum').on('click', '.setNum',function(e) {

	  
      value = $('#usercode').val()+$(this).html();
	 $('#usercode').val(value);
	 console.log($(this).html());
	 event.preventDefault();
	 return false;
});
	

  $(document).off('touchend', '.delNum').on('click', '.delNum',function(e) {

	value = $('#usercode').val().slice(0,-1);
	$('#usercode').val(value);
	event.preventDefault();
});

});

</script>
<h1 style="text-align: center; text-transform: uppercase;">Digita numero di telefono</h1>
<ul class="unselectable">
<li><a href="#" class="setNum">1</a></li>
<li><a href="#" class="setNum">2</a></li>
<li><a href="#" class="setNum">3</a></li>
<li><a href="#" class="setNum">4</a></li>
<li><a href="#" class="setNum">5</a></li>
<li><a href="#" class="setNum">6</a></li>
<li><a href="#" class="setNum">7</a></li>
<li><a href="#" class="setNum">8</a></li>
<li><a href="#" class="setNum">9</a></li>
<li><a href="#" class="setNum">+</a></li>
<li><a href="#" class="setNum">0</a></li>
<li><a href="#" class="delNum">&lt;</a></li>
</ul>
<br>
<p id="loading" style="display: none; clear: both;"> ATTENDERE, Stiamo verificando il numero inserito.  <br><img src="../../fl_set/lay/preloader.gif" alt="Caricamento in corso"/></p>
</div>

<div class="action"><p><a class="big-button green loadric unselectable" product="<?php echo $riga['id']; ?>" href="#" id="shaman">AVANTI &gt;</a></p></div>

</div>


<?php } else { ?>
<h2>Hai gia avviato un operazione o devi riselezionare il prodotto.</h2>
<?php 
if(isset($_SESSION['OrigId'])) {
	
	 echo '<h2>OPERAZIONE IN SOSPESO</h2><p>'.$_SESSION['product_label'].'</p>
	 <p><a id="confirm" class="big-button green" href="./conferma.php?'.$_SESSION['Results'].'">Conferma operazione</a></p>
     <p><a id="annulla" class="big-button red" href="./conferma.php?annulla=1&'.$_SESSION['Results'].'">Annulla operazione</a></p></div>';

} else { echo '<p><a class="big-button orange" href="index.php">ESEGUI ALTRA OPERAZIONE</a></p>'; }

} ?>

</div>


</body>
</html>