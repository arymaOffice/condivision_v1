<?php 

require_once('../../fl_core/autentication.php');
include('../../fl_core/dataset/items_rel.php');

unset($chat);
$tab_id = 56;
$parent_id = check($_GET['reQiD']);
$tipo_richiesta = array('Call','Email','Follow up','Qt. Rifiutata','Perso','Vinto','');	
include("../../fl_inc/headers.php");
 ?>


<body style=" background: #FFFFFF;">

<h1>Log Attivit&agrave;</h1>   
 
 <div id="">
<a href="mod_richiesta.php?tipo_richiesta=0&reQiD=<?php echo $parent_id; ?>" data-fancybox-type="iframe" class="fancybox_small touch blue_push"><i class="fa fa-phone"></i> <br>Call</a>
<a href="mod_richiesta.php?tipo_richiesta=1&reQiD=<?php echo $parent_id; ?>" data-fancybox-type="iframe" class="fancybox_small touch blue_push"><i class="fa fa-envelope"></i> <br>Inviata Mail</a>
<a href="<?php echo ROOT.$cp_admin; ?>fl_modules/mod_appuntamenti/mod_user.php?j=<?php echo base64_encode($id); ?>" class="touch blue_push setAction" data-gtx="<?php echo base64_encode($tab_id); ?>" data-id="<?php echo base64_encode($id); ?>" data-azione="5"  data-esito="1"  data-note="Meeting"><i class="fa fa-calendar"></i> <br>Follow up</a>
<a href="mod_opera.php?id=<?php echo $id; ?>&notanswered=1" class="touch orange_push setAction" data-gtx="<?php echo base64_encode($tab_id); ?>" data-id="<?php echo base64_encode($id); ?>" data-azione="2"  data-esito="3" data-note=""><i class="fa fa-hand-o-left"></i> <br>Qt. Rifiutata</a>
<a href="mod_opera.php?id=<?php echo $id; ?>&status_potential=3" class="touch red_push setAction" data-gtx="<?php echo base64_encode($tab_id); ?>" data-id="<?php echo base64_encode($id); ?>" data-azione="2"  data-esito="4" data-note="CAMPAGNA ID"><i class="fa fa-thumbs-down"></i> <br>Perso</a>
<a href="<?php echo ROOT.$cp_admin; ?>fl_modules/mod_anagrafica/mod_inserisci.php?id=1&j=<?php echo base64_encode($id); ?>" class="touch green_push setAction" data-gtx="<?php echo base64_encode($tab_id); ?>" data-id="<?php echo base64_encode($id); ?>" data-azione="6"  data-esito="5" data-note="Conversione Cliente"><i class="fa fa-check-square-o"></i> <br>Vinto</a>

</div>
 
  	 <table class="dati" summary="Dati" style=" width: 100%;">
        <tr>
     <th scope="col"></th>
       <th scope="col">Data</th>
       <th scope="col">Azione</th>
       <th scope="col">Esito</th>
          <th scope="col">Note</th>
       <th scope="col">Account</th>
      </tr>
	<?php 
	
		$query = "SELECT * FROM `fl_calls_log` WHERE `tab_id` = $tab_id AND `item_id` = $id ORDER BY data_creazione DESC;";
		$risultato = mysql_query($query, CONNECT);


while ($riga = mysql_fetch_array($risultato)) 
	{
		if($riga['issue'] == 0) { $colore = "class=\"tab_blue\"";  }
		if($riga['issue'] == 1) { $colore = "class=\"tab_green\"";  }
		if($riga['issue'] == 2) { $colore = "class=\"tab_blue\"";  } 
		if($riga['issue'] == 3)  { $colore = "class=\"tab_orange\"";  }
		if($riga['issue'] == 4) { $colore = "class=\"tab_red\"";  }
		if($riga['issue'] == 5) { $colore = "class=\"tab_green\"";  }
		if($riga['issue'] == 6) { $colore = "class=\"tab_green\"";  }

		
			
    
	
			echo "<tr><td $colore><span class=\"Gletter\"></span></td>"; 
			echo "<td>".mydatetime($riga['data_creazione'])."</td>"; 
			echo "<td>".@$item_action[$riga['item_action']]."</td>";	
			echo "<td>".@$item_issue[$riga['issue']]."</td>";	
			echo "<td>".@$riga['note']."</td>";	
			echo "<td>".@$proprietario[$riga['operatore']]."</td>";	
		    echo "</tr>";
		
	
			
	}

	echo "</table>";
	
?>
<?php 
	
	
	$query = "SELECT * FROM `fl_richieste` WHERE `workflow_id` = 2 AND `parent_id` = $parent_id";
	$risultato = mysql_query($query, CONNECT);
	if(mysql_affected_rows() == 0){ echo "<p>Nessun Elemento</p>"; } else {
	?>
    
    <table class="dati">
      <tr>
   <th style="width: 1%;"></th>
    <th>Data Invio</th>
   <th>Destinatario</th>
   <th>Descrizione</th>
   <th>Chiusura</th>
   <th></th>
   </tr>
          
 <?php
	

	
	while ($riga = mysql_fetch_assoc($risultato)) 
	{ 
	?> 
    
     
      <tr>
      <td><span class=\"Gletter\"></span></td>
       <td><?php echo mydate($riga['data_apertura']); ?></td>
      <td><?php echo $tipo_richiesta[$riga['tipo_richiesta']]; ?></td>
      <td><?php echo $riga['note']; ?></td>
      <td><input type="text" name="data_chiusura" class="updateField calendar" data-rel="<?php echo $riga['id']; ?>" value="<?php echo ($riga['data_chiusura'] == '0000-00-00') ? $riga['data_chiusura'] : mydate($riga['data_chiusura']); ?>" /></td>

  	  <td><a href="../mod_basic/action_elimina.php?gtx=<?php echo $tab_id; ?>&amp;unset=<?php echo $riga['id'];?>" title="Elimina"  onclick="return conferma_del();"><i class="fa fa-trash-o"></i></a></td>
</tr>

    <?php } } //Chiudo la Connessione	?>
    
 </table>
</body></html>
