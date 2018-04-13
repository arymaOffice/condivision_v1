<?php
  require('db.config.php');
  // Create Query
  $query_tavoli = "SELECT * FROM fl_tavoli ORDER BY numero_tavolo asc";
  $result_tavoli = mysqli_query($conn, $query_tavoli);

  $query_commensali = "SELECT * FROM fl_tavoli_commensali ORDER BY nome desc";
  $result_commensali = mysqli_query($conn, $query_commensali);

mysqli_close($conn);
