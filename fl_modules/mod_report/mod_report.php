  <h1>Report dei Feedback</h1>



<?php
 

$_SESSION['anno_fiscale'] = (isset($_SESSION['anno_fiscale'])) ? $_SESSION['anno_fiscale'] : date('Y');
if(isset($_GET['anno_fiscale'])) $_SESSION['anno_fiscale'] = check($_GET['anno_fiscale']);
$precedente = $_SESSION['anno_fiscale']-1;


$colors = array('#51A526','#BFD52B','#7E9924','#DA9B3D','#51A526');
$c = 0;

$color = $colors[0];  


$domande_report = array('domanda1'=>'Come giudica l\'accoglienza ricevuta *',
                 'domanda2'=>'Come ritiene le informazioni ricevute *',
                 'domanda3'=>'Pensa di prenotare presso il '.client,
                 'domanda5'=>'Come ha conosciuto il '.client);

?>       


<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>


<form action="" method="GET">
Anno selezionato: <select name="anno_fiscale" onchange="form.submit();">

<?php 

$pastYears = date('Y')-4;
$nextYears = date('Y')+3;

for($i=$pastYears;$i<=$nextYears;$i++) {
    $selected = ($_SESSION['anno_fiscale'] == $i) ? 'selected' : '';
    echo '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
}    
?>
</select>




<?php foreach($domande_report AS $key => $domanda) { 

    $risposte =  $key.'_risposte';

    ?>

<div id="<?php echo $key; ?>" style="min-width: 310px; max-width: 800px; height: 400px; margin: 0 auto"></div>

<script type="text/javascript">
Highcharts.chart('<?php echo $key; ?>', {
    chart: {
        type: 'bar',
    },
    title: {
        text: '<?php echo str_replace("*","", str_replace("'","\'", $domanda)); ?>'
    },
    subtitle: {
        text: '<?php echo $_SESSION['anno_fiscale'] ; ?>'
    },
    xAxis: {
        categories: [<?php foreach ($$risposte as $d => $value) { if(isset($x)) echo ',';  echo "'".$value."'";  $x = 1;   } unset($x); ?>],
        title: {
            text: null
        }
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Numero risposte ',
            align: 'high'
        },
        labels: {
            overflow: 'justify'
        }
    },
    tooltip: {
        valueSuffix: ' risposte'
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
        y: 80,
        floating: true,
        borderWidth: 1,
        backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
        shadow: true
    },
    credits: {
        enabled: false
    },
    series: [{
        name: 'Risposte nel <?php echo $_SESSION['anno_fiscale'] ; ?>',
        color: '<?php echo $colors[$c]; ?>',

        <?php 

        $dati = GQS('fl_feedback',$key.', count(*) AS tot','  YEAR(data_creazione) = '.$_SESSION['anno_fiscale'].'  GROUP BY '.$key.' ORDER BY '.$key.' ASC'); ?>

        data: [<?php foreach ($dati as $keys => $values) { if(isset($y)) echo ',';  echo $values["tot"];  $y = 1;   } unset($y); ?>]
    },
    {
        name: 'Risposte nel <?php echo $precedente; ?>',
        color: '#ccc',

        <?php 
        
        $dati = GQS('fl_feedback',$key.', count(*) AS tot',' YEAR(data_creazione) = '.$precedente.' GROUP BY '.$key.' ORDER BY '.$key.' ASC'); ?>

        data: [<?php foreach ($dati as $keys => $values) { if(isset($y)) echo ',';  echo $values["tot"];  $y = 1;   } unset($y); ?>]
    }
    ]
});
    

</script>


<?php  $c++; } ?>

<style type="text/css">

.table-row {
background: #f9f7f0;
padding: 10px;
margin: 5px 0;
}

.table-col {display: inline-table; }
.w30 {   width: 30%; }
.w70 {   width: 70%; }


</style>

<h1>Motivazioni per cui hanno scelto di non procedere alla prenotazione</h1>
<?php

   $dati = GQS('fl_feedback','domanda4 , count(*) AS tot','  YEAR(data_creazione) = '.$_SESSION['anno_fiscale'].'  GROUP BY domanda4 ORDER BY tot DESC'); 

 foreach ($dati as $keys => $values) { 
if($values["domanda4"] == '') $values["domanda4"] = "Non risposto";
  echo '<div class="table-row"><div class="table-col w70">'.$values["domanda4"].'</div><div class="table-col w30 "><h2>'.$values["tot"].'</h2></div></div>';    } 








   ?>
  

