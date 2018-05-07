  <h1>Report Transazioni</h1>



 
 <div class="filtri" id="filtri">
<h2> Filtri</h2>  
<form method="get" action="" id="fm_filtri">
  
   <div class="filter_box">   
   PVR: <select name="operatore" id="operatore">
            <option value="-1">Tutti</option>
            <?php 
              
             foreach($proprietario as $valores => $label){ // Recursione Indici di Categoria
            $selected = ($userid == $valores) ? " selected=\"selected\"" : "";
            echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; 
            }
         ?>
       </select></div>
  
     
   <div class="filter_box">   

   Anno :
   <select name="anno_fiscale">

<?php 

$_SESSION['anno_fiscale'] = (isset($_SESSION['anno_fiscale'])) ? $_SESSION['anno_fiscale'] : date('Y');
if(isset($_GET['anno_fiscale'])) $_SESSION['anno_fiscale'] = check($_GET['anno_fiscale']);



$pastYears = date('Y')-4;
$nextYears = date('Y')+3;

for($i=$pastYears;$i<=$nextYears;$i++) {
    $selected = ($_SESSION['anno_fiscale'] == $i) ? 'selected' : '';
    echo '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
}    
?>
</select></div>


   <div class="filter_box">   
<label ><input type="checkbox" value="1" name="confronta" onclick="$('#anno2').fadeToggle();" style="display: inline; width: auto; padding:0" <?php  echo (isset($_GET['confronta'])) ? 'checked' : ' '; ?> /> Confronta</label>
</div>

<select name="anno_fiscale2" id="anno2"  <?php  echo (isset($_GET['confronta'])) ? '' : 'style="display: none;"'; ?>>

<?php 

$_SESSION['anno_fiscale2'] = (isset($_SESSION['anno_fiscale2'])) ? $_SESSION['anno_fiscale2'] : (date('Y')-1);
if(isset($_GET['anno_fiscale2'])) $_SESSION['anno_fiscale2'] = check($_GET['anno_fiscale2']);



$pastYears = date('Y')-4;
$nextYears = date('Y')+3;

for($i=$pastYears;$i<=$nextYears;$i++) {
    $selected = ($_SESSION['anno_fiscale2'] == $i) ? 'selected' : '';
    echo '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
}    
?>
</select>

 &nbsp;

       <input type="submit" value="Mostra" class="button" />
       <input type="hidden" value="fatturati" name="report" />
       </form>
 
      </div>



<script src="https://code.highcharts.com/highcharts.js"></script>







<?php
    
    $colors = array('#68B828','#7C38BC','#40BBEA','#FFBA00','#CF4B50');
    $d = array();
    for($i = 1; $i < 13; $i++)  $d[] = $i;//date("Y-m-d", strtotime('-'. $i .' days'));
    $color = $colors[2];  
    $expand = '';

    $fatturato =  $fatturato2 = 0;  
    $w = 0; 
    $record = $record2 =  '';    
    $select = "MONTH(data_creazione) AS data,user,srv,prodotto_id,COUNT(*) as totale, SUM(amount) as euro";
   

    $tipologia_main = "WHERE id != 1";
    if($_SESSION['usertype'] > 0) $tipologia_main = "WHERE id != 1  AND user = ".$_SESSION['number']." ";
    if(isset($userid) && @$userid != -1 && $_SESSION['usertype'] == 0) {  $tipologia_main .= " AND user = $userid ";     }
    $tipologia_main .= " AND YEAR(data_creazione) = ".$_SESSION['anno_fiscale'];

    $query = "SELECT $select FROM `$tabella` $tipologia_main GROUP BY data $expand";
    $risultato = mysql_query($query, CONNECT_CLIENT);
    
    while ($riga = mysql_fetch_array($risultato)) 
    {
        $fatturato += $riga['euro'];    
        if($w > 0) $record .= ','; 
        $record .= numdec($riga['euro'],2); 
        $w = 1; 
    
    }


   

   
    if(isset($_GET['confronta'])) {
    $w = 0;
    $tipologia_main = "WHERE id != 1";
    if($_SESSION['usertype'] > 0) $tipologia_main = "WHERE id != 1  AND user = ".$_SESSION['number']." ";
    if(isset($userid) && @$userid != -1 && $_SESSION['usertype'] == 0) {  $tipologia_main .= " AND user = $userid ";     }
    $tipologia_main .= " AND YEAR(data_creazione) = ".($_SESSION['anno_fiscale2']);

    $query = "SELECT $select FROM `$tabella` $tipologia_main GROUP BY data $expand";
    $risultato = mysql_query($query, CONNECT_CLIENT);
    
    while ($riga = mysql_fetch_array($risultato)) 
    {
        $fatturato2 += $riga['euro'];    
        if($w > 0) $record2 .= ','; 
        $record2 .= numdec($riga['euro'],2); 
        $w = 1; 
    
    }
    }



    echo '<h1 style="padding: 10px; text-align: center;">Totale transazioni &euro; '.numdec($fatturato,2).'</h1>';
    
    ?>

    <script type="text/javascript">

$(function () {
    


    $('#chart').highcharts({
        chart: {
                type: 'bar'
        },
        title: {
            text: 'Fatturato'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        xAxis: {
                categories: [<?php foreach($d as $chiave => $valore) { if($chiave > 0) echo ','; echo "'".$mesi[$valore]."'"; } ?>],
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
                    '<td style="padding:0"><b>&euro; {point.y:.1f} </b></td></tr>',
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

        series: [{
            name: 'Fatturato <?php echo $_SESSION['anno_fiscale']; ?>',
            color: '#5D9A42',
            data: [<?php echo $record ;?>]  
            }
            <?php   if(isset($_GET['confronta'])) { ?>
            ,
            {
            name: 'Anno Precedente <?php echo ($_SESSION['anno_fiscale2']); ?>',
            data: [<?php echo $record2 ;?>]  
            }
            <?php } ?> 
            ]

    });
});
    

        </script>

<div id="chart" style="min-width: 310px; width: 100%; height: 600px;  margin: 0 auto"></div>  


