<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 

include("../../fl_inc/headers.php"); include("../../fl_inc/testata_mobile.php");
 ?>



<div id="container" style=" text-align: left;">

<?php if(isset($_GET['close'])) { ?><script type="text/javascript"> parent.$.fancybox.close();</script><?php exit; }  ?>
<?php if(isset($_GET['esito'])) { $class = (isset($_GET['success'])) ? 'green' : 'red'; echo '<p style="padding: 13px; width: 56%;"  class="'.$class.'">'.check($_GET['esito']).'</p>'; }  ?>

<div class="info_dati">
<?php 

$potential = get_potential( check($_GET['potential_rel']) ); 

$telefono = phone_format($potential['telefono']);

echo '<h1>'.$potential['nome'].' '.$potential['cognome'].'</h1>';
echo '<h2>Nation: '.@$paese[@$potential['paese']].'</h2>';
echo '<p>Phone: '.@$telefono.' mail: <a href="mailto:'.@$potential['email'].'" >'.@$potential['email'].'</a></h2>';


?>
</div>

<a href="mod_opera.php?profile_rel=<?php echo $potential['id']; ?>&id=<?php echo check($_GET['id']); ?>&issue=1" class="touch orange_push"><i class="fa fa-thumbs-up"></i> <br>Arrived</a>
<a href="mod_opera.php?profile_rel=<?php echo $potential['id']; ?>&id=<?php echo check($_GET['id']); ?>&issue=6" class="touch blue_push"><i class="fa fa-thumbs-down"></i> <br>In meeting</a>
<a href="mod_opera.php?profile_rel=<?php echo $potential['id']; ?>&id=<?php echo check($_GET['id']); ?>&issue=3" class="touch red_push"><i class="fa fa-thumbs-down"></i> <br>Not Show</a>


<!--<a href="../mod_anagrafica/mod_inserisci.php?external&action=1&id=1&potential_id=<?php echo $potential['id']; ?>&nominativo=<?php echo $potential['nome'].' '.$potential['cognome']; ?>" class="touch green_push"><i class="fa fa-thumbs-up"></i> <br>Interested</a>
-->



<form id="scheda" action="../mod_basic/action_modifica.php" method="post" enctype="multipart/form-data">
<?php include('../mod_basic/action_estrai.php');  ?>



<input type="hidden" name="dir_upfile" value="icone_articoli" />
<?php if(isset($_GET['external'])) { ?><input type="hidden" name="external" value="1" /><?php } ?>

</form>

</div></body></html>
