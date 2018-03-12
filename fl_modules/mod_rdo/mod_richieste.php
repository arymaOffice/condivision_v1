<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 

unset($chat);
	$jquery = 1;
	$fancybox = 1;

$tab_id = 56;
$parent_id = check($_GET['reQiD']);
include("../../fl_inc/headers.php");
 ?>

<?php if($parent_id < 2) { echo '<h2>Salva la scheda prima di inserire una richiesta.'; exit; } ?>

<h1>Log Attivit&agrave;</h1>   
 
<?php if($parent_id < 2) { echo '<h2>Puoi impostare un\'attivit&agrave; dopo aver salvato le schede.'; exit; } ?>
<a href="mod_richiesta.php?tipo_richiesta=0&parent_id=<?php echo $parent_id; ?>" title="Registra Chiamata" data-fancybox-type="iframe" class="fancybox_small touch blue_push"><i class="fa fa-phone"></i> <br>Call</a>
<a href="mod_richiesta.php?tipo_richiesta=1&parent_id=<?php echo $parent_id; ?>" title="Registra Invio Email" data-fancybox-type="iframe" class="fancybox_small touch blue_push"><i class="fa fa-envelope"></i> <br>Inviata Mail</a>
<a href="mod_richiesta.php?tipo_richiesta=2&parent_id=<?php echo $parent_id; ?>"  data-fancybox-type="iframe" class="fancybox_small touch orange_push setAction" title="Imposta Follow Up"><i class="fa fa-calendar"></i> <br>Follow up</a>
<a href="mod_richiesta.php?tipo_richiesta=3&parent_id=<?php echo $parent_id; ?>"  data-fancybox-type="iframe" class="fancybox_small touch red_push setAction" title="Registra Quotazione Rifiutata"><i class="fa fa-hand-o-left"></i> <br>Qt. Rifiutata</a>
<a href="mod_richiesta.php?tipo_richiesta=4&parent_id=<?php echo $parent_id; ?>"  data-fancybox-type="iframe" class="fancybox_small touch red_push setAction" title="Registra Perdita"><i class="fa fa-thumbs-down"></i> <br>Perso</a>
<a href="mod_richiesta.php?tipo_richiesta=5&parent_id=<?php echo $parent_id; ?>"  data-fancybox-type="iframe" class="fancybox_small touch green_push setAction" title="Regitra Vittoria!"><i class="fa fa-check-square-o"></i> <br>Vinto</a>



<div id="results"><?php if(isset($_GET['esito'])) echo '<h2 class="red">'.check($_GET['esito']).'</h2>'; ?></div>

<?php 
	
	
	$query = "SELECT * FROM `fl_richieste` WHERE `workflow_id` = 2 AND `parent_id` = $parent_id";
	$risultato = mysql_query($query, CONNECT);
	if(mysql_affected_rows() == 0){ echo "<p>Nessun Elemento</p>"; } else {
	?>
    
    <table class="dati">
      <tr>
   <th style="width: 1%;"></th>
    <th>Data</th>
   <th>Azione</th>
   <th>Descrizione</th>
   <th>Chiusura</th>
   <th></th>
   </tr>
          
 <?php
	

	
	while ($riga = mysql_fetch_assoc($risultato)) 
	{ 
	
		if($riga['tipo_richiesta'] == 0) { $colore = "class=\"tab_blue\"";  }
		if($riga['tipo_richiesta'] == 1) { $colore = "class=\"tab_blue\"";  }
		if($riga['tipo_richiesta'] == 2) { $colore = "class=\"tab_orange\"";  } 
		if($riga['tipo_richiesta'] == 3)  { $colore = "class=\"tab_red\"";  }
		if($riga['tipo_richiesta'] == 4) { $colore = "class=\"tab_red\"";  }
		if($riga['tipo_richiesta'] == 5) { $colore = "class=\"tab_green\"";  }
		if($riga['tipo_richiesta'] == 6) { $colore = "class=\"tab_green\"";  }

	?> 
    
    
     
      <tr>
      <td <?php echo $colore; ?>><span class=\"Gletter\"></span></td>
       <td><?php echo mydate($riga['data_scadenza']); ?></td>
      <td><?php echo $tipo_richiesta[$riga['tipo_richiesta']]; ?></td>
      <td><?php echo $riga['note']; ?></td>
      <td><?php if($riga['tipo_richiesta'] == 2) { ?>
      <input type="text" name="data_chiusura" class="updateField calendar" data-rel="<?php echo $riga['id']; ?>" value="<?php echo ($riga['data_chiusura'] == '0000-00-00') ? $riga['data_chiusura'] : mydate($riga['data_chiusura']); ?>" /></td>
		<?php } ?>
  	  <td><a href="../mod_basic/action_elimina.php?gtx=<?php echo $tab_id; ?>&amp;unset=<?php echo $riga['id'];?>" title="Elimina"  onclick="return conferma_del();"><i class="fa fa-trash-o"></i></a></td>
</tr>

    <?php } } //Chiudo la Connessione	?>
    
 </table>
</body></html>