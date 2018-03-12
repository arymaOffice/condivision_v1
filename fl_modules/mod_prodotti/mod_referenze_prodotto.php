<?php 

require_once('../../fl_core/autentication.php');


$product_id = check($_GET['product_id']);
$tab_id = 104;
$table = $tables[$tab_id];
$prod = 0;

require('../../fl_core/dataset/array_statiche.php');
require('../../fl_core/class/ARY_dataInterface.class.php');
$data_set = new ARY_dataInterface();
$produttore = $data_set->get_items_key('produttore');

$labels = array("Codice","Telaio","Dimensioni","Peso","Imballo",'Prezzo','','','','');//Chiusini
if(@$_SESSION['categoria_id'] == 10) $labels = array("Codice","Dimensioni [LxIxHxS]","Superfice di scarico","Peso","Imballo",'Prezzo','','','',''); //Griglie
if(@$_SESSION['categoria_id'] >= 14 && @$_SESSION['categoria_id'] < 17) $labels = array("Codice ",'Tipologia',"DN",'DN Flangia',"DE","Peso",'Prezzo','','','',''); //Raccordi
if(@$_SESSION['categoria_id'] >= 17 && @$_SESSION['categoria_id'] < 21) $labels = array("Codice ","DN","Lunghezza","Classe di pressione","Peso",'Prezzo','','','',''); //Tubazioni
if(@$_SESSION['categoria_id'] >= 23) $labels = array("Codice","DN","PN","Dimensioni","Tipologia","Pollici","Peso",'Prezzo','Dettagli','',''); //Valvole
$valori = GRD('fl_cat_prodotti',$_SESSION['categoria_id']);
$labels = array(
                $valori['value1'],
                $valori['value2'],
                $valori['value3'],
                $valori['value4'],
                $valori['value5'],
                $valori['value6'],
                $valori['value7'],
                $valori['value8'],
                $valori['value9'],
                $valori['value10'],
                @$valori['value11']
                );//Chiusini




include('../../fl_core/dataset/items_rel.php');
include("../../fl_inc/headers.php");
unset($chat);


 ?>
 
<body style=" background: rgb(241, 241, 241) none repeat scroll 0% 0%; text-align: left; padding: 20px;">




<?php 
	$query = "SELECT * FROM `$table` WHERE `parent_id` = $product_id ORDER BY value".$valori['ordinamento_predefinito']." DESC";
	$risultato = mysql_query($query, CONNECT);
	if(mysql_affected_rows() < 1){ echo "<p>Inserisci un elemento</p>"; } else {
?>

   <table class="dati">
   <tr>
   <th>Prod.</th>
<?php 
foreach ($labels as $chiave => $valore) { if($valore != '') echo '<th>'.$valore.'</th>'; } ?>
   <th></th>
   </tr>
          
<?php
	while ($riga = mysql_fetch_assoc($risultato)) 
	{ 

  $prod = $riga['produttore'];
	?> 
      <tr>

      <td><?php echo $produttore[$riga['produttore']];?></td>
<?php 
foreach ($labels as $chiave => $valore) { if($valore != '') { $key = 'value'.($chiave+1); echo '<td><input type="text" name="value'.($chiave+1).'" placeholder="'.$valore.'" value="'.$riga[$key].'" class="updateField" data-rel="'.$riga['id'].'" /></td>';  }}
?>
  	  <td><a href="../mod_basic/action_elimina.php?gtx=<?php echo $tab_id; ?>&unset=<?php echo $riga['id'];?>" title="Elimina"  onclick="return conferma_del();"><i class="fa fa-trash-o"></i></a></td>
</tr>

    <?php } } mysql_close(CONNECT);	?>
    
 </table>
 
 <form id="" action="./mod_opera.php" method="post" enctype="multipart/form-data">
<input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />
<select name="produttore">
<?php foreach ($produttore as $key => $value) {
  $selected = ($prod == $key) ?  'selected' : '';
  echo '<option value="'. $key.'" '.$selected.'>'.$value.'</option>' ;
} ?>
</select>

<?php 
foreach ($labels as $chiave => $valore) { if($valore != '') echo '<input type="text" name="value'.($chiave+1).'" placeholder="'.$valore.'" value="" style="width: 20%" />';  }
?>
<input type="submit" value="Aggiungi riga" />
</form>

</body></html>