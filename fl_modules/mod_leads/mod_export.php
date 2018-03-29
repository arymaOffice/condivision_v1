<?php
/*
* modulo di esportazione in excel i leads presenti nella vista dal quale lo richiamano
*    con i filtri della stessa
*
*   in mod_home.php tasto che la richiama
*/
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../../fl_core/autentication.php';
include 'fl_settings.php';


// liberia per creazione excel
require_once $_SERVER['DOCUMENT_ROOT'] . '/fl_set/librerie/PhpSpreadsheet/phpoffice/phpspreadsheet/src/Bootstrap.php';
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

$query_filter = ''; //conterrà filtri per la query

//date che devono subire cambiamneti prima di essere inserite nella query
if(isset($_GET['data_da']) && isset($_GET['data_a'])){
$data_da = date("Y-m-d",strtotime(check($_GET['data_da']))); $data_a = date("Y-m-d",strtotime(check($_GET['data_a'])));
unset($_GET['data_da']); unset($_GET['data_a']);
}



foreach($_GET as $key => $value){
    if($value > -1){
        $query_filter .= check($key).'='.check($value).' AND '; 
    }
}

if(isset($_GET['data_da']) && isset($_GET['data_a'])){
    $query_filter .= ' (data_creazione BETWEEN '.$data_da.' AND '.$data_a.' ) ';
}

$query_filter = trim($query_filter,' AND ');



//prendi i leads con query filtrata
$leads = GQS($tabella, '*', 'id != 1 AND '.$query_filter);
$dati = array();
foreach ($leads as $key => $value) {

    $value['status_potential'] = $status_potential[$value['status_potential']]; //Valori da array per alcuni campi
    $value['source_potential'] = $source_potential[$value['source_potential']]; //Valori da array per alcuni campi
    $value['campagna_id'] = $campagna_id[$value['campagna_id']]; //Valori da array per alcuni campi


    $dati[$value['id']] = array('id' => $value['id'], 'stato' => $value['status_potential'], 'sorgente' => $value['campagna_id'], 'attivita' => $value['source_potential'], 'nome' => ucfirst(strtolower($value['nome'])), 'cognome' => ucfirst(strtolower($value['cognome'])), 'citta' => $value['citta'], 'cellulare' => $value['telefono'], 'email' => strtolower($value['email']));
}
$campi = array('id', 'nome', 'cognome', 'citta', 'cellulare', 'email');
mysql_close(CONNECT);

/* nuova implementazione*/
$name = 'aaa';
$return = 'source';

$objPHPExcel = new Spreadsheet();
$objPHPExcel->getProperties()->setCreator("Condivision")
    ->setLastModifiedBy("Condivision")
    ->setTitle("Export")
    ->setSubject("Export")
    ->setDescription("Export")
    ->setKeywords("condivision Export Excel")
    ->setCategory("Export");

$sheet = $objPHPExcel->setActiveSheetIndex(0);
$sheet->setTitle("Lista  " . date('d-m-Y'));

$letters = range('A', 'Z');
$count = 0;
$second_count = -1;
$cell_name = "";

foreach ($campi as $tittle) {
    $cell = $letters[$count];
    if ($second_count >= 0) {
        $cell = $letters[$second_count] . $cell;
    }

    $count++;
    $cell_name = $cell . "1";

    if ($count > count($letters)) {$count = 0;
        $second_count++;}
    $value = $tittle;
    $sheet->SetCellValue($cell_name, $value);
    $sheet->getColumnDimension($cell)->setWidth(20);
    $sheet->getStyle("$cell_name:$cell_name")->getFont()->setBold(true);

}

foreach ($dati as $valore) {
    $highestRow = $sheet->getHighestDataRow() + 1;
    $sheet->fromArray($valore, null, 'A' . $highestRow);
}



$objPHPExcel->setActiveSheetIndex(0);

$name = ($name != '') ? $name : __FILE__;
$objWriter = IOFactory::createWriter($objPHPExcel, 'Xlsx');

if ($return == 'source') {

    // Redirect output to a client’s web browser (Xlsx)
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="01simple.xlsx"');
    header('Cache-Control: max-age=0');
    // If you're serving to IE 9, then the following may be needed
    header('Cache-Control: max-age=1');
    
    // If you're serving to IE over SSL, then the following may be needed
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
  
    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header('Pragma: public'); // HTTP/1.0
   
    $objWriter->save('php://output');
    exit;

} else {
    $objWriter->save(str_replace('.php', '.xlsx', $name));

}

//doExcel('Esportazione.xlsx', $campi, $dati);

function doExcel($name = '', $campi = '', $dati, $return = 'source')
{

define('EOL', (PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

//Include PHPExcel
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
$sheet->setTitle("Lista  " . date('d-m-Y'));

$letters = range('A', 'Z');
$count = 0;
$second_count = -1;
$cell_name = "";

foreach ($campi as $tittle) {
$cell = $letters[$count];
if ($second_count >= 0) {
$cell = $letters[$second_count] . $cell;
}

$count++;
$cell_name = $cell . "1";

if ($count > count($letters)) {$count = 0;
$second_count++;}
$value = $tittle;
$sheet->SetCellValue($cell_name, $value);
$sheet->getColumnDimension($cell)->setWidth(20);
$sheet->getStyle("$cell_name:$cell_name")->getFont()->setBold(true);
$sheet->getStyle("$cell_name:$cell_name")->applyFromArray(
array(
'fill' => array(
'type' => PHPExcel_Style_Fill::FILL_SOLID,
'color' => array('rgb' => 'F5FFAF'),
),
)
);
}

foreach ($dati as $valore) {
$highestRow = $sheet->getHighestDataRow() + 1;
$sheet->fromArray($valore, null, 'A' . $highestRow);
}

$objPHPExcel->setActiveSheetIndex(0);
$name = ($name != '') ? $name : __FILE__;
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

if ($return == 'source') {
// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: inline; filename=$name");
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');
// If you're serving to IE over SSL, then the following may be needed
header('Expires: Mon, 31 Jul 2050<<<< 05:00:00 GMT'); // Date in the past
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header('Pragma: public'); // HTTP/1.0
$objWriter->save('php://output');
exit;

} else {
$objWriter->save(str_replace('.php', '.xlsx', $name));
return $name;
}

}

