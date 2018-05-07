<?php 

ini_set('display_errors',0);
error_reporting(E_ALL);

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
$conf = GRD('fl_security',$_SESSION['number']);
$daysworking = explode(',',$conf['giorni_lavorativi']);
$today = date('w');
$now = DateTime::createFromFormat('H:i:s', date('H:i:s'));
$init = DateTime::createFromFormat('H:i:s', $conf['ore_inizio']);
$end = DateTime::createFromFormat('H:i:s', $conf['ore_fine']);


include("../../fl_inc/headers.php");	
$categoria_prodotto = $data_set->data_retriever('fl_cat_prodotti','label','WHERE attivo = 1','id ASC');
unset($categoria_prodotto[0]); 

?>


<?php 	if($_SESSION['anagrafica'] < 2 || !isset($_SESSION['profilo_commissione'])) { echo "<h2 class=\"red\">Modulo non accessibile.</h2> <p>Non esiste un profilo anagrafica te</p>"; exit; } ?>


<?php if(isset($_SESSION['OrigId'])) {
echo '<div id="main_service" style=" margin: 55px auto; max-width: 800px;">

<div id="left_box" style="width: 100%; padding: 20px;"><div class="info red" style="display: block;"><h1>&ensp;</h1>
<h1>Hai un operazione da confermare: </h1><h2>'.$_SESSION['product_label'].'</h2></div>
<p><a id="confirm" class="big-button green" href="./conferma.php?'.$_SESSION['Results'].'">Conferma operazione</a></p>
<p><a id="annulla" class="big-button gray" href="./conferma.php?annulla=1&'.$_SESSION['Results'].'">Annulla operazione</a></p></div></div>';
} else {


if($conf['attivazione_cassa'] == 0){ ?> 
<h1>La cassa è sospesa</h1>
<p>Contatta il customer service</p>
<p><a href="<?php echo ROOT.$cp_admin; ?>" class="button">Esci</a></p>

<?php 


} else if($daysworking[$today] == 0) { ?>

<h1>La tua cassa non è attiva di <?php echo $giorni_lavorativi[$today]; ?></h1>
<p>Contatta il customer service</p>
<p><a href="<?php echo ROOT.$cp_admin; ?>" class="button">Esci</a></p>


<?php } else if($now < $init || $now > $end) { ?>

<h1>La cassa è attiva dalle <?php echo $conf['ore_inizio']; ?> alle <?php echo $conf['ore_fine']; ?></h1>
<p>Contatta il customer service</p>
<p><a href="<?php echo ROOT.$cp_admin; ?>" class="button">Esci</a></p>


<?php } else { 	?>


<div id="top_bar">

<a href="./?a=dashboard"><img src="<?php echo LOGO; ?>" alt="<?php echo client; ?>" style="float: left; margin:  0 5px; " /></a>
<ul>

<?php 
foreach($categoria_prodotto as $chiave => $valore) {
 if($chiave > 1) echo '<li><a href="#" style=" text-align: center;" id="'.$chiave.'" title="'.$valore.'" class="select_line">'.$valore.'</a></li>'; } 
?>
<li style="text-align: center; width: 50px; min-width: 0;  float: right; margin-right:  10px; font-size: 200%; background: transparent;" ><a href="<?php echo ROOT.$cp_admin; ?>" style="color: #c81616;  width: 50px; background: transparent;"><i class="fa fa-power-off" aria-hidden="true"></i>
</a></li>
<li style=" text-align: center; width: 50px;  min-width: 0; float: right; margin-right:  0px; font-size: 200%; background: transparent;"><a href="#" style="color:#cbad0d;  width: 50px; background: transparent;" class="info_cassa"><i class="fa fa-info-circle" aria-hidden="true"></i>

</a></li>
</ul>
</div>
<div id="side_bar">

</div>
<div id="right_container">

<div id="products" style="max-width: 700px; margin: auto;">
<img src="<?php echo ROOT.$cp_admin.$cp_set; ?>lay/intro_cassa.png" alt=" Operatore">
</div>




</div><?php }	?>
<?php } ?>

<div style="position: absolute; bottom: 0; right: 0; font-size: 300%;">
</div>
</body>
</html>
<?php mysql_close(CONNECT); ?>