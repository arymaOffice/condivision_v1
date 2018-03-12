<?php 

require_once('../../fl_core/autentication.php');
require('../../fl_core/class/ARY_dataInterface.class.php');
include('../../fl_core/dataset/array_statiche.php');

$data_set = new ARY_dataInterface();
$aliquote = $data_set->data_retriever('fl_aliquote','aliquota','WHERE id > 1');

unset($chat);
$tab_id = 81;
$fattura_id = check($_GET['DAiD']);
include("../../fl_inc/headers.php");
 ?>
 
<body style=" background: rgb(241, 241, 241) none repeat scroll 0% 0%; text-align: left; padding: 20px;">


<h1>Funzionalità non attiva</h1>
</body></html>