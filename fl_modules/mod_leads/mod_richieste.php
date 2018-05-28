<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 

unset($chat);
	$jquery = 1;
	$fancybox = 1;

$tab_id = 56;
$parent_id = check($_GET['reQiD']);
$dettagliLead = GRD('fl_potentials',$parent_id);
include("../../fl_inc/headers.php");
 ?>

<style>
.fancybox-wrap { 
  top: 25px !important; 
}</style>
<h1>Modifica Priorità</h1>   
<span style="text-align:  center;">

<a href="mod_opera.php?priorita_contatto=0&id=<?php echo base64_encode($parent_id); ?>" class="touch  <?php echo ($dettagliLead['priorita_contatto'] == 0) ? 'orange_push' : 'gray_push'; ?> setAction" data-gtx="<?php echo base64_encode($parametri_modulo['tab_id']); ?>" data-id="<?php echo base64_encode($parent_id); ?>" data-azione="10"  data-esito="7" data-note="Priorità Contatto: Bassa">Bassa</a>
<a href="mod_opera.php?priorita_contatto=1&id=<?php echo base64_encode($parent_id); ?>" class="touch  <?php echo ($dettagliLead['priorita_contatto'] == 1) ? 'orange_push' : 'gray_push'; ?> setAction" data-gtx="<?php echo base64_encode($parametri_modulo['tab_id']); ?>" data-id="<?php echo base64_encode($parent_id); ?>" data-azione="10"  data-esito="7" data-note="Priorità Contatto: Media">Media</a>
<a href="mod_opera.php?priorita_contatto=2&id=<?php echo base64_encode($parent_id); ?>" class="touch  <?php echo ($dettagliLead['priorita_contatto'] == 2) ? 'orange_push' : 'gray_push'; ?> setAction" data-gtx="<?php echo base64_encode($parametri_modulo['tab_id']); ?>" data-id="<?php echo base64_encode($parent_id); ?>" data-azione="10"  data-esito="7" data-note="Priorità Contatto: Alta">Alta</a>


	

<h1>Log Attivit&agrave;</h1>   
 
<?php if($parent_id < 2) { echo '<h2>Puoi impostare un\'attivit&agrave; dopo aver salvato le schede.'; exit; } ?>
<!--<a href="mod_richiesta.php?tipo_richiesta=0&parent_id=<?php echo $parent_id; ?>" title="Registra Chiamata" data-fancybox-type="iframe" class="fancybox_small touch blue_push"><i class="fa fa-phone"></i> <br>Call</a>
<a href="mod_richiesta.php?tipo_richiesta=1&parent_id=<?php echo $parent_id; ?>" title="Registra Invio Email" data-fancybox-type="iframe" class="fancybox_small touch blue_push"><i class="fa fa-envelope"></i> <br>Inviata Mail</a>
<a href="mod_richiesta.php?tipo_richiesta=2&parent_id=<?php echo $parent_id; ?>"  data-fancybox-type="iframe" class="fancybox_small touch orange_push setAction" title="Imposta Follow Up"><i class="fa fa-calendar"></i> <br>Follow up</a>
<a href="mod_richiesta.php?status_potential=3&tipo_richiesta=3&parent_id=<?php echo $parent_id; ?>"  data-fancybox-type="iframe" class="fancybox_small touch red_push setAction" title="Non interessato"><i class="fa fa-hand-o-left"></i> <br>Non interessato</a>
<a href="mod_richiesta.php?status_potential=6&tipo_richiesta=4&parent_id=<?php echo $parent_id; ?>"  data-fancybox-type="iframe" class="fancybox_small touch red_push setAction" title="Passato a Concorrenza"><i class="fa fa-thumbs-down"></i> <br>Concorrenza</a>
<a href="mod_richiesta.php?tipo_richiesta=2&parent_id=<?php echo $parent_id; ?>"  data-fancybox-type="iframe" class="fancybox_small touch orange_push setAction" title="Imposta Follow Up"><i class="fa fa-share"></i><br>Follow up</a>
-->
<!--<a href="<?php echo ROOT.$cp_admin; ?>fl_modules/mod_appuntamenti/mod_user.php?j=<?php echo $parent_id; ?>" class="touch blue_push setAction" data-gtx="<?php echo base64_encode($tab_id); ?>" data-id="<?php echo $parent_id; ?>" data-azione="5"  data-esito="1"  data-note="Appuntamento"><i class="fa fa-calendar"></i> <br>Appuntamento</a>
<a href="<?php echo ROOT.$cp_admin; ?>fl_modules/mod_preventivi/mod_inserisci.php?POiD=<?php echo $parent_id; ?>&id=1" class="touch red_push setAction" data-gtx="<?php echo base64_encode($tab_id); ?>" data-id="<?php echo $parent_id; ?>" data-azione="5"  data-esito="1"  data-note="Preventivo"><i class="fa fa-pencil-square-o"></i> <br>Preventivo</a>
<a href="<?php echo ROOT.$cp_admin; ?>fl_modules/mod_anagrafica/mod_inserisci.php?id=1&j=<?php echo $parent_id; ?>" class="touch green_push setAction" data-gtx="<?php echo base64_encode($tab_id); ?>" data-id="<?php echo $parent_id; ?>" data-azione="6"  data-esito="5" data-note="Conversione Cliente"><i class="fa fa-check-square-o"></i> <br>Converti in cliente</a>
-->

<div id="results"><?php if(isset($_GET['esito'])) echo '<h2 class="red">'.check($_GET['esito']).'</h2>'; ?></div>

<?php 
	
	
	$query = "SELECT * FROM `fl_richieste` WHERE `workflow_id` = 16 AND `parent_id` = $parent_id ORDER BY data_apertura DESC, data_scadenza DESC";
	$risultato = mysql_query($query, CONNECT);
	if(mysql_affected_rows() == 0){ echo "<p>Nessun Elemento</p>"; } else {
	?>
    
    <table class="dati">
      <tr>
   <th style="width: 1%;"></th>
   <th>Data / Operatore </th>
   <th>Azione</th>
   <th>Note</th>
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
		if($riga['tipo_richiesta'] == 10) { $colore = "class=\"tab_gray\"";  } 

	?> 
    
    
     
      <tr>
      <td <?php echo $colore; ?>><span class=\"Gletter\"></span></td>
      <td><?php echo mydatetime($riga['data_apertura']); ?><br> <?php echo $proprietario[$riga['operatore']]; ?></td>
      <td><?php echo $tipo_richiesta[$riga['tipo_richiesta']]; ?>
      <?php if($riga['data_scadenza'] != '' && $riga['data_scadenza'] != '0000-00-00 00:00:00') { echo 'Scad. '.mydatetime($riga['data_scadenza']); } ?>
<!--      <input type="text" name="data_chiusura" class="updateField calendar" data-rel="<?php echo $riga['id']; ?>" value="<?php echo ($riga['data_chiusura'] == '0000-00-00') ? $riga['data_chiusura'] : mydate($riga['data_chiusura']); ?>" />
-->		</td> <td><textarea style="height: 30px;" name="note" class="updateField" data-rel="<?php echo $riga['id']; ?>"><?php echo $riga['note']; ?></textarea></td>
  	  <!--<td><a href="../mod_basic/action_elimina.php?gtx=<?php echo $tab_id; ?>&amp;unset=<?php echo $riga['id'];?>" title="Elimina"  onclick="return conferma_del();"><i class="fa fa-trash-o"></i></a></td>-->
  	  <!--  colonna elimina nascosta su richiesta di giuseppe 08/03/2017-->
</tr>


    <?php } } //Chiudo la Connessione	?>
    
 </table><p><a href="#" onclick="location.reload();"><i class="fa fa-refresh" aria-hidden="true"></i> Aggiorna lista </a> 
</p>
</body></html>