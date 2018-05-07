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

  $sql = "SELECT id,titolo_ricorrenza,data_evento
  FROM fl_eventi_hrc
  WHERE data_evento
  BETWEEN CURDATE( )
  AND (
  NOW( ) + INTERVAL 15
  DAY
  )";

  $result = mysqli_query($conn, $sql);
 
  // $json = array('id'=>'0','titolo'=>'Nessun Evento');
  
 while($row = mysqli_fetch_assoc($result)) {
      $json[] = array('id'=>$row['id'],'titolo'=>html_entity_decode($row['titolo_ricorrenza'].' del '.mydate($row['data_evento'])));
  }
 

  echo json_encode($json);
  mysqli_close($conn);




?>