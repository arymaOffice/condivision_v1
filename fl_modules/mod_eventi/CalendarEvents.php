<?php
/**
*                          CalendarEvents:
*1) setConnessione = imposta la connessione tramite i paramentri passati
*      - $classeConnessione: url della classe di connessione
*      - $user: username dell'utente nel db
*      - $host: nome dell'host
*      - $database: nome del database
*      - $password: la password dell'utente nel db
*
*2) getEvents = data una tabella e un anno restituisce una stringa html
*che compone il calendario di quell'anno ma senza i giorni in cui ci sono
*eventi. Per poter usare il metodo è necessario settare la connessione
*tramite setConnessione
*
*3) getCalendar = dato un anno restituisce la stringa html del calendatio
*di quell'anno, completo. Nota per usare questo metodo npn è necessario
*impostare la connessione con setConnessione
*
* NOTE sullo stile:
* - ogni singolo calendario è wrappato in un div con id: calendario
* all'interno del quale esiste una tabella. In questa classe non viene
*applicato nessuno stile, bisognerà applicarlo dall'esterno.
*/

class CalendarEvents {

  private static $eventi = array(0=>'');                                                                       //array che conterrà i giorni che contengono eventi



  /*---------------------------- METODO CHE ESEGUE LA RICERCA DEGLI EVENTI E RESTITUISCE IL CALENDARIO PULITO --------------------------*/
  public static function getEvents($sourceTbl,$anno, $mesi=0,$ambienti=array(),$metodo='getCalendar'){
    $or = $ambientiSql = '';

    if(!empty($ambienti)) {
      $ambientiSql = ' AND (';
      foreach($ambienti AS $key => $val) {
        $ambientiSql .= " $or ambienti LIKE '%".check($val)."%' OR ambiente_principale = ".check($val)." OR ambiente_1 = ".check($val)." OR ambiente_2 = ".check($val)." OR notturno = ".check($val);
        $or = ' OR  ';
      }
      $ambientiSql .= ' ) ';
    }

    $query_giorni = "SELECT data_evento FROM " . $sourceTbl.' WHERE stato_evento != 4 AND YEAR(data_evento) = '.$anno.' '.$ambientiSql;
    $risultato_giorni = mysql_query($query_giorni,CONNECT);
    while($record_giorni = mysql_fetch_assoc($risultato_giorni)){

      $date = substr($record_giorni['data_evento'], 0 , 10);                                  //prelevo solo i primi 10 caratteri della data, in modo tale da eliminare l'orario
      array_push(self::$eventi,$date);                                                        //inserisco nel vettore le date in cui ci sono gli eventi
    }

    $stringaCalendario = self::$metodo($anno,$mesi);                                                  //prelevo la stringa del calendario
    self::$eventi = array();                                                                        //svuoto l'array di ricerca evento
    return $stringaCalendario;                                                                      //restituisco la stringa

  }

  /*--------------------------------------- FUNZIONE CHE RESTITUISCE IL CALENDARIO DATO UN ANNO ----------------------------------------*/
  public static function getCalendar($anno_rif, $mesi){
    $stringa_calendario = "";

    $anno = $anno_rif;
    $meseTxt = array("Tutti","Gennaio","Febbraio","Marzo","Aprile","Maggio","Giugno","Luglio","Agosto","Settembre","Ottobre","Novembre","Dicembre");
    $giorni_sett = ["L","M","M","G","V","S","D"];                                                       //setto il vettore con le iniziali dei giorni che anfrà come intestazione dei giorni
    $contatore = 0;                                                                                     //conto i campi
    $flag_stampato = false;                                                                             //flag che mi indica se ho shiftato il giorno 1
    $match_anno;

    if(empty($mesi)){                                                                                   //se il vettore è vuoto allora stampo lo riempio con tutti i mesi
      $mesi = [1,2,3,4,5,6,7,8,9,10,11,12];
    }

    if($anno){

      preg_match('/[1-9]\d{3}/',$anno,$match_anno);                                                   //controllo il formato dell'anno, che cominci effettivamente con almeno in 1


      if(!empty($match_anno)){                                                                        //se il formato dell'anno inserito è corretto allora compongo il calendario

        $stringa_calendario .= "<table id='tabella_calendario'>";                                   //creo la tabella che conterrè tutti i mesi
        for ($i = 1; $i <= 12; $i++){

          if($i == 1 || $i == 7 ){                                                                 //se l'indice è 1 o 7 vuol dire che mi trovo o all'inizio o alla fine della riga, quindi devo creare una riga
            $stringa_calendario .= "<tr>";                                                       //creo la riga

          }

          $stringa_calendario .= "<td>";                                                          //creo il campo

          $contatore = 0;
          $flag_stampato = false;
          $numeroMese = date("n", mktime(0, 0, 0, $i, 1, $anno));
          $nome_mese = $meseTxt[$numeroMese];                                //prelevo il nome del mese
          $numero_giorni = cal_days_in_month(CAL_GREGORIAN, $i, $anno);                           //dal mese prelevo il numero dei giorni

          $stringa_calendario .= "<div class='calendario'>";
          if(in_array($i, $mesi)){//********************************************NEW
            $stringa_calendario .= "<table style=\"border: none;\">";
            $stringa_calendario .= "<tr>";
            $stringa_calendario .= "<th colspan='7'>" . $nome_mese . "</th>";
            $stringa_calendario .= "</tr>";
            $stringa_calendario .= "<tr>";
            for ($x = 0; $x < 7; $x++){                                                         //stampo le intestazioni dei giorni
              $stringa_calendario .= "<td>" . $giorni_sett[$x] . "</td>";
            }
            $stringa_calendario .= "</tr>";
            $stringa_calendario .= "<tr>";                                                  //stampo le righe contenenti i numeri dei giorni
            for($y = 1; $y <= $numero_giorni; $y++){

              $date = $anno . "/" . $i . "/" . $y;                                        //costruisco la data per prelevare il giorno della settimana
              $weekday = date('N', strtotime($date));                                     //prelevo il numero del giorno nella settimana

              for($e = 1; $e < $weekday; $e++){                                           //vado a shiftare il primo giorno stampando degli 0 dove
                if(!$flag_stampato){
                  $stringa_calendario .= "<td> - </td>";
                  $contatore++;
                }
              }
              $flag_stampato = true;                                                      //riporto il flag a true per evitare di shiftare gli altri giorni
              //$stringa_calendario .= "<td>" . $y . "</td>";                             //stampa del numero del giorno
              $contatore++;                                                               //incremento il contatore per vedere a che numero di campo sono arrivato


              if(empty(self::$eventi)){                                                   //controllo se il vettore eventi è vuoto, in caso affermativo stampo il giorno nel calendario
                $stringa_calendario .= "<td>" . $y . "</td>";
              }else{                                                                      //altrimenti vado alla ricerca dell'evento corrispondente al giorno in esame
                if(strlen($y) == 1){                                                    //compongo il giorno in 2 cifre
                  $giorno = "0" . $y;
                }else{
                  $giorno = $y;
                }

                if(strlen($i) == 1){                                                    //compongo il mese in 2 cifre
                  $mese = "0" . $i;
                }else{
                  $mese = $i;
                }

                $data_in_esame = $anno . "-" . $mese . "-" . $giorno;

                $ricerca = array_search($data_in_esame, self::$eventi);
                if($ricerca){                                                           //se la ricerca ha dato esito positivo allora stampo uno 0
                  $stringa_calendario .= "<td> - </td>";
                }else{                                                                  //altrimenti stampo il giorno
                  $stringa_calendario .= "<td>" . $y . "</td>";
                }
              }

              if($contatore == 7){                                                        //se il campo è 7 allora devo aggiungere un'altra riga e ricominciare
              $stringa_calendario .= "<tr></tr>";
              $contatore = 0;
            }
          }
          $stringa_calendario .= "</tr>";
          $stringa_calendario .= "</table>";

          }//********************************************NEW
          $stringa_calendario .= "</div>";




          $stringa_calendario .= "</td>";                                                         //chiudo il campo del singolo mese

          if($i == 6 || $i == 12){                                                                //se l'indice è 6 o 12 vuol dire che mi trovo alla fine e devo chiudere la riga
            $stringa_calendario .= "</tr>";                                                      //chiudo la riga
          }
        }

        $stringa_calendario .= "</table>";                                                          //chiudo la tabella
      }else{
        $stringa_calendario .= ("<br>Attenzione formato anno non corretto");
      }
    }else{
      $stringa_calendario .= "Attenzione, nessun anno specificato";
    }
    return $stringa_calendario;
  }

  /*------------------------------ Funzione che ritorna il caledario cliccabile --------------------------------------- */
  public static function getCalendarButton($anno_rif, $mesi){
    $stringa_calendario = "";

    $anno = $anno_rif;
    $meseTxt = array("Tutti","Gennaio","Febbraio","Marzo","Aprile","Maggio","Giugno","Luglio","Agosto","Settembre","Ottobre","Novembre","Dicembre");
    $giorni_sett = ["L","M","M","G","V","S","D"];                                                       //setto il vettore con le iniziali dei giorni che anfrà come intestazione dei giorni
    $contatore = 0;                                                                                     //conto i campi
    $flag_stampato = false;                                                                             //flag che mi indica se ho shiftato il giorno 1
    $match_anno;

    if(empty($mesi)){                                                                                   //se il vettore è vuoto allora stampo lo riempio con tutti i mesi
      $mesi = [1,2,3,4,5,6,7,8,9,10,11,12];
    }

    if($anno){

      preg_match('/[1-9]\d{3}/',$anno,$match_anno);                                                   //controllo il formato dell'anno, che cominci effettivamente con almeno in 1


      if(!empty($match_anno)){                                                                        //se il formato dell'anno inserito è corretto allora compongo il calendario

        $stringa_calendario .= "<table id='tabella_calendario'>";                                   //creo la tabella che conterrè tutti i mesi
        for ($i = 1; $i <= 12; $i++){

          if($i == 1 || $i == 7 ){                                                                 //se l'indice è 1 o 7 vuol dire che mi trovo o all'inizio o alla fine della riga, quindi devo creare una riga
            $stringa_calendario .= "<tr>";                                                       //creo la riga

          }

          $stringa_calendario .= "<td>";                                                          //creo il campo

          $contatore = 0;
          $flag_stampato = false;
          $numeroMese = date("n", mktime(0, 0, 0, $i, 1, $anno));
          $nome_mese = $meseTxt[$numeroMese];                                //prelevo il nome del mese
          $numero_giorni = cal_days_in_month(CAL_GREGORIAN, $i, $anno);                           //dal mese prelevo il numero dei giorni

          $stringa_calendario .= "<div class='calendario'>";
          if(in_array($i, $mesi)){//********************************************NEW
            $stringa_calendario .= "<table style=\"border: none;\">";
            $stringa_calendario .= "<tr>";
            $stringa_calendario .= "<th colspan='7'>" . $nome_mese . "</th>";
            $stringa_calendario .= "</tr>";
            $stringa_calendario .= "<tr>";
            for ($x = 0; $x < 7; $x++){                                                         //stampo le intestazioni dei giorni
              $stringa_calendario .= "<td>" . $giorni_sett[$x] . "</td>";
            }
            $stringa_calendario .= "</tr>";
            $stringa_calendario .= "<tr>";                                                  //stampo le righe contenenti i numeri dei giorni
            for($y = 1; $y <= $numero_giorni; $y++){

              $date = $anno . "/" . $i . "/" . $y;                                        //costruisco la data per prelevare il giorno della settimana
              $weekday = date('N', strtotime($date));                                     //prelevo il numero del giorno nella settimana

              for($e = 1; $e < $weekday; $e++){                                           //vado a shiftare il primo giorno stampando degli 0 dove
                if(!$flag_stampato){
                  $stringa_calendario .= "<td> - </td>";
                  $contatore++;
                }
              }
              $flag_stampato = true;                                                      //riporto il flag a true per evitare di shiftare gli altri giorni
              //$stringa_calendario .= "<td>" . $y . "</td>";                             //stampa del numero del giorno
              $contatore++;                                                               //incremento il contatore per vedere a che numero di campo sono arrivato


              if(empty(self::$eventi)){                                                   //controllo se il vettore eventi è vuoto, in caso affermativo stampo il giorno nel calendario
                $stringa_calendario .= "<td>" . $y . "</td>";
              }else{                                                                      //altrimenti vado alla ricerca dell'evento corrispondente al giorno in esame
                if(strlen($y) == 1){                                                    //compongo il giorno in 2 cifre
                  $giorno = "0" . $y;
                }else{
                  $giorno = $y;
                }

                if(strlen($i) == 1){                                                    //compongo il mese in 2 cifre
                  $mese = "0" . $i;
                }else{
                  $mese = $i;
                }

                $data_in_esame = $anno . "-" . $mese . "-" . $giorno;

                $ricerca = array_search($data_in_esame, self::$eventi);
                if($ricerca){                                                           //se la ricerca ha dato esito positivo allora stampo uno 0
                  //$stringa_calendario .= "<td> - </td>";
                  $stringa_calendario .= "<td style='color:red'>" . $y . "</td>";

                }else{                                                                  //altrimenti stampo il giorno
                  $stringa_calendario .= "<td><input type='checkbox' value='".$data_in_esame."' id='".$data_in_esame."' name='date_disponibilita[]'><label for='".$data_in_esame."'>" . $y . "</label></td>";
                }
              }

              if($contatore == 7){                                                        //se il campo è 7 allora devo aggiungere un'altra riga e ricominciare
              $stringa_calendario .= "<tr></tr>";
              $contatore = 0;
            }
          }
          $stringa_calendario .= "</tr>";
          $stringa_calendario .= "</table>";

          }//********************************************NEW
          $stringa_calendario .= "</div>";




          $stringa_calendario .= "</td>";                                                         //chiudo il campo del singolo mese

          if($i == 6 || $i == 12){                                                                //se l'indice è 6 o 12 vuol dire che mi trovo alla fine e devo chiudere la riga
            $stringa_calendario .= "</tr>";                                                      //chiudo la riga
          }
        }

        $stringa_calendario .= "</table>";                                                          //chiudo la tabella
      }else{
        $stringa_calendario .= ("<br>Attenzione formato anno non corretto");
      }
    }else{
      $stringa_calendario .= "Attenzione, nessun anno specificato";
    }
    return $stringa_calendario;
  }
  /* -------------------------------- Funzione che ritorna calendario in colonne ------------------------------ */
  /*------------------------------ Funzione che ritorna il caledario cliccabile --------------------------------------- */
  public static function getCalendarColumn($anno_rif, $mesi){
    $stringa_calendario = "";
    $anno = $anno_rif;
    $meseTxt = array("Tutti","Gennaio","Febbraio","Marzo","Aprile","Maggio","Giugno","Luglio","Agosto","Settembre","Ottobre","Novembre","Dicembre");
    $giorni_sett = ["zero","Lunedì","Martedì","Mercoledì","Giovedì","Venerdì","Sabato","Domenica"];                                                       //setto il vettore con le iniziali dei giorni che anfrà come intestazione dei giorni                                                                                   //conto i campi
    $match_anno;
    global $ambienti_disponibilta;
    global $codici_ambiente;
    $stringa_ambienti = '';
    foreach ($ambienti_disponibilta as $key => $value) {
      $stringa_ambienti .="<input type='checkbox' title=\"\" value='{{data}}.$key' id='{{data}}.$key' name='data-{{data}}[]' ><label id=\"environments\" class='' title='".$value."' for='{{data}}.$key'>".$codici_ambiente[$key]."</label>";

    }


    if(empty($mesi)){                                                                                   //se il vettore è vuoto allora stampo lo riempio con tutti i mesi
      $mesi = [1,2,3,4,5,6,7,8,9,10,11,12];
    }

    if($anno){

      preg_match('/[1-9]\d{3}/',$anno,$match_anno);                                                   //controllo il formato dell'anno, che cominci effettivamente con almeno in 1


      if(!empty($match_anno)){                                                                        //se il formato dell'anno inserito è corretto allora compongo il calendario

        $stringa_calendario .= "<div class=\"grid-container\">";                                   //creo la tabella che conterrè tutti i mesi
        for ($i = 1; $i <= 12; $i++){

          $numeroMese = date("n", mktime(0, 0, 0, $i, 1, $anno));
          $nome_mese = $meseTxt[$numeroMese];                                //prelevo il nome del mese
          $numero_giorni = cal_days_in_month(CAL_GREGORIAN, $i, $anno);                           //dal mese prelevo il numero dei giorni

          if(in_array($i, $mesi)){//********************************************NEW
            $stringa_calendario .= "<div class='grid-item'>";
            $stringa_calendario .= "<h3>" . $nome_mese . "</h3>";
            for($y = 1; $y <= $numero_giorni; $y++){

              if(empty(self::$eventi)){ //controllo se il vettore eventi è vuoto, in caso affermativo stampo il giorno nel calendario
                $data_in_esame = $anno . "-" . $numeroMese . "-" . $y;
                $nameOfDay = $giorni_sett[date('N', strtotime($data_in_esame))];
                $stringa_ambienti_replace = str_replace('{{data}}',$data_in_esame,$stringa_ambienti);
                $stringa_calendario .= "<p>$y $nameOfDay $stringa_ambienti_replace  </p>";
              }else{                                                                      //altrimenti vado alla ricerca dell'evento corrispondente al giorno in esame
                if(strlen($y) == 1){                                                    //compongo il giorno in 2 cifre
                  $giorno = "0" . $y;
                }else{
                  $giorno = $y;
                }

                if(strlen($i) == 1){                                                    //compongo il mese in 2 cifre
                  $mese = "0" . $i;
                }else{
                  $mese = $i;
                }

                $data_in_esame = $anno . "-" . $mese . "-" . $giorno;


                $nameOfDay = $giorni_sett[date('N', strtotime($data_in_esame))];
                $stringa_ambienti_replace = str_replace('{{data}}',$data_in_esame,$stringa_ambienti);
                                         //se la ricerca ha dato esito positivo allora stampo uno 0
                $stringa_calendario .= "<p class='giorni_rossi'><span style='width:100px;display: -webkit-inline-box;'>$y $nameOfDay</span> $stringa_ambienti_replace</p>";

              }
            }
            $stringa_calendario .= "</div>";

          }
          }//********************************************NEW


          $stringa_calendario .= "</div>";                                                          //chiudo la tabella
        }else{
          $stringa_calendario .= ("<br>Attenzione formato anno non corretto");
        }
      }else{
        $stringa_calendario .= "Attenzione, nessun anno specificato";
      }
      return $stringa_calendario;
    }
  }
  ?>
