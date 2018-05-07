<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 

include("../../fl_inc/headers.php");
$categoria_prodotto = $data_set->data_retriever('fl_cat_prodotti','label');
unset($categoria_prodotto[0]); 
?>

<script type="text/javascript">
function print_frame(frame) {
window.frames[frame].focus();
window.frames[frame].print();
}

</script>

<h2 style="text-align: center; padding: 2px;"> <a href="#" class="button" style="font-size: 16px" onClick="print_frame('iprint');">
<i class="fa fa-print"></i> Stampa Scontrino</a>
</h2>

<iframe id="iprint" name="iprint" src="ricevuta.php?<?php echo $_SERVER['QUERY_STRING']?>" style="width: 100%; height: 800px;"></iframe>

</body>
</html>
