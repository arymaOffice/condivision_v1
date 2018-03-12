<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
$module_title = "Movimenti";

include("../../fl_inc/headers.php");?>



<?php if(!isset($_GET['external'])) include('../../fl_inc/testata.php'); ?>
<?php if(!isset($_GET['external'])) include('../../fl_inc/menu.php'); ?>
<?php if(!isset($_GET['external'])) include('../../fl_inc/module_menu.php'); ?>

<h2 class="msg orange" style="margin-left: 0;">Gestione magazzino non attiva</h2>

<br><input name="barcode" id="barcode" style="border: 2px solid;
width: 100%;
padding: 20px;
text-align: center;
font-size: 200%;">
<div class="list"></div>

<p style="font-size: 300%;
color: #666;
line-height: 120%;">Richiedi attivazione  <strong>modulo magazzino</strong> o <br> integralo al tuo gestionale magazzino <br>per avere ultimo prezzo e giacenza sincronizzata.</p>




<?php include("../../fl_inc/footer.php"); ?>
