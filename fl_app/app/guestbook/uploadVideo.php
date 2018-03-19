<?php


include_once("header.php");
$user_id=$userData['id'];
$conferma=0;



if($_SERVER['REQUEST_METHOD'] == 'POST')  {



try {

	$name = microtime(true).".png";
	$name2 = $_FILES["video"]["name"];
	
	$path ="./images/".$userData['matrimonio_id'];
    if(!file_exists($path)) mkdir($path, 0777, true);

	$newFile= $path . "/";		
	

if(is_uploaded_file($_FILES["video"]["tmp_name"])) {

if(move_uploaded_file($_FILES["video"]["tmp_name"], $path. "/" .$name2)) {

		if ($conferma==0) {	
			$commento='a';
			$data = date('Y-m-d h:i:s', time());
			$commento = preg_replace('/[^A-Za-z0-9]/', ' ', $_POST['commento']);
			$stmt = $mysqli->prepare("INSERT INTO cc_immagini(cartella,nome,user_id,commento,data_creazione,bloccato) VALUES (?,?, ?, ?,?,?)");
			$stmt->bind_param('ssissi',
			$newFile,
			$name2, 
			$user_id,
			$commento,
			$data,
			$blocco_commenti);

			$stmt->execute();
			$stmt->close();
			$conferma=1;
			unset($_POST);
		}
	}
	
} 
}
	catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}		
}

header("Location: gallery.php");
exit;

?>