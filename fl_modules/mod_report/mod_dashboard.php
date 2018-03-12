


<h1>General Report</h1>  

 
 
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
        $('#container').highcharts({
            chart: {
                type: 'bar'
            },
            title: {
                text: 'Dashboard Report'
            },
            subtitle: {
                text: 'Escape Group'
            },
            xAxis: {
                categories: ['Africa', 'America', 'Asia', 'Europe', 'Oceania'],
                title: {
                    text: null
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Population (millions)',
                    align: 'high'
                },
                labels: {
                    overflow: 'justify'
                }
            },
            tooltip: {
                valueSuffix: ' millions'
            },
            plotOptions: {
                bar: {
                    dataLabels: {
                        enabled: true
                    }
                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: -40,
                y: 100,
                floating: true,
                borderWidth: 1,
                backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor || '#FFFFFF'),
                shadow: true
            },
            credits: {
                enabled: false
            },
            series: [{
                name: 'Year 1800',
                data: [107, 31, 635, 203, 2]
            }, {
                name: 'Year 1900',
                data: [133, 156, 947, 408, 6]
            }, {
                name: 'Year 2008',
                data: [973, 914, 4054, 732, 34]
            }]
        });
    });
    

		</script>
	</head>
	<body>
<script src="./js/highcharts.js"></script>
<script src="./js/modules/exporting.js"></script>

<div id="chart" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
