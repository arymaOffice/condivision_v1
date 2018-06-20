<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); 

$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine";
$risultato = mysql_query($query, CONNECT);
$dati = array();

while ($riga = mysql_fetch_assoc($risultato)) 
	{					
			$show = 1;
			
			if(ATTIVA_ACCOUNT_ANAGRAFICA == 1){			
			$account = get_account($riga['id']);
			if($stato_account_id != -1 && (!isset($account['attivo'] ) || @$account['attivo'] != $stato_account_id))   $show = 0;
			if($tipo_account_id != -1 && (!isset($account['tipo'] ) || @$account['tipo'] != $tipo_account_id))   $show = 0;
			}
			
			if($show==1 && ATTIVA_ACCOUNT_ANAGRAFICA == 1) {
				
				
			foreach($riga as $chiave => $valore){ 
			
			if((select_type($chiave) == 2 || select_type($chiave) == 19)) { $val = $$chiave; $riga[$chiave] = @html_entity_decode($val[$valore]); 		
			} else if(select_type($chiave) == 20){ $riga[$chiave] = mydate($valore); 
			} else if(select_type($chiave) == 5) { unset($riga[$chiave]); 
			} else { $riga[$chiave] = html_entity_decode(strip_tags(converti_txt($valore))); 		
			}
			}
			
			if(ATTIVA_ACCOUNT_ANAGRAFICA == 1){ 
			$tipo_account = $tipo[$account['tipo']];
			$account_attivo = ($account['attivo'] == 1)  ? "ATTIVO" : "NON ATTIVO"; 
			$account_attivo = ($account['attivo'] == 0)  ? $account_attivo.' ('.$account['motivo_sospensione'].')' : $account_attivo; 
			$riga = array('tipo_account'=>$tipo_account,'account_attivo'=>$account_attivo)+$riga; 
			}

			
			$dati[] = $riga;
			
			}
	}
	
	
	mysql_close(CONNECT);
	
	doExcel('Export_'.$_SESSION['number'].'_'.date('d-m-Y').'.xlsx',$campi,$dati);



function doExcel($name='',$campi='',$dati,$return='source'){
	
define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');


/** Include PHPExcel */
require_once dirname(__FILE__) . '/../../fl_set/librerie/PHPexcel/PHPExcel.php';

$objPHPExcel = new PHPExcel();
$objPHPExcel->getProperties()->setCreator("Condivision Cloud")
							 ->setLastModifiedBy("Condivision")
							 ->setTitle("Report ")
							 ->setSubject("Report")
							 ->setDescription("Report")
							 ->setKeywords("condivision report excel")
							 ->setCategory("Reports");
							 
$sheet = $objPHPExcel->setActiveSheetIndex(0);
$sheet->setTitle("Report  ".date('d-m-Y') );
						 
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
   
   if($count > count($letters)-1)  {  $count = 0; $second_count++;  }
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

