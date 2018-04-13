<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 





if(isset($_POST['doc_vendita'])) {

$doc_vendita_id = check($_POST['doc_vendita']); // ID BOLLA
$ordine_id = check($_POST['ordine_id']); // ID ORDINE FORNITORE SE ESISTE

if($doc_vendita_id > 1) $dati_doc_vendita = GRD('fl_doc_acquisto',$doc_vendita_id);


foreach($_POST['codice'] as $item => $val) {

   // echo '<br>'.$_POST['id'][$item];
   echo '<br>'.$_POST['codice'][$item];
   // echo '<br>'.$_POST['descrizione'][$item];
   // echo '<br>'.$_POST['unita_di_misura'][$item];
   // echo '<br>'.$_POST['qty'][$item];
   // echo '<br>'.$_POST['prezzo'][$item];
   // echo '<br>'.$_POST['sc'][$item];
  //  echo '<br>'.$_POST['iva'][$item];
    
    $id = check($_POST['id'][$item]); // ID del formato se 0 è un nuovo formato non anagrafato
    $codice_fornitore = check($_POST['codice'][$item]);
    $descrizione = check($_POST['descrizione'][$item]);
    $unita_di_misura = check($_POST['unita_di_misura'][$item]);
    $qty = str_replace(',','.',check($_POST['qty'][$item]));
    $sc = str_replace(',','.',check($_POST['sc'][$item]));
    $prezzo = str_replace(',','.',check($_POST['prezzo'][$item]));
    $iva = str_replace(',','.',check($_POST['iva'][$item]));
    $lotto = $note = ''; // TO ADD ON FORM
    $imposta = (($prezzo*$qty)*$iva)/100;
    $subtotale = ($prezzo*$qty)+$imposta;

    if(trim($codice_fornitore.$descrizione) != '') {
    
    $insertVoceDoc = "INSERT INTO `fl_doc_acquisto_voci` 
    (`id`, `parent_id`, `codice`, `descrizione`, `unita_di_misura`, `quantita`, `valuta`, `importo`, `sconto`, `aliquota`, `imposta`, `subtotale`, `data_creazione`,`operatore`) 
    VALUES (NULL, '$doc_vendita_id', '$codice_fornitore', '$descrizione', '$unita_di_misura', '$qty', 'EUR', '$prezzo', '$sc', '$iva', '$imposta', '$subtotale', NOW(),".$_SESSION['number'].");";
    if(mysql_query($insertVoceDoc,CONNECT)) echo '<br><br>'.$descrizione." caricato e associato al DDT"; 

    $insertMovimentoMag = "INSERT INTO `fl_magazzino_movimentazioni` (`id`, `status_movimentazione`, `ordine_id`, `doc_vendita_id`, `causale_movimentazione`, `workflow_id`, `parent_id`, `codice_fornitore`, `codice_ean`, `descrizione`, `unita_di_misura`, `valuta`, `prezzo_unitario`, `aliquota`, `lotto`, `note`, `quantita`, `magazzino_id`, `settore_id`, `cella_id`, `data_creazione`, `data_aggiornamento`, `operatore`) 
    VALUES (NULL, '0', '$ordine_id', '$doc_vendita_id', '0', '116', '$id', '$codice_fornitore', '', '$descrizione', '$unita_di_misura', 'EUR', '$prezzo', '$iva', '$lotto', '$note', '$qty', '0', '0', '0', NOW(), NOW(), '".$_SESSION['number']."');";
    if(mysql_query($insertMovimentoMag,CONNECT)) echo " e movimentazione di magazzino acquisita per ".$descrizione;
     
    if($id > 1) {  //AGGIORNO GIACENZA
        $codiceUPDATE = ($codice_fornitore != '') ? ', codice_fornitore = \''. $codice_fornitore.'\'' : '';
        echo $updateGiacenza = "UPDATE ".$tables[116]." SET giacenza = giacenza +$qty $codiceUPDATE WHERE id = $id LIMIT 1";
        if(mysql_query($updateGiacenza ,CONNECT)) echo "<br>Ho aggiornato giacenza!";
        echo mysql_error();
    } else { //CREO NUOVO FORMATO E MATERIA
        
        $fornitoredaDocVen = (isset($dati_doc_vendita)) ? $dati_doc_vendita['anagrafica_id'] : 1;
        $idM = 0;

         $nuovaMateria = "INSERT INTO `fl_materieprime` (`id`, `attivo`, `codice_articolo`, `descrizione`,  `valore_di_conversione`, `categoria_materia`, `unita_di_misura`, `ultimo_prezzo`, `prezzo_medio`, `tipo_materia`, `anagrafica_id`, `marchio`,  `note`, `magazzino_base`, `aggancia_semilavorato`, `data_creazione`, `data_aggiornamento`) 
        VALUES (NULL, '1', '$codice_fornitore', '$descrizione',  '1',  'NESSUNA', '$unita_di_misura', '$prezzo', '$prezzo', '0', '$fornitoredaDocVen',  '',  '', '0','0', NOW(), NOW());";
        if(mysql_query($nuovaMateria,CONNECT)) {
        echo "<br>Nuovo elemento anagrafico creato!";
        $idM = mysql_insert_id();
        }
        
        $nuovoFormato = "INSERT INTO ".$tables[116]." (`id`, `id_materia`, `attivo`, `fornitore`, `codice_fornitore`, `formato`, `unita_di_misura_formato`, `valore_di_conversione`, `valuta`, `prezzo_unitario`, `iva`, `codice_ean`, `giacenza`, `giacenza_minima`, `data_validita`, `data_scadenza`, `data_creazione`, `data_aggiornamento`, `operatore`) 
        VALUES (NULL, '$idM', '1', '$fornitoredaDocVen', '$codice_fornitore', '$descrizione', '$unita_di_misura', '1', 'EUR', '$prezzo', '$iva', '', '$qty', '0',  NOW(), '', NOW(), NOW(), '".$_SESSION['number']."');";
        if(mysql_query($nuovoFormato,CONNECT)) echo "<br>Nuovo formato creato!";
        $id = mysql_insert_id();
    }
    
    $formatoRef = GRD($tables[116],$id);
    echo '<br>'.$descrizione." ha una giacenza attuale di ".$unita_di_misura.' '.$formatoRef['giacenza'];
            


    } else { echo "<br>Riga Vuota non gestita!"; }


}
exit;

}




mysql_close(CONNECT);
header("Location: ".$_SERVER['HTTP_REFERER']); 
exit;

?>
