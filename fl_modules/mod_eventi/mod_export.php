<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); 


$monthSelected = (isset($_GET['mese_evento'])) ? $_GET['mese_evento'] : date('n');
$tipologia_main = gwhere($campi,'','','id','',0,0);//Imposta i filtri della query prendendo i dati GET e se sono tra i filtri li applica
$anno = array(2017,2018,2019,2020,2021,2022,2023,2024);
$annoSelected = (isset($_GET['anno'])) ? check($_GET['anno']) : date('Y') ;
if(isset($_GET['anno'])) $_SESSION['security'] = $annoSelected;



  
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
      
      /*Ambienti */
      $ambientia = explode(',',$value['ambienti']);
      $ambiente = '';
      foreach ($ambientia as $a => $ambi) { $ambiente .= $ambienti[$ambi].' ';  }
        
      
      $titolo_ricorrenza = explode('-',$value['titolo_ricorrenza']);
      $titolo_ricorrenza = trim(substr(html_entity_decode(str_replace('Matrimonio ','',$titolo_ricorrenza[0])),0,11));
      if($titolo_ricorrenza == '') $titolo_ricorrenza = 'Evento senza nome';

      $cliente1 = GRD('fl_anagrafica',$value['anagrafica_cliente']);
      $cliente2 = GRD('fl_anagrafica',$value['anagrafica_cliente2']);
      
      $tipoEvento = $tipo_evento[$value['tipo_evento']];
      $ambientiEvento = $ambiente;
      $eventoData = mydate($value['data_evento']);
 
      $nomi[$cliente1['id']] = array('stato'=>$stato_evento[$value['stato_evento']],'tipoEvento'=>$tipoEvento,'ambientiEvento'=>$ambientiEvento,'eventoData'=>$eventoData,'titolo_ricorrenza'=>$titolo_ricorrenza,'nome'=>ucfirst(strtolower($cliente1['nome'])),'cognome'=>ucfirst(strtolower($cliente1['cognome'])),'citta'=>$cliente1['citta'],'cellulare'=>$cliente1['telefono'],'email'=>strtolower($cliente1['email']));
      $nomi[$cliente2['id']] = array('stato'=>$stato_evento[$value['stato_evento']],'tipoEvento'=>$tipoEvento,'ambientiEvento'=>$ambientiEvento,'eventoData'=>$eventoData,'titolo_ricorrenza'=>$titolo_ricorrenza,'nome'=>ucfirst(strtolower($cliente2['nome'])),'cognome'=>ucfirst(strtolower($cliente2['cognome'])),'citta'=>$cliente1['citta'],'cellulare'=>$cliente2['telefono'],'email'=>strtolower($cliente2['email']));

    }
  }
}
}



$campi = array('stato','tipoEvento','ambientiEvento','eventoData','titolo_ricorrenza','nome','cognome','citta','cellulare','email');
unset($_SESSION['security']);
mysql_close(CONNECT);
	
doExcel('Esportazione Eventi .xlsx',$campi,$nomi);



function doExcel($name='',$campi='',$dati,$return='source'){
	
define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');


/** Include PHPExcel */
require_once dirname(__FILE__) . '/../../fl_set/librerie/PHPexcel/PHPExcel.php';

$objPHPExcel = new PHPExcel();
$objPHPExcel->getProperties()->setCreator("Condivision")
							 ->setLastModifiedBy("Condivision")
							 ->setTitle("Export")
							 ->setSubject("Export")
							 ->setDescription("Export")
							 ->setKeywords("condivision Export Excel")
							 ->setCategory("Export");
							 
$sheet = $objPHPExcel->setActiveSheetIndex(0);
$sheet->setTitle("Lista  ".date('d-m-Y') );
						 
  $letters = range('A','Z');
  $count = 0;
  $second_count = -1;
  $cell_name="";
  
  foreach($campi as $tittle)
  {
   $cell = $letters[$count];
   if($second_count >= 0) $cell = $letters[$second_count].$cell;
   $count++;
   $cell_name = $cell."1";
   
   if($count > count($letters))  {  $count = 0; $second_count++;  }
   $value = $tittle;
   $sheet->SetCellValue($cell_name, $value);
   $sheet->getColumnDimension($cell)->setWidth(20);
   $sheet->getStyle("$cell_name:$cell_name")->getFont()->setBold(true);
   $sheet->getStyle("$cell_name:$cell_name")->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'F5FFAF')
        )
    )
	);
   }



foreach($dati as $valore){
$highestRow = $sheet->getHighestDataRow()+1;
$sheet->fromArray($valore, NULL, 'A'.$highestRow);
}

$objPHPExcel->setActiveSheetIndex(0);
$name = ($name != '') ? $name :  __FILE__;
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

if($return == 'source'){
// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header ("Content-Disposition: inline; filename=$name");
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');
// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 31 Jul 2050<<<< 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0
$objWriter->save('php://output');
exit;

} else {	
$objWriter->save(str_replace('.php', '.xlsx', $name));
return  $name;
}



}


	


?>

