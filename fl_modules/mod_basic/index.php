<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 

 
include("../../fl_inc/headers.php");?>

<div id="container">

<?php include('../../fl_inc/testata.php'); ?>


<?php include('../../fl_inc/menu.php'); ?>

<div id="content">  

<h1>Supporto</h1>
<p class="percorso"><span class="subcolor">&gt;&gt;</span>Home\Supporto</p>
<p class="strumenti"><a href="javascript:history.back();" title="Torna Indietro">&lt;&lt; Indietro</a> | <a href="javascript:window.print();" title="Stampa Pagina">Stampa</a></p>
<hr />
<br class="clear" />

<?php /* Inclusione Pagina */ if(isset($_GET['action'])) { include($pagine[$_GET['action']]); } else { 


include("mod_contact.php"); 

} ?>


<?php include("../../fl_inc/footer.php"); ?>