<?php 

require_once('../../fl_core/autentication.php');
require_once('fl_settings.php');

unset($chat);

$product_id = check($_GET['prodotto_id']);
$elemento = GRD($tabella,$product_id);
$tab_id = 136;


include("../../fl_inc/headers.php");
 ?>
 
<body style="background: rgb(241, 241, 241) none repeat scroll 0% 0%; text-align: left; padding: 20px;">
<style type="text/css">
  .select2-container { min-width: 100%; max-width: 100%;  }
</style>
<h1><?php echo $elemento['descrizione']; ?></h1>

<a href="#" onclick="$('#aggiungi').fadeToggle();" class="button">Assegna</a> 

<div id="aggiungi" style="display: none; width: auto; background: white; padding: 20px;">





<form id="" action="./mod_opera.php" method="post" enctype="multipart/form-data">
<h2>Seleziona un punto</h2>
<select name="anagrafica_id" class="select2">
<?php 
     foreach($anagrafica_id as $valores => $label){ // Recursione Indici di Categoria
			echo "<option value=\"$valores\">".ucfirst($label)."</option>\r\n"; 
			}
		 ?>
</select>

<input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />

<p>Q.tà: <input type="number" name="valore" value="1" /> 


Status: <select name="status_assegnazione" >
<?php 
     foreach($status_assegnazione as $valores => $label){ // Recursione Indici di Categoria
          echo "<option value=\"$valores\">".ucfirst($label)."</option>\r\n"; 
      }
     ?>
</select>
<!--
<select name="centro_di_supporto" >
<?php 
     foreach($centro_di_supporto as $valores => $label){ // Recursione Indici di Categoria
          echo "<option value=\"$valores\">".ucfirst($label)."</option>\r\n"; 
      }
     ?>
</select>-->
</p>


<input type="submit" value="Inserisci" class="button big" style="background: green;" />
</form>
</div>



<?php 
	
	
	$query = "SELECT * FROM `".$tables[$tab_id]."` WHERE `prodotto_id` = $product_id";
	$risultato = mysql_query($query, CONNECT);
	if(mysql_affected_rows() == 0){ echo "<p>Nessun elemento</p>"; } else {
	?>
    
   <table class="dati">
   <tr>
   <th>Prodotto</th>
   <th>Punto</th>
   <th>Valore</th>
   <th>Stato</th>
   <th></th>
   </tr>
          
 <?php
	
 $tot = 0;
	
	while ($riga = mysql_fetch_assoc($risultato)) 
	{ $tot += $riga['valore'];

	?> 
    
     
      <tr>
      <td><?php echo  $elemento['descrizione']; ?></td>
      <td>
      <select name="anagrafica_id" class="updateField" data-rel="<?php echo $riga['id']; ?>">
      <?php 
      foreach($anagrafica_id as $valores => $label){ // Recursione Indici di Categoria
            $selected = ($riga['anagrafica_id'] == $valores) ? 'selected' : '';
            echo "<option value=\"$valores\"  $selected>".ucfirst($label)."</option>\r\n"; 
      }
      ?>  
      </select>
      </td>
      <td>
      <select name="status_assegnazione" class="updateField" data-rel="<?php echo $riga['id']; ?>">
      <?php 
      foreach($status_assegnazione as $valores => $label){ // Recursione Indici di Categoria
            $selected = ($riga['status_assegnazione'] == $valores) ? 'selected' : '';
            echo "<option value=\"$valores\"  $selected>".ucfirst($label)."</option>\r\n"; 
      }
      ?>
      </select>
      </td>

      <td><input type="text" class="updateField" data-rel="<?php echo $riga['id']; ?>" name="valore" value="<?php echo $riga['valore']; ?>" style="width: 50px;" /></td>
  	  <td>
        <a href="../mod_basic/action_elimina.php?gtx=<?php echo $tab_id; ?>&amp;unset=<?php echo $riga['id'];?>" title="Elimina"  onclick="return conferma_del();"><i class="fa fa-trash-o"></i></a></td>
      </tr>

    <?php } } //Chiudo la Connessione	?>
    
 </table>
<h3>Totale <?php echo  $elemento['descrizione']; ?> gestiti: <?php echo $tot; ?></h3>
 <a href="javascript:location.reload();" class="button">Aggiorna Giacenza</a>
<a href="javascript:window.print();" class="button">Stampa</a>

</body></html>
