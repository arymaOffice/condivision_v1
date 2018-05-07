<?php 
require_once('../../../../fl_core/autentication.php');
include('../../../../fl_core/category/cms.php');

if(isset($_GET['product'])) $_SESSION['product'] = check($_GET['product']);
$gateway = (isset($_GET['ric'])) ? 'ricarica' : 'pin';
header('Location: ../../'.$gateway.'.php');
exit;

?>