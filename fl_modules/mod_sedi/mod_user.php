<?php

// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
$_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];

	echo "<h2>".ucfirst(@$proprietario[$_SESSION['number']])."</h2>";  ?>

