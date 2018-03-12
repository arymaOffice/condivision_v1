<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 



//Declare which files you want to merge (must be in the order you want them to be displayed
require_once('../../fl_set/librerie/pclZip/pclzip.lib.php');

$zipName = $_SESSION['number'].'export.zip';
unlink($zipName);

$archive = new PclZip($zipName);

$files = implode(',',$_POST['prints']);

$v_list = $archive->add($files,PCLZIP_OPT_REMOVE_ALL_PATH);

  if ($v_list == 0) {
    die("Error : ".$archive->errorInfo(true));
  }

header('Location: '.$zipName);
exit;

?>




