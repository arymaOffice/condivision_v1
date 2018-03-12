<?php 


// Controllo Login
session_start(); 
if(!isset($_SESSION['user'])){ Header("Location: ../../login.php"); exit; }
require('../../fl_core/core.php'); 


?>
