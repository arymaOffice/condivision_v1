<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
$nochat = 1;
unset($chat);


$tab_id = 56;
$parent_id = check($_GET['reQiD']);
$evento_id = (isset($_GET['evento_id'])) ?  check($_GET['evento_id']) : 0;
include("../../fl_inc/headers.php");
 ?>

<style>
.fancybox-wrap { 
  top: 25px !important; 
}</style>

<h1>Log Attivit&agrave;</h1>   
 
<?php if($parent_id < 2 && $parent_id != 0) { echo '<h2>Puoi impostare un\'attivit&agrave; dopo aver salvato le schede.'; mysql_close(CONNECT); exit; } 
	  if($parent_id == 0) { echo '<h2>Nessun contatto associato a questo elemento!</h2> <br><p><a href="./mod_opera.php?creaLeadVuoto&workflow_id=6&parent_id='.$evento_id.'" class="button">Crea un contatto</a></p><br>'; } else { ?>


<div id="" >
<a href="mod_richiesta.php?tipo_richiesta=0&parent_id=<?php echo $parent_id; ?>" title="Registra Chiamata" data-fancybox-type="iframe"  class="fancybox_small touch blue_push"><i class="fa fa-phone"></i> <br>Chiamata</a>
<a href="mod_invia_mail.php?potential_rel=<?php echo $parent_id; ?>" title="Registra Invio Email" data-fancybox-type="iframe"  class="fancybox touch blue_push"><i class="fa fa-envelope"></i> <br>Invia Mail</a>
<a href="mod_invia_sms.php?potential_rel=<?php echo $parent_id; ?>" data-fancybox-type="iframe" class="fancybox_small touch blue_push"><i class="fa fa-envelope"></i> <br>Invia SMS</a>
<!--<a href="mod_richiesta.php?tipo_richiesta=2&parent_id=<?php echo $potential['id']; ?>"  data-fancybox-type="iframe" class="fancybox_small touch orange_push setAction" title="Imposta Follow Up"><i class="fa fa-calendar"></i> <br>Follow up</a>
--><a href="mod_richiesta.php?status_potential=3&tipo_richiesta=3&parent_id=<?php echo $parent_id; ?>"  data-fancybox-type="iframe" class="fancybox_small touch red_push setAction" title="Non interessato"><i class="fa fa-hand-o-left"></i> <br>Non interessato</a>
<a href="mod_richiesta.php?status_potential=6&tipo_richiesta=4&parent_id=<?php echo $parent_id; ?>"  data-fancybox-type="iframe" class="fancybox_small touch red_push setAction" title="Passato a Concorrenza"><i class="fa fa-thumbs-down"></i> <br>Concorrenza</a>
<a href="mod_richiesta.php?tipo_richiesta=2&parent_id=<?php echo $parent_id; ?>"  data-fancybox-type="iframe" class="fancybox_small touch orange_push setAction" title="Imposta Follow Up"><i class="fa fa-share"></i><br>Follow up</a>


</div>

<div id="results"><?php if(isset($_GET['esito'])) echo '<h2 class="red">'.check($_GET['esito']).'</h2>'; ?></div>

<?php 
	
	
	$query = "SELECT * FROM `fl_richieste` WHERE `workflow_id` = $tab_parent_id AND `parent_id` = $parent_id ORDER BY data_apertura DESC, data_scadenza DESC";
	$risultato = mysql_query($query, CONNECT);
	if(mysql_affected_rows() == 0){ echo "<p>Nessun Elemento</p>"; } else {
	?>




    <table class="dati">
      <tr>
   <th style="width: 1%;"></th>
    <th>Persona / Data </th>
   <th>Azione</th>
   <th>Scadenza</th>
   <th></th>
   <th></th>
   </tr>
          
 <?php
	

	
	while ($riga = mysql_fetch_assoc($risultato)) 
	{ 
	
		$colore = 'tab_blue';
		if($riga['tipo_richiesta'] == 0) { $colore = "class=\"tab_blue\"";  }
		if($riga['tipo_richiesta'] == 1) { $colore = "class=\"tab_blue\"";  }
		if($riga['tipo_richiesta'] == 2) { $colore = "class=\"tab_orange\"";  } 
		if($riga['tipo_richiesta'] == 3)  { $colore = "class=\"tab_red\"";  }
		if($riga['tipo_richiesta'] == 4) { $colore = "class=\"tab_red\"";  }
		if($riga['tipo_richiesta'] == 5) { $colore = "class=\"tab_green\"";  }
		if($riga['tipo_richiesta'] == 6) { $colore = "class=\"tab_green\"";  }
		if($riga['tipo_richiesta'] == 7) { $colore = "class=\"tab_orange\"";  }
		if($riga['tipo_richiesta'] == 8) { $colore = "class=\"tab_red\"";  } 
		if($riga['tipo_richiesta'] == 9) { $colore = "class=\"tab_blue\"";  } 
		if($riga['tipo_richiesta'] == 10) { $colore = "class=\"tab_green\"";  } 


	?> 
    
    
     
      <tr>
      <td <?php echo $colore; ?>><span class=\"Gletter\"></span></td>
      <td><strong><?php echo $proprietario[$riga['operatore']]; ?></strong><br><?php echo mydatetime($riga['data_apertura']); ?></td>
      <td><?php echo $tipo_richiesta[$riga['tipo_richiesta']]; ?>
      <?php if($riga['data_scadenza'] != '' && $riga['data_scadenza'] != '0000-00-00 00:00:00') { echo 'Scad. '.mydate($riga['data_scadenza']); } ?>
<!--      <input type="text" name="data_chiusura" class="updateField calendar" data-rel="<?php echo $riga['id']; ?>" value="<?php echo ($riga['data_chiusura'] == '0000-00-00') ? $riga['data_chiusura'] : mydate($riga['data_chiusura']); ?>" />
-->		</td> <td><textarea  name="note" class="updateField" data-rel="<?php echo $riga['id']; ?>"><?php echo $riga['note']; ?></textarea></td>
  	  <td><a href="../mod_basic/action_elimina.php?gtx=<?php echo $tab_id; ?>&amp;unset=<?php echo $riga['id'];?>" title="Elimina"  onclick="return conferma_del();"><i class="fa fa-trash-o"></i></a></td>
</tr>


    <?php } } //Chiudo la Connessione	?>
    
 </table>

 <?php } mysql_close(CONNECT); ?>

<p><a href="#" onclick="location.reload();"><i class="fa fa-refresh" aria-hidden="true"></i> Aggiorna lista </a> 
<?php if($parent_id > 1) echo '| <a href="../mod_leads/mod_inserisci.php?id='.$parent_id.'" target="_parent"><i class="fa fa-user" aria-hidden="true"></i> Vai a scheda contatto </a>'; ?>
</p>


</body></html>