<?php
include_once('config.php');//inclusione fiel configurazione

//query seleziona gallery
$queryGallery="SELECT id,label,descrizione,file FROM $dms WHERE parent_id = 3 AND resource_type = 0 AND account_id = $account_id";
$queryGallery=$mysqli->query($queryGallery);

$templateSingolaGallery = '<!-- GALLERY --><figure class="isg-portfolio-item" data-filter="{{id}}"><a id="gallery{{id}}" href="#"></a>
<img src="{{src}}" alt="gallery {{label}}" /><i class="fa fa-search"></i><figcaption><h4>{{label}}</h4><p>{{descrizione}}</p></figcaption></figure>';
$scriptModello = 'jQuery(\'#gallery{{id}}\').on(\'click\', function (e) {"use strict"; e.preventDefault(); jQuery(this).lightGallery({dynamic: true, zoom: true, fullScreen: true, autoplay: false, autoplayControls: true, thumbnail: true, download: true, counter: true, actualSize: true, dynamicEl: [ {{elements}} ] }); return false; });';

$gallery = '';//conterrà gallery
$galleryIMG = '';//conterrà le gallery con le immagini
$gallerySCRIPT = '';//conterrà gli per ogni gallery con le immagini
//creazione menù delle gallery
if($mysqli->affected_rows>0){
	while ($row = $queryGallery->fetch_assoc()){
		$prima = $endpointIMMAGINI.'default.jpg';
		$galleryName = ($row['label'] != '')? $row['label'] : 'Gallery '.$row['id'];
		$gallery .= '<li data-filter="'.$row['id'].'">'.$galleryName.'</li>';//elemento del menù
		$galleryIMG.= $templateSingolaGallery;
		$galleryIMG = str_replace('{{id}}', $row['id'] ,$galleryIMG);//categoria
		$galleryIMG = str_replace('{{label}}', $row['label'] ,$galleryIMG);//label
		$galleryIMG = str_replace('{{descrizione}}', $row['descrizione'] ,$galleryIMG);//descrizione
		//per singola gallery seleziono immagini e creo lo script
		$queryImages = "SELECT label,descrizione,file FROM $dms WHERE parent_id = ". $row['id']." AND resource_type = 1 AND account_id = $account_id";
		$queryImages = $mysqli->query($queryImages);
		if($mysqli->affected_rows>0){
			$gallerySCRIPT .= $scriptModello;
			$gallerySCRIPT = str_replace('{{id}}', $row['id'] ,$gallerySCRIPT);
			$elements = '';
			while ($rowIMG = $queryImages->fetch_assoc()) {
				$prima = $endpointIMMAGINI.$row['id']."/".$rowIMG['file'];
				$elements .= "{	'src': '".$endpointIMMAGINI.$row['id']."/".$rowIMG['file']."',
					            'thumb': '".$endpointIMMAGINI.$row['id']."/".$rowIMG['file']."',
					            'subHtml': '".$rowIMG['descrizione']."'
        					},";
			}//while ($rowIMG = $queryImages->fetch_assoc())
		$elements = trim($elements,',');
		$gallerySCRIPT = str_replace('{{elements}}', $elements ,$gallerySCRIPT);
		}//if($mysqli->affected_rows>0) -- foto
		$galleryIMG = str_replace('{{src}}', $prima  ,$galleryIMG);//immmagine copertina
	}//while ($row = $queryGallery->fetch_assoc())
}// if($mysqli->affected_rows>0) -- gallery
?>
