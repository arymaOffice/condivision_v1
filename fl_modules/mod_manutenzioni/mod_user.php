<?php

// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}

?>

<h1><span class="intestazione">Registro Manutenzioni <?php echo $proprietario[$_SESSION['number']]; ?></span></h1>
<?php
	

	if(isset($_GET['ordine'])) { if(!is_numeric($_GET['ordine'])){ exit; } else { $ordine = $ordine_mod[$_GET['ordine']]; }}
	
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0);
						
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	
	$risultato = mysql_query($query,CONNECT);
	
	
	
	?>
       

   <form method="get" action="" id="sezione_select">
    <p>STATO RICHIESTE: 
     <select name="approvato" id="approvato"  onchange="form.submit();">
         <option value="-1">Mostra Tutto</option>
           	<?php 
              
		    foreach($approvato as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($approvato_id == $valores) ? " selected=\"selected\"" : "";
			echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; 
			}
		 ?>
       </select>
       </p>
</form>
<table class="dati" summary="Dati">
      <tr>
              <th>Urgenza</th>
              
<th>Data Richiesta</th>
        <th>Oggetto</th>
        <th>Costo</th>
         <th>Azioni</th>
        <th></th>

 
      </tr>
	  
	<?php 
	
	$i = 1;
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"7\">Nessun Record Inserito</td></tr>";		}
	
	$entrate = 0;
	$uscite = 0;
	$saldo = 0;
	$saldo_parziale = 0;
	

	
	while ($riga = mysql_fetch_array($risultato)) 
	{
	if(trim($riga['note']) != ""){ $note = "*"; } else { 	$note = ""; }
			
			$saldo_parziale = 0.00+$riga['costo_consuntivo'];
			//$entrate += $riga['entrate'];
			//$uscite += $riga['uscite'];
			$saldo = $saldo+$saldo_parziale;
				$urgenza = get_parametri(0,$riga['categoria_mnt']);
			if($urgenza == 0 ) $colore = "class=\"tab_green\""; 
			if($urgenza == 1 ) $colore = "class=\"tab_orange\""; 
			if($urgenza == 2 ) $colore = "class=\"tab_red\""; 
			if($urgenza == 3 ) $colore = "class=\"tab_red\""; 
			if($urgenza == 4 ) $colore = "class=\"tab_red\""; 
			if($urgenza == 5 ) $colore = "class=\"tab_red\""; 
		
			echo "<tr><td $colore><span class=\"Gletter\"></span></td>";
			echo "<td><h1 style=\"margin-bottom: 5px;\">".mydate($riga['data_creazione'])."</h1><span class=\"msg green\">".$categoria_mnt[$riga['categoria_mnt']]."</span>
			</td>";
			echo "<td><a href=\"mod_inserisci.php?id=".$riga['id']."\" title=\"".$riga['descrizione']."\">".strtoupper($riga['oggetto'])."</a>
			<br /><i class=\"fa fa-".$approvato_icons[$riga['approvato']]."\"></i> ".$approvato[$riga['approvato']]."</td>";
			echo "<td>&euro; ".@numdec($saldo_parziale,2)."</td>";
			
			if($riga['approvato'] < 3) {
			echo "<td><a href=\"mod_inserisci.php?id=".$riga['id']."\" title=\"Modifica\"> <i class=\"fa fa-search\"></i> </a></td>";
			echo "<td><a  href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=".$riga['id']."\" title=\"Elimina\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i></a></td>";
		    } else {
			echo "<td>--</td>";
			echo "<td>--</td>";
		  	}
				
		    echo "</tr>";
	}
	

	echo "<tr style=\"height: 30px;\"><td colspan=\"11\"></td></tr>";
	
		if($i==1){ $i=0; echo "<tr>"; } else { $i=1; echo "<tr class=\"alternate\">"; }		
			
				
			echo "<td>Riepilogo</td>";
			echo "<td>Interventi: ".mysql_affected_rows()."</td>";
			echo "<td></td>";
			echo "<td>&euro; ".numdec($saldo,2)."</td>";
			echo "<td></td>";
			echo "<td style=\"background: #E8FFE8; font-weight: bold;\" colspan=\"3\">Spesa: &euro; ".numdec($saldo,2)."</td>";
			
				
		    echo "</tr>";
	

	

?>
</table>
