<?php



require_once('../../fl_core/autentication.php');

$uploaddir = './fileupload/';

$numberFile=count($_FILES['file']['name']);


for($i=0 ; $i < $numberFile ; $i++) {
	

	echo $uploadfile = $uploaddir.basename($_FILES['file']['name'][$i]);


	if (move_uploaded_file($_FILES['file']['tmp_name'][$i], $uploadfile)) {
   

    header('Location: ./readcsv.php');

	} else {
    echo "Possibile attacco tramite file upload!\n";
	}


}



?>