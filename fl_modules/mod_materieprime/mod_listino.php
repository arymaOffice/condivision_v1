<?php 

require_once('../../fl_core/autentication.php');
require_once('fl_settings.php');


unset($chat);
$nochat;
$product_id = check($_GET['record_id']);
$elemento = GRD($tabella,$product_id);
$tab_id = 116;

include("../../fl_inc/headers.php");
 ?>
 
<body style="background: rgb(241, 241, 241) none repeat scroll 0% 0%; text-align: left; padding: 20px;">
<style type="text/css">
  .select2-container { min-width: 100%; max-width: 100%;  }
</style>
<h1><?php echo $elemento['descrizione']; ?></h1>
<p>Questo elemento viene utilizzato in <?php echo $elemento['unita_di_misura']; ?></p>
<a href="#" onclick="$('#aggiungi').fadeToggle();" class="button">Aggiungi Formato</a> 

<div id="aggiungi" style="display: none; width: auto; background: white; padding: 20px;">

<form id="" action="./mod_opera.php" method="post" enctype="multipart/form-data">
<h2>Seleziona un fornitore</h2>
<select name="fornitore" class="select2">
<?php 
     foreach($fornitore as $valores => $label){ // Recursione Indici di Categoria
			echo "<option value=\"$valores\">".ucfirst($label)."</option>\r\n"; 
			}
		 ?>
</select>

<input type="hidden" name="id_materia" value="<?php echo $product_id; ?>" />

<p>Dettagli Formato: <select name="unita_di_misura_formato" >
<?php 
     foreach($unita_di_misura_formato as $valores => $label){ // Recursione Indici di Categoria
      $selected = ($elemento['unita_di_misura'] == $label) ? 'selected' : '';
      echo "<option value=\"$label\" $selected>".ucfirst($label)."</option>\r\n"; 
      }
     ?>
</select>
<input type="text" name="formato"   value="" class="" placeholder="Descrizione Formato" style="width: 50%;" />
<input type="text" name="valore_di_conversione"   value="" class="" placeholder="Quanti <?php echo $elemento['unita_di_misura']; ?> contiene?" />
</p>
<p>Prezzo di acquisto: <select name="valuta" >
<?php 
  echo "<option value=\"EUR\">EUR</option>\r\n";
?>
</select>
<input type="text" name="prezzo_unitario" placeholder="0.00" value="" />
</p>
<p><input type="text" name="giacenza"   value="" class="" placeholder="Giacenza" />
<input type="text" name="giacenza_minima"   value="" class="" placeholder="Giacenza Minima" />

<input type="text" name="codice_fornitore"   value="" class="" placeholder="Codice Fornitore" />
<input type="text" name="codice_ean"   value="" class="" placeholder="EAN CODE" />
</p>
<input type="submit" value="Inserisci" class="button big" style="background: green;" />
</form>
</div>



<?php 
	
	
	$query = "SELECT * FROM `".$tables[$tab_id]."` WHERE `id_materia` = $product_id";
	$risultato = mysql_query($query, CONNECT);
	if(mysql_affected_rows() == 0){ echo "<p>Nessun elemento</p>"; } else {
	?>
    
   <table class="dati">
   <tr>
   <th>Fornitore/Codice</th>
   <th>Formato</th>
   <th>Approvigionamento</th>
   <th>Prezzo</th>
   <th>Prezzo al <?php echo $elemento['unita_di_misura']; ?></th>
   <th>Giacenza </th>
   <th>Giacenza M.</th>
   <th></th>
   </tr>
          
 <?php
	

	
	while ($riga = mysql_fetch_assoc($risultato)) 
	{ 
	?> 
    
     
      <tr>
      <td><?php echo $fornitore[$riga['fornitore']]; ?><br><?php echo $riga['codice_fornitore']; ?></td>
      <td><span class="msg orange"><?php echo $riga['unita_di_misura_formato']; ?></span><?php echo $riga['formato']; ?><br>
      <strong> EAN: <a href="https://www.google.it/search?q=<?php echo $riga['codice_ean']; ?>" target="_blank"><?php echo $riga['codice_ean']; ?></a></strong></td>
      <td><span class="msg blue"><?php echo $elemento['unita_di_misura']; ?></span><?php echo $riga['valore_di_conversione']; ?></td>
      <td><?php echo $riga['valuta']; ?> <?php echo numdec($riga['prezzo_unitario'],2); ?></td>
      <td><?php echo $riga['valuta']; ?> <?php echo numdec($riga['prezzo_unitario']/$riga['valore_di_conversione'],2); ?></td>
      <td><input type="text" class="updateField" data-rel="<?php echo $riga['id']; ?>"  name="giacenza" value="<?php echo $riga['giacenza']; ?>" style="width: 50px;" /></td>
      <td><input type="text" class="updateField" data-rel="<?php echo $riga['id']; ?>"  name="giacenza_minima" value="<?php echo $riga['giacenza_minima']; ?>" style="width: 50px;" /></td>
  	  <td><a href="../mod_basic/action_elimina.php?gtx=<?php echo $tab_id; ?>&amp;unset=<?php echo $riga['id'];?>" title="Elimina"  onclick="return conferma_del();"><i class="fa fa-trash-o"></i></a></td>
</tr>

    <?php } } //Chiudo la Connessione	?>
    
 </table>

 <a href="javascript:location.reload();" class="button">Aggiorna Listino</a>
<a href="javascript:window.print();" class="button">Stampa</a>

</body></html>
