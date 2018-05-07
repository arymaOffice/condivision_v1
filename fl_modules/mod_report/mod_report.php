  <h1>Report Transazioni</h1>



 

 <div class="filtri" id="filtri">
<h2>Filtri</h2>
 <form method="get" action="" id="fm_filtri">
  
     <div class="filter_box">   
PVR:     <select name="operatore" id="operatore">
            <option value="-1">Tutti</option>
            <?php 
              
             foreach($proprietario as $valores => $label){ // Recursione Indici di Categoria
            $selected = ($userid == $valores) ? " selected=\"selected\"" : "";
            echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; 
            }
         ?>
       </select></div>


   <div class="filter_box">   

       Periodo:
       <input type="text" name="data_da" onFocus="this.value='';" value="<?php  echo $data_da_t;  ?>"  class="calendar" size="10" /> 
 </div> <div class="filter_box">   
        <input type="text" name="data_a" onFocus="this.value='';" value="<?php  echo $data_a_t;  ?>" class="calendar" size="10" /> 
        
</div>

   <div class="filter_box">   

    <label>
      <input type="checkbox" value="1" name="espandi" style="display: inline; width: auto;" <?php  echo (isset($_GET['espandi'])) ? 'checked' : ' '; ?> /> Dettagliata</label>
</div>
       <input type="submit" value="Mostra" class="button" />

       </form>
 
      </div>



<script src="https://code.highcharts.com/highcharts.js"></script>







<?php
    

    $fatturato = 0;  
    $w = 0; 
    $record = '';    
    $expand = (!isset($_GET['espandi'])) ? '' : ', prodotto_id ';             
    $query = "SELECT $select FROM `$tabella` $tipologia_main GROUP BY srv $expand";
    $risultato = mysql_query($query, CONNECT_CLIENT);
    
    while ($riga = mysql_fetch_array($risultato)) 
    {
        $fatturato += $riga['euro'];    
        if($w > 0) $record .= ','; 
        $record .= "['".ucfirst(str_replace('000','00',$rif_operazione[$riga['prodotto_id']]))."',".numdec($riga['euro'],2)."]"; 
        $w = 1; 
    
    }
    echo '<h1 style="padding: 10px; text-align: center;">Totale transazioni &euro; '.numdec($fatturato,2).'</h1>';
    
    ?>

    <script type="text/javascript">

$(function () {
    


    $('#chart').highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false
        },
        title: {
            text: 'Vendite per operatore'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
                }
            }
        },
       /*legend: {
        align: 'left',
        layout: 'vertical',
        verticalAlign: 'top',
        x: 40,
        y: 40,
        labelFormatter: function() {
            return this.name + " " + ( Math.round(this.percentage * 100) / 100 ) + "%";
        }

        },*/
        series: [{
            type: 'pie',
            name: 'Vendite per operatore',
            data: [<?php echo $record ;?>]  
            }]
    });
});
    

        </script>

<div id="chart" style="min-width: 310px; width: 100%; height: 600px;  margin: 0 auto"></div>  


