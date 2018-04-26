<?php


class webservice
{

    public $demo = false; // Se abilitare o meno il demo
    public $datatype = "JSON";
    public $contenuto = array();
    public $token;
    public $user;
    public $password;
    public $obbligatorio = array();
    public $numerici = array('telefono', 'cellulare');
    public $date = array('data_test_drive', 'periodo_cambio_vettura');
    public $secret = 're56fdsfw285hfw5k3923k2ASYLWJ8tr3';
    public $push_type = 'post';
    public $deviceToken = 0;

    public function app_start()
    {

        session_cache_limiter('private_no_expire');
        session_cache_expire(time() + 5259200);
        session_start();

        $res = mysql_query("SELECT token FROM `fl_tokens` WHERE token = '" . $this->token . "';", CONNECT);

        if (mysql_affected_rows() < 1) {
            $this->contenuto = '';
            $this->contenuto['esito'] = 0;
            $this->contenuto['info_txt'] = 'Not valid session. Please restart app';
            mysql_close(CONNECT);
            echo json_encode($this->contenuto);
            exit;
        }

    }

    public function cnv_makedata()
    {

        mysql_close(CONNECT);

        if ($this->datatype == 'JSON') {
            echo json_encode($this->contenuto);
            exit;
        }

        if ($this->datatype == 'OBJECT') {
            return $this->contenuto;
        }

        if ($this->datatype == 'XML') {
            echo json_encode($this->contenuto);
            exit;
        }

    }

    public function cleanPhone($phoneNumber)
    {

        $phoneNumber = str_replace(' ', '', $phoneNumber);
        $phoneNumber = str_replace('.', '', $phoneNumber);
        $phoneNumber = str_replace('/', '', $phoneNumber);
        $phoneNumber = str_replace('\\', '', $phoneNumber);
        $phoneNumber = str_replace('-', '', $phoneNumber);
        $phoneNumber = str_replace('+', '', $phoneNumber);

        return $phoneNumber;

    }

    public function insert_lead()
    {

        $this->contenuto['esito'] = false;

        $mail_message = '';
        // Campi Obbligatori

        foreach ($_REQUEST as $chiave => $valore) {

            if ($chiave == 'telefono' || $chiave == 'telefono_alternativo') {
                $valore = $this->cleanPhone($this->check($valore));
            }

            if (in_array($chiave, $this->obbligatorio)) {
                if ($valore == "") {
                    $chiave = ucfirst(check($chiave));
                    $this->contenuto['esito'] = false;
                    $this->contenuto['info_txt'] = "Compila il campo $chiave";
                    $this->cnv_makedata();

                }}

            if (in_array($chiave, $this->numerici)) {
                if (!is_numeric(trim($valore))) {
                    $chiave = ucfirst(check($chiave));
                    $this->contenuto['esito'] = false;
                    $this->contenuto['info_txt'] = "Inserisci solo numeri in $chiave";
                    $this->cnv_makedata();
                }}

            $$chiave = $this->check($valore);
            if (in_array($chiave, $this->date)) {
                $$chiave = $this->convert_data($this->check($valore), 1);
            }

            $mail_message .= '<p>' . $chiave . ' = ' . $$chiave . '</p>';
        }

        $status_potentials = array('Da Assegnare', 'Assegnato a BDC', 'Appuntamento', 'Non Interessato', 'Cliente', 'Valuta', 'Acquistato Concorrenza', 'Preventivo', 'Assegnato a Venditore', 'Eliminato');

        $campi = array('professione', 'sms_send', 'id_cliente_fordpower', 'id_cliente_drakkar', 'campagna_id', 'source_potential', 'priorita_contatto', 'paese', 'company', 'job_title', 'nome', 'cognome', 'email', 'telefono', 'website', 'indirizzo', 'provincia', 'citta', 'cap', 'industry', 'ragione_sociale', 'partita_iva', 'fatturato_annuo', 'numero_dipendenti', 'messaggio', 'tipo_interesse', 'interessato_a', 'periodo_cambio_vettura', 'permuta', 'test_drive', 'data_test_drive', 'pagamento_vettura', 'note', 'operatore', 'tipologia_veicolo', 'data_acquisto', 'pagamento_veicolo', 'marca', 'modello', 'colore', 'descrizione', 'anno_immatricolazione', 'chilometri_percorsi', 'alimentazione', 'targa', 'quotazione_attuale', 'proprietario', 'venditore');

        foreach ($campi as $chiave => $valore) {

            if (!isset($$valore)) {
                $$valore = '';
            }

        }

        if (!isset($source_potential)) {
            $source_potential = 1;
        }

        if (!isset($status_potential)) {
            $status_potential = 0;
        }

        $industry = $job_title;

        $telCheck = ($telefono != '' && $telefono != 0) ? " OR telefono LIKE '$telefono' " : '';
        $isInDb = GQS('fl_potentials', 'id,email,telefono,telefono_alternativo,source_potential,status_potential', "(email  != '' AND email  != '@' AND email  != 0 AND email LIKE '$email') $telCheck ");

        if (count($isInDb) > 0) {

            $sql = "UPDATE `fl_potentials` SET
id_cliente_fordpower = '$id_cliente_fordpower' ,
id_cliente_drakkar = '$id_cliente_drakkar' ,
status_potential = '$status_potential',
source_potential = '$source_potential',
data_aggiornamento = NOW(),
nome = '$nome',
cognome =  '$cognome',
email = '$email',
telefono = '$telefono',
website = '$website',
indirizzo = '$indirizzo',
provincia = '$provincia',
citta = '$citta',
cap = '$cap',
industry = '$industry',
company = '$company',
partita_iva = '$partita_iva',
note = CONCAT('$note ',note),
operatore = '$operatore',
proprietario = '$proprietario',
venditore = '$venditore',
data_associazione_attivita = NOW()
 WHERE id = " . $isInDb[0]['id'] . ";";

            if (mysql_query($sql, CONNECT)) {

                if ($isInDb[0]['source_potential'] != $source_potential) {
                    $source_potentials = GRD('fl_campagne_attivita', $source_potential, 'oggetto');
                    actionTracer(16, $isInDb[0]['id'], 10, 1, 'Passaggio a: ' . $source_potentials['oggetto']);
                }
                if ($isInDb[0]['status_potential'] != $status_potential) {actionTracer(16, $isInDb[0]['id'], 10, 1, 'Cambio di Status: ' . $status_potentials[$status_potential]);}

                $this->contenuto['class'] = 'green';
                $this->contenuto['url'] = '#';
                $this->contenuto['esito'] = true;
                $this->contenuto['insert_id'] = $isInDb[0]['id'];
                $this->contenuto['info_txt'] = "Operazione eseguita correttamente! " . mysql_error();

                $this->cnv_makedata();
            }
        }

        $sql = "INSERT INTO `fl_potentials` (`id`, `in_use`, `id_cliente_fordpower` ,`id_cliente_drakkar` , `status_potential`, `sent_mail`, `marchio`, `campagna_id`, `source_potential`, `data_aggiornamento`, `is_customer`, `priorita_contatto`, `paese`, `company`, `job_title`, `nome`, `cognome`, `email`, `telefono`, `website`, `indirizzo`, `provincia`, `citta`, `cap`, `industry`, `ragione_sociale`, `partita_iva`, `fatturato_annuo`, `numero_dipendenti`, `messaggio`, `tipo_interesse`, `interessato_a`, `periodo_cambio_vettura`, `permuta`, `test_drive`, `data_test_drive`, `promo_pneumatici`,`pagamento_vettura`, `note`, `operatore`, `proprietario`, `venditore`, `data_creazione`, `data_assegnazione`,`data_scadenza`,`data_scadenza_venditore`,`data_associazione_attivita`)
VALUES (NULL, '0', '$id_cliente_fordpower' ,'$id_cliente_drakkar' , '$status_potential', '0', '0', '$campagna_id', '$source_potential', NOW(), '0', '$priorita_contatto', '$paese', '$company', '$job_title', '$nome', '$cognome', '$email', '$telefono', '$website', '$indirizzo', '$provincia', '$citta', '$cap', '$industry', '$company', '$partita_iva', '$fatturato_annuo', '$numero_dipendenti', '$messaggio', '$tipo_interesse', '$interessato_a', '$periodo_cambio_vettura', '$permuta', '$test_drive', '$data_test_drive',0, '$pagamento_vettura', '$note','$operatore', '$proprietario',  '$venditore', NOW(),NOW(),'','',NOW());";

        if (mysql_query($sql, CONNECT)) {

            $lead_id = mysql_insert_id();
            $mail_subject = "Nuovo Lead Inserito";

            $source_potentials = GRD('fl_campagne_attivita', $source_potential, 'oggetto');
            actionTracer(16, $lead_id, 10, 1, 'Prima attività: ' . $source_potentials['oggetto']);

// INSERIMENTO VEICOLO
            if (trim($marca . $modello) != '') {
                $sql = "INSERT INTO `fl_veicoli` (`id`, `workflow_id`, `parent_id`, `tipologia_veicolo`, `data_acquisto`, `pagamento_veicolo`, `marca`, `modello`, `colore`, `descrizione`, `anno_immatricolazione`, `chilometri_percorsi`, `alimentazione`, `targa`, `quotazione_attuale`, `data_creazione`, `data_aggiornamento`, `operatore`)
VALUES (NULL, '16', '$lead_id', '$tipologia_veicolo', '$data_acquisto', '$pagamento_veicolo', '$marca', '$modello', '$colore', '$descrizione', '$anno_immatricolazione', '$chilometri_percorsi', '$alimentazione', '$targa', '$quotazione_attuale', NOW(), NOW(), '$operatore');";
                if (mysql_query($sql, CONNECT)) {$mail_message .= "<p>Veicolo Inserito!</p>";}
            }

//INSERIMENTO SECONDO VEICOLO
            if (isset($marca2) && isset($modello2)) {
                $campi2 = array('tipologia_veicolo2', 'data_acquisto2', 'pagamento_veicolo2', 'marca2', 'modello2', 'colore2', 'descrizione2', 'anno_immatricolazione2', 'chilometri_percorsi2', 'alimentazione2', 'targa2', 'quotazione_attuale2');
                foreach ($campi2 as $chiave => $valore) {
                    if (!isset($$valore)) {
                        $$valore = '';
                    }

                }
                $sql2 = "INSERT INTO `fl_veicoli` (`id`, `workflow_id`, `parent_id`, `tipologia_veicolo`, `data_acquisto`, `pagamento_veicolo`, `marca`, `modello`, `colore`, `descrizione`, `anno_immatricolazione`, `chilometri_percorsi`, `alimentazione`, `targa`, `quotazione_attuale`, `data_creazione`, `data_aggiornamento`, `operatore`)
VALUES (NULL, '16', '$lead_id', '$tipologia_veicolo', '$data_acquisto', '$pagamento_veicolo2', '$marca2', '$modello2', '$colore2', '$descrizione2', '$anno_immatricolazione2', '$chilometri_percorsi2', '$alimentazione2', '$targa2', '$quotazione_attuale2', NOW(), NOW(), '$operatore');";
                if (mysql_query($sql2, CONNECT)) {$mail_message .= "<p>Veicolo 2 Inserito!</p>";}
            }

//INSERIMENTO PRENOTAZIONE TEST DRIVE
            if (isset($_POST['test_drive'][0])) {

                if (!isset($orario_test_drive)) {
                    $this->contenuto['esito'] = false;
                    $this->contenuto['info_txt'] = "Inserisci orario test drive";
                    $this->cnv_makedata();
                }
                if (!isset($data_test_drive)) {
                    $this->contenuto['esito'] = false;
                    $this->contenuto['info_txt'] = "Inserisci data test drive";
                    $this->cnv_makedata();
                }
                if (!isset($location_testdrive)) {
                    $this->contenuto['esito'] = false;
                    $this->contenuto['info_txt'] = "Inserisci sede per il test drive";
                    $this->cnv_makedata();
                }
                if (!isset($veicolo)) {
                    $this->contenuto['esito'] = false;
                    $this->contenuto['info_txt'] = "Inserisci veicolo test drive";
                    $this->cnv_makedata();
                }

                $start_meeting = $data_test_drive . ' ' . $orario_test_drive;
                $end_meeting = date('Y-m-d H:i', strtotime($start_meeting . ' +30 minutes'));

                $check = "SELECT * FROM `fl_testdrive` WHERE (location_testdrive > 1 || location_testdrive = $location_testdrive) AND veicolo = $veicolo AND start_meeting BETWEEN '$start_meeting' AND '$end_meeting'";
                mysql_query($check, CONNECT);
                if (mysql_affected_rows(CONNECT) > 0) {
                    $this->contenuto['esito'] = false;
                    $this->contenuto['info_txt'] = "Questo veicolo è occupato in questo giorno dalle $orario_test_drive alle " . date('H:i', strtotime($start_meeting . ' +30 minutes'));
                    $this->cnv_makedata();
                }

                $insertTest = "INSERT INTO `fl_testdrive` (`id`, `marchio`, `stato_testdrive`, `location_testdrive`, `veicolo`, `start_meeting`, `end_meeting`, `potential_rel`, `nominativo`, `note`, `callcenter`, `operatore`, `proprietario`, `data_creazione`, `data_aggiornamento`)
VALUES (NULL, '1', '0', '$location_testdrive', '$veicolo', '$start_meeting', '$end_meeting' , '$lead_id', '$nome $cognome', '', '$operatore', '$operatore', '0', NOW(), NOW());";
                if (mysql_query($insertTest, CONNECT)) {$mail_message .= "<p>Prenotazione test drive per il $start_meeting</p>";} else {
                    $this->contenuto['esito'] = false;
                    $this->contenuto['info_txt'] = mysql_error();

                    $this->cnv_makedata();
                }
            }

            $this->contenuto['class'] = 'green';
            $this->contenuto['url'] = '#';
            $this->contenuto['esito'] = true;
            $this->contenuto['insert_id'] = $lead_id;
            $this->contenuto['info_txt'] = "Operazione eseguita correttamente! " . mysql_error();

            $mail_body2 = str_replace("[*CORPO*]", $mail_message, mail_template);

        } else {

            $this->contenuto['class'] = 'red';
            $this->contenuto['esito'] = false;
            $this->contenuto['info_txt'] = "Error 1101: Problema inserimento lead.";
            $mail_message = "ERRORE DATABASE: " . mysql_error() . '<br>' . $mail_message;
            $mail_body3 = str_replace("[*CORPO*]", $mail_message, mail_template);
            mail('server@aryma.it', $this->contenuto['info_txt'], $sql . mysql_error());

        }

        $this->cnv_makedata();

    } // Lead in

    public function insert_lead_app()
    {

        $this->contenuto['esito'] = false;

        $mail_message = '';

        unset($_REQUEST['insert_lead_app']);
        unset($_REQUEST['token']);

        //echo $_REQUEST;

        foreach ($_REQUEST as $chiave1 => $valore1) {

            $array_json = json_decode($chiave1, true);

          

            foreach ($array_json as $chiave => $valore) {

                if ($chiave == 'telefono' || $chiave == 'telefono_alternativo') {
                    $valore = $this->cleanPhone($this->check($valore));
                }

                if (in_array($chiave, $this->obbligatorio)) {
                    if ($valore == "") {
                        $chiave = ucfirst(check($chiave));
                        $this->contenuto['esito'] = false;
                        $this->contenuto['info_txt'] = "Compila il campo $chiave";
                        $this->cnv_makedata();

                    }}

                if (in_array($chiave, $this->numerici)) {
                    if (!is_numeric(trim($valore))) {
                        $chiave = ucfirst(check($chiave));
                        $this->contenuto['esito'] = false;
                        $this->contenuto['info_txt'] = "Inserisci solo numeri in $chiave";
                        $this->cnv_makedata();
                    }}

                $$chiave = $this->check($valore);
                if (in_array($chiave, $this->date)) {
                    $$chiave = $this->convert_data($this->check($valore), 1);
                }

                $mail_message .= '<p>' . $chiave . ' = ' . $$chiave . '</p>';
            }
        }

        $status_potentials = array('Da Assegnare', 'Assegnato a BDC', 'Appuntamento', 'Non Interessato', 'Cliente', 'Valuta', 'Acquistato Concorrenza', 'Preventivo', 'Assegnato a Venditore', 'Eliminato');

        $campi = array('professione', 'sms_send', 'id_cliente_fordpower', 'id_cliente_drakkar', 'campagna_id', 'source_potential', 'priorita_contatto', 'paese', 'company', 'job_title', 'nome', 'cognome', 'email', 'telefono', 'website', 'indirizzo', 'provincia', 'citta', 'cap', 'industry', 'ragione_sociale', 'partita_iva', 'fatturato_annuo', 'numero_dipendenti', 'messaggio', 'tipo_interesse', 'interessato_a', 'periodo_cambio_vettura', 'permuta', 'test_drive', 'data_test_drive', 'pagamento_vettura', 'note', 'operatore', 'tipologia_veicolo', 'data_acquisto', 'pagamento_veicolo', 'marca', 'modello', 'colore', 'descrizione', 'anno_immatricolazione', 'chilometri_percorsi', 'alimentazione', 'targa', 'quotazione_attuale', 'proprietario', 'venditore');

        foreach ($campi as $chiave => $valore) {

            if (!isset($$valore)) {
                $$valore = '';
            }

        }

        if (!isset($source_potential)) {
            $source_potential = 1;
        }

        if (!isset($status_potential)) {
            $status_potential = 0;
        }

        $industry = $job_title;

        $telCheck = ($telefono != '' && $telefono != 0) ? " OR telefono LIKE '$telefono' " : '';
        $isInDb = GQS('fl_potentials', 'id,email,telefono,telefono_alternativo,source_potential,status_potential', "(email  != '' AND email  != '@' AND email  != 0 AND email LIKE '$email') $telCheck ");

        if (count($isInDb) > 0) {

            $sql = "UPDATE `fl_potentials` SET
                id_cliente_fordpower = '$id_cliente_fordpower' ,
                id_cliente_drakkar = '$id_cliente_drakkar' ,
                status_potential = '$status_potential',
                source_potential = '$source_potential',
                data_aggiornamento = NOW(),
                nome = '$nome',
                cognome =  '$cognome',
                email = '$email',
                telefono = '$telefono',
                website = '$website',
                indirizzo = '$indirizzo',
                provincia = '$provincia',
                citta = '$citta',
                cap = '$cap',
                industry = '$industry',
                company = '$company',
                partita_iva = '$partita_iva',
                note = CONCAT('$note ',note),
                operatore = '$operatore',
                proprietario = '$proprietario',
                venditore = '$venditore',
                data_associazione_attivita = NOW()
                WHERE id = " . $isInDb[0]['id'] . ";";

            if (mysql_query($sql, CONNECT)) {

                if ($isInDb[0]['source_potential'] != $source_potential) {
                    $source_potentials = GRD('fl_campagne_attivita', $source_potential, 'oggetto');
                    actionTracer(16, $isInDb[0]['id'], 10, 1, 'Passaggio a: ' . $source_potentials['oggetto']);
                }
                if ($isInDb[0]['status_potential'] != $status_potential) {actionTracer(16, $isInDb[0]['id'], 10, 1, 'Cambio di Status: ' . $status_potentials[$status_potential]);}

                $this->contenuto['class'] = 'green';
                $this->contenuto['url'] = '#';
                $this->contenuto['esito'] = true;
                $this->contenuto['insert_id'] = $isInDb[0]['id'];
                $this->contenuto['info_txt'] = "Operazione eseguita correttamente! " . mysql_error();

                $this->cnv_makedata();
            }
        }

        $sql = "INSERT INTO `fl_potentials` (`id`, `in_use`, `id_cliente_fordpower` ,`id_cliente_drakkar` , `status_potential`, `sent_mail`, `marchio`, `campagna_id`, `source_potential`, `data_aggiornamento`, `is_customer`, `priorita_contatto`, `paese`, `company`, `job_title`, `nome`, `cognome`, `email`, `telefono`, `website`, `indirizzo`, `provincia`, `citta`, `cap`, `industry`, `ragione_sociale`, `partita_iva`, `fatturato_annuo`, `numero_dipendenti`, `messaggio`, `tipo_interesse`, `interessato_a`, `periodo_cambio_vettura`, `permuta`, `test_drive`, `data_test_drive`, `promo_pneumatici`,`pagamento_vettura`, `note`, `operatore`, `proprietario`, `venditore`, `data_creazione`, `data_assegnazione`,`data_scadenza`,`data_scadenza_venditore`,`data_associazione_attivita`)
VALUES (NULL, '0', '$id_cliente_fordpower' ,'$id_cliente_drakkar' , '$status_potential', '0', '0', '$campagna_id', '$source_potential', NOW(), '0', '$priorita_contatto', '$paese', '$company', '$job_title', '$nome', '$cognome', '$email', '$telefono', '$website', '$indirizzo', '$provincia', '$citta', '$cap', '$industry', '$company', '$partita_iva', '$fatturato_annuo', '$numero_dipendenti', '$messaggio', '$tipo_interesse', '$interessato_a', '$periodo_cambio_vettura', '$permuta', '$test_drive', '$data_test_drive',0, '$pagamento_vettura', '$note','$operatore', '$proprietario',  '$venditore', NOW(),NOW(),'','',NOW());";
        
        if (mysql_query($sql, CONNECT)) {

            $lead_id = mysql_insert_id();
            $mail_subject = "Nuovo Lead Inserito";

            $source_potentials = GRD('fl_campagne_attivita', $source_potential, 'oggetto');
            actionTracer(16, $lead_id, 10, 1, 'Prima attività: ' . $source_potentials['oggetto']);

// INSERIMENTO VEICOLO
            if (trim($marca . $modello) != '') {
                $sql = "INSERT INTO `fl_veicoli` (`id`, `workflow_id`, `parent_id`, `tipologia_veicolo`, `data_acquisto`, `pagamento_veicolo`, `marca`, `modello`, `colore`, `descrizione`, `anno_immatricolazione`, `chilometri_percorsi`, `alimentazione`, `targa`, `quotazione_attuale`, `data_creazione`, `data_aggiornamento`, `operatore`)
VALUES (NULL, '16', '$lead_id', '$tipologia_veicolo', '$data_acquisto', '$pagamento_veicolo', '$marca', '$modello', '$colore', '$descrizione', '$anno_immatricolazione', '$chilometri_percorsi', '$alimentazione', '$targa', '$quotazione_attuale', NOW(), NOW(), '$operatore');";
                if (mysql_query($sql, CONNECT)) {$mail_message .= "<p>Veicolo Inserito!</p>";}
            }

            $this->contenuto['class'] = 'green';
            $this->contenuto['url'] = '#';
            $this->contenuto['esito'] = true;
            $this->contenuto['insert_id'] = $lead_id;
            $this->contenuto['info_txt'] = "Operazione eseguita correttamente! " . mysql_error();

            $mail_body2 = str_replace("[*CORPO*]", $mail_message, mail_template);

        } else {

            $this->contenuto['class'] = 'red';
            $this->contenuto['esito'] = false;
            $this->contenuto['info_txt'] = "Error 1101: Problema inserimento lead.";
            $mail_message = "ERRORE DATABASE: " . mysql_error() . '<br>' . $mail_message;
            $mail_body3 = str_replace("[*CORPO*]", $mail_message, mail_template);
            mail('server@aryma.it', $this->contenuto['info_txt'], $sql . mysql_error());

        }

        $this->cnv_makedata();

    } // insert_lead_app

    public function get_attivita()
    {

        $dati = array();

        $query = "SELECT * FROM `fl_campagne_attivita`  WHERE data_inizio < NOW() AND data_fine > NOW() and id > 1 ORDER BY data_creazione DESC";

        if ($risultato = mysql_query($query, CONNECT)) {

            while ($riga = mysql_fetch_array($risultato)) {

                array_push($dati, array(

                    'id' => $riga['id'],
                    'oggetto' => $riga['oggetto'],
                    'descrizione' => strip_tags(html_entity_decode($riga['descrizione'])),
                    'scadenza_default_ore' => $riga['scadenza_default_ore'],
                    'data_inizio' => $riga['data_inizio'],
                    'data_fine' => $riga['data_fine'],
                ));

            }
            $this->contenuto['class'] = 'green';
            $this->contenuto['esito'] = "OK";
            $this->contenuto['leads'] = $dati;

        } else {
            $this->contenuto['class'] = 'red';
            $this->contenuto['esito'] = "Error 1102: Errore caricamento." . mysql_error();
        }

        $this->cnv_makedata();
    }

    public function get_marche()
    { //torna le marche delle auto recuperate dal db , basato su quello di eurotax

        $dati = array();

        $query = "SELECT * FROM `fl_marca_eurotax`   ORDER BY `label` ASC";

        if ($risultato = mysql_query($query, CONNECT)) {

            while ($riga = mysql_fetch_array($risultato)) {

                array_push($dati, array(

                    'id' => $riga['id'],
                    'label' => $riga['label'],
                    'codice' => $riga['codice'], /* non usato nell'app */
                ));

            }
            $this->contenuto['class'] = 'green';
            $this->contenuto['esito'] = "OK";
            $this->contenuto['leads'] = $dati;

        } else {
            $this->contenuto['class'] = 'red';
            $this->contenuto['esito'] = "Error 1102: Errore caricamento." . mysql_error();
        }

        $this->cnv_makedata();
    }

    public function get_modelli()
    { //torna tutte le macchine nel db, dal 2000 al 2018

        $dati = array();

        $query = "SELECT * FROM `fl_modello_eurotax_new` ORDER BY `modello` ASC";

        if ($risultato = mysql_query($query, CONNECT)) {

            while ($riga = mysql_fetch_array($risultato)) {

                array_push($dati, array(

                    'id' => $riga['id'],
                    'id_marca_eurotax' => $riga['id_marca_eurotax'],
                    'modello' => $riga['modello'],
                    'cilindrata' => $riga['cilindrata'],
                    'codice_alimentazione' => $riga['codice_alimentazione'],
                    'anno' => $riga['anno'],
                ));

            }
            $this->contenuto['class'] = 'green';
            $this->contenuto['esito'] = "OK";
            $this->contenuto['leads'] = $dati;

        } else {
            $this->contenuto['class'] = 'red';
            $this->contenuto['esito'] = "Error 1102: Errore caricamento." . mysql_error();
        }

        $this->cnv_makedata();
    }

    public function insert_veicolo()
    {

        $tipologia_veicolo = '';
        $data_acquisto = '';
        $operatore = '';

        $this->contenuto['esito'] = false;

        $mail_message = '';
// Campi Obbligatori

        foreach ($_POST as $chiave => $valore) {

            if (in_array($chiave, $this->obbligatorio)) {
                if ($valore == "") {
                    $chiave = ucfirst(check($chiave));
                    $this->contenuto['esito'] = false;
                    $this->contenuto['info_txt'] = "Compila il campo $chiave";
                    $this->cnv_makedata();

                }}

            if (in_array($chiave, $this->numerici)) {
                if (!is_numeric(trim($valore))) {
                    $chiave = ucfirst(check($chiave));
                    $this->contenuto['esito'] = false;
                    $this->contenuto['info_txt'] = "Inserisci solo numeri in $chiave";
                    $this->cnv_makedata();
                }}

            $$chiave = $this->check($valore);
            if (in_array($chiave, $this->date)) {
                $$chiave = $this->convert_data($this->check($valore), 1);
            }

            $mail_message .= '<p>' . $chiave . ' = ' . $$chiave . '</p>';
        }

        $source_potential = 1;
        $campi = array('pagamento_veicolo', 'id_cliente_fordpower', 'id_drakkar', 'insert_id', 'parent_id', 'marca', 'modello', 'colore', 'descrizione', 'telaio', 'anno_immatricolazione', 'chilometri_percorsi', 'alimentazione', 'targa', 'quotazione_attuale', 'data_consegna', 'data_saldo');

        foreach ($campi as $chiave => $valore) {
            if (!isset($$valore)) {
                $$valore = '';
            }

        }

        $parent_id = $insert_id;

        $mail_subject = "Nuovo Veicolo Inserito";

// INSERIMENTO VEICOLO
        if (trim($marca . $modello) != '') {

            $sql = "INSERT INTO `fl_veicoli` (`id`, `id_drakkar`,`workflow_id`, `parent_id`, `tipologia_veicolo`, `data_acquisto`, `pagamento_veicolo`, `telaio`, `marca`, `modello`, `colore`, `descrizione`, `anno_immatricolazione`, `chilometri_percorsi`, `alimentazione`, `targa`, `quotazione_attuale`, `data_creazione`, `data_aggiornamento`,`data_saldo`,`data_consegna`, `operatore`)
VALUES (NULL, '$id_drakkar', 16, '$parent_id', '$tipologia_veicolo', '$data_acquisto', '$pagamento_veicolo', '$telaio','$marca', '$modello', '$colore', '$descrizione', '$anno_immatricolazione', '$chilometri_percorsi', '$alimentazione', '$targa', '$quotazione_attuale', NOW(), NOW(), '$data_saldo', '$data_consegna', '$operatore');";

            if (mysql_query($sql, CONNECT)) {

                if ($operatore > 1) {
                    mysql_query('UPDATE fl_potentials SET venditore = ' . $operatore . ', operatore = ' . $operatore . ' WHERE id = ' . $parent_id, CONNECT);
                }

                $mail_message .= "<p>Veicolo Inserito!</p>";

                $lead_id = mysql_insert_id();

                $this->contenuto['class'] = 'green';
                $this->contenuto['url'] = '#';
                $this->contenuto['esito'] = true;
                $this->contenuto['insert_id'] = $lead_id . mysql_error();
                $this->contenuto['info_txt'] = "Operazione eseguita correttamente!";
                $this->cnv_makedata();
                $mail_body2 = str_replace("[*CORPO*]", $mail_message, mail_template);

            } else {

                $this->contenuto['class'] = 'red';
                $this->contenuto['esito'] = false;
                $this->contenuto['info_txt'] = "Error 1101: Problema inserimento veicolo." . mysql_error();
                $mail_message = "ERRORE DATABASE: " . mysql_error() . '<br>' . $mail_message;
                $mail_body3 = str_replace("[*CORPO*]", $mail_message, mail_template);
                smail('server@aryma.it', "Problema inserimento nuovo veicolo su: " . ROOT, $mail_body3);
            }

        }

        $this->cnv_makedata();

    } // Lead in

    public function update_preventivo()
    {

        $this->contenuto['esito'] = false;

        $mail_message = '';
// Campi Obbligatori

        foreach ($_REQUEST as $chiave => $valore) {

            if (in_array($chiave, $this->obbligatorio)) {
                if ($valore == "") {
                    $chiave = ucfirst(check($chiave));
                    $this->contenuto['esito'] = false;
                    $this->contenuto['info_txt'] = "Compila il campo $chiave";
                    $this->cnv_makedata();

                }}

            if (in_array($chiave, $this->numerici)) {
                if (!is_numeric(trim($valore))) {
                    $chiave = ucfirst(check($chiave));
                    $this->contenuto['esito'] = false;
                    $this->contenuto['info_txt'] = "Inserisci solo numeri in $chiave";
                    $this->cnv_makedata();
                }}

            $$chiave = $this->check($valore);
            if (in_array($chiave, $this->date)) {
                $$chiave = $this->convert_data($this->check($valore), 1);
            }

            $mail_message .= '<p>' . $chiave . ' = ' . $$chiave . '</p>';

        }

        $updateLead = 'UPDATE fl_potentials SET priorita_contatto = 0, status_potential = 4  WHERE `id_cliente_fordpower` = ' . $lead_id;
        mysql_query($updateLead, CONNECT);

        $getLead = GQS('fl_potentials', 'id,`id_cliente_fordpower`,`id_cliente_drakkar`,nome,cognome,email', " id_cliente_fordpower = '$lead_id'");
        $updatePreventivi = 'UPDATE fl_rdo SET status_preventivo = 2, data_chiusura = \'' . $data_chiusura . '\' WHERE potential_id = ' . $getLead[0]['id'];
        if ($getLead[0]['id'] > 1) {
            mysql_query($updatePreventivi, CONNECT);

            $updatePreventivo = 'UPDATE fl_rdo SET status_preventivo = 3, data_chiusura = \'' . $data_chiusura . '\' WHERE id_fordpower = ' . $fordpower_id;
            mysql_query($updatePreventivo, CONNECT);
            $this->contenuto['class'] = 'green';
            $this->contenuto['esito'] = "OK";
            $this->contenuto['leads'] = $getLead[0]['id'];
            $this->contenuto['info_txt'] = 'Operazione eseguita';
        } else {
            mail('server@aryma.it', "Update preventivo senza LEAD", $mail_message . $updatePreventivi . $updatePreventivo . mysql_error());
            $this->contenuto['class'] = 'red';
            $this->contenuto['esito'] = "ERROR";
            $this->contenuto['leads'] = 0;
            $this->contenuto['info_txt'] = 'Operazione con errori';
        }

        $this->cnv_makedata();
    }

    public function insert_preventivo()
    {

        $this->contenuto['esito'] = false;

        $mail_message = '';
// Campi Obbligatori

        foreach ($_REQUEST as $chiave => $valore) {

            if (in_array($chiave, $this->obbligatorio)) {
                if ($valore == "") {
                    $chiave = ucfirst(check($chiave));
                    $this->contenuto['esito'] = false;
                    $this->contenuto['info_txt'] = "Compila il campo $chiave";
                    $this->cnv_makedata();

                }}

            if (in_array($chiave, $this->numerici)) {
                if (!is_numeric(trim($valore))) {
                    $chiave = ucfirst(check($chiave));
                    $this->contenuto['esito'] = false;
                    $this->contenuto['info_txt'] = "Inserisci solo numeri in $chiave";
                    $this->cnv_makedata();
                }}

            $$chiave = $this->check($valore);
            if (in_array($chiave, $this->date)) {
                $$chiave = $this->convert_data($this->check($valore), 1);
            }

            $mail_message .= '<p>' . $chiave . ' = ' . $$chiave . '</p>';
        }

        $source_potential = 1;
        $campi = array('insert_id', 'id_fordpower', 'marchio', 'anno_fiscale', 'status_preventivo', 'tipo_preventivo', 'cliente_id', 'potential_id', 'categoria_preventivo', 'supervisore', 'venditore', 'oggetto_preventivo', 'id_cliente_drakkar', 'marca', 'modello', 'colore', 'descrizione', 'allestimento', 'kilometraggio', 'alimentazione', 'operatore', 'proprietario', 'data_scadenza', 'data_apertura', 'data_emissione', 'proprietario', 'offerta', 'importo_ordine', 'note', 'data_creazione');

        foreach ($campi as $chiave => $valore) {
            if (!isset($$valore)) {
                $$valore = '';
            }

        }

        if ($potential_id == '') {
            $potential_id = $insert_id;
        }

        if ($data_creazione == '') {
            $data_creazione = date('Y-m-d');
        }

        if ($data_apertura == '') {
            $data_apertura = $data_creazione;
        }

        if (1) {

            $mail_subject = "Nuovo Preventivo Inserito";

// INSERIMENTO VEICOLO

            $sql = "INSERT INTO `fl_rdo` (`id`, `id_fordpower`, `marchio`, `anno_fiscale`, `status_preventivo`, `tipo_preventivo` ,`categoria_preventivo` , `cliente_id`, `potential_id`, `supervisore`, `venditore`, `oggetto_preventivo`, `marca`, `modello`, `allestimento`, `descrizione`, `produzione`, `operatore`, `proprietario`, `data_creazione`, `data_apertura`, `data_scadenza`, `data_emissione`, `stima`, `offerta`, `importo_ordine`, `note`) VALUES (NULL, '$id_fordpower', '$marchio', '$anno_fiscale', '$status_preventivo', '$tipo_preventivo','$categoria_preventivo', '$cliente_id', '$potential_id', '$supervisore', '$venditore', '$oggetto_preventivo', '$marca', '$modello', '$allestimento', '$descrizione', '', '$operatore', '$proprietario', '$data_creazione', '$data_apertura', '$data_scadenza', '$data_emissione', '',  '$offerta', '$importo_ordine','$note');";

            if (mysql_query($sql, CONNECT)) {
                $lead_id = mysql_insert_id();
                $mail_message .= "<p>Veicolo Inserito!</p>";
                $sedeRif = GRD('fl_account', $venditore, 'sede_principale');
                $sede = ($sedeRif['sede_principale'] > 0) ? ' sede = ' . $sedeRif['sede_principale'] . ', ' : '';
                $updateLead = 'UPDATE fl_potentials SET priorita_contatto = 2, ' . $sede . ' tipo_interesse = \'' . $tipo_preventivo . '\' , categoria_interesse = \'' . $categoria_preventivo . '\' , note = CONCAT(\'' . $note . '\',note), venditore = ' . $venditore . ' WHERE id = ' . $potential_id;
                mysql_query($updateLead, CONNECT);
            }

            $this->contenuto['class'] = 'green';
            $this->contenuto['url'] = '#';
            $this->contenuto['esito'] = true;
            $this->contenuto['insert_id'] = $lead_id;
            $this->contenuto['info_txt'] = 'Operazione eseguita';
            $this->cnv_makedata();
            $mail_body2 = str_replace("[*CORPO*]", $mail_message, mail_template);

        } else {
            $this->contenuto['class'] = 'red';
            $this->contenuto['esito'] = false;
            $this->contenuto['info_txt'] = "Error 1101: Problema inserimento lead." . mysql_error();
            $mail_message = "ERRORE DATABASE: " . mysql_error() . '<br>' . $mail_message;
            $mail_body3 = str_replace("[*CORPO*]", $mail_message, mail_template);
            smail('server@aryma.it', "Problema inserimento nuovo lead su: " . ROOT, $mail_body3);
        }

        $this->cnv_makedata();

    } // Lead in

    public function get_leads()
    {
        $leads = array();
        $query = "SELECT * FROM `fl_potentials`  WHERE 1 ORDER BY data_creazione DESC";

        if ($risultato = mysql_query($query, CONNECT)) {

            while ($riga = mysql_fetch_array($risultato)) {

                array_push($leads, array(

                    'id' => $riga['id'],
                    'nome' => $riga['nome'],
                    'cognome' => $riga['cognome'],
                    'priorita' => $riga['priorita'],
                ));

            }
            $this->contenuto['class'] = 'green';
            $this->contenuto['esito'] = "OK";
            $this->contenuto['leads'] = $leads;

        } else {
            $this->contenuto['class'] = 'red';
            $this->contenuto['esito'] = "Error 1102: Errore caricamento." . mysql_error();
        }

        $this->cnv_makedata();
    }

    public function get_items($item_rel, $condition = 0)
    {
        $query = "SELECT * FROM `fl_items` WHERE id != 1 AND attiva > 0 AND item_rel = $item_rel ORDER BY label ASC";
        $dati = array();
        if ($risultato = mysql_query($query, CONNECT)) {

            while ($riga = mysql_fetch_array($risultato)) {

                array_push($dati, array(

                    'id' => $riga['id'],
                    'label' => $riga['label'],
                    'descrizione' => $riga['descrizione'],
                ));

            }
            $this->contenuto['class'] = 'green';
            $this->contenuto['esito'] = "OK";
            $this->contenuto['dati'] = $dati;

        } else {
            $this->contenuto['class'] = 'red';
            $this->contenuto['esito'] = "Error 1102: Errore caricamento." . mysql_error();
        }

        $this->cnv_makedata();
    }

/*Funzioni Globali e Utility */
    public function lead_info($lead_id)
    {

        $this->app_start();
        $query = "SELECT * FROM `fl_potentials` WHERE `id` = $lead_id LIMIT 1";
        $risultato = mysql_query($query, CONNECT);
        $riga = @mysql_fetch_array($risultato);

        if (mysql_affected_rows(CONNECT) < 1) {
            $this->contenuto = array('id' => $lead_id, 'ragione_sociale' => 'Unknow', 'nome' => 'Unknow', 'cognome' => 'Unknow', "email" => 'Unknow', "telefono" => 'Unknow', "indirizzo" => 'Unknow', "cap" => 'Unknow', "localita" => 'Unknow', "citta" => 'Unknow');
        } else {
            $this->contenuto = array('id' => $riga['id'], 'ragione_sociale' => $riga['ragione_sociale'], 'nome' => $riga['nome'], 'cognome' => $riga['cognome'], "email" => $riga['email'], "telefono" => $riga['telefono'], "indirizzo" => $riga['indirizzo'], "cap" => $riga['cap'], "citta" => $riga['citta']);
        }
        $this->cnv_makedata();

    }
    public function get_page()
    {

        $query = "SELECT * FROM `fl_articles` WHERE `id`  = '" . $this->page_id . "' LIMIT 1";
        if ($risultato = mysql_query($query, CONNECT)) {
            $riga = mysql_fetch_array($risultato);

            $this->contenuto['esito'] = 1;
            $this->contenuto['info_txt'] = "Pagina";
            $this->contenuto['page_title'] = $riga['titolo'];
            $this->contenuto['page_content'] = $this->css . $this->convert($riga['articolo']);

        } else {

            $this->contenuto['esito'] = 0;
            $this->contenuto['info_txt'] = "Errore";

        }

        $this->cnv_makedata();
    }

    public function do_login()
    {
        $this->app_start();

        $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
        if ($this->user != 'sistema' && !preg_match($regex, strtolower(trim($this->user)))) {
            $this->contenuto['esito'] = 0;
            $this->contenuto['info_txt'] = "Per usare le api devi essere registrato";
            $this->cnv_makedata();
        }

        if ($this->user == "" || $this->password == "") {
            $this->contenuto['esito'] = 0;
            $this->contenuto['info_txt'] = "Inserisci user e password!";
            $this->cnv_makedata();
        }

        if ($this->user != "" && $this->password != "") {

            $this->password = md5($this->password);

            $query = "SELECT * FROM `fl_account` WHERE `password`  = '" . $this->password . "' AND `user`  = '" . $this->user . "' LIMIT 1";

            $risultato = mysql_query($query, CONNECT);

            $this->contenuto['esito'] = 0;
            $this->contenuto['info_txt'] = mysql_affected_rows();

            if (mysql_affected_rows(CONNECT) < 1) {
                $this->contenuto['esito'] = 0;
                $this->contenuto['info_txt'] = "Email o password errate!";
                $this->cnv_makedata();
            }

            $riga = mysql_fetch_array($risultato);

            if ($riga['attivo'] == 0) {
                $this->contenuto['esito'] = 0;
                $this->contenuto['info_txt'] = "Utente non attivo.";
                $this->cnv_makedata();
            }

            mysql_query("UPDATE `fl_account` SET `visite` = visite+1 WHERE '" . $this->password . "' AND `user`  = '" . $this->user . "' LIMIT 1;");

            $_SESSION['user'] = $riga['user'];
            $_SESSION['operatore'] = $riga['user'];
            $_SESSION['userid'] = $riga['id'];
            $_SESSION['nome'] = $riga['nominativo'];
            $_SESSION['mail'] = $riga['email'];
            $_SESSION['number'] = $riga['id'];
            $_SESSION['usertype'] = $riga['tipo'];
            $_SESSION['time'] = time();
            $_SESSION['idh'] = $_SERVER['REMOTE_ADDR'];
            $_SESSION['aggiornamento_password'] = $riga['aggiornamento_password'];
            $_SESSION['marchio'] = $riga['marchio'];
            // Fine Avvio Sessione
            $agent = @$_SERVER['HTTP_USER_AGENT'];
            $referer = @$_SERVER['HTTP_REFERER'];
            $lang = @$_SERVER['HTTP_ACCEPT_LANGUAGE'];
            $data = time();

            $this->contenuto['esito'] = $riga['attivo'];
            $this->contenuto['info_txt'] = "Login OK";
            $this->contenuto['usertype'] = $_SESSION['usertype'];
            $this->contenuto['user'] = $_SESSION['user'];
            $this->contenuto['operatore'] = $_SESSION['user'];
            $this->contenuto['email'] = $_SESSION['mail'];
            $this->contenuto['usr_id'] = $_SESSION['number'];
            $this->contenuto['token'] = session_id();
            $this->contenuto['nome'] = $_SESSION['nome'];
            $this->contenuto['aggiornamento_password'] = $riga['aggiornamento_password'];
            $this->contenuto['time'] = time();
            $this->contenuto['idh'] = $_SERVER['REMOTE_ADDR'];
            $this->contenuto['marchio'] = $_SESSION['marchio'];
        }
        $this->cnv_makedata();
    } // Login

    private function data_labels($item_rel, $condition = 0)
    {
        $query = "SELECT * FROM `fl_items` WHERE id != 1 AND attiva > 0 AND item_rel = $item_rel ORDER BY label ASC";
        $risultato = mysql_query($query, CONNECT);
        $rel_info = array();

        while ($riga = @mysql_fetch_array($risultato)) {
            $rel_info[$riga['id']] = $riga['label'];
        }

        if ($condition == 1) {
            $this->contenuto = array('dati' => $rel_info, 'esito' => 1, 'info_txt' => "dati caricati");
            echo json_encode($this->contenuto);
            mysql_close(CONNECT);
            exit;
        } else {
            return $rel_info;
        }
    }
    public function html_to_text($stringa, $quot = 0)
    {
        $stringa = str_replace("&gt;", ">", str_replace("&lt;", "<", str_replace("'", "&rsquo;", $stringa)));
        //sostituisc i <br/>
        $stringa = preg_replace("/<br\W*?\/>/", "\r\n", $stringa);
        //elimino tutti i tag
        $stringa = strip_tags($stringa);
        return $stringa . "\r\n\r\n\r\n\r\n\r\n\r\n\r\n";
    }
    public function mydate($mysqldate)
    {
        if ($mysqldate != '0000-00-00') {
            $phpdate = strtotime($mysqldate);
            return date('d/m/Y', $phpdate);
        } else {return '--';}
    }
    public function mydatetime($mysqldate)
    {
        $phpdate = strtotime($mysqldate);
        return date('H:i d/m/Y', $phpdate);
    }
    public function convert($var, $quot = 0)
    {
        $var = str_replace("../../../", ROOT, str_replace("&gt;", ">", str_replace("&lt;", "<", str_replace("'", "&rsquo;", $var))));
        if ($quot == 0) {$var = str_replace("&quot;", '"', $var);}
        str_replace('"', "&quot;", $var);
        return $var;
    }

    public function check($var)
    {
        $var = trim(str_replace("<", "&lt;", str_replace(">", "&gt;", @addslashes(@stripslashes(@str_replace('"', "&quot;", str_replace("'", "&rsquo;", $var)))))));
        return $var;
    }

    public function convert_data($data, $mode = 0)
    {

        if ($mode == 0) {
            $tempo = explode("/", $data);
            $extra = "";
            $data = @mktime(0, 0, 0, @$tempo[1], @$tempo[0], @$tempo[2]);
        } else if ($mode == 1) {
            $tempo = explode("/", $data);
            $extra = "";
            $data = trim(@$tempo[2]) . "-" . trim(@$tempo[1]) . "-" . trim($tempo[0]);
        }
        return $data;

    }

}
