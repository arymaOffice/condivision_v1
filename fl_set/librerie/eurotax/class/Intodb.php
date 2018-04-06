<?php
require_once 'Eurotax.php';
require_once 'classe_connessione_database.php';

class Intodb{
    var $et;                                                                                                            //oggetto di tipo Eurotax
    var $connessione;                                                                                                   //connessione al db interno
    
    /*------------------------------------------------------------------ COSTRUTTORE ------------------------------------------------------------------------------*/
    function __construct(){
        $this->et = new Eurotax("nacci", "eurotax-motornet");
        $this->connessione = database::getConnectionWhithParam("localhost","root","","eurotax");
    }

    /*-------------------------------------------------- METODO CHE PRELEVA LE MARCHE E LE INSERICE NEL DB --------------------------------------------------------*/
    function preleva_marche($tipoVeicolo){                                                                              //auto,moto,vind,vcom
        $id_veicolo = 1;                                                                                                //contiene l'id del veicolo
        $arrayMarche = array();                                                                                         //array che conterrè le marche prelevate con getMarche di $et

        switch (strtoupper($tipoVeicolo)) {                                                                             //controllo il veicolo per assegnare l'id corrispettivo
            case 'AUTO':
                $id_veicolo = 1;
                break;
            case 'MOTO':
                $id_veicolo = 2;
                break;
            case 'VIND':
                $id_veicolo = 3;
                break;
            case 'VCOM':
                $id_veicolo = 4;
                break;
        }

        $arrayMarche = json_decode($this->et->getMarche($tipoVeicolo),true);                                                  //prelevo le marche decodificandole dal json

        try{
            foreach ($arrayMarche as $key_marche => $value_marche) {                                                    //eseguo un ciclo per prelevare le marche. Il ciclo più esterno non contiene direttamente nome e acronimo ma un altro vettore, quindi è necessario innestare un altro ciclo
                if(is_array($value_marche)){
                    foreach ($value_marche as $key => $value) {                                                         //ciclo annidato per prelevare effettivamente il nome e l'acronimo
                        echo "Nome: " . $value['nome'] . " Acronimo: " . $value['acronimo'] . "<br>";                   //stampo il nome e l'acronimo per debug
                        $insert_update = "INSERT INTO marche_veicoli (acronimo,nome,tipo_veicolo) VALUES ( '" . $value['acronimo'] .  "', '" . $value['nome'] . "' , '" . $id_veicolo . "')ON DUPLICATE KEY UPDATE nome ='" . $value['nome'] .  "' , tipo_veicolo = '" . $id_veicolo . "'";//query che inserisce, se non presente già, o aggiorna, se presente già un record con quella chiave
                        //echo $insert_update . "<br>";                                                                 //stampo le query per debug
                        $this->connessione->query($insert_update);                                                            //eseguo la query inserendo o aggiornando la tabella 'marche_veicoli'
                    }
                } 
            }
        }catch(Exception $e){                                                                                           //catturo l'eccezione mostrandone il messaggio
            echo "Si è verificato un errore: " . $e->getMessage();
        }
        echo "Operazione conclusa, tatella 'marche_veicoli' aggiornata !<br>";
    }

    /*--------------------------------------------------- METODO CHE PRELEVA I MODELLI E LI ISERISCE NEL DB -----------------------------------------------------*/
    function preleva_modelli($tipoVeicolo, $acronimo, $anno){
        $arrayModellie = array();
        $acronimo = strtoupper($acronimo);
        $id_veicolo = 1;

        switch (strtoupper($tipoVeicolo)) {                                                                             //controllo il veicolo per assegnare l'id corrispettivo
            case 'AUTO':
                $id_veicolo = 1;
                break;
            case 'MOTO':
                $id_veicolo = 2;
                break;
            case 'VIND':
                $id_veicolo = 3;
                break;
            case 'VCOM':
                $id_veicolo = 4;
                break;
        }

        $arrayModelli = json_decode($this->et->getModelli($tipoVeicolo,$acronimo,$anno),true);

        try{
            foreach ($arrayModelli as $key_modelli => $value_modelli) {
                if(is_array($value_modelli)){
                    foreach ($value_modelli as $key => $value) {
                        echo "Codice Gamma: " . $value['cod_gamma_mod'] . " Descrizione gamma: " . $value['desc_gamma_mod'] . " Descrizione modello: " . $value['descrizione_modello']. "Descrizione gruppo storico: " . $value['descrizione_gruppo_storico'] ." Tipo veicolo: " . $id_veicolo . " Acronimo: " . $acronimo . "<br><br>";
                        $insert_update = "INSERT INTO modelli (cod_gamma_mod,desc_gamma_mod,da,a,codice_gruppo_storico,descrizione_gruppo_storico,codice_serie_gamma,descrizione_serie_gamma,codice_modello,descrizione_modello,acronimo,tipo_veicolo) VALUES ( '" . $value['cod_gamma_mod'] .  "', '" . htmlentities($value['desc_gamma_mod']) . "' , '" . $value['da'] . "' , '" . $value['a'] . "' , '" . $value['codice_gruppo_storico'] . "' , '" . htmlentities($value['descrizione_gruppo_storico']) . "' , '" . $value['codice_serie_gamma'] . "' , '" . htmlentities($value['descrizione_serie_gamma']) . "' , '" . $value['codice_modello'] . "' , '" . htmlentities($value['descrizione_modello']) . "' , '" . $acronimo ."' , '" . $id_veicolo ."')ON DUPLICATE KEY UPDATE desc_gamma_mod ='" . htmlentities($value['desc_gamma_mod']) .  "' , da = '" . $value['da'] . "' , a = '" . $value['a'] . "'  , codice_gruppo_storico = '" . $value['codice_gruppo_storico'] . "' , descrizione_gruppo_storico = '" . htmlentities($value['descrizione_gruppo_storico']) . "' , codice_serie_gamma = '" . $value['codice_serie_gamma'] . "' , descrizione_serie_gamma = '" . htmlentities($value['descrizione_serie_gamma']) . "' , codice_modello = '" . $value['codice_modello'] . "' , descrizione_modello = '" . htmlentities($value['descrizione_modello']) . "' , acronimo='" . $acronimo . "', tipo_veicolo= '" . $id_veicolo . "'";//query che inserisce, se non presente già, o aggiorna, se presente già un record con quella chiave
                        $this->connessione->query($insert_update);
                    }
                }
            }
        }catch(Exception $e){
            echo "Si è verificato un errore: " . $e->getMessage();
        }
        echo "Operazione conclusa, tatella 'modelli' aggiornata !<br>";
    }

       
}
?>