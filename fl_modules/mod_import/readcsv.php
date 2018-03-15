<?php
require_once('../../fl_core/autentication.php');


/***************************************************************
*
*
*   leggi file .csv di bluemotive 
*   
*   1. lettura di tutti i file all'interno dell acrtella file upload
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

function query($sql){ 
  $results = mysql_query($sql,CONNECT);
  return  (mysql_affected_rows() >= 0) ? $results : -1;
}

function insertUpdate($table,$recordId,$campiUpdate=array()){

    $issue = 0;
    if($recordId == 0) mail('michelefazio@aryma.it','BM Record id 0 in insertUpdate',$recordId);
    $sql = 'DESCRIBE '.$table.';';
    $updateSQL = 'UPDATE '.$table.' SET ';
    $createSQL = 'INSERT INTO '.$table.' VALUES ();';
    $fields = query($sql);
    
    
    while($FieldProp = mysql_fetch_array($fields)){ 
        
        $FieldName = check($FieldProp['Field']);
        if($FieldName == 'data_aggiornamento' && $recordId != 1) $updateRecord = 1;
        if($FieldName != 'id' && $FieldName != 'data_creazione'){
            
            if(isset($campiUpdate[$FieldName])){
                $Field = str_replace("'","\'",check($campiUpdate[$FieldName])); // Security Checl of the received string
                if(isset($comma)) { $updateSQL .=  ',';  }  else { $comma = 1; }
                $updateSQL .= '`'.$FieldName.'` =  \''.$Field.'\'';
            }
        }
    }

    if(!isset($updateRecord) && $recordId == 1) $updateSQL .=  ', data_creazione = NOW() '; //Used only for new entries!
    if(isset($updateRecord)) $updateSQL .=  ', data_aggiornamento = NOW() '; 


    

    if($recordId == 1 && !isset($_GET['explain'])) { query($createSQL); $recordId = mysql_insert_id();  } // if 1 create a new record    
    $updateSQL .= ' WHERE id = '.$recordId;

    $issue = (isset($_GET['explain'])) ? '(NO QUERY SENT IN DEBUG MODE) : '.$updateSQL : query($updateSQL);
    if($issue < 1) mail('supporto@aryma.it','Query app error',$updateSQL);
    return  $recordId;//Issue of Update
    
}


/************REMAKE DATA****************/
function REMAKEDATA($data)
{
    $originalDate = str_replace('/', '-', $data);
    $newDate = date("Y-m-d", strtotime($originalDate));
    return  $newDate;

}
/*************REMAKE DATA********************/



$refer=check($_GET['source_potential']);


// definire le variabili iniziali 
$break  = 0 ;

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

        $k = 0 ;

        //lavoriamo sui dati del csv
        while (($data = fgetcsv($handle, 3000, ",")) !== FALSE) {


            $replaced = str_replace(';', '', $data[0]);

            if ($replaced != ' ' && !empty($replaced) && isset($replaced))
            { 

                    // per definire i valori visto che ci sono errori di conversione
                    $val_pul = explode(';', $data[0]);

                    //conteggio dei valori
                    $count = count($val_pul);

                    //trascuro la prima riga perchè ha l'intestazione del file
           if ($row == 0){

               //creazione dell array vuoto
                       $campi = array();

                    //assegnazione dei valori
                       for ($c=0; $c <= $count; $c++)
                       {
                            if(!empty($val_pul[$c]))
                            {
                                $campi[$c]=$val_pul[$c];

                            }

                        }



           // foreach($campi as $child) { echo $child . "<br>\n"; }

                       
           }
//echo '<br><br><br>';



           if ($row > 0){



                    //creazione dell array vuoto
                    $val = array();

                    //assegnazione dei valori
                    for ($c=0; $c < $count; $c++)
                       {
                            if(!empty($val_pul[$c]))
                            {
                                $val[$campi[$c]]=$val_pul[$c];

                            } else {

                                $val[@$campi[$c]]='';

                            }
                        }

                  
                      
                   //foreach($val as $nomeCampo => $child) { echo $nomeCampo.' = '.$child . "<br>\n"; } //Stampa debug              
                  $val['campagna_id'] = (isset($_GET['campagna_id'])) ? check($_GET['campagna_id']) : 0;
                  $val['source_potential'] = (isset($_GET['source_potential'])) ? check($_GET['source_potential']) : 0;
                  $val['status_potential'] = (isset($_GET['status_potential'])) ? check($_GET['status_potential']) : 0;

                   /* Controllo che ci siano i seguenti campi obbligatori
                   $expected = array('nome','cognome','email','telefono','proprietario','sede','campagna_id','source_potential');
                   //recupero valori per la query sottostante
                   foreach($expected as $kc => $campoNomex) { if(!isset($val[$campoNomex]) ) { echo 'Manca campo: '. $campoNomex ; unlink($name); exit; }}
                   //fine recupero*/

                   $parent_id = insertUpdate('fl_potentials',1,$val);
                  // echo  "<br>Inserito potential n. ".$parent_id;
                   $val['parent_id'] = $parent_id;


                   /*Inserimento Veicolo se presente*/
                   if(isset($val['workflow_id']) && $val['workflow_id'] > 0) {
                      /* Controllo che ci siano i seguenti campi obbligatori
                      $exspected2 = array('workflow_id','tipologia_veicolo','data_acquisto','data_saldo','data_consegna','marca','modello');
                      //recupero valori per la query sottostante
                      foreach($exspected2 as $kc => $campoNomex) { if(!isset($val[$campoNomex]) ) { echo 'Manca campo: '. $campoNomex ; unlink($name); exit; }}*/
                      insertUpdate('fl_veicoli',1,$val); // Solo se workflow_id è valorizzato
                   }

                   /*Inserimento Preventivo se presente*/
                   if(isset($val['tipo_preventivo']) && @$val['tipo_preventivo'] > 0)  {
                      $val['potential_id'] = $parent_id;
                      insertUpdate('fl_rdo',1,$val);
                   }
                    

                    $k++ ;


                    
            }//fine if e gestione della riga dopo la riga 0

        }// fine stringa vuota

        $row++;

    }//fine while


    }//fine primo if
    fclose($handle); // chiudo il documento
    unlink($name); //Elimino il file

} // fine foreach
mysql_close(CONNECT);

if(!isset($_GET['external'])) header("Location: ./?action=9&succes&esito=".$k." record caricati!");
if(isset($_GET['external'])) header("Location: ./mod_user.php?id=".$val['source_potential']."&esito=".$k." record caricati!"); 

exit;


?>
