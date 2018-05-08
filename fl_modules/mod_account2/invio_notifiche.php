<?php
  /**
    Servizio di invio notifica per l'app 
    attualmente il servizio invia una notifica a un singolo id specificato nella variabile $id_dispositivo.
    Per inviarlo a tutti basta reperire tutti gli id di tutti i dispositivi che hanno installato ENClass.
    Per l'invio si utilizza anche il Server_key, il quale è unico per ogni app, che possiamo reperire nelle impostazioni
    del progetto al tab CLOUD MESSAGING. Questi dati sono assolutamente necessati per il corretto invio.
  */

  $titolo_notifica = "";
  $testo_notifica = "";
  $id_dispositivo = "";//il mio asus: c_o1jAqngBI:APA91bHj5sfmJzyohLeNMb9DYp16iiP6-RpQDQsItqSIG647ZqTILbDTqbxqy30yMD3WhZJkaoZZ_nlZ1_KIteR-rfMSiyd5k42882p2vH84F8t38sIFSEEsQVOcxwq__SCaWFcq9_lt
  $server_key = "AAAAfqi7TZ0:APA91bGYnURwodI2atyJvokwPU8efMDcrr4e86J8ZZngrkKeVmb3f0SGath8qRXzhZHd07Gy6XT467bpQXQ1QC4yXthxlVMl9oNvXkAEIWZK0dmI0VN6_U9NNNsgZ7ZsVhufK7fAka91";
  $indirizzo_server = "https://fcm.googleapis.com/fcm/send";

  if(isset($_POST['titolo_notifica']) && isset($_POST['testo_notifica']) && isset($_POST['id_dispositivo'])){
   
    $titolo_notifica = $_POST['titolo_notifica'];
    $testo_notifica = $_POST['testo_notifica'];
    $id_dispositivo = $_POST['id_dispositivo'];

    echo ("Server key: " . $server_key ."<br>Id dispositivo: " . $id_dispositivo . "<br>Titolo: " . $titolo_notifica . "<br>Testo: " . $testo_notifica);

  }else{
      echo ("Errore ricontrollare i dati inseriti: dati mancanti");
  }

  /*--------------------------------------------------- SEZIONE DI INVIO NOTIFICA --------------------------------------------------*/

  $fields = array (
            'registration_ids' => array($id_dispositivo),
            'data' => array (
                    "title" => $titolo_notifica,
                    "message" => $testo_notifica
            )
    );

    $fields = json_encode ( $fields );

    $headers = array (
            'Authorization: key=' . $server_key,
            'Content-Type: application/json'
    );

    $ch = curl_init ();
    curl_setopt ( $ch, CURLOPT_URL, 'fcm.googleapis.com/fcm/send');
    curl_setopt ( $ch, CURLOPT_POST, true );
    curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
    curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );

    $result = curl_exec ( $ch );
    echo "<br><br>risultato del curl: " . $result . "<br>curl: " . $ch;
    curl_close ( $ch );

?>
