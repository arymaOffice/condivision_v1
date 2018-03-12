
<?php 

$monthSelected = (isset($_GET['mese_evento'])) ? $_GET['mese_evento'] : date('n');
$tipologia_main = gwhere($campi,'','','id','',0,0);//Imposta i filtri della query prendendo i dati GET e se sono tra i filtri li applica

$_SESSION['POST_BACK_PAGE'] = $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']; ?>


<div class="filtri" id="filtri">
<form method="get" action="" id="fm_filtri">
<h2>Filtri</h2>

<?php if(isset($_GET['action'])) echo '<input type="hidden" value="'.check($_GET['action']).'" name="action" />'; ?>
<?php if(isset($_GET['start'])) echo '<input type="hidden" value="'.check($_GET['start']).'" name="start" />'; ?>
<?php if(isset($_GET['anno'])) echo '<input type="hidden" value="'.check($_GET['anno']).'" name="anno" />'; ?>
   <?php 
 
	foreach ($campi as $chiave => $valore) 
	{		
			if(in_array($chiave,$basic_filters)){
			
			

			if((select_type($chiave) == 2 || select_type($chiave) == 23 ||  select_type($chiave) == 19 || select_type($chiave) == 9 || select_type($chiave) == 8 || select_type($chiave) == 12) && $chiave != 'id') {
				
								
				echo '<div class="filter_box">';
				echo '  <label>'.$valore.'</label>';
				echo '<select name="'.$chiave.'" class="select"><option value="-1">Non impostato</option>';
				foreach($$chiave as $val => $label) { $selected = (isset($_GET[$chiave]) && check(@$_GET[$chiave]) == $val) ? 'selected' : ''; echo '<option '.$selected.' value="'.$val.'">'.$label.'</option>'; }
				echo '</select>';
				echo '</div>';
			} else if( $chiave != 'id') { $valtxt = (isset($_GET[$chiave])) ? check($_GET[$chiave]) : ''; 
			echo '<div class="filter_box">';
			echo '<label>'.$valore.'</label><input type="text" name="'.$chiave.'" value="'.$valtxt.'" />'; echo '</div>';}

			
			
			} 
		
	}
?>    

<input type="submit" value="<?php echo SHOW; ?>" class="button" />

</form>

</div>



	<ul class="periodSelector" style="text-align:center;">
		
	<?php 

$anno = array(2017,2018,2019,2020,2021,2022,2023,2024);
$annoSelected = (isset($_GET['anno'])) ? check($_GET['anno']) : date('Y') ;
if(isset($_GET['anno'])) $_SESSION['security'] = $annoSelected;

		foreach ($anno as $key => $value) {

		$sel = ($value == @$annoSelected) ? 'selected' : '';
		$tot = mk_count('fl_eventi_hrc',' (YEAR(data_evento) = '.$value.') '.$tipologia_main );  

		echo '<li class="'.$sel.'" id="'.$key.'"><a href="./?action='.check($_GET['action']).'&anno='.$value.'">'.$value.' ('.$tot.')</a></li>';
	
	}


	?>
	</ul>	

<div><?php
foreach ($colors as $key => $value) {
			echo '<a href="./?'.$_SERVER['QUERY_STRING'].'&tipo_evento='.$key.'" style="color: white;"><span class="msg" style="background: '.$colors[$key]. '">'.$tipo_evento[$key].'</span></a>';
	}

	?></div>


	<?php
	
unset($mese[0]);
$gsett = array('D','L','M','M','G','V','S');
$nomi= array();
$periodColors = array();
$periodColors[101] = '#FF00FF';
$periodColors[102] = '#0066FF';
$year = $annoSelected;
foreach ($mese as $key => $value) {

$list=array();
$month = $key;
$monthVal = $value;

$tot = mk_count('fl_eventi_hrc',' (MONTH(data_evento) ='. $month.' AND YEAR(data_evento) = '.$year.') '.$tipologia_main );  
//echo '<div class="box" style="min-height: 700px; float: left; width: 8%; margin: 0 4px 4px 0; padding: 0px;"><h2>'. $value.' <span style="font-size: 12px;">('.$tot.')</span></h2><table style="width: 100%;">';	


$maxDays = cal_days_in_month (CAL_GREGORIAN, $month ,$year );

for($d=1; $d<=31; $d++)
{
    $time=mktime(12, 0, 0, $month, $d, $year);          
    if (date('m', $time)==$month)      
    $dayWeek = date('w', $time);
	$red = ($dayWeek == 0) ? 'c-red' : '';
	$eventiDelGiorno = GQS('fl_eventi_hrc','stato_evento,ambienti,id,titolo_ricorrenza,data_evento,tipo_evento,periodo_evento,anagrafica_cliente,anagrafica_cliente2',' data_evento BETWEEN \''.date('Y-m-d', $time).' 00:00:00\' AND \''.date('Y-m-d', $time).' 23:59:59\''.$tipologia_main );
	$eventi = '';

	foreach ($eventiDelGiorno as $key => $value) {

		if($value['id'] > 1 ) {
			$data_split = explode(' ',$value['data_evento']);
			$times = substr($data_split[1],0,2);
			$ambientia = explode(',',$value['ambienti']);
			$ambiente = '';
			foreach ($ambientia as $a => $ambi) { $ambiente .= $ambienti[$ambi].' ';	}
		    
		    $stato_evento  = ($value['stato_evento'] < 2) ? '<span style="color: red !important;">! </span>' : '';
			$titolo_ricorrenza = explode('-',$value['titolo_ricorrenza']);
			$titolo_ricorrenza = trim(substr(html_entity_decode(str_replace('Matrimonio ','',$titolo_ricorrenza[0])),0,11));

			$cliente1 = GRD('fl_anagrafica',$value['anagrafica_cliente']);
			$cliente2 = GRD('fl_anagrafica',$value['anagrafica_cliente2']);
			
			$eventoDetails = '<span style="width: 150px; display: inline-block; color: white; padding: 2px; background: '.$colors[$value['tipo_evento']]. '" title="'.$ambiente.'">'.$tipo_evento[$value['tipo_evento']].'</span> <span style="display: inline-block; width: 200px; " class="msg orange">'.$ambiente.'</span>  - <strong> '.$titolo_ricorrenza.' del '.mydate($value['data_evento']).'</strong>';
			
			$nomi[$cliente1['id']] = array('evento'=>$eventoDetails,'nome'=>ucfirst(strtolower($cliente1['nome'])).' '.ucfirst(strtolower($cliente1['cognome'])),'cellulare'=>$cliente1['telefono'],'email'=>strtolower($cliente1['email']));
			$nomi[$cliente2['id']] = array('evento'=>$eventoDetails,'nome'=>ucfirst(strtolower($cliente2['nome'])).' '.ucfirst(strtolower($cliente2['cognome'])),'cellulare'=>$cliente2['telefono'],'email'=>strtolower($cliente2['email']));

			if($titolo_ricorrenza == '') $titolo_ricorrenza = 'Senza nome';
			foreach ($ambientia as $a => $ambi) { $ambiente .= $ambienti[$ambi];	}
			$eventi .= '<div><span style="background: '.$periodColors[$value['periodo_evento']].';  margin: 0; width: 10px; height: 10px;">&nbsp;</span><span style="display: inline-block; width: 90%;  color: white; padding: 2px; background: '.$colors[$value['tipo_evento']]. '" title="'.$ambiente.'">'.$stato_evento.' <a href="./mod_inserisci.php?id='.$value['id'].'" style="color: white;" > '.$titolo_ricorrenza.'</a></span></div>';
		}
	}
    //if($d <= $maxDays) echo '<tr><td>'.date('j', $time).'</td><td class="'.$red.'">'.$gsett[$dayWeek].'</td><td>'.$eventi.'</td></tr>';
}
//echo "<pre>";
//print_r($list);
//echo "</pre>";

//echo '</table></div>';
}

foreach ($nomi as $key => $nome) {
	$sino = ($nome['telefono'] != '') ? '' : 'NON ';
	$esito = 'Invieremo';
	$backgroundr = '';
	if(isset($_GET['confirm']) && isset($_SESSION['security'])) $esito = 'INVIATO';

	if(isset($_GET['confirm']) && isset($_SESSION['security']) && $nome['telefono'] != '') { 
	
	$to = phone_format($nome['telefono'],'39');
	$messaggio = '';
	$messaggio = str_replace('[cry_customer_id]',base64_encode($nome['id']).' ',$messaggio);
	$send = 'Non attivo invio!!'; //sms($to,$messaggio,from);
	$esito = ($send == 1) ? ' INVIATO ' : $send; 
	$backgroundr = ''; 
	set_time_limit (30);
	}

echo   '<div style="margin: 2px 0;">'.$nome['evento'].' - <strong><a href="../mod_anagrafica/mod_inserisci_smart.php?id='.$key.'" data-fancybox-type="iframe" class="fancybox_view">'. $nome['nome'] .' </a></strong> Telefono: '.$nome['cellulare'].' Email: '.$nome['email'].'</div>';

}


if(isset($_GET['confirm'])) unset($_SESSION['security']);


if(isset($_SESSION['security'])) { 
	//echo '<a class="button" href="'.$_SESSION['POST_BACK_PAGE'].'&confirm" onclick="conferma(\'Procedere con invio?\');">Conferma invio a lista '.$annoSelected.'</a>'; 
	echo '<a class="button" href="mod_export.php?'.$_SERVER['QUERY_STRING'].'"><i class="fa fa-windows"></i> Esporta in Excel </a>'; 
} else {
	echo "Seleziona un anno in alto o dei filtri per procedere con invio";
}




?>
