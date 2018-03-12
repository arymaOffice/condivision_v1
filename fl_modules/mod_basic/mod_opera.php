<?php 

require_once('../../fl_core/autentication.php');
include('../../fl_core/dataset/items_rel.php');
include('../../fl_core/dataset/array_statiche.php');




if(isset($_REQUEST['creaElemento'])) {   

$array = array('id'=>123,'nominativo'='Michele Fazio');
echo json_encode($array);

}

?>