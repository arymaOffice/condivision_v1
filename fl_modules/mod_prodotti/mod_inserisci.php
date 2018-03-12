<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 

if(check($_GET['id']) != 1 && defined('CATALOGO_AVANZATO')) {
$tab_div_labels = array('id'=>'Dettaglio','./mod_referenze_prodotto.php?product_id=[*ID*]'=> "Referenze Prodotto");
}

include("../../fl_inc/headers.php");
include("../../fl_inc/testata_mobile.php");

 ?>


<body>
<div id="container" >


<div id="content_scheda">


<form id="scheda" action="../mod_basic/action_modifica.php" method="post" enctype="multipart/form-data">
<?php if(isset($_GET['esito'])) { $class = (isset($_GET['success'])) ? 'green' : 'red'; echo '<p class="'.$class.'">'.check($_GET['esito']).'</p>'; }  ?>
<h1>Scheda Prodotto</h1>

<?php include('../mod_basic/action_estrai.php');  ?>


<?php if(check($_GET['id']) == 1) { ?><input type="hidden" name="reload" value="../mod_prodotti/mod_inserisci.php?t=MQ==&id=" /><?php } ?>

</form>

</div>
</div></body></html>
