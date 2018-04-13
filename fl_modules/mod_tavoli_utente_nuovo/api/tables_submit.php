<?php
  ini_set('display_errors', 'On');
  error_reporting(E_ALL | E_STRICT);
  require('../config/db.config.php');
  
  $layout_id = 15;
  $evento_id = 449;
  $tipo_tavolo_id = 2;
  $nome_tavolo = $_POST['nome_tavolo'];
  $numero_tavolo = $_POST['numero_tavolo'];
  // $data_creazione = strtotime("now");
  
  $sql = "INSERT INTO fl_tavoli (numero_tavolo, layout_id, evento_id, nome_tavolo, data_creazione, tipo_tavolo_id)
  VALUES ('$numero_tavolo', '$layout_id', '$evento_id', '$nome_tavolo', (now()), '$tipo_tavolo_id')";
  $result = mysqli_query($conn, $sql);

  mysqli_close($conn);