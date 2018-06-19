<?php

// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
$_SESSION['last_referrer'] = ROOT.$_SERVER['PHP_SELF'];
?>

<?php include('../mod_basic/action_visualizza.php'); ?>


<form method="get" action="mod_opera.php" id="conferma">
<input type="hidden" name="set" value="1">
<input type="hidden" name="id" value="<?php echo check($_GET['id']); ?>">
<input type="hidden" name="causale" value="<?php echo check($_GET['causale']); ?>">
<h2>Data Valuta</h2>
<input type="text" value="<?php echo date('d/m/Y'); ?>" class="calendar" name="data_valuta" />
<a href="#" onclick="$('#conferma').submit();" class="button">Conferma</a>
</form>

