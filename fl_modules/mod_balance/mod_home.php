<?php
$saldo = array('Positivi','Negativi');
// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
$_SESSION['POST_BACK_PAGE'] = "https://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];;
?>
<h1>Registro Saldi</h1>   

 
 <div class="filtri" id="filtri">

 	<h2>Filtri</h2>



<form method="get" action="" id="fm_filtri">
  
  
      Affiliato: <select name="operatore" id="operatore"  class="select2" >
            <option value="-1">Tutti</option>
			<?php 
		    foreach($affiliato as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($userid == $valores) ? " selected=\"selected\"" : "";
		    echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; 
			}
		 ?>
       </select>Saldo  <select name="saldo" id="saldo"  class="select2" >
            <option value="-1">Tutti</option>
			<?php 
		    foreach($saldo as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($saldo_id == $valores) ? " selected=\"selected\"" : "";
		    echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; 
			}
		 ?>
       </select> Data    

       <input type="text" name="data_da" onFocus="this.value='';" value="<?php  echo $data_da_t;  ?>"  class="calendar" size="10" onChange="form.submit();" />
     
       <input type="submit" value="Mostra" class="button" />

       </form>
     
 
      </div>
      

<?php
	

	if(isset($_GET['ordine'])) { if(!is_numeric($_GET['ordine'])){ exit; } else { $ordine = $ordine_mod[$_GET['ordine']]; }}
	
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0);
						
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	
	$risultato = mysql_query($query, CONNECT);
	//echo $query;

 		
	?>

 
 
  
  	 <table class="dati" summary="Dati" style=" width: 100%;">
        <tr>
    
  <th style="width: 1%;"></th>
       <th scope="col">Utente</th>
       <th scope="col">Data</th>
       <th scope="col">Positivo</th>
       <th scope="col">Negativo</th>
       <th scope="col">Saldo Giornaliero</th>
      </tr>
	<?php 
	
	$i = 1;
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"7\">Nessun Record Inserito</td></tr>";		}
	$positivi = 0;
	$negativi = 0;
	$totaleSaldi = 0;

	while ($riga = mysql_fetch_array($risultato)) 
	{
			$positivo = 0;
			$negativo = 0;
			
			if($riga['saldo_iniziale'] >= 0){
			$positivo = numdec($riga['saldo_iniziale'],2);
			$positivi += $riga['saldo_iniziale'];
			} else {
			$negativo = numdec($riga['saldo_iniziale'],2);
			$negativi += $riga['saldo_iniziale'];
			}
			

			$day_before = date( 'Y-m-d', strtotime( $data_da . ' -1 day' ) );
			$query = "SELECT * FROM `$tabella` WHERE proprietario =  ".$riga['proprietario']." AND `data` = '$day_before' LIMIT 1";
			$rd = mysql_fetch_assoc(mysql_query($query, CONNECT));
			$precedente = $rd['saldo_iniziale'];
			$precedente = ($precedente != NULL) ? $riga['saldo_iniziale']-$precedente : 0;
			$totaleSaldi += $precedente;
			$precedente = numdec($precedente,2);

			$modifica_id = "<a data-fancybox-type=\"iframe\" class=\"fancybox_view\" href=\"mod_visualizza.php?&amp;id=".$riga['id']."\" title=\"Visualizza\"> DETTAGLI </a>"; 
			$colore = ($riga['saldo_iniziale']-$riga['saldo_finale'] < 0) ? "style=\" background: #FF3A17; color: #FFF;\"" : "style=\" background: green; color: #FFF;\"";

			echo "<td $colore><span class=\"Gletter\"></span></td>"; 
     		echo "<td>".$proprietario[$riga['proprietario']]."</td>";
			echo "<td>".mydate($riga['data'])."</td>"; 
			echo "<td> &euro; $positivo</td>"; 
			echo "<td> &euro; $negativo</td>"; 
			echo "<td> &euro; $precedente</td>"; 
		    echo "</tr>";
			
			
			
	}
	echo "<tr>
	<td colspan=\"3\"></td>
	<td> &euro;  ".numdec($positivi,2)."</td>
	<td> &euro;  ".numdec($negativi,2)."</td>
	<td> &euro;  ".numdec($totaleSaldi,2)."</td>
	</tr>";	
	echo "</table>";
	

?>	


<?php $start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,1); ?>
