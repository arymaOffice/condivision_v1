<?php

  require('../config/db.config.php');

  $query_commensali = "SELECT * FROM fl_tavoli_commensali";
  $result_commensali = mysqli_query($conn, $query_commensali);

  while($row = mysqli_fetch_assoc($result_commensali)){
     $json[] = $row;
  }

  $data['data'] = $json;
  $data['total'] = mysqli_num_rows($result_commensali);

  echo json_encode($data);
?>