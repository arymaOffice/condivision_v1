<?php 


// Controllo Login
session_start(); 
if(!isset($_SESSION['user'])){ Header("Location: ../../login.php"); exit; }
require('../../fl_core/core.php'); 






mysql_close(CONNECT);
header("Location: ".check($_SERVER['HTTP_REFERER'])); 
exit;

?>
