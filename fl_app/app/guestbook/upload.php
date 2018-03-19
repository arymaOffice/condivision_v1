<?php

function base64_to_jpeg( $base64_string, $output_file ) {
    $ifp = fopen( $output_file, "wb" ); 
    fwrite( $ifp, base64_decode( $base64_string) ); 
    fclose( $ifp ); 
    return( $output_file ); 
}

function png2jpg($originalFile, $outputFile, $quality) {
    $image = imagecreatefrompng($originalFile);
    imagejpeg($image, $outputFile, $quality);
    imagedestroy($image);
}

/*function png2jpgBase64($originalFile, $outputFile, $quality) {
    $image = imagecreatefrompng($originalFile);
    imagejpeg($image, $outputFile, $quality);
    imagedestroy($image);
}*/


//error_reporting(E_ALL);
//ini_set('display_errors', 1);
include_once("header.php");
$user_id=$userData['id'];
$conferma=0;



if($_SERVER['REQUEST_METHOD'] == 'POST')  {

try {

	//$base64size = strlen($_POST['base64']);
	//$f = base64_decode($_POST['base64']);
	$name = microtime(true).".png";
	$name2 = microtime(true).".jpeg";
	//file_put_contents("./$name", $f);
	$base64= $_POST['base64'];
	$image = base64_to_jpeg( $base64, $name );
	
	$path ="./images/".$userData['matrimonio_id'];
    		if (!file_exists($path)) {
    			mkdir($path, 0777, true);
				}
	$newFile=$path . "/";		
	png2jpg($name,$newFile.$name2, 50); //importante
	unlink($name);
	
	//$image2 = 'data:image/png;base64,'+$base64;
	//$image2 = imagecreatefrompng($image2);
	//imagejpeg($image2, $name2 , 50);
//	if(move_uploaded_file($image2, $path. "/" .$name2)) {
				//$insert_sql = "INSERT INTO cc_immagini(nome,user_id,commento) VALUES ('".$newFile."',1,'".$commento."')";
				//mysqli_query($conn, $insert_sql) or die("database error: ". mysqli_error($conn));	
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
//	}
	imagedestroy($image2);		
} 
	catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}		
}



?>