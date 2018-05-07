<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
if(isset($_SESSION['request'])){ unset($_SESSION['request']);  header('Location: ./');  exit; } 
unset($_SESSION['lastSentRequest']);
if($_SESSION['usertype'] < 2) $selectLoadSrc = array('id'=>'#tipologia_hd','destinatario'=>'#tb_0 > iframe','src'=>'../mod_faq/mod_faq.php?nochat&categoria_faq=');

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
<?php if(check($_GET['id']) == 1) { echo '<input type="hidden" name="reload" value="../mod_helpdesk/?action=5&newRequest=" />'; } ?>
</form>

<?php
if(check($_GET['id']) == 1) {
	
	echo "<script type=\"text/javascript\">
	
	$('#nominativo').val('".$_SESSION['nome']."');
	$('#email_contatto').val('".$_SESSION['mail']."');

	</script>";
}
?>

</div>
</div>
</body></html>
