


<h1>Call Center Report</h1>  

<div class="filtri">
<form method="get" action="" id="fm_filtri">
  <input type="hidden" name="report"  value="personal"  /> 
   Manager:   
<select name="operatore" id="operatore" style="width: 100px;" onChange="form.submit();">
           <option value="-1">All</option>
			<?php 
              
		     foreach($proprietario as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($userid == $valores) ? " selected=\"selected\"" : "";
			 echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; 
			}
		 ?>
       </select>

    
      from <input type="text" name="data_da"  value="<?php  echo $data_da_t;  ?>"  class="calendar" size="10" /> 
      to  <input type="text" name="data_a"value="<?php  echo $data_a_t;  ?>"  class="calendar" size="10" />
       <input type="submit" value="<?php echo SHOW; ?>" class="button" />

       </form>
     
      </div>
 
 
 	 <table class="dati" summary="Dati" style=" width: 100%;">
       <tr>
       <th scope="col">Potential Calls</th>
       <th scope="col">Potential Not Interested</th>
       <th scope="col">Meetings Taken</th>
       <th scope="col"  style=" background: #FDFFC9">Meetings Done</th>
       <th scope="col"  style=" background: #FDFFC9">Not Issued</th>
       <th scope="col"  style=" background: #FDFFC9">Contracts</th>
       <th scope="col"  style=" background: #FDFFC9">Pending</th>
       <th scope="col"  style=" background: #FDFFC9">Not interested</th>
       <th scope="col"  style=" background: #FDFFC9">Not show</th>
       <th scope="col"  style=" background: #75D174">Worth</th>

     </tr>


      
<?php

	
	$not_show = 0;
	$calls = 0;
	$calls_not_interested = 0;
	$calls_meetings = 0;
	$not_interested = 0;
	$contracts = 0;
	$meetings_booked = 0;
	$meetings = 0;
	$meetings_done = 0;
	$pending = 0;
	$incorso = 0;
	$fatturato = 0;
	$date = array();
							
	$query = "SELECT * FROM `fl_meeting_agenda` WHERE (`meeting_date` BETWEEN '$data_da' AND '$data_a') $user ORDER BY meeting_date ASC;";
	
	$risultato = mysql_query($query, CONNECT);
	
	while ($riga = mysql_fetch_array($risultato)) 
	{
	$meetings++;
	if($riga['issue'] == 2) $contracts++;
	if($riga['issue'] > 0 && $riga['issue'] != 3) $meetings_done++;
	if($riga['issue'] < 2 || $riga['issue'] == 6) $incorso++;
	if($riga['issue'] == 3) $not_show++;
	if($riga['issue'] == 4) $not_interested++;
	if($riga['issue'] == 5) $pending++;
	if(!isset($date[$riga['meeting_date']])) $date[$riga['meeting_date']] = $riga['meeting_date'];
	}
	
 	/*Chiamate fatte dall'account */
	$query2 = "SELECT * FROM `fl_potentials` WHERE `data_aggiornamento` BETWEEN '$data_da 00:00:00' AND '$data_a 23:59:00' $opt;";
	$risultato2 = mysql_query($query2, CONNECT);
	while ($riga2 = mysql_fetch_array($risultato2)) 
	{	
	$calls++;
	if($riga2['status_potential'] == 3) $calls_not_interested++;
	}
 
 	/*Pagamenti fatte dall'account */
	$query3 = "SELECT *,SUM(`importo`) AS `toto` FROM `fl_pagamenti`  WHERE valuta < 2 AND (status_pagamento = 4) AND causale = 1 AND `data_operazione` BETWEEN '$data_da' AND '$data_a' $prop;";
	$risultato3 = mysql_query($query3, CONNECT);
	while ($riga3 = mysql_fetch_array($risultato3)) 
	{	
	$fatturato = $riga3['toto'];
	}
	
	
	

	
			echo "<tr>";
			echo "<td>".$calls."</td>"; 
			echo "<td>".$calls_not_interested."</td>";
			echo "<td>".$meetings."</td>";		
 			echo "<td>".$meetings_done."</td>";		
			echo "<td>".$incorso."</td>";			
			echo "<td>".$contracts."</td>"; 
			echo "<td>".$pending."</td>"; 
			echo "<td>".$not_interested."</td>"; 
			echo "<td>".$not_show."</td>"; 
			echo "<td>".$fatturato."</td>"; 
			echo "</tr></table>";
	
	 		$meetings_done++;
			echo "<h2>Performance Meeting fissati/Contratti: ".numdec(($contracts/$meetings)*100,2)." % Meeting Eseguiti/Contratti: ".numdec(($contracts/$meetings_done)*100,2)." %</h2>";





?>
		<script type="text/javascript">
$(function () {
        $('#chart').highcharts({
            chart: {
                type: 'area'
            },
            title: {
                text: 'Performance Chart'
            },
            subtitle: {
                text: 'Period <?php echo $data_da.' > '.$data_a; ?>'
            },
            xAxis: {
				       categories: [''<?php foreach($date as $valore) echo ",'".$valore."'"; ?>],
	         minRange: 1,
	  		 minTickInterval: <?php echo count($date)/7; ?>, 	   
       		 allowDecimals: false
            
            },
            yAxis: {
                title: {
                    text: 'Value'
                },
                labels: {
                    formatter: function() {
                        return this.value;// / 1000 +'k';
                    }
                }
            },
            tooltip: {
                pointFormat: '{series.name} <b>{point.y:,.0f}</b>'
            },
            plotOptions: {
                area: {
                      marker: {
                        enabled: false,
                        symbol: 'circle',
                        radius: 2,
                        states: {
                            hover: {
                                enabled: true
                            }
                        }
                    }
                }
            },
            series: [
			
			
			{
                name: 'Meetings',
                data: [0<?php
				foreach($date as $valore) {
				$query = "SELECT * FROM `fl_meeting_agenda` WHERE (`meeting_date` = '$valore') $user;";
				$risultato = mysql_query($query, CONNECT);
				echo ','.mysql_affected_rows().'';
				}
				?> ]
            }, 
			
			{
                name: 'Contracts',
                data: [0<?php
				foreach($date as $valore) {
				$query = "SELECT * FROM `fl_meeting_agenda` WHERE (`meeting_date` = '$valore') $user AND issue = 2;";
				$risultato = mysql_query($query, CONNECT);
				echo ','.mysql_affected_rows().'';
				}
				?>]
            },
				
			
			]
        });
    });
    

		</script>
	</head>
	<body>
<script src="./js/highcharts.js"></script>
<script src="./js/modules/exporting.js"></script>

<div id="chart" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
