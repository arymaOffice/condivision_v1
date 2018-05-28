


<h1>Main Report</h1>  

<div class="filtri">
<form method="get" action="" id="fm_filtri">
  
  <span style="position: relative;">
    <input type="hidden" value="recruiters" name="report" />

    
      from <input type="text" name="data_da"  value="<?php  echo $data_da_t;  ?>"  class="calendar" size="10" /> 
      to  <input type="text" name="data_a"value="<?php  echo $data_a_t;  ?>"  class="calendar" size="10" />
       <input type="submit" value="<?php echo SHOW; ?>" class="button" />

       </form>
     
      </div>
	 <table class="dati" summary="Dati" style=" width: 100%;">
       <tr>
        <th scope="col">Staff</th>
       <th scope="col">Created Vacancies</th>
       <th scope="col">Managed  Vacancies</th>
       <th scope="col">Interviews Booked</th>
       <th scope="col">Interviews Succesfoul</th>
       <th scope="col">Candidates Number</th>
     </tr>
      
       
<?php

	$tot_vacancy_new = 0;
	$tot_vacancy_open = 0;
	$tot_interview_booked = 0;
	$tot_interview_succesfoul = 0;
	$tot_candidates = 0;

	$query = "SELECT * FROM `fl_customers_cv` WHERE id != 1 AND (status_profilo BETWEEN 2 AND 3) AND (`data_aggiornamento` BETWEEN '$data_da' AND '$data_a');";
	$risultato = mysql_query($query, CONNECT);
	$tot_candidates = mysql_affected_rows();



foreach($recruiters as $chiave => $valore) {
	
	$vacancy_new = 0;
	$vacancy_open = 0;
	$interview_booked = 0;
	$interview_succesfoul = 0;
	

							
	$query = "SELECT * FROM `fl_vacancies` WHERE (`data_creazione` BETWEEN '$data_da' AND '$data_a') AND `operatore` = $chiave;";
	$risultato = mysql_query($query, CONNECT);
	$vacancy_new = mysql_affected_rows();
	$tot_vacancy_new += $vacancy_new;
	$query = "SELECT * FROM `fl_vacancies` WHERE vacancy_issue < 2 AND (`data_aggiornamento` BETWEEN '$data_da 00:00:00' AND '$data_a 23:59:00') AND `operatore` = $chiave;";
	$risultato = mysql_query($query, CONNECT);
	$vacancy_open = mysql_affected_rows();
	$tot_vacancy_open += $vacancy_open;
	
	$query = "SELECT * FROM `fl_interview_agenda` WHERE (`data_interview` BETWEEN '$data_da' AND '$data_a') AND `operatore` = $chiave;";
	$risultato = mysql_query($query, CONNECT);
	$interview_booked = mysql_affected_rows();
	$tot_interview_booked += $interview_booked;

	$query = "SELECT * FROM `fl_interview_agenda` WHERE interview_issue = 1 AND (`data_interview` BETWEEN '$data_da' AND '$data_a') AND `operatore` = $chiave;";
	$risultato = mysql_query($query, CONNECT);
	$interview_succesfoul = mysql_affected_rows();
	$tot_interview_succesfoul += $interview_succesfoul;

	

?>

	<?php 
	

	
			echo "<tr>";
			echo "<td><strong>".$proprietario[$chiave]."</strong></td>"; 
			echo "<td>".$vacancy_new."</td>"; 
			echo "<td>".$vacancy_open."</td>"; 
			echo "<td>".$interview_booked."</td>";			
			echo "<td>".$interview_succesfoul."</td>"; 
			echo "<td>".$tot_candidates."</td>"; 
		    echo "</tr>";
	
	
}	
echo "<tr class=\"total\">";
		echo "<td><strong>TOTAL</strong></td>"; 
			echo "<td>".$tot_vacancy_new."</td>"; 
			echo "<td>".$tot_vacancy_open."</td>"; 
			echo "<td>".$tot_interview_booked."</td>"; 
			echo "<td>".$tot_interview_succesfoul."</td>";
			echo "<td>".$tot_candidates."</td>"; 
			    echo "</tr>";
	
	
echo "</table>";
	

?>
