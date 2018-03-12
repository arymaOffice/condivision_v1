<?php 

require_once('../../fl_core/autentication.php');
include('../../fl_core/dataset/items_rel.php');
require('../../fl_core/class/ARY_dataInterface.class.php');
$data_set = new ARY_dataInterface();
$anagrafica = $data_set->data_retriever('fl_anagrafica','cognome,nome','WHERE id > 1 AND tipo_profilo != 2','cognome ASC');
unset($chat);
$categoria = array('Conoscenze','Capacità/Abilità','Caratteristiche');
$livello_atteso = array('A','B','C');
$tab_id = 84;
$persona_id = check($_GET['PerID']);
$parent_id = GRD('fl_persone',$persona_id);
$valutazione = array(0=>'Non valutato','1'=>1,'2'=>2,'3'=>3,'4'=>4,'5'=>5);
$a0=$a1=$a2=$a3=$a4=$a5= 0;

include("../../fl_inc/headers.php");

?>
 
<body style=" background: rgb(241, 241, 241) none repeat scroll 0% 0%; text-align: left; padding: 20px;">

<?php if($parent_id < 2) { echo '<h2>Salva la scheda prima di inserire le competenze.'; exit; } ?>
<h1>Panoramica competenze</h1>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<div style="width: 25%; padding: 0; float: left; height: 200px; margin: 0 auto; text-align: center">
		<div id="container-speed0" style="width:  200px; height: 200px; margin: 0 auto;"></div>
</div>

<div style="width: 25%; padding: 0; float: left; height: 200px; margin: 0 auto; text-align: center">
		<div id="container-speed1" style="width:  200px; height: 200px; margin: 0 auto;"></div>
</div>

<div style="width: 25%; padding: 0; float: left;  height: 200px; margin: 0 auto; text-align: center">
	<div id="container-speed2" style="width: 200px; height: 200px; margin: 0 auto;"></div>
</div>
<div style="width: 25%; padding: 0; float: left;   height: 200px; margin: 0 auto; text-align: center">
	<div id="container-speed" style="width: 200px; height: 200px; margin: 0 auto;"></div>
</div>

<div id="results"><?php if(isset($_GET['esito'])) echo '<h2 class="red">'.check($_GET['esito']).'</h2>'; ?></div>


<?php 
function getVal($competenza,$persona){
	$query = "SELECT * FROM fl_competenze_val WHERE persona_id = $persona AND `competenza_id` = ".$competenza." ORDER BY data_aggiornamento DESC LIMIT 1";
	$risultato = mysql_fetch_assoc(mysql_query($query, CONNECT));
	return $risultato;	
	}
	
	$query = "SELECT * FROM `fl_competenze` WHERE id > 1 AND `funzione_id` = ".$parent_id['profilo_funzione'];
	$risultato = mysql_query($query, CONNECT);
	if(mysql_affected_rows() == 0){ echo "<p>Nessun Elemento</p>"; } else {
		
		
	?>
   
   
<table class="dati">
    <tr>
   <th>Categoria</th>
   <th>Competenze</th>
   <th>Atteso</th>
   <th>Aderenza</th>
   </tr>
          
 <?php
	
	$lastcat = '';
	$rowcatCount = 1;
	$val0 = $val1 = $val2 = 1;
	while ($riga = mysql_fetch_assoc($risultato)) 
	{ 
	$ultima_valutazione = getVal($riga['id'],$persona_id);
	$u = 'a'.$ultima_valutazione['valore'];
	$val = 'val'.$riga['categoria'];
	if(isset($ultima_valutazione['data_aggiornamento'])){ if($ultima_valutazione['valore'] > 2 ) { $$val++; } $$u++; }
	if($$val > 5 ) $$val = 5;
	$span = ($ultima_valutazione['valore'] < 3) ? 'c-orange' : 'c-green';
	?> 
    
     
      <tr>
      <td><?php echo $categoria[$riga['categoria']]; ?><input type="hidden" name="competenze[]" value="<?php echo $riga['id']; ?>" /> </td>
      <td><?php echo $riga['etichetta']; ?><br><span class="<?php echo $span; ?>"><?php echo (isset($ultima_valutazione['data_aggiornamento'])) ? 'Valutato il: '.mydate($ultima_valutazione['data_aggiornamento']).' - Esito: '.$valutazione[$ultima_valutazione['valore']] : 'Mai valutato'; ?></span></td>
      <td><?php echo $livello_atteso[$riga['livello_atteso']]; ?> </td>
      <td><?php 
		  if(isset($ultima_valutazione['data_aggiornamento'])) {
			 if( $ultima_valutazione['valore'] == 1) $color = '#DF5353';
			 if( $ultima_valutazione['valore'] == 2 && $ultima_valutazione['valore'] < 3) $color = 'RGBA(194, 96, 42, 0.9);';
			 if( $ultima_valutazione['valore'] == 3) $color = '#55BF3B';
			 if( $ultima_valutazione['valore'] == 4) $color = '#55BF3B';
			 if( $ultima_valutazione['valore'] == 5) $color = '#55BF3B';
			 $width = $ultima_valutazione['valore']*100/5;
			  echo '<span class="msg " style="color: white; background: '.$color.'; display: inline-block; width: '.$width.'%">'.$ultima_valutazione['valore'].'<span>';
 
		  }  ?>
	  
      </td>

    
</tr>

    <?php } }
	$arr = array('0' => $a0,'1' => $a1,'2' => $a2,'3' => $a3,'4' => $a4,'5' => $a5,);
	arsort($arr);
	$name = key($arr);
	$val = 0;
	if(($a0+$a1+$a3) > 3) $val = 2;
	if(($a0+$a1+$a3) > 5) $val = 1;
	if(($a5) > 10) $val = 5;
	//Chiudo la Connessione	?>
    
 </table>
<script type="text/javascript">
$(function () {
	
    var gaugeOptions = {
	
	    chart: {
	        type: 'solidgauge'
	    },
	    
	    title: null,
	    
	    pane: {
	    	center: ['50%', '85%'],
	    	size: '100%',
	        startAngle: -90,
	        endAngle: 90,
            background: {
                backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || '#EEE',
                innerRadius: '60%',
                outerRadius: '100%',
                shape: 'arc'
            }
	    },

	    tooltip: {
	    	enabled: false
	    },
	       
	    // the value axis
	    yAxis: {
			stops: [
				[0.0, '#DF5353'], // green 55BF3B
	        	[0.6, '#DDDF0D'], // yellow DDDF0D
	        	[0.7, '#55BF3B'] // red DF5353
			],
			lineWidth: 0,
            minorTickInterval: null,
            tickPixelInterval: 400,
            tickWidth: 0,
	        title: {
                y: -70
	        },
            labels: {
                y: 16
            }        
	    },
        
        plotOptions: {
            solidgauge: {
                dataLabels: {
                    y: -30,
                    borderWidth: 0,
                    useHTML: true
                }
            }
        }
    };
    
    // The speed gauge
    $('#container-speed').highcharts(Highcharts.merge(gaugeOptions, {
        yAxis: {
	        min: 0,
	        max: 5,
	        title: {
	            text: 'Totale'
	        }       
	    },

	    credits: {
	    	enabled: false
	    },
	
	    series: [{
	        name: 'Competenza',
	        data: [0],
	        dataLabels: {
	        	format: '<div style="text-align:center"><span style="font-size:25px;color:' + 
                    ((Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black') + '">{y}</span><br/>' + 
                   	'<span style="font-size:12px;color:silver">Coeff.</span></div>'
	        },
	        tooltip: {
	            valueSuffix: ' Coeff.'
	        }
	    }]
	
	}));
    

                               
        var chart = $('#container-speed').highcharts();
        if (chart) {
            var point = chart.series[0].points[0],
                newVal,
                inc = 5;
            
            newVal = (point.y < 2) ? 5 : 1;
         
            
            point.update(<?php echo $val; ?>);
        }

       // The speed gauge
    $('#container-speed0').highcharts(Highcharts.merge(gaugeOptions, {
        yAxis: {
	        min: 0,
	        max: 5,
	        title: {
	            text: '<?php echo $categoria[0]; ?>'
	        }       
	    },

	    credits: {
	    	enabled: false
	    },
	
	    series: [{
	        name: '<?php echo $categoria[0]; ?>',
	        data: [0],
	        dataLabels: {
	        	format: '<div style="text-align:center"><span style="font-size:25px;color:' + 
                    ((Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black') + '">{y}</span><br/>' + 
                   	'<span style="font-size:12px;color:silver">Coeff.</span></div>'
	        },
	        tooltip: {
	            valueSuffix: ' Coeff.'
	        }
	    }]
	
	}));
    

                               
        var chart = $('#container-speed0').highcharts();
        if (chart) {
            var point = chart.series[0].points[0],
                newVal,
                inc = 5;
            
            newVal = (point.y < 2) ? 5 : 1;
         
            
            point.update(<?php echo $val0; ?>);
        }

    // The speed gauge
    $('#container-speed1').highcharts(Highcharts.merge(gaugeOptions, {
        yAxis: {
	        min: 0,
	        max: 5,
	        title: {
	            text: '<?php echo $categoria[1]; ?>'
	        }       
	    },

	    credits: {
	    	enabled: false
	    },
	
	    series: [{
	        name: '<?php echo $categoria[1]; ?>',
	        data: [0],
	        dataLabels: {
	        	format: '<div style=""><span style="font-size:25px;color:' + 
                    ((Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black') + '">{y}</span><br/>' + 
                   	'<span style="font-size:12px;color:silver">Coeff.</span></div>'
	        },
	        tooltip: {
	            valueSuffix: ' Coeff.'
	        }
	    }]
	
	}));
    

                               
        var chart = $('#container-speed1').highcharts();
        if (chart) {
            var point = chart.series[0].points[0],
                newVal,
                inc = 5;
            
            newVal = (point.y < 2) ? 5 : 1;
         
            
            point.update(<?php echo $val1; ?>);
        }

    // The speed gauge
    $('#container-speed2').highcharts(Highcharts.merge(gaugeOptions, {
        yAxis: {
	        min: 0,
	        max: 5,
	        title: {
	            text: '<?php echo $categoria[2]; ?>'
	        }       
	    },

	    credits: {
	    	enabled: false
	    },
	
	    series: [{
	        name: '<?php echo $categoria[2]; ?>',
	        data: [0],
	        dataLabels: {
	        	format: '<div style="text-align:center"><span style="font-size:25px;color:' + 
                    ((Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black') + '">{y}</span><br/>' + 
                   	'<span style="font-size:12px;color:silver">Coeff.</span></div>'
	        },
	        tooltip: {
	            valueSuffix: ' Coeff.'
	        }
	    }]
	
	}));
    

                               
        var chart = $('#container-speed2').highcharts();
        if (chart) {
            var point = chart.series[0].points[0],
                newVal,
                inc = 5;
            
            newVal = (point.y < 2) ? 5 : 1;
         
            
            point.update(<?php echo $val2; ?>);
        }
       
     
	
});
		</script>
<script src="<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/highcharts/highcharts.js"></script>
<script src="<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/highcharts/highcharts-more.js"></script>
<script src="<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/highcharts/modules/solid-gauge.src.js"></script>

</body></html>