<?php

class Eurotax{

    private $grant_tyoe = "client_credentials";
    private $endpint_generic = "https://webservice.motornet.it/";
    private $username;
    private $password;
    private $token = "";

    /*----------------------------------------------------- COSTRUTTORE CHE PRELEVA IL TOKEN ---------------------------------------------------------------*/
    function __construct($client_id, $client_secret){
        $url_richiesta = $this->endpint_generic . "token.php";                                                          //costruisco l'url
        $fields = "grant_type=" . $this->grant_tyoe ."&client_id=" . $client_id . "&client_secret=" .  $client_secret;  //costruisco i field

        $data = $this->execute_curl($url_richiesta,$fields);                                                            //effettuo la richesta prelevando l'intera risposta
        
        $this->username = $client_id;                                                                                   //setto lo username
        $this->password = $client_secret;                                                                               //setto la password
        $this->token = $data['access_token'];                                                                           //dalla risposta prelevo solo il token settando la proprietà omonima
    }

    /*-------------------------------------------------------------- METODO DI LOGIN -----------------------------------------------------------------------*/
    function login(){
        $url_richiesta = $this->endpint_generic . "restws/login";                                                       //costruisco l'url
        $fields = "access_token=" . $this->token ."&username=" . $this->username . "&password=" .  $this->password;     //costruisco i field
        $data = json_encode($this->execute_curl($url_richiesta,$fields));                                               //effettuo la richesta prelevando l'intera risposta
        return $data;
    }

    /*---------------------------------------------- METODO CHE RECUPERA I DATI DELL'UTENTE LOGGATO --------------------------------------------------------*/
    function getDatiUtente(){
        $url_richiesta = $this->endpint_generic . "restws/getDatiUtente";                                               //costruisco l'url
        $fields = "access_token=" . $this->token;                                                                       //costruisco i field
        $data = json_encode($this->execute_curl($url_richiesta,$fields));                                               //effettuo la richesta prelevando l'intera risposta
        return $data;
    }

    /*-------------------------------------------------- METODO CHE PRELEVA LE MARCHE DELLE AUTO -----------------------------------------------------------*/
    function getMarche($tipoVeicolo, $libro = ""){
        $this->checkVeicolo($tipoVeicolo);                                                                              //controllo il tipo di veicolo inserito, per evitare di effettuare richieste non necessarie
        
        $url_richiesta = $this->endpint_generic . "restws/getMarche";                                                   //costruisco l'url
        $fields = "access_token=" . $this->token ."&tipo_veicolo=" . $tipoVeicolo;                                      //costruisco i field

        if($libro != ""){                                                                                               //se libro non è vuoto allora lo aggiungo agli altri field
            $fields .= "&libro=" .  $libro;
        }

        $data = json_encode($this->execute_curl($url_richiesta,$fields));                                               //effettuo la richesta prelevando l'intera risposta
        return $data;
    }

    /*----------------------------------- METODO CHE RESTITUISCE L'ELENCO DEGLI ANNI DEI MODELLI DI UNA MARCA ----------------------------------------------*/
    function getAnni($tipoVeicolo,$marca){
        $this->checkVeicolo($tipoVeicolo);                                                                              //controllo il tipo di veicolo inserito, per evitare di effettuare richieste non necessarie
        
        $url_richiesta = $this->endpint_generic . "restws/getAnni";                                                     //costruisco l'url
        $fields = "access_token=" . $this->token ."&tipo_veicolo=" . $tipoVeicolo . "&marca=" .  $marca;                //costruisco i field

        $data = json_encode($this->execute_curl($url_richiesta,$fields));                                               //effettuo la richesta prelevando l'intera risposta
        return $data;
    }

    /*----------------------------- METODO CHE RESTITUISCE LE TIPOLOGIE IN BASE ALL'ANNO E ALLA MARCA DEL TIPO DI VEICOLO ----------------------------------*/
    function getTipologia($tipoVeicolo, $marca, $anno, $libro=""){
        $this->checkVeicolo($tipoVeicolo);                                                                              //controllo il tipo di veicolo inserito, per evitare di effettuare richieste non necessarie
        
        $url_richiesta = $this->endpint_generic . "restws/getTipologia";                                                  //costruisco l'url
        $fields = "access_token=" . $this->token ."&tipo_veicolo=" . $tipoVeicolo . "&marca=" .  $marca . "&anno=" . $anno;//costruisco i field

        if($libro != ""){
           $fields .= "&libro=" . $libro;
        }

        $data = json_encode($this->execute_curl($url_richiesta,$fields));                                               //effettuo la richesta prelevando l'intera risposta
        return $data;
    }

    /*------------------------------------------ METODO CHE RESTITUISCE I MODELLI DI UNA MARCA DI VEICOLI --------------------------------------------------*/
    function getModelli($tipoVeicolo, $marca, $anno, $libro ="", $codice_tipo = "" ){
        $url_richiesta = $this->endpint_generic . "restws/getModelli";                                                  //costruisco l'url
        $fields = "access_token=" . $this->token ."&tipo_veicolo=" . $tipoVeicolo . "&marca=" .  $marca . "&anno=" . $anno;//costruisco i field
        
        if($libro != ""){
           $fields .= "&libro=" . $libro; 
        }

        if($codice_tipo != ""){
            $fields .= "&codice_tipo=" . $codice_tipo;
        }
        
        $data = json_encode($this->execute_curl($url_richiesta,$fields));                                               //effettuo la richesta prelevando l'intera risposta
        return $data;
    }

    /*----------------------------------------- METODO CHE RESTITUISCE IL NUMERO DI PORTE DI UN MODELLO DI VEICOLO ------------------------------------------*/
    function getPorte($tipoVeicolo, $modello, $anno){
        $this->checkVeicolo($tipoVeicolo);                                                                              //controllo il tipo di veicolo inserito, per evitare di effettuare richieste non necessarie

        $url_richiesta = $this->endpint_generic . "restws/getPorte";                                                    //costruisco l'url
        $fields = "access_token=" . $this->token ."&tipo_veicolo=" . $tipoVeicolo . "&modello=" .  $modello . "&anno=" . $anno;//costruisco i field
        $data = json_encode($this->execute_curl($url_richiesta,$fields));                                               //effettuo la richesta prelevando l'intera risposta
        return $data;
    }

    /*------------------------------- METODO CHE RESTITUISCE I TIPI DI UN MODELLO DI VEICOLI COMMERCIALI/INDUSTRIALI ---------------------------------------*/
    function getTipoVINC($tipoVeicolo, $modello, $porte){
        $this->checkVeicoloInd($tipoVeicolo);                                                                           //controllo il tipo di veicolo inserito, per evitare di effettuare richieste non necessarie

        $url_richiesta = $this->endpint_generic . "restws/getTipoVINC";                                                 //costruisco l'url
        $fields = "access_token=" . $this->token ."&tipo_veicolo=" . $tipoVeicolo . "&modello=" .  $modello . "&porte=" . $porte;//costruisco i field
        $data = json_encode($this->execute_curl($url_richiesta,$fields));                                               //effettuo la richesta prelevando l'intera risposta
        return $data;
    }

    /*----------------------- METODO CHE RESTITUISCE I PASSI DISPONIBILI PER UN MODELLO DI VEICOLI COMMERCIALI/INDUSTRIALI ---------------------------------*/
    function getPassiVINC($tipoVeicolo, $tipoVINC, $modello, $porte){
        $this->checkVeicoloInd($tipoVeicolo);                                                                           //controllo il tipo di veicolo inserito, per evitare di effettuare richieste non necessarie
        
        $url_richiesta = $this->endpint_generic . "restws/getPassiVINC";                                                 //costruisco l'url
        $fields = "access_token=" . $this->token ."&tipo_veicolo=" . $tipoVeicolo . "&tipo=" . $tipoVINC . "&modello=" .  $modello . "&porte=" . $porte;//costruisco i field
        $data = json_encode($this->execute_curl($url_richiesta,$fields));                                               //effettuo la richesta prelevando l'intera risposta
        return $data;
    }

    /*--------------------------------- METODO CHE RESTITUISCE L'ELENCO DELLE VERSIONI DI UN MODELLO DI VEICOLO --------------------------------------------*/
    /*VERIFICARE*/
    function getVersioni($tipoVeicolo, $modello="", $marca="", $anno="", $codiceCostruttore="", $porte="", $passo="", $libro=""){
        $this->checkVeicolo($tipoVeicolo);                                                                           //controllo il tipo di veicolo inserito, per evitare di effettuare richieste non necessarie
        
        $url_richiesta = $this->endpint_generic . "restws/getVersioni";                                                 //costruisco l'url
        $fields = "access_token=" . $this->token ."&tipo_veicolo=" . $tipoVeicolo;//costruisco i field

        if($marca == "" || $codiceCostruttore == ""){
            if($modello == ""){
                echo "Errore! manca il paramentro 'modello'";
                exit;
            }else{
                $fields .= "&modello=" . $modello;
            } 
        }

        if($modello == "" || $codiceCostruttore == ""){
            if($marca == ""){
                echo "Errore! manca il parametro 'marca'";
                exit;
            }else{
                $fields .= "&marca=" . $marca;
            }
        }

        if($codiceCostruttore == ""){
            if($anno == ""){
                echo "Errore! manca il parametro 'anno'";
            }
            $fields .= "&anno=" . $anno;
        }else{
            $fields .= "&codice_costruttore=" . $codiceCostruttore;
        }

        if(($modello == "" && $anno == "") || ($marca == "" && $anno == "")){
            if($codiceCostruttore == ""){
                echo "Errore! manca il parametro 'codiceCostruttore'";
                exit;
            }else{
                $fields .= "&codice_costruttore=" . $codiceCostruttore;
            }
        }

        if($modello != ""){
            $fields .= "&modello=" . $modello;
        }

        if($marca != ""){
            $fields .= "&marca=" . $marca;
        }

        if($anno != ""){
            $fields .= "&anno=" . $anno;
        }

        if($porte != ""){
            $fields .= "&porte=" . $porte;
        }

        if($passo != ""){
            $fields .= "&passo=" . $passo;
        }

        if($libro != ""){
            $fields .= "&libro=" . $libro;
        }

        $data = json_encode($this->execute_curl($url_richiesta,$fields));                                               //effettuo la richesta prelevando l'intera risposta
        return $data;
    }

    /*--------------------------------------- METODO CHE RESTITUISCE L'ELENCO DEGLI ACCESSORI DI UN VEICOLO ------------------------------------------------*/
    function getAccessori($tipoVeicolo, $anno, $mese, $codiceMotornet = "", $codiceEurotax = ""){
        $this->checkVeicolo($tipoVeicolo);                                                                              //controllo il tipo di veicolo inserito, per evitare di effettuare richieste non necessarie

        $url_richiesta = $this->endpint_generic . "restws/getAccessori";                                                //costruisco l'url
        $fields = "access_token=" . $this->token ."&tipo_veicolo=" . $tipoVeicolo;                                      //costruisco i field

        if($codiceEurotax == ""){
            if($codiceMotornet == ""){
                echo "Errore| manca il paramentro 'codiceEurotax'";
                exit;
            }else{
                $fields .= "&codice_motornet=" . $codiceMotornet;
            }
        }else{
            $fields .= "&codice_eurotax=" . $codiceEurotax;
        }

        if($codiceMotornet == ""){
            if($codiceEurotax == ""){
                echo "Errore! manca il parametro 'codiceEurotax'";
                exit;
            }else{
                $fields .= "&codice_eurotax=" . $codiceEurotax;
            }
        }else{
            $fields .= "&codice_motornet=" . $codiceMotornet;
        }

        $fields .= "&anno=$anno&mese=$mese";
        
        $data = json_encode($this->execute_curl($url_richiesta,$fields));                                               //effettuo la richesta prelevando l'intera risposta
        return $data;
    }

    /*----------------------------------------- METODO CHE RESTITUISCE I DETTAGLI TECNICI DI UNA VETTURA ---------------------------------------------------*/
    function getDettaglioAuto($codiceMotornet="", $codiceEurotax = ""){
        $url_richiesta = $this->endpint_generic . "restws/getDettaglioAuto";                                                //costruisco l'url
        $fields = "access_token=" . $this->token;                                      //costruisco i field

        if($codiceEurotax == ""){
            if($codiceMotornet == ""){
                echo "Errore! manca il parametro 'codiceMotornet'";
                exit;
            }else{
                $fields .= "&codice_motornet=$codiceMotornet";
            }
        }else{
            $fields .= "&codice_eurotax=$codiceEurotax";
        }

        if($codiceMotornet == ""){
            if($codiceEurotax == ""){
                echo "Errore! manca il parametro 'codiceEurotax'";
            }else{
                $fields .= "&codice_eurotax=$codiceEurotax";
            } 
        }else{
            $fields .= "&codice_motornet=$codiceMotornet";
        }

        $data = json_encode($this->execute_curl($url_richiesta,$fields));                                               //effettuo la richesta prelevando l'intera risposta
        return $data;
    }

    /*------------------------------------- METODO CHE RESTITUISCE I DETTAGLI DI UNA MOTO/CICLOMOTORE/SCOOTER ----------------------------------------------*/
    function getDettaglioMoto($codiceMotornet="", $codiceEurotax = ""){
        return $this->getDettaglioAuto($codiceMotornet,$codiceEurotax);
    }

    /*------------------------------------------ METODO CHE RESTITUISCE I DETTAGLI DI UN VEICOLO INDUSTRIALE -----------------------------------------------*/
    function getDettaglioVind($codiceMotornet="", $codiceEurotax = ""){
        return $this->getDettaglioAuto($codiceMotornet,$codiceEurotax);
    }

    /*----------------------------------------- METODO CHE RESTITUISCE I DETTAGLI DI UN VEICOLO COMMERCIALE ------------------------------------------------*/
    function getDettaglioVcom($codiceMotornet="", $codiceEurotax = ""){
        return $this->getDettaglioAuto($codiceMotornet,$codiceEurotax);
    }

    /*--------------------------------------------- METODO DI VALUTAZIONE DI UN DETERMINATO VEICOLO --------------------------------------------------------*/
    function getValutazione($tipoVeicolo, $anno, $mese, $km, $codiceMotornet="", $codiceEurotax="", $accessori=array(), $codiceOmologazione="", $targa="", $telaio="", $costoHManMec="", $costoHManCarr="", $autocarro=1, $lavoriMecc=array(), $lavoriCarr=array(), $valutazioneDealer="", $guidKey="", $annoValutazione="", $meseValutazione=""){
        $listaAccessori = "#";
        $listaLavoriMecc = "#";
        $listaLavoriCarr = "#";

        $this->checkVeicolo($tipoVeicolo);                                                                              //controllo il tipo di veicolo inserito, per evitare di effettuare richieste non necessarie

        $url_richiesta = $this->endpint_generic . "restws/getValutazione";                                                //costruisco l'url
        $fields = "access_token=" . $this->token . "&tipo_veicolo=" . $tipoVeicolo . "&anno=" . $anno . "&mese=" . $mese . "&km=" . $km . "&autocarro=" . $autocarro; //costruisco i field

        if($codiceEurotax == ""){
            if($codiceMotornet == ""){
                echo "Errore! manca il parametro 'codiceMotornet'";
                exit;
            }else{
                $fields .= "&codice_motornet=$codiceMotornet";
            }
        }else{
            $fields .= "&codice_eurotax=$codiceEurotax";
        }

        if($codiceMotornet == ""){
            if($codiceEurotax == ""){
                echo "Errore! manca il parametro 'codiceEurotax'";
            }else{
                $fields .= "&codice_eurotax=$codiceEurotax";
            } 
        }else{
            $fields .= "&codice_motornet=$codiceMotornet";
        }

        if(!empty($accessori)){                                                                                         //se la lista accessori non è vuota
            foreach ($accessori as $valore) {                                                                           //prelevo ogni elemento dal vettore accessori e li concateno separandoli da un '#'
                $listaAccessori .= $valore . "#";
            }
            $listaAccessori = substr($listaAccessori, 0 , strlen($listaAccessori)-1);                                   //prelevo l'intera stringa tranne l'ultimo carattere
            $listaAccessori = base64_encode($listaAccessori);                                                           //codifico la lista accessori in base64
            $fields .= "&accessori=$listaAccessori";
        }

        if($codiceOmologazione != ""){
            $fields .= "&codice_omologazione=$codiceOmologazione";
        }

        if($targa != ""){
            $fields .= "&targa=$targa";
        }

        if($telaio != ""){
            $fields .= "&telaio=$telaio";
        }

        if($costoHManMec != ""){
            $fields .= "&costo_orario_manodopera_meccanica=$costoHManMec";
        }

        if($costoHManCarr != ""){
            $fields .= "&costo_orario_manodopera_carrozzeria=$costoHManCarr";
        }

        if(!empty($lavoriMecc)){                                                                                        //controllo che la lista dei lavori meccanica non sia vuota
            foreach ($lavoriMecc as $valore) {                                                                          //prelevo ogni elemento dal vettore accessori e li concateno separandoli da un '#'
                $listaLavoriMecc .= $valore . "#";
            }
            $listaLavoriMecc = substr($listaLavoriMecc, 0 , strlen($listaLavoriMecc)-1);                                //prelevo l'intera stringa tranne l'ultimo carattere
            $listaLavoriMecc = base64_encode($listaLavoriMecc);                                                         //codifico la lista accessori in base64
            $fields .= "&lavori_meccanica=$listaLavoriMecc";
        }

        if(!empty($lavoriCarr)){                                                                                        //controllo che la lista dei lavori carrozzeria non sia vuota
            foreach ($lavoriCarr as $valore) {                                                                          //prelevo ogni elemento dal vettore accessori e li concateno separandoli da un '#'
                $listaLavoriCarr .= $valore . "#";
            }
            $listaLavoriCarr = substr($listaLavoriCarr, 0 , strlen($listaLavoriCarr)-1);                                //prelevo l'intera stringa tranne l'ultimo carattere
            $listaLavoriCarr = base64_encode($listaLavoriCarr);                                                         //codifico la lista accessori in base64
            $fields .= "&lavori_carrozzeria=$listaLavoriCarr";
        }

        if($valutazioneDealer != ""){
            $fields .= "&valutazione_dealer=$valutazioneDealer";
        }

        if($guidKey != ""){
            $fields .= "&guidkey=$guidKey";
        }

        if($annoValutazione != ""){
            $fields .= "&anno_valutazione=$annoValutazione";
        }

        if($meseValutazione != ""){
            $fields .= "&mese_valutazione=$meseValutazione";
        }

        $data = json_encode($this->execute_curl($url_richiesta,$fields));                                               //effettuo la richesta prelevando l'intera risposta
        return $data;
    }

    /*--------------------------------------------- METODO CHE RICERCA UN VEICOLO IN BASE ALLA TARGA -------------------------------------------------------*/
    function searchTarga($targa){
        $url_richiesta = $this->endpint_generic . "restws/searchTarga";                                                //costruisco l'url
        $fields = "access_token=" . $this->token . "&targa=" . $targa;
        $data = json_encode($this->execute_curl($url_richiesta,$fields));                                               //effettuo la richesta prelevando l'intera risposta
        return $data;
    }

    /*------------------------------------------ METODO CHE RICERCA UN VEICOLO TRAMITE CODICE EUROTAX ------------------------------------------------------*/
    function searchEurotax($tipoVeicolo, $codiceEurotax){
        $this->checkVeicolo($tipoVeicolo);                                                                              //controllo il tipo di veicolo inserito, per evitare di effettuare richieste non necessarie

        $url_richiesta = $this->endpint_generic . "restws/searchEurotax";
        $fields = "access_token=" . $this->token . "&tipo_veicolo=" . $tipoVeicolo . "&codice_eurotax=" . $codiceEurotax;
        $data = json_encode($this->execute_curl($url_richiesta,$fields));                                               //effettuo la richesta prelevando l'intera risposta
        return $data;
    }

    /*---------------------------------------- METODO CHE RICERCA UN VEICOLO IN BASE ALO CODICE OMOLOGAZIONE -----------------------------------------------*/
    function searchOmologazione($tipoVeicolo, $codiceOmologazione, $dataImmatricolazione){
        $this->checkVeicolo($tipoVeicolo);                                                                              //controllo il tipo di veicolo inserito, per evitare di effettuare richieste non necessarie

        $url_richiesta = $this->endpint_generic . "restws/searchOmologazione";
        $fields = "access_token=" . $this->token . "&tipo_veicolo=" . $tipoVeicolo . "&codice_omologazione=" . $codiceOmologazione . "&data_immatricolazione=" . $dataImmatricolazione;
        $data = json_encode($this->execute_curl($url_richiesta,$fields));                                               //effettuo la richesta prelevando l'intera risposta
        return $data;
    }

    /*------------------------------------------------- METODO RECUPERA L'XML DI UNA VALUTAZIONE -----------------------------------------------------------*/
    function getXML($guidKey){
        $url_richiesta = $this->endpint_generic . "restws/getXML";
        $fields = "access_token=" . $this->token . "&guidkey=" . $guidKey;
        $data = json_encode($this->execute_curl($url_richiesta,$fields));                                               //effettuo la richesta prelevando l'intera risposta
        return $data;
    }

    /*----------------------------- METODO CHE RECUPERA GLI ANNI/MESI SU CUI EFFETTUARE VALUTAZIONI RETRODATATE --------------------------------------------*/
    function getAnniValutazione($tipoVeicolo){
        $this->checkVeicolo($tipoVeicolo); 

        $url_richiesta = $this->endpint_generic . "restws/getAnniValutazione";
        $fields = "access_token=" . $this->token . "&tipo_veicolo=" . $tipoVeicolo;
        $data = json_encode($this->execute_curl($url_richiesta,$fields));                                               //effettuo la richesta prelevando l'intera risposta
        return $data;
    }

    /*-------------------------------------------- METODO CHE RECUPERA LE RIPARAZIONI DI UN VEICOLO --------------------------------------------------------*/
    function getRiparazioni($tipoVeicolo, $codiceMotornet="", $codiceEurotax=""){
        $this->checkVeicolo($tipoVeicolo); 

        $url_richiesta = $this->endpint_generic . "restws/getRiparazioni";
        $fields = "access_token=" . $this->token . "&tipo_veicolo=" . $tipoVeicolo;

        if($codiceEurotax == ""){
            if($codiceMotornet == ""){
                echo "Errore! manca il parametro 'codiceMotornet'";
                exit;
            }else{
                $fields .= "&codice_motornet=$codiceMotornet";
            }
        }else{
            $fields .= "&codice_eurotax=$codiceEurotax";
        }

        if($codiceMotornet == ""){
            if($codiceEurotax == ""){
                echo "Errore! manca il parametro 'codiceEurotax'";
            }else{
                $fields .= "&codice_eurotax=$codiceEurotax";
            } 
        }else{
            $fields .= "&codice_motornet=$codiceMotornet";
        }

        $data = json_encode($this->execute_curl($url_richiesta,$fields));                                               //effettuo la richesta prelevando l'intera risposta
        return $data;
    }

    /*------------------------------------------------ METODO DI STAMPA DEL TOKEN USATO PER DEBUG ----------------------------------------------------------*/
    /*
    function printToken(){
        if($this->token != ""){
            echo $this->token;
        }else{
            echo "nessun toker ricevuto";
        }
    }
    */

    /********************************************************************************************************************************************************/
    /*                                                                                                                                                      */
    /*****************************************************************  UTILITY  ****************************************************************************/
    /*                                                                                                                                                      */
    /********************************************************************************************************************************************************/

    /*---------------------------------------------------- METODO PRIVATO DI ESECUZIONE DEL CURL -----------------------------------------------------------*/
    private function execute_curl($url_richiesta, $fields){
        
        $ch = curl_init();                                                                                              //inizializzo il curl
        curl_setopt ( $ch, CURLOPT_URL, $url_richiesta );                                                               //imposto il curl e l'url parziale della richiesta
        curl_setopt($ch, CURLOPT_POSTFIELDS,  $fields);                                                                 //inserisco i campi
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);                                                                    //imposto a true il prelievo della risposta
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);                                                                  //imposto un timeout
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);                                                                //disattivo la verifica del certificato SSL altrimenti genera un errore del certificato
        curl_setopt($ch, CURLOPT_HEADER, 0);                                                                            //imposto a false l'inclusione degli header
        curl_setopt($ch, CURLOPT_POST, 1);                                                                              //imposto a true il post 
        
        if(curl_exec($ch)){                                                                                             //verifico che il curl sia andato a buon file
            $data = json_decode(curl_exec($ch),true);                                                                   //prelevo la risposta decodificandola dal json in array
        }else{                                                                                                          //altrimenti se il curl non è andato a buon fine
            echo "Errore nella richiesta: " . curl_error($ch);                                                          //mostro l'errore generato
            exit;
        }
        curl_close($ch);                                                                                                //chiudo il curl
        return $data;
    }

    /*---------------------------------------- METODO PRIVATO CHE EFFETTUA IL CONTROLLO DEL TIPO DI VEICOLO(tutti) --------------------------------------------*/
    private function checkVeicolo($tipoVeicolo){
        
        switch (strtoupper( $tipoVeicolo)){                                                                                          //controllo che $tipoVeicolo sia tra quelli permessi altrimenti genero un errore
            case "AUTO":
            case "MOTO":
            case "VCOM":
            case "VIND":
                break;
            default:
                echo "Tipo veicolo errato. I tipi sono: auto,moto,vcom,vind";
                exit;
        }
    }

    /*----------------------------- METODO PRIVATO CHE EFFETTUA IL CONTROLLO DEL TIPO DI VEICOLO INDUSTRIALE/COMMERCIALE ------------------------------------*/
    private function checkVeicoloInd($tipoVeicolo){
        
        switch (strtoupper($tipoVeicolo)){                                                                                          //controllo che $tipoVeicolo sia tra quelli permessi altrimenti genero un errore
            case "VCOM":
            case "VIND":
                break;
            default:
                echo "Tipo veicolo errato. I tipi sono: vcom,vind";
                exit;
        }
    }
    
}

?>