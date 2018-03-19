<?php
include_once('config.php');//inclusione file configurazione
$queryWishlist = "SELECT SUBSTRING(nome,1,60) as nome,SUBSTRING(descrizione,1,60) as descrizione,img,url FROM $fl_wishlist w LEFT JOIN $fl_wishlist_immagini ON w.id = wishlist_id WHERE account_id = $account_id";
$queryWishlist = $mysqli->query($queryWishlist);

$pDamostrare = '<br>';

if($mysqli->affected_rows>0){
	while ($row = $queryWishlist->fetch_assoc()){

		$pDamostrare .= '<a href="'.$row['url'].'" target="_blank"><p style="width:30%;float: left;margin-right: 15px;"><img onerror="this.onerror=null;this.src=\'./images/wishlist_icon.png\';" src="'.$row['img'].'" style="width:100px;"></p><h7 style="font-size: smaller;">'.html_entity_decode($row['nome']).'...</h7><p style="font-size: smaller;">'.html_entity_decode($row['descrizione']).'...</p></a><br style="clear:both">';
	}
}

$mysqli->close(); //ultimo file incluso che chiude la connessione

?>
