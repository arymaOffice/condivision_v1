<?php
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL); 

  require_once($_SERVER['DOCUMENT_ROOT'].'/fl_core/autentication.php');
  // require('../../fl_core/core.php');

  // $db_host = "localhost";
  // $db_user = "root";
  // $db_pass = "";
  // $db_name = "banquet_losmeraldo";

  $db_host = $host;
  $db_user = $login;
  $db_pass = $pwd;
  $db_name = $db;
  
  // Create and check Connection
  $conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

  /* Check connection */
  if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
?>
