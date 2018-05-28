<?php

// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
$_SESSION['last_referrer'] = ROOT.$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];
	echo "<h2>".ucfirst(@$proprietario[$_SESSION['number']])."</h2>";  ?>

