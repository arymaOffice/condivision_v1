<?php
require_once('../../fl_core/autentication.php');


/***************************************************************
*
*
*   leggi file .csv di calderoni
*   
*   1. lettura di tutti i file all'interno della cartella file upload
*   
*   2. lettura del file
*
*   3. query di inserimento dopo aver ottenuto i campi
*
***************************************************************/

// più tempo per l'esecuzione dello script 

ini_set('max_execution_time', 600);  //300 seconds = 5 minutes


// per migliorare la ricerca della fine di ogni riga 
ini_set("auto_detect_line_endings", true);




/************REMAKE DATA****************/
function REMAKEDATA($data)
{
    $originalDate = str_replace('/', '-', $data);
    $newDate = date("Y-m-d", strtotime($originalDate));
    return  $newDate;

}
/*************REMAKE DATA********************/


// definire le variabili iniziali 
$i=0;
$names=array('');


//inclusione dei file

foreach (glob("./fileupload/*.csv") as $filename)
{
 $names[$i]=$filename;
 $i++;
}

//valori da eliminare all'interno delle stringhe
$valori = array('\'','"','""','\'\'',';',',');



//inizio dello script di lettura ed inserimento

foreach ($names as $name ) {

    //inizzializzazione delle righe
    $row = 0;

    //controllo se è pieno 
    if (($handle = fopen($name, "r")) !== FALSE) {

        //lavoriamo sui dati del csv
        while (($data = fgetcsv($handle, 3000, ",")) !== FALSE) {

        //ripuliosco i valori 
        $val_pul = explode(';', $data[0]);
        //conteggio dei valori presenti
        $count = count($val_pul);

        if ($row > 0){

            //inizzializzo un array vuoto
            $val = array('');

                for ($c=0; $c <= $count; $c++) {

                    //id   attivo  codice_articolo descrizione formato unita_di_misura ultimo_prezzo   prezzo_medio    categoria_materia   tipo_materia    anagrafica_id   marchio data_creazione

                    $val[$c]=(!empty($val_pul[$c])) ? $val_pul[$c] : '-';
                 }

      //assegno valori  

     $codice_articolo =  $val[2] ;           
     $descrizione =  str_replace($valori, '', $val[3]) ;           
     $formato =  str_replace($valori, '', $val[4]) ;            
     $unita_di_misura =  $val[5] ;           
     $ultimo_prezzo =  $val[6] ;                      
     $prezzo_medio =  $val[7] ;                      
     $categoria_materia =  $val[8] ;           
     $tipo_materia =  $val[9] ;                      
       
     //inserimento della materia prima              
     $sql="INSERT INTO `fl_materieprime2`(`id`, `attivo`, `codice_articolo`, `descrizione`, `formato`, `unita_di_misura`, `ultimo_prezzo`, `prezzo_medio`, `categoria_materia`, `tipo_materia`, `anagrafica_id`, `marchio`, `data_creazione`) VALUES (NULL,1,'$codice_articolo', '$descrizione','$formato','$unita_di_misura','$ultimo_prezzo','$prezzo_medio','$categoria_materia','$tipo_materia',0,0,NOW())";
     
    $query = mysql_query($sql,CONNECT);
    //fine inserimento della materia prima

        }//fine if e gestione della riga dopo la riga 0

        $row++;

    }//fine while

    fclose($handle); // chiudo il documento
    unlink($name); //Elimino il file
    }//fine primo if

} // fine foreach

mysql_close(CONNECT);

header("Location: ./?action=9&succes&esito=".$row." record caricati!"); 
exit;


?>
