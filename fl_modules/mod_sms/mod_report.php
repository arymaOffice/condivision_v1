
<h1>Report SMS</h1>  


<div class="filtri" id="filtri">
<form method="get" action="" id="fm_filtri">
  
  <label>Template</label>
  <label>Data invio</label>  <input type="text" name="data_da" onChange="form.submit();" value="<?php  echo $data_da_t;  ?>"  class="calendar" size="10" /> 
  <input type="submit" value="<?php echo SHOW; ?>" class="button" />
  </form>
     
 </div>
      

       
<?php
	
	foreach($template as $chiave => $valore) {
	$query = "SELECT id , MONTH(`data_creazione`) AS mese, YEAR(`data_creazione`) AS anno, COUNT( `id` ) AS `tot` FROM `fl_sms` WHERE template = $chiave GROUP BY  YEAR(`data_creazione`), MONTH(`data_creazione`)";
	$risultato = mysql_query($query, CONNECT);
	if(mysql_affected_rows() > 0) {
	?>

 
 
  	 <table class="dati" summary="Dati" style=" width: 100%;">
        <tr>
     <th scope="col" style="width: 50%;">Template</th>
       <th scope="col"> Mese</th>
       <th scope="col">Inviati</th>
      </tr>
	<?php 


	
	while ($riga = mysql_fetch_array($risultato)) 
	{
		


	 $colore = "class=\"tab_green\"";  
		
			echo "<tr ><td>$valore</td>"; 
			echo "<td>".$mese[$riga['mese']]."/".$riga['anno']."</td>"; 
			echo "<td><strong>".$riga['tot']."</strong></td>"; 
				
		    echo "</tr>";
			
			
			
	}

	echo "</table>";
	
	}}
?>
