<?php
// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
?>

<div id="filtri" class="filtri"> 

<form method="get" action="" id="fm_filtri">
 <h2> Filtri</h2> 

   <label for="proprietario">ACCOUNT</label>
      <select name="proprietario" id="proprietario" onchange="form.submit();">
      
      <option value="-1">Mostra Tutti</option>
            
			<?php 
              
		    foreach($proprietario as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($proprietario_id == $valores) ? " selected=\"selected\"" : "";
			if($valores != 0){ echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; }
			}
		 ?>
       </select>

       
       <label for="approvato">STATO</label>
       <select name="approvato" id="approvato"  onchange="form.submit();">
         <option value="-1">Mostra Tutto</option>
           	<?php 
              
		    foreach($approvato as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($approvato_id == $valores) ? " selected=\"selected\"" : "";
			echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; 
			}
		 ?>
       </select>
		<label for="data_da">periodo da</label>
       <input name="data_da" id="data_da" value="<?php echo $data_da_t; ?>" size="20" type="text" class="calendar" > 
       <label for="data_a">a</label>
       <input name="data_a" id="data_a" value="<?php echo $data_a_t;  ?>" size="20" type="text"  class="calendar" >
               
    <input type="submit" value="Mostra" class="button" /> 
    </p>
</form>
</div>



<?php
	
     
	$_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];

	if(isset($_GET['ordine'])) { if(!is_numeric($_GET['ordine'])){ exit; } else { $ordine = $ordine_mod[$_GET['ordine']]; }}
	
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,1);
						
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	
	$risultato = mysql_query($query,CONNECT);
	

		
	?>
       


  
<table class="dati" summary="Dati">
      <tr>
              <th style="width: 1%;"></th>
  <th ><a href="./?ordine=0">Data Richiesta</a></th>
       <th>Oggetto | <a href="./?ordine=3">Operatore</a></th>
       <th >Costo</th>
       <th></th>
      <th></th>
      
        <th ></th>

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
		
			echo "<tr><td $colore></td>";

			echo "<td><h2>".mydate($riga['data_creazione'])."</h2><span class=\"msg gray\">".$categoria_mnt[$riga['categoria_mnt']]."</span>
			</td>";
			echo "<td><a href=\"mod_inserisci.php?id=".$riga['id']."\" title=\"".$riga['descrizione']."\">".strtoupper($riga['oggetto'])."</a>
			<br />".@$proprietario[$riga['proprietario']]." <i class=\"fa fa-".$approvato_icons[$riga['approvato']]."\"></i> ".$approvato[$riga['approvato']]."</td>";
			echo "<td>&euro; ".@numdec($saldo_parziale,2)."</td>";
			echo "<td><a href=\"mod_inserisci.php?id=".$riga['id']."\" title=\"Modifica\" > <i class=\"fa fa-search\"></i> </a></td>"; 
					echo "<td><a  href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=".$riga['id']."\" title=\"Elimina\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i></a></td>"; 


		    echo "</tr>";
	}


	echo "<tr style=\"height: 30px;\"><td colspan=\"11\"></td></tr>";

	 "<tr>"; 


				echo "<td></td>";
			echo "<td>Riepilogo</td>";
			echo "<td></td>";
			echo "<td style=\"background: #E8FFE8; font-weight: bold;\" colspan=\"3\">Spesa: &euro; ".numdec($saldo,2)."</td>";
	

		    echo "</tr>";

		echo "</table>";


?>

