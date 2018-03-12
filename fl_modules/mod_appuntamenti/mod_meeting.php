<?php 
$meeting_page = 1;
require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
include("../../fl_inc/headers.php"); 
 ?>



<div id="container" style=" text-align: left;">

<?php if(isset($_GET['close'])) { ?><script type="text/javascript"> parent.$.fancybox.close();</script><?php exit; }  ?>
<?php if(isset($_GET['esito'])) { $class = (isset($_GET['success'])) ? 'green' : 'red'; echo '<p style="padding: 13px; width: 56%;"  class="'.$class.'">'.check($_GET['esito']).'</p>'; }  ?>

<div class="info_dati">
<?php 

$potential = get_potential( check($_GET['potential_rel']) ); 
mysql_query("UPDATE `fl_meeting_agenda` SET `issue`= 6 , data_aggiornamento = ".date('Y-m-d H:i:00')." WHERE id = ".check(@$_GET['id'])." LIMIT 1",CONNECT);

echo '<h1>'.$potential['nome'].' '.$potential['cognome'].'</h1>';
echo '<h2>Nation: '.@$paese[@$potential['paese']].'</h2>';
echo '<p>Phone: '.@$potential['telefono'].' mail: <a href="mailto:'.@$potential['email'].'" >'.@$potential['email'].'</a></h2>';
echo "<br><div style=\"width: 50%;\">".converti_txt($potential['messaggio'])."</div>";

$new_contract = ($potential['is_customer'] > 1) ? '../mod_anagrafica/mod_inserisci.php?external&action=1&proprietario=1&id='.$potential['is_customer'].'&meeting_id='.check($_GET['id']).'&potential_id='.$potential['id'].'&nominativo='.$potential['nome'].' '.$potential['cognome'] : '../mod_anagrafica/mod_inserisci.php?external&action=1&id=1&meeting_id='.check($_GET['id']).'&potential_id='.$potential['id'].'&nominativo='.$potential['nome'].' '.$potential['cognome'];
?>
</div>
<div style="margin: 0 auto; text-align: center; ">
<h1>Does <?php echo $potential['nome'].' '.$potential['cognome']; ?> accept 75% funding?</h1>
<a href="<?php echo $new_contract; ?>" class="touch green_push"><i class="fa fa-thumbs-up"></i> <br>YES</a>
<!--<a href="mod_opera.php?profile_rel=<?php echo $potential['id']; ?>&id=<?php echo check($_GET['id']); ?>&issue=5" class="touch orange_push"><i class="fa fa-hand-o-left"></i> <br>Pending</a>
--><a href="mod_opera.php?profile_rel=<?php echo $potential['id']; ?>&id=<?php echo check($_GET['id']); ?>&issue=4" class="touch red_push"><i class="fa fa-thumbs-down"></i> <br>NO</a>
<!--<a href="mod_opera.php?profile_rel=<?php echo $potential['id']; ?>&id=<?php echo check($_GET['id']); ?>&issue=3" class="touch red_push"><i class="fa fa-thumbs-down"></i> <br>No Show</a>-->
<h2 id="worked" style="font-size: 50px;">00:00</h2>
<p>Time left to set an issue for this meeting </p>
<h2 id="worked2" style="color: red;"></h2>
<script type="text/javascript">
$(document).ready(function (e) {
    var $worked = $("#worked");
	 var $worked2 = $("#worked2");
	<?php  if(!isset($_SESSION['meeting_doing'])) echo "localStorage.remain = '02:00:00';"; ?> 

	$worked.html(localStorage.remain);

    function update() {
        var myTime = $worked.html();
        var ss = myTime.split(":");
        var dt = new Date();
        dt.setHours(ss[0]);
        dt.setMinutes(ss[1]);
        dt.setSeconds(ss[2]);

        var dt2 = new Date(dt.valueOf() - 1000);
        var temp = dt2.toTimeString().split(" ");
        var ts = temp[0].split(":");

        $worked.html(ts[0]+":"+ts[1]+":"+ts[2]);
        if(Number(ts[0]) == 0 && Number(ts[1]) < 10) $worked2.html('Less than 10 minutes left!');
		if(Number(ts[0]) == 0 && Number(ts[1]) < 1) $worked2.html('The time is going to finish!');
		if(Number(ts[0])+Number(ts[1])+Number(ts[2]) < 0) window.location = 'mod_opera.php?profile_rel=<?php echo $potential['id']; ?>&id=<?php echo check($_GET['id']); ?>&issue=4';
		setTimeout(update, 1000);
		localStorage.remain = ts[0]+":"+ts[1]+":"+ts[2];

    }

    setTimeout(update, 1000);
});

<?php
$_SESSION['meeting_doing'] = check($_GET['id']);
$_SESSION['potential_rel'] = check($_GET['potential_rel']);

?>
</script></div>



<!--<form id="scheda" action="../mod_basic/action_modifica.php" method="post" enctype="multipart/form-data">
<?php include('../mod_basic/action_estrai.php');  ?>



<input type="hidden" name="dir_upfile" value="icone_articoli" />
<?php if(isset($_GET['external'])) { ?><input type="hidden" name="external" value="1" /><?php } ?>

</form>-->

</div></body></html>
