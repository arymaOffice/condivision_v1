<?php 
// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
if(@!is_numeric($_GET['action'])){ exit; };
?>

<h1>Scheda Modulo</h1>
<form id="scheda" action="../mod_basic/action_modifica.php" method="post" enctype="application/x-www-form-urlencoded">

<?php include('../mod_basic/action_estrai.php'); ?>


</form>
