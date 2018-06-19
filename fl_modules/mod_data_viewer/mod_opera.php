<?php 


require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 



$baseref = explode('?', $_SERVER['HTTP_REFERER']);
$rct = $baseref[0]; 
$val = (count($baseref) > 1) ? $baseref[1] : "";
$valb = explode('#',$val);
$vars = $valb[0];



if (isset($_GET['step'])) 
{   
$_SESSION['step'] = check($_GET['step']);
}

if (isset($_GET['ordine_type'])) 
{   
$_SESSION['ordine_type'] = check($_GET['ordine_type']);
}

if (isset($_GET['ordinamento'])) 
{   
$_SESSION['ordinamento'] = check($_GET['ordinamento']);
}

if (isset($_GET['ordinamento2'])) 
{   
$_SESSION['ordinamento2'] = check($_GET['ordinamento2']);
}

header("location: ".$rct);
exit;




?>
