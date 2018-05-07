<?php 
require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 

$parent_id = (isset($_GET['parent_id'])) ? check($_GET['parent_id']) : 0;
$order_id = (isset($_GET['ordine_id'])) ? check($_GET['ordine_id']) : 0;
$multi_order = (isset($_GET['multi_order'])) ? check($_GET['multi_order']) : 0;
// $multi_order = 1;

$module_title = "Movimentazioni di Scarico";

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



<div class="container" lang="en-US">

  <form id="form"name="" method="POST" class="download" action="mod_opera.php" data-rel="<?php echo $multi_order ?>">
    <?php if($order_id > 1) echo "Questi movimenti verranno associati all'ordine con id: ".'<input type="text" name="ordine_id" value="'.$order_id.'" />'; ?>
    
    <select class="um-field " name="causale_movimentazione" id="causale_movimentazione">
    <?php
    foreach($causale_movimentazione as $val => $label) { 
    $selected = (isset($_GET['causale_movimentazione']) && check(@$_GET['causale_movimentazione']) == $val) ? 'selected' : ''; echo '<option '.$selected.' value="'.$val.'">'.$label.'</option>'; 
    } ?>
    </select>
    <select class="um-field " name="magazzino_id" id="magazzino_id">
    <?php
    foreach($magazzino_id as $val => $label) { 
      $selected = (isset($_GET['magazzino_id']) && check(@$_GET['magazzino_id']) == $val) ? 'selected' : ''; echo '<option '.$selected.' value="'.$val.'">'.$label.'</option>'; 
    } ?>
    </select>

     <div class="dati-wrapper">
      <table id ="dati" class="dati" summary="Dati">
        <tr>
          <th>Codice</th>
          <th>Descrizione</th>
          <th>UM</th>
          <th>QTY</th>
          <!-- <th>Prezzo</th>
          <th>Sc. %</th>
          <th>IVA</th>
          <th>Importo</th> -->
          <th>Evento ID</th>
          <th></th>
        </tr>

        <tr id="r1">
          <td class="codice"><input class="codice-field" type="text" name="codice[]" placeholder="Inserisci codice"><input class="id-field" type="hidden" name="id[]"></td>
          <td class="descrizione"><input class="descrizione-field" type="text" name="descrizione[]" placeholder="Inserisci descrizione"></td>
          <td class="um">
            <select class="um-field numero-field" name="unita_di_misura[]" id="">
              <option value="KG">KG</option>
              <option value="LT">LT</option>
              <option value="PZ">PZ</option>
              <option value="BT">BT</option>
							<option value="CT">CT</option>
              <option value="KP">KP</option>
            </select>
          </td>
          <td class="qty numero-field">
            <input class="qty-field" type="number" step="any" name="qty[]" value="1">
          </td>
          <td class="prezzo numero-field" hidden>
            <input class="prezzo-field" step="any" type="number" name="prezzo[]" value="0.00">
          </td>
          <td class="sc numero-field" hidden>
            <input class="sc-field" type="number" step="any" name="sc[]"  value="0.00">
          </td>
          <td class="iva numero-field" hidden>
            <input class="iva-field" type="number" step="any" min="0" name="iva[]" value="0.00">
          </td>
          <td class="importo numero-field" hidden>
            <input class="importo-field" step="any" type="number" name="importo[]" value="0.00" readonly>
          </td>
          
          <td class="evento_id">
            <select class="evento-field" name="evento_id[]" id="">
 
            </select>
          </td>

          <th class="delete-row"></th>
        </tr>
      </table>

 

    </div>
 
    <input type="hidden" name="download" value="1">
    <input type="hidden" name="doc_vendita" value="<?php echo $parent_id; ?>">
    <input type="hidden" name="ordine_id" value="<?php echo $order_id; ?>">
    <input type="hidden" name="multi_order" value="0">
    <div class="aggiungi msg green">
      <label id="aggiungi" for=""><i  title="Aggiungi riga" class="fa fa-plus"></i></label>
    </div>
    <button id="submit" type="submit" class="salva button">Scarica magazzino</button>

  </form>
</div>
<?php include_once("../../fl_inc/footer.php"); ?>