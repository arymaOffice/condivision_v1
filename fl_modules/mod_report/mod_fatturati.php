


<h1>Call Center Report</h1>  

<div class="filtri">
<form method="get" action="" id="fm_filtri">
  <input type="hidden" name="report"  value="fatturati"  /> 
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
       <th scope="col"  style=" background: #75D174">Worth</th>

     </tr>


      
<?php

	
	$fatturato = 0;
	$date = array();
							

 	/*Pagamenti fatte dall'account */
	$query3 = "SELECT * FROM `fl_pagamenti` WHERE valuta < 2 AND (status_pagamento = 4) AND causale = 1 AND `data_operazione` BETWEEN '$data_da' AND '$data_a' $prop;";
	$risultato3 = mysql_query($query3, CONNECT);
	while ($riga = mysql_fetch_array($risultato3)) 
	{	
	$fatturato += $riga['importo'];
	if(!isset($date[$riga['data_operazione']])) $date[$riga['data_operazione']] = $riga['data_operazione'];
	}
	
	
	

	
			echo "<tr>";
			echo "<td>".$fatturato."</td>"; 
			echo "</tr></table>";
	




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
                name: 'Payments',
                data: [0<?php
				foreach($date as $valore) {
				$query = "SELECT *,SUM(`importo`) AS `toto` FROM `fl_pagamenti` WHERE valuta < 2 AND (status_pagamento = 4) AND causale = 1 AND `data_operazione` = '$valore' $prop;";
				$risultato = mysql_query($query, CONNECT);
				while ($riga3 = mysql_fetch_array($risultato)) 	{	$fatturato = $riga3['toto']; }

				echo ','.$fatturato.'';
				}
				?>]
            }
			
			]
        });
    });
    

		</script>
	</head>
	<body>
<script src="./js/highcharts.js"></script>
<script src="./js/modules/exporting.js"></script>

<div id="chart" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
