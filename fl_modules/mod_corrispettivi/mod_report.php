  <?php
 
 if($proprietario_id > 1) {
$_SESSION['anno_fiscale'] = (isset($_SESSION['anno_fiscale'])) ? $_SESSION['anno_fiscale'] : date('Y');
$precedente = $_SESSION['anno_fiscale']-1;

$propFilter = (isset($proprietario_id)) ? 'AND proprietario = '.$proprietario_id : '';
$accountFilter = (isset($proprietario_id)) ? 'AND account_id = '.$proprietario_id : '';

$colors = array('#51A526','#BFD52B','#51A526','#BFD52B','#BFD52B');
$d = array();
for($i = 1; $i < 13; $i++)  $d[] = $i;//date("Y-m-d", strtotime('-'. $i .' days'));

$color = $colors[0];  


$correnteMese = date('m');

?>       


<script type="text/javascript">
$(function () {
        $('#dati').highcharts({
            chart: {
                type: 'area'
            },
            title: {
                text: 'Andamento fatturato <?php echo $proprietario[$proprietario_id]; ?>'
            },
            subtitle: {
                text: 'Anno <?php echo  $_SESSION['anno_fiscale']; ?>'
            },
            xAxis: {
    			 categories: [<?php foreach($d as $chiave => $valore) { if($chiave > 0) echo ','; echo "'".$mese[$valore]."'"; } ?>],
				//minRange: 3,
	  			//minTickInterval: 7 	
            },
            yAxis: {
                min: 0,
				
                title: {
                      text: ''
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y:.1f} </b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
           

		series: 
			[
				{
                name: 'Obiettivi <?php echo $_SESSION['anno_fiscale']; ?>',
                color: '##F6CD40',
                data: [ 
                <?php 
                $dati = GQD('fl_budget_values','*','id != 1 AND year = \''.$_SESSION['anno_fiscale'].'\' '.$accountFilter);
                foreach($d as $chiave => $valore) {
                if($chiave > 0) echo ',';
                $chiave++;
                echo  ($dati['m'.$chiave] > 0) ? $dati['m'.$chiave] : 0; 
                }

                ?>]                
                },{
               name: '<?php echo $_SESSION['anno_fiscale']; ?>',
               color: '#255396',
               data: [ 
                <?php foreach($d as $chiave => $valore) { 
                if($valore <= $correnteMese) {
                if($chiave > 0) echo ',';	
                $dati = GQD('fl_corrispettivi','SUM(euro) as tot','id != 1  '.$propFilter.' AND MONTH(`data_corrispettivo`) = '.$valore.' AND YEAR(`data_corrispettivo`) = '.$_SESSION['anno_fiscale']); 
                echo  ($dati['tot'] > 0) ? ($dati['tot']/1.1) : 0;
                }}  ?>]                
            	}, {
                name: '<?php echo $precedente ; ?>',
                color: '#CADDF9',
                data: [ 
                <?php foreach($d as $chiave => $valore) {
                if($valore <= $correnteMese) { 
                
                if($chiave > 0) echo ',';
                $dateFilter = ($valore == $correnteMese) ? 'AND DAY(`data_corrispettivo`) < '.date('d') : '';	
                $dati = GQD('fl_corrispettivi','SUM(euro) as tot','id != 1 '.$propFilter.'  '.$dateFilter.' AND MONTH(`data_corrispettivo`) = '.$valore.' AND YEAR(`data_corrispettivo`) = '.$precedente); 
                echo  ($dati['tot'] > 0) ? ($dati['tot']/1.1) : 0; 
                }} ?>]                
            	}

            	
            ]


        });
		

    	
	
    });
    

</script>


<div id="dati" style=" width: 100%;  padding: 0; height: 300px; margin: 0 0;"></div>


<?php }

/*   $dateFilter = ($valore == $correnteMese) ? 'AND DAY(`data_corrispettivo`) < '.date('d') : '';    
                $dati = GQD('fl_corrispettivi','SUM(euro) as tot','id != 1 '.$propFilter.'  '.$dateFilter.' AND MONTH(`data_corrispettivo`) = '.$valore.' AND YEAR(`data_corrispettivo`) = '.$precedente); 
            echo 'SELECT SUM(euro) as tot  FROM fl_corrispettivi WHERE id != 1 '.$propFilter.'  '.$dateFilter.' AND MONTH(`data_corrispettivo`) = '.$valore.' AND YEAR(`data_corrispettivo`) = '.$precedente;
            echo $dati['tot']. " TEST IN CORSO";*/
             ?>