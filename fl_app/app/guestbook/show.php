<html>
<head>
<title>Caricamento canvas</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<style>
body {
    font-size: 16px;
    font-family: Arial;
}
#preview {
    display:none;
}
#base64 {
    display:none;
}
</style>
</head>
<body>
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

function png2jpgBase64($originalFile, $outputFile, $quality) {
    $image = imagecreatefrompng($originalFile);
    imagejpeg($image, $outputFile, $quality);
    imagedestroy($image);
}


//$base64size = strlen($_POST['base64']);
//$f = base64_decode($_POST['base64']);
$name = microtime(true).".png";
$name2 = microtime(true).".jpeg";
//file_put_contents("./$name", $f);

$image = base64_to_jpeg( $_POST['base64'], $name );


png2jpg($name, $name2, 50);



$image2 = 'data:image/png;base64,'+$_POST['base64'];
$image2 = imagecreatefrompng($image2);
imagejpeg($image2, $name2 , 50);
imagedestroy($image2);


#header("Content-type: image/jpeg");
#header("Content-Disposition: attachment; filename=\"canvas.jpeg\"");
#echo $f;
#die();




//$image = 'http://images.itracki.com/2011/06/favicon.png';
// Read image path, convert to base64 encoding
//$imageData = base64_encode(file_get_contents($image));

// Format the image SRC:  data:{mime};base64,{data};
//$src = 'data: '.mime_content_type($image).';base64,'.$imageData;

//$src = 'data: '.mime_content_type($image).';base64,'.$_POST['base64'];

// Echo out a sample image
//echo '<img src="'.$src.'">';


?>
<h2>Test caricamento canvas</h2>
<p>base 64: <?= $_POST['base64'];?> byte</p>
<p>Trasmessi: <?=$base64size;?> byte (in base64)</p>
<p>Nuova immagine: <?=filesize("./$name");?> byte</p>
<p><img src="<?=$name;?>"/></p>
</body>
</html>