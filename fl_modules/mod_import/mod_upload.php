<?php



require_once('../../fl_core/autentication.php');

$uploaddir = './fileupload/';

$numberFile=count($_FILES['file']['name']);


for($i=0 ; $i < $numberFile ; $i++) {
	

	$uploadfile = $uploaddir.strtolower(basename($_FILES['file']['name'][$i]));

	if (move_uploaded_file($_FILES['file']['tmp_name'][$i], $uploadfile)) {

	} else {
    echo "Possibile attacco tramite file upload!\n";
	}



}


	mysql_close(CONNECT);
    $refer=check($_POST['source_potential']);
    $campagna=check($_POST['campagna_id']);
    $status=check($_POST['status_potential']);
    $external= (isset($_POST['external'])) ? '&external='.check($_POST['external']) : '';

    header('Location: ./readcsv.php?campagna_id='.$campagna.'&status_potential='.$status.'&source_potential='.$refer.$external);

?>