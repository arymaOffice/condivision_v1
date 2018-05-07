<?php

// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}

$_SESSION['last_referrer'] = ROOT.$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];
$proprietario_id = $_SESSION['number'];
?>

<h1 style=" text-align: left;">Estratto Conto <?php echo $proprietario[$proprietario_id]; ?></h1>
  <?php 

	if(isset($_GET['ordine'])) { if(!is_numeric($_GET['ordine'])){ exit; } else { $ordine = $ordine_mod[$_GET['ordine']]; }}
	
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0);
						
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	
	$risultato = mysql_query($query, CONNECT);
	//echo $query;

 		
	?>
 <div class="filtri">
<form method="get" action="" id="fm_filtri">
  
        
       <select name="status_pagamento" id="status_pagamento">
            <option value="-1">Stato</option>
			<?php 
              
		     foreach($status_pagamento as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($status_pagamento_id == $valores) ? " selected=\"selected\"" : "";
		    echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; 
			}
		 ?>
       </select>
       
             <select name="metodo_di_pagamento" id="metodo_di_pagamento">
            <option value="-1">Metodo</option>
			<?php 
              
		     foreach($metodo_di_pagamento as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($metodo_di_pagamento_id == $valores) ? " selected=\"selected\"" : "";
		    echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; 
			}
		 ?>
       </select>

       creato tra il <input type="text" name="data_da" onFocus="this.value='';" value="<?php  echo $data_da_t;  ?>"  class="calendar" size="10" /> e il <input type="text" name="data_a" onFocus="this.value='';" value="<?php  echo $data_a_t;  ?>" class="calendar" size="10" /> 
        
       <input type="submit" value="Mostra" class="button" />

       </form>
     
      </div>
 
 
  
  	 <table class="dati" summary="Dati" style=" width: 100%;">
        <tr>
    
       <th scope="col">Stato</th>
       <th scope="col">Data</th>
       <th scope="col">Operazione</th>
              <th scope="col">Estremi</th>

       <th scope="col">Accrediti</th>
       <th scope="col">Addebiti</th>

    <th scope="col"></th> 
      </tr>
	<?php 
	
	$i = 1;
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"9\">Nessun Record Inserito</td></tr>";		}
	$tot_res = 0;
	$incomplete = 0;
		$dare = 0;
	$avere = 0;

	while ($riga = mysql_fetch_array($risultato)) 
	{
		
						$dare += $riga['dare'];
			$avere += $riga['avere'];
			$cred = 'c-red';
			$cgreen = 'c-green';

			
		if($riga['status_pagamento'] == 0){
			$elimina_id = "";  
			$modifica_id = "<a data-fancybox-type=\"iframe\" class=\"fancybox_view\" href=\"mod_visualizza.php?&amp;id=".$riga['id']."\" title=\"Visualizza\"> <i class=\"fa fa-search\"></i> </a>"; 
			$colore = "class=\"gray\"";
			$cred = 'c-gray';
			$cgreen = 'c-gray';

			} else if($riga['status_pagamento'] == 1){
			$elimina_id = "";  
			$modifica_id = "<a data-fancybox-type=\"iframe\" class=\"fancybox_view\" href=\"mod_visualizza.php?&amp;id=".$riga['id']."\" title=\"Visualizza\"> <i class=\"fa fa-search\"></i> </a>"; 
			$colore = "class=\"tab_green\"";
			} else {
			$elimina_id = "";  
			$modifica_id = "<a data-fancybox-type=\"iframe\" class=\"fancybox_view\" href=\"mod_visualizza.php?&amp;id=".$riga['id']."\" title=\"Visualizza\"> <i class=\"fa fa-search\"></i> </a>"; 
			$colore = "class=\"gray\"";
			$cred = 'c-gray';
			$cgreen = 'c-gray';

			}
		$pagamento = ($riga['causale'] != 85) ? $metodo_di_pagamento[$riga['metodo_di_pagamento']] : '';
	
			echo "<tr><td $colore>".$status_pagamento[$riga['status_pagamento']]."</td>"; 
			echo "<td>".mydatetime($riga['data_creazione'])."</td>"; 
			echo "<td>".$causale[$riga['causale']]."<br>".$riga['rif_operazione']."</td>";	
			echo "<td><strong>".$pagamento."</strong><br>".$riga['estremi_del_pagamento']."</td>";	
			echo "<td class=\"".$cgreen."\">";
			if($riga['dare'] > 0) echo " &euro; ".numdec($riga['dare'],3); 
			echo "</td><td class=\"".$cred."\">";
			if($riga['avere'] > 0) echo " &euro; ".numdec($riga['avere'],3); 
			echo "</td>"; 

			
			echo "<td>$modifica_id</td>";
			
		    echo "</tr>";
			
			
			
	}

	echo "<tr><td colspan=\"3\"></td><td>Totale periodo: </td><td class=\"c-green\">&euro; ".numdec($dare,3)."</td><td class=\"c-red\">&euro; ".numdec($avere,3)."</td></tr>";
	echo "<tr><td colspan=\"7\"></td></tr>";
	echo "<tr><td colspan=\"7\"><br><br></td></tr>";
	echo "<tr><td colspan=\"7\"></td></tr>";
	$anagra = get_anagrafica_account($proprietario_id) ;
	$fido = get_fido($anagra['anagrafica']);

	$saldo = get_saldo($proprietario_id);
	$colore = ($saldo > 0) ? 'green' : 'red'; 
	if($saldo == 0) $colore = 'gray'; 

	echo "<tr><td colspan=\"3\"></td>
	<td>Saldo Disponibile: </td>
	<td colspan=\"2\" class=\"$colore\" style=\"font-size: larger; text-align: center;\"><strong>&euro; ".numdec($saldo,3)."</strong></td>
	<td colspan=\"3\"></td></tr>";
	
	$saldofoglio = get_saldo($proprietario_id,1);

	echo "<tr style=\"background: white;\"><td colspan=\"3\"></td>
	<td>Saldo Contabile: </td>
	<td colspan=\"2\">&euro; ".numdec($saldofoglio,3)."</td>
	<td colspan=\"3\"></td></tr>";


if($fido > 0 )echo "<tr><td colspan=\"3\"></td><td>Fido Concesso: </td><td class=\"\">&euro; ".numdec($fido,2)."</td><td></td></tr>";
	
	

?>	


<?php $start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,1); ?>
</div>
</body>
