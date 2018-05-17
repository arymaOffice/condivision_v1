<?php 
require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 

$parent_id = (isset($_GET['parent_id'])) ? check($_GET['parent_id']) : 0;
$order_id = (isset($_GET['ordine_id'])) ? check($_GET['ordine_id']) : 0;
$multi_order = (isset($_GET['multi_order'])) ? check($_GET['multi_order']) : 0;
// $multi_order = 1;

$module_title = "Movimentazioni";

if($parent_id > 1) {
$dati_doc_vendita = GRD('fl_doc_acquisto',$parent_id);
$order_id = $dati_doc_vendita['ordine_id'];
$module_title = "Movimentazioni Merce <a href=\"../mod_doc_acquisto/mod_inserisci.php?id=".$dati_doc_vendita['id']."\">".$dati_doc_vendita['ragione_sociale'].' - DOC ID: '.$dati_doc_vendita['id'].'</a>';
}
require_once("../../fl_inc/headers.php");

?>

<script src="js/script.js"></script>
<link rel="stylesheet" href="css/style.css">

<?php if(!isset($_GET['external'])) include_once('../../fl_inc/testata.php'); ?>
<?php if(!isset($_GET['external'])) include_once('../../fl_inc/menu.php'); ?>
<?php if(!isset($_GET['external'])) include_once('../../fl_inc/module_menu.php'); ?>


<?php 
  # code...
  echo '<a href="mod_download.php?causale_movimentazione=1"><div class="module_icon box"><i class="fa fa-cloud-download" aria-hidden="true"></i> SCARICO MAGAZZINO</div></a>';
  echo '<a href="mod_load.php?causale_movimentazione=0"><div class="module_icon box"><i class="fa fa-cloud-upload" aria-hidden="true"></i> CARICO MAGAZZINO</div></a>';
  echo '<a href="mod_reso.php?causale_movimentazione=2"><div class="module_icon box"><i class="fa fa-glass" aria-hidden="true"></i> RESO EVENTO</div></a>';
?>
<table class="dati">
<?php 

$caricamenti = GQS('fl_magazzino_movimentazioni','`id`,`magazzino_id`, `evento_id`,`ordine_id`, `doc_vendita_id`, `causale_movimentazione`, `codice_fornitore`, `codice_ean`, `lotto`,`descrizione`, `unita_di_misura`, `quantita`,  `data_creazione`','1');
$s = 0;
foreach($caricamenti as $key => $val){

  if($s == 0) {
  echo '<tr>';
  foreach ($val as $list => $item) {
    echo '<th>'.record_label($list,CONNECT,1).'</th>';
  }
  echo '</tr>';
 }

  echo '<tr>';
  foreach ($val as $list => $item) {
    if($list == 'causale_movimentazione') $item = $causale_movimentazione[$item];
    if($list == 'magazzino_id') $item = $magazzino_id[$item];
    if($list == 'lotto') $item = '<input type="text" class="updateField" value="'.$item.'" name="'.$list.'" placeholder="Compila '.$list.'" data-gtx="141" data-rel="'.$val['id'].'" />';
    if($list == 'note') $item = '<input type="text" class="updateField" value="'.$item.'" name="'.$list.'" placeholder="Compila '.$list.'" data-gtx="141" data-rel="'.$val['id'].'" />';
    echo '<td>'.$item.'</td>';
  }
   echo '</tr>';

  $s = 1;
}
echo '</table>';


include_once("../../fl_inc/footer.php"); ?>