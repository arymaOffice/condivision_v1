<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 

if(isset($_GET['auto'])) {

	mysql_query('INSERT INTO fl_gruppi (id,nome_gruppo) VALUES (NULL ,\'Nuovo gruppo\')',CONNECT);
	$new = mysql_insert_id();
	header('Location: ./mod_inserisci.php?associaCampagna='.check($_GET['auto']).'&id='.$new);
	mysql_close(CONNECT);
	exit;
}

include("../../fl_inc/headers.php");
include("../../fl_inc/testata_mobile.php");




 ?>


<body style=" background: #FFFFFF;">
<div id="container" >
<div id="content_scheda">

<form id="scheda" action="../mod_basic/action_modifica.php" method="post" enctype="multipart/form-data">
<?php if(isset($_GET['esito'])) { $class = (isset($_GET['success'])) ? 'green' : 'red'; echo '<p class="'.$class.'">'.check($_GET['esito']).'</p>'; }  ?>
<h1>Scheda</h1>
<?php   include('../mod_basic/action_estrai.php');  ?>

<?php if(check($_GET['id']) == 1) { ?><input type="hidden" name="reload" value="../mod_gruppi/mod_inserisci.php?id=" /><?php } ?>
<?php if(isset($_GET['associaCampagna'])) { ?><input type="hidden" name="reload" value="../mod_campagne_attivita/mod_inserisci.php?id=<?php echo check($_GET['associaCampagna']); ?>&gruppo_id=" /><?php } ?>


</form>


</div>
</div>
</body></html>
