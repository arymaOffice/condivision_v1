<?php
$saldo = array('Positivi','Negativi');
// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
$_SESSION['POST_BACK_PAGE'] = "https://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];;
?>
<h1>Operazioni contabili giornaliere</h1>   

 
 <div class="filtri" id="filtri"> 	<h2>Filtri</h2>

<form method="get" action="" id="fm_filtri">
  
 <p>
       <input type="hidden" value="18" name="action" />

       Periodo dal  <input type="text" name="data_da" onFocus="this.value='';" value="<?php  echo $data_da_t;  ?>"  class="calendar" size="10" />
           al   <input type="text" name="data_a" onFocus="this.value='';" value="<?php  echo $data_a_t;  ?>"  class="calendar" size="10" />
       
   </p>
       <input type="submit" value="Mostra" class="button" />

       </form>
     
 
      </div>
      

<h2>Operazioni Globali</h2>
 <p>Periodo dal <?php  echo $data_da_t;  ?> al <?php  echo $data_a_t;  ?></p> 

<?php
	
	$positivi = 0;
	$negativi = 0;
				
	$query = "SELECT *, COUNT(*) as tot, SUM(dare) as tot_dare, SUM(avere) as tot_avere FROM `fl_pagamenti` WHERE data_creazione BETWEEN '$data_da 00:00:00' AND '$data_a 23:59:59' AND status_pagamento = 1 GROUP BY causale;";
	
	$risultato = mysql_query($query, CONNECT);
 	?>

 
 
  
  	 <table class="dati" summary="Dati" style=" width: 100%;">
        <tr>
    
  <th style="width: 1%;"></th>
       <th scope="col">Operazione</th>
          <th scope="col">Totale</th> 
       <th scope="col">Positivo</th>
       <th scope="col">Negativo</th>
       <th scope="col">Dettagli</th>   
      </tr>
	<?php 
	
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"7\">Nessun Record Inserito</td></tr>";		}
	
	$positivi = 0;
	$negativi = 0;
		$totali = 0;

	while ($riga = mysql_fetch_array($risultato)) 
	{
			$dettagli = '';
			$positivi += $riga['tot_avere'];
			$negativi += $riga['tot_dare'];
			if($riga['causale'] == 85) 	$dettagli = '<a href="#operazioni" class="fancybox_view">OPERAZIONI</a>';
			$totali  += $riga['tot'];



			echo "<td><span class=\"Gletter\"></span></td>"; 
			echo "<td>".$causale[$riga['causale']]."</td>"; 
			echo "<td>".$riga['tot']."</td>"; 
			echo "<td> &euro; ".$riga['tot_avere']."</td>"; 
			echo "<td> &euro; ".$riga['tot_dare']."</td>"; 
			echo "<td>$dettagli</td>";
		    echo "</tr>";
			
			
			
	}

	echo "<tr>
	<td colspan=\"2\"></td>
			<td><strong>$totali</strong></td>

	<td> &euro;  ".numdec($positivi,2)."</td>
	<td> &euro;  ".numdec($negativi,2)."</td>
	<td></td>
	<td></td></tr>";	
	echo "</table>";
	

?>	

<div id="operazioni" style="display: none;">
<?php
	

				
	$query = "SELECT *, COUNT(*) as tot, SUM(dare) as tot_dare, SUM(avere) as tot_avere FROM `fl_pagamenti` WHERE causale = 85 AND data_creazione BETWEEN '$data_da 00:00:00' AND '$data_a 23:59:59' AND status_pagamento = 1 GROUP BY rif_operazione;";
	
	$risultato = mysql_query($query, CONNECT);
 	?>

 
 <h2>Dettaglio Operazioni Giornaliere</h2>
 <p>Periodo dal <?php  echo $data_da_t;  ?> al <?php  echo $data_a_t;  ?></p> 
  	 <table class="dati" summary="Dati" style=" width: 100%;">
        <tr>
    
  <th style="width: 1%;"></th>
       <th scope="col">Operazione</th>
          <th scope="col">Totale</th> 
       <th scope="col">Positivo</th>
             <th scope="col"></th>  
       <th scope="col">Dettagli</th>   
      </tr>
	<?php 
	
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"7\">Nessun Record Inserito</td></tr>";		}
	
	$positivi = 0;
	$negativi = 0;
	$totali = 0;
	
	while ($riga = mysql_fetch_array($risultato)) 
	{
			$positivi += $riga['tot_avere'];
			$negativi += $riga['tot_avere'];
			$totali  += $riga['tot'];

			echo "<td><span class=\"Gletter\"></span></td>"; 
			echo "<td>".$riga['rif_operazione']."</td>"; 
			echo "<td>".$riga['tot']."</td>"; 
			echo "<td> &euro; ".$riga['tot_avere']."</td>"; 
			echo "<td></td>";
			echo "<td></td>";
		    echo "</tr>";
			
			
			
	}

	echo "<tr>
	<td colspan=\"2\"></td>
		<td><strong>$totali</strong></td>
	
	<td><strong> &euro;  ".numdec($positivi,2)."</strong></td>
	<td></td>
	<td></td></tr>";	
	echo "</table>";
	

?>	
</table>