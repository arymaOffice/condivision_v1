<?php
include_once("header.php");
$errore="";
if (isset($_GET["error"])) {
	$errore = "<span style=\"color:red;\"> Codice non valodio, riprova! </span>";
	
}
include_once("footer.php");
?>


