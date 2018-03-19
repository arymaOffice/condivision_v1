<?php

include 'config.php';
if (isset($_POST["img"])) { // crea l'immagine

$encodedData = explode(',', $_POST["img"]);
$data = base64_decode($encodedData[1]);

$urlUploadImages = $_SERVER['DOCUMENT_ROOT'].'/images/toShare/';
$nameImage = $code . '.png';
$img = imagecreatefromstring($data);
if ($img) {
    header('Content-Type: image/png');
    imagepng($img, $urlUploadImages . $nameImage, 0);
    imagedestroy($img);
    $url['esito'] = 1;
    echo json_encode($url);
} else {
    $url['esito'] = 0;
    echo json_encode($url);
}
exit;
}

?>