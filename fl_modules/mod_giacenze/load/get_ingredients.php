<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
  require_once('../../../fl_core/autentication.php');
  // $host = "localhost";
  // $login = "root";
  // $pwd = "";
  // $db = "banquet_lacavallerizza";

  $conn = mysqli_connect($host,$login,$pwd,$db);

  // Check connection
  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }

  $sql = "SELECT a.id, a.codice_fornitore, b.descrizione,a.formato, a.unita_di_misura_formato, a.valore_di_conversione, a.prezzo_unitario, a.iva
  FROM fl_listino_acquisto a
  LEFT JOIN fl_materieprime b ON a.id_materia = b.id
  WHERE a.id >1";
  $result = mysqli_query($conn, $sql);
 

  if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
      $row['descrizione'] = html_entity_decode($row['descrizione']);
      $json[] = $row;
    }
    echo json_encode($json);
  } else {
    echo "0 risultati";
  }

  mysqli_close($conn);