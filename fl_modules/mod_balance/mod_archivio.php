<?php
$saldo = array('Positivi','Negativi');
// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
$_SESSION['POST_BACK_PAGE'] = "https://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];;
?>
<h1>Archivio Operazioni contabili</h1>   

 
 <div class="filtri" id="filtri"> 	<h2>Filtri</h2>

<form method="get" action="" id="fm_filtri">
  
 
    <p>   <input type="hidden" value="17" name="action" />

       Periodo dal  <input type="text" name="data_da" onFocus="this.value='';" value="<?php  echo $data_da_t;  ?>"  class="calendar" size="10" />
           al   <input type="text" name="data_a" onFocus="this.value='';" value="<?php  echo $data_a_t;  ?>"  class="calendar" size="10" />
      </p> 
   
       <input type="submit" value="Mostra" class="button" />

       </form>
     
 
      </div>
      

 <p>Registrazioni contabili dal <?php  echo $data_da_t;  ?> al <?php  echo $data_a_t;  ?></p> 

<?php
	
	$positivi = 0;
	$negativi = 0;
				
	$query = "SELECT *,SUM(totale) as tot, SUM(dare) as tot_dare, SUM(avere) as tot_avere FROM `fl_operazioni_contabili` WHERE data_creazione BETWEEN '$data_da 00:00:00' AND '$data_a 23:59:59' GROUP BY causale;";
	
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
			$positivi += $riga['tot_dare'];
			$negativi += $riga['tot_avere'];
			if($riga['causale'] == 85) 	$dettagli = '<a href="#operazioni" class="fancybox_view">OPERAZIONI</a>';
			if($riga['causale'] == 84) 	$dettagli = '<a href="#depositi" class="fancybox_view">OPERAZIONI</a>';
			if($riga['causale'] == 86) 	$dettagli = '<a href="#prelievi" class="fancybox_view">OPERAZIONI</a>';
			if($riga['causale'] == 88) 	$dettagli = '<a href="#bonus" class="fancybox_view">OPERAZIONI</a>';
			$totali  += $riga['tot'];



			echo "<td><span class=\"Gletter\"></span></td>"; 
			echo "<td>".$causale[$riga['causale']]."</td>"; 
			echo "<td>".$riga['tot']."</td>"; 
			echo "<td> &euro; ".numdec($riga['tot_dare'],2)."</td>"; 
			echo "<td> &euro; ".numdec($riga['tot_avere'],2)."</td>"; 
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






<div id="depositi" style="display: none;">
<?php
	

				
	 $query = "SELECT metodo_di_pagamento,causale, SUM(dare) as tot_dare, SUM(avere) as tot_avere FROM `fl_pagamenti` WHERE causale = 84 AND data_creazione BETWEEN '$data_da 00:00:00' AND '$data_a 23:59:59' GROUP BY metodo_di_pagamento;";
	
	$risultato = mysql_query($query, CONNECT);
 	?>

 
 <h2>Dettaglio Depositi </h2>
 <p>Periodo dal <?php  echo $data_da_t;  ?> al <?php  echo $data_a_t;  ?></p> 
  	 <table class="dati" summary="Dati" style=" width: 100%;">
        <tr>
    
  <th style="width: 1%;"></th>
       <th scope="col">Conto</th>
        <th scope="col">Operazione</th>
       <th scope="col">Positivo</th> 
       <th scope="col">Negativo</th>

      </tr>
	<?php 
	
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"7\">Nessun Record Inserito</td></tr>";		}
	
	$tot_dare = 0;
	$tot_avere = 0;
	
	while ($riga = mysql_fetch_array($risultato)) 
	{
			$tot_dare += $riga['tot_dare'];
			$tot_avere += $riga['tot_avere'];


			echo "<td><span class=\"Gletter\"></span></td>"; 
			echo "<td style=\"width: 500px;\">".$metodo_di_pagamento[$riga['metodo_di_pagamento']]."</td>"; 
			echo "<td style=\"width: 500px;\">".$causale[$riga['causale']]."</td>"; 
			echo "<td>".numdec($riga['tot_dare'],2)."</td>"; 
			echo "<td> &euro; ".numdec($riga['tot_avere'],2)."</td>"; 

		    echo "</tr>";
			
			
			
	}

	echo "<tr>
	<td colspan=\"3\"></td>
	<td><strong> &euro;  ".numdec($tot_dare,2)."</strong></td>	
	<td><strong> &euro;  ".numdec($tot_avere,2)."</strong></td>
	</tr>";	
	echo "</table>";
	

?>	
</table>

</div>


<div id="prelievi" style="display: none;">
<?php
	

				
	 $query = "SELECT metodo_di_pagamento,causale, SUM(dare) as tot_dare, SUM(avere) as tot_avere FROM `fl_pagamenti` WHERE causale = 86 AND data_creazione BETWEEN '$data_da 00:00:00' AND '$data_a 23:59:59' GROUP BY metodo_di_pagamento;";
	
	$risultato = mysql_query($query, CONNECT);
 	?>

 
 <h2>Dettaglio Prelievi </h2>
 <p>Periodo dal <?php  echo $data_da_t;  ?> al <?php  echo $data_a_t;  ?></p> 
  	 <table class="dati" summary="Dati" style=" width: 100%;">
        <tr>
    
  <th style="width: 1%;"></th>
       <th scope="col">Conto</th>
        <th scope="col">Operazione</th>
       <th scope="col">Positivo</th> 
       <th scope="col">Negativo</th>

      </tr>
	<?php 
	
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"7\">Nessun Record Inserito</td></tr>";		}
	
	$tot_dare = 0;
	$tot_avere = 0;
	
	while ($riga = mysql_fetch_array($risultato)) 
	{
			$tot_dare += $riga['tot_dare'];
			$tot_avere += $riga['tot_avere'];


			echo "<td><span class=\"Gletter\"></span></td>"; 
			echo "<td style=\"width: 500px;\">".$metodo_di_pagamento[$riga['metodo_di_pagamento']]."</td>"; 
			echo "<td style=\"width: 500px;\">".$causale[$riga['causale']]."</td>"; 
			echo "<td>".numdec($riga['tot_dare'],2)."</td>"; 
			echo "<td> &euro; ".numdec($riga['tot_avere'],2)."</td>"; 

		    echo "</tr>";
			
			
			
	}

	echo "<tr>
	<td colspan=\"3\"></td>
	<td><strong> &euro;  ".numdec($tot_dare,2)."</strong></td>	
	<td><strong> &euro;  ".numdec($tot_avere,2)."</strong></td>
	</tr>";	
	echo "</table>";
	

?>	
</table>

</div>


<div id="bonus" style="display: none;">
<?php
	

				
	 $query = "SELECT metodo_di_pagamento,causale, SUM(dare) as tot_dare, SUM(avere) as tot_avere FROM `fl_pagamenti` WHERE causale = 88 AND data_creazione BETWEEN '$data_da 00:00:00' AND '$data_a 23:59:59' GROUP BY metodo_di_pagamento;";
	
	$risultato = mysql_query($query, CONNECT);
 	?>

 
 <h2>Dettaglio Bonus </h2>
 <p>Periodo dal <?php  echo $data_da_t;  ?> al <?php  echo $data_a_t;  ?></p> 
  	 <table class="dati" summary="Dati" style=" width: 100%;">
        <tr>
    
  <th style="width: 1%;"></th>
       <th scope="col">Conto</th>
        <th scope="col">Operazione</th>
       <th scope="col">Positivo</th> 
       <th scope="col">Negativo</th>

      </tr>
	<?php 
	
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"7\">Nessun Record Inserito</td></tr>";		}
	
	$tot_dare = 0;
	$tot_avere = 0;
	
	while ($riga = mysql_fetch_array($risultato)) 
	{
			$tot_dare += $riga['tot_dare'];
			$tot_avere += $riga['tot_avere'];


			echo "<td><span class=\"Gletter\"></span></td>"; 
			echo "<td style=\"width: 500px;\">".$metodo_di_pagamento[$riga['metodo_di_pagamento']]."</td>"; 
			echo "<td style=\"width: 500px;\">".$causale[$riga['causale']]."</td>"; 
			echo "<td>".numdec($riga['tot_dare'],2)."</td>"; 
			echo "<td> &euro; ".numdec($riga['tot_avere'],2)."</td>"; 

		    echo "</tr>";
			
			
			
	}

	echo "<tr>
	<td colspan=\"3\"></td>
	<td><strong> &euro;  ".numdec($tot_dare,2)."</strong></td>	
	<td><strong> &euro;  ".numdec($tot_avere,2)."</strong></td>
	</tr>";	
	echo "</table>";
	

?>	
</table>

</div>





<div id="operazioni" style="display: none;">
<?php
	

				
	$query = "SELECT *, SUM(totale) as tot, SUM(dare) as tot_dare, SUM(avere) as tot_avere FROM `fl_operazioni_contabili` WHERE causale = 85 AND data_creazione BETWEEN '$data_da 00:00:00' AND '$data_a 23:59:59' GROUP BY operazione;";
	
	$risultato = mysql_query($query, CONNECT);
 	?>

 
 <h2>Dettaglio Operazioni Periodo</h2>
 <p>Periodo dal <?php  echo $data_da_t;  ?> al <?php  echo $data_a_t;  ?></p> 
  	 <table class="dati" summary="Dati" style=" width: 100%;">
        <tr>
    
  <th style="width: 1%;"></th>
       <th scope="col">Operazione</th>
          <th scope="col">Totale</th> 
       <th scope="col">Negativo</th>
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
			echo "<td style=\"width: 500px;\">".$riga['operazione']."</td>"; 
			echo "<td>".$riga['tot']."</td>"; 
			echo "<td> &euro; ".numdec($riga['tot_avere'],2)."</td>"; 
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
</div>