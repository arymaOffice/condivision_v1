<?php 

require_once('../../fl_core/autentication.php');
require_once('fl_settings.php');


unset($chat);
$nochat;
$tab_id = 116;
$product_id = check($_GET['record_id']);

include("../../fl_inc/headers.php");
 ?>
 
<body style="background: rgb(241, 241, 241) none repeat scroll 0% 0%; text-align: left; padding: 20px;">


<form id="" action="./mod_opera.php" method="post" enctype="multipart/form-data">

<select name="fornitore" >
<?php 
     foreach($fornitore as $valores => $label){ // Recursione Indici di Categoria
			echo "<option value=\"$valores\">".ucfirst($label)."</option>\r\n"; 
			}
		 ?>
</select>

<select name="valuta" >
<?php 
  echo "<option value=\"EUR\">EUR</option>\r\n";
?>
</select>

<input type="hidden" name="id_materia" value="<?php echo $product_id; ?>" />
<input type="text" name="prezzo_unitario" placeholder="0.00" value="" />
<input type="text" name="data_validita"   value="<?php echo date('d/m/Y'); ?>" class="calendar" />
<input type="submit" value="Inserisci" />
</form>




<?php 
	
	
	$query = "SELECT * FROM `".$tables[$tab_id]."` WHERE `id_materia` = $product_id";
	$risultato = mysql_query($query, CONNECT);
	if(mysql_affected_rows() == 0){ echo "<p>Nessun elemento</p>"; } else {
	?>
    
   <table class="dati">
   <tr>
   <th>Fornitore</th>
   <th>Prezzo</th>
   <th>Data Validità</th>
   <th></th>
   </tr>
          
 <?php
	

	
	while ($riga = mysql_fetch_assoc($risultato)) 
	{ 
	?> 
    
     
      <tr>
      <td><?php echo $fornitore[$riga['fornitore']]; ?></td>
      <td><?php echo $riga['valuta']; ?> <?php echo $riga['prezzo_unitario']; ?></td>
      <td><input type="text" class="updateField calendar" data-rel="<?php echo $riga['id']; ?>"  name="data_validita" value="<?php echo mydate($riga['data_validita']); ?>" /></td>
  	  <td><a href="../mod_basic/action_elimina.php?gtx=<?php echo $tab_id; ?>&amp;unset=<?php echo $riga['id'];?>" title="Elimina"  onclick="return conferma_del();"><i class="fa fa-trash-o"></i></a></td>
</tr>

    <?php } } //Chiudo la Connessione	?>
    
 </table>
</body></html>