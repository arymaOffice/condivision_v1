
<?php
  require_once('../../../fl_core/autentication.php');
  // $host = "localhost";
  // $login = "root";
  // $pwd = "";
  // $db = "banquet_lacavallerizza";

  $conn = mysqli_connect($host,$login,$pwd,$db);
  // CONNECT
  // Check connection
  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }

  $codice = $_POST['codice'];
  $descrizione = $_POST['descrizione'];

  if (isset($codice)) {
    $sql = "SELECT id, codice_fornitore, formato, unita_di_misura_formato, valore_di_conversione, prezzo_unitario, iva FROM fl_listino_acquisto WHERE LOWER(codice_fornitore) LIKE '%$codice%' OR codice_fornitore LIKE '%$codice%'";
    $result = mysqli_query($conn, $sql);
  } elseif (isset($descrizione)) {
    $sql = "SELECT id, codice_fornitore, formato, unita_di_misura_formato, valore_di_conversione, prezzo_unitario, iva FROM fl_listino_acquisto WHERE LOWER(formato) LIKE '%$descrizione%' OR formato LIKE '%$descrizione%'";
    $result = mysqli_query($conn, $sql);
  }

  if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
      $json[] = $row;
    }
    echo json_encode($json);
  } else {
    echo "0 resultati";
  }

  mysqli_close($conn);
// $formato1 = array('id'=>'1','codice_fornitore'=>'A0001','descrizione'=>'PRODOTTO CIAO 1','unita_di_misura'=>'KG','qty'=>1,'prezzo'=>'10.50','iva'=>'22');
// $formato2 = array('id'=>'2','codice_fornitore'=>'A0002','descrizione'=>'PRODOTTO CIAO 2','unita_di_misura'=>'LT','qty'=>1,'prezzo'=>11.50,'iva'=>10);
// $formato3 = array('id'=>'3','codice_fornitore'=>'A0003','descrizione'=>'PRODOTTO CIAO 3','unita_di_misura'=>'PZ','qty'=>1,'prezzo'=>12.50,'iva'=>22);
// $formato4 = array('id'=>'4','codice_fornitore'=>'A0004','descrizione'=>'PRODOTTO CIAO 4','unita_di_misura'=>'PZ','qty'=>1,'prezzo'=>13.50,'iva'=>4);
// $formato5 = array('id'=>'5','codice_fornitore'=>'A0005','descrizione'=>'PRODOTTO CIAO 5','unita_di_misura'=>'KG','qty'=>1,'prezzo'=>14.50,'iva'=>10);

  
