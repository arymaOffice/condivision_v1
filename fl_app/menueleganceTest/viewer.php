<?php 
//if($_SERVER['HTTP_HOST'] != 'menutranslate.condivision.biz') header("Location: http://menutranslate.condivision.biz/'.$folderContent.'/?".$_SERVER['QUERY_STRING']);
session_start();
header('Content-Type: text/html; charset=UTF-8');
ini_set('default_charset', 'utf-8');



require_once("../../fl_core/core.php"); 
$folder = DMS_PUBLIC.'ricettario/';
$autorefresh = 0;
if(!isset($_SESSION['menuId'])) $_SESSION['menuId'] = 1;
if(isset($_GET['menuId'])) $_SESSION['menuId'] = check($_GET['menuId']);

$jquery = 1;
$fancybox = 1;

$portata_id = check($_GET['portata']);
$startSlide = (isset($_GET['startSlide'])) ? check($_GET['startSlide']) : 0;

?>

<!DOCTYPE html>
<html lang="it">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Digital Menù Composer 1.0</title>
<meta name="description" content="App aziendale con traduzione in tempo reale dei contenuti">

<!-- Smarthphone -->
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="format-detection" content="telephone=no">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<link rel="icon" href="<?php echo ROOT.$cp_admin.$cp_set; ?>css/lay/a.ico" type="image/x-icon" /> 
<link rel="shortcut icon" href="<?php echo ROOT.$cp_admin.$cp_set; ?>css/lay/a.ico" type="image/x-icon" />
<link rel="apple-touch-icon" href="<?php echo ROOT.$cp_admin.$cp_set; ?>lay/a.png" />

<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Arimo:400,700,400italic">
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT.$cp_admin.$cp_set; ?>css/condivision4-jquery.css" media="screen, projection, tv" />
<link rel="stylesheet" type="text/css" href="<?php echo ROOT.$cp_admin.$cp_set; ?>css/condivision4.css" media="screen, projection, tv" />
<link rel="stylesheet" type="text/css" href="<?php echo ROOT.$cp_admin.$cp_set; ?>css/fl_print.css" media="print" />
<link rel="stylesheet" type="text/css" href="<?php echo ROOT.$cp_admin.'fl_config/'.HTTP_HOST.'/'; ?>css/custom.css" media="screen, projection, tv" />


<script src="<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/jquery-1.8.3.js" type="text/javascript"></script>
<script src="<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/jquery-ui.js" type="text/javascript"></script>


<!-- Add fancyBox main JS and CSS files -->
<script type="text/javascript" src="<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/fancybox3/jquery.fancybox.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/fancybox3/jquery.fancybox.css" media="screen" />



<!-- lazyload Javascript file -->
<script src="<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/lazyload/jquery.lazyload.js"></script>
<script src="<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/lazyload/jquery.scrollstop.js" type="text/javascript"></script> 

<!-- bxSlider Javascript file -->
<script src="<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/bxslider/jquery.bxslider.min.js" type="text/javascript"></script>
<link href="<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/bxslider/jquery.bxslider.css" rel="stylesheet" />
<style media="all">
body { color: #6f6f6f; font:  normal 14px/1.7 "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif; }
#app_container { display: none; }
h2.first {
	text-align: left;
	padding-left: 10px;
}
.input_text {
    width: 100%;
    text-align: center;
}
.input_text *,.input_text input,.input_text textarea { width: 90%; }
.subtitle { text-align: left; font-weight: bold; text-transform: uppercase; }
.pie_pagina { padding: 20px; text-align: center; }
.round { float: none; }
li .selected { background: rgba(10, 149, 1, 0.8);  }
.selected span { background: #287539b3;  }
.bx-controls-direction { position: absolute;
top: 250px;
width: 100%;  }

.bx-wrapper img {
    width: 100%;
    display: block;
  }
.bx-wrapper .bx-caption { background: rgba(60, 40, 40, 0.8); padding-right: 45px; }

.bx-wrapper .bx-caption span {
     font-size: 17px;
}
.aggiungi { 

position: absolute;
z-index: 999;
bottom: 6px;
right: 8px;
border-radius: 17px;
background: #c5b5b5;
padding: 4px 11px;
margin: 0;
color: white;

}

<?php echo check($profilo['css_app']); ?>

</style>
<script>


$(document).ready(function(){

/*------------------------------------------- FUNZIONE DI INSERIMENTO/CANCELLAZIONE PORTATA --------------------------------*/

$("h3 > a").click(function(e){
	
	var url = "mod_opera.php";																			//url della pagin di richiesta
	var type1 = "123";																					//tabella fl_menu_portate
	var type2 = "119";																					//tabella ricettario
	var data_rel = $(this).attr("data-rel");															//id della ricettario
	var data_qta = $(this).attr("data-qta");															//quantità
	var data_status = $(this).attr("data-status");														//iserito o non inserito
	var menu_id = <?php echo $_SESSION['menuId'] ?> ;													//id del menu che copiliamo
	$("#preloader").show();

	$.ajax({
		type: "GET",
		url: url,
		data: {"type1":type1, "type2":type2, "id1":menu_id, "id2":data_rel},
		success: function(data){
			if(data_status == 0){																		//controllo lo status, se è 0 vuol dire che la portata non è stata ancota aggiunta 
				$("h3 > a[data-rel= '" + data_rel + "']").attr("data-status","1");						//setto lo status a 1
				$("h3 > a[data-rel= '" + data_rel + "'] > i" ).attr("class", "fa fa-minus-square-o");
				$("h3 > a[data-rel= '" + data_rel + "'] > i" ).closest('li').attr("class", "ricetta selected");
				
						//alert("Portata inserita con successo!");
			}else{																						//se è diverso da 0 vuol dire che la portata è stata già aggiunta quindi
				$("h3 > a[data-rel= '" + data_rel + "']").attr("data-status","0");						//setto lo status a 0
				$("h3 > a[data-rel= '" + data_rel + "'] > i" ).attr("class", "fa fa-plus-square-o");
				$("h3 > a[data-rel= '" + data_rel + "'] > i" ).closest('li').attr("class", "ricetta ");
				
					//riporto la classe a plus, riportando l'icona a più
				//alert("Portata eliminata con successo!");
			}
			$("#preloader").hide();			
		},
		error: function(xhr, status, error){
			alert("ERRORE! Portata non inserita");
			$("#preloader").hide();			
		}
	});
	return false;
});

/*------------------------------------------------------------- FINE FUNZIONE -----------------------------------------*/

$("img.lazy").lazyload({
  event: "load", //scrollstop
  effect : "fadeIn"
});

$(".botton-up2").click(function () {
$("#menuPortate").fadeToggle();
});

$("#scroll-up").hide();

$(window).scroll(function () {
			if ($(this).scrollTop() > 400) {
				$('#scroll-up').fadeIn();
			} else {
				$('#scroll-up').fadeOut();
			}
		});
$('#scroll-up').click(function () {
			$('body,html').animate({scrollTop: 0}, 800);
			return false;
});


$(".fancybox").fancybox({
    autoSize : false,
	margin: 0,
	padding: 0,
	beforeShow: function () {
        var customContent = "<div class='customHTML'>Aggiungi al Menù</div>";
        title = customContent;
    }

	});

/*PRELOAD CONTENT */
$("#preloader").hide();
$("#app_container").show(); 


slider = $(".bxslider");

    slider.bxSlider({
      adaptiveHeight: true, 
      captions: true,
      startSlide: <?php echo $startSlide; ?>,
      onSliderLoad: function() {

          $("body").keydown(function(e) {
              if (e.keyCode == 37) { // left
                slider.goToPrevSlide();
              } else if(e.keyCode == 39) { // right
                slider.goToNextSlide();
              }
            });

      }
    });

});



/*Aggiungi/rimuovi portata */
var menuId = '<?php echo $_SESSION['menuId']; ?>';

function avanti(){
	slider.goToNextSlide();
}
function indietro(){
	slider.goToPrevSlide();
}



</script>



</head>
<body>
<input type="hidden" name="myInput"  id="myInput" >

<div id="preloader"><img src="<?php echo ROOT.$cp_admin; ?>fl_set/lay/preloader.png" /><a href="#" onClick="location.reload();" style="font-size: smaller; display: block; text-align: center;">Aggiornamento del Menù</a></div>

<div id="app_container">

<div  class="botton-up hideMobile"><a style="color: white;" data-fancybox-type="iframe" class="fancybox" href="../../fl_modules/mod_menu_portate/mod_configura.php?preview&menuId=<?php echo $_SESSION['menuId']; ?>"><i class="fa fa-print" aria-hidden="true"></i> Riepilogo </a></div>
<div  class="botton-up2 hideMobile"><a style="color: white;" href="#"><i class="fa fa-list" aria-hidden="true"></i> Portate </a></div>



<div id="up_menu" class="noprint" style="text-align:left;">

<span class="topsx" style="top: 10%;"></span>

<span class="appname"><a href="#"><img src="<?php echo LOGO; ?>" alt="<?php echo client; ?>"/></a></span>

<span class="topdx">
<a class="logout" href="#" onclick="window.close();"><i class="fa fa-power-off"></i> <span class="desktop">Chiudi</span> </a>  
</span>

</div>


<div style="width: 80%; margin: 50px auto; max-width: 800px;  background: white; padding: 20px; position: relative;" >
<ul class="bxslider">
  


<?php 


$query = "SELECT * FROM fl_ricettario WHERE variante < 1 AND  portata = $portata_id;"; 
$res = mysql_query($query, CONNECT);
	

while ($riga_res = mysql_fetch_array($res)) 
	
{ 

$isInMenu = GQD('fl_synapsy','id,valore',' type1 = 123 AND id1 = '.$_SESSION['menuId'].' AND type2 = 119 AND id2 = '.$riga_res['id']);
$valore = (isset($isInMenu['valore'])) ? $isInMenu['valore'] : 0;
$segno = (isset($isInMenu['valore'])) ? 'minus' : 'plus';
$class = (isset($isInMenu['valore'])) ? 'selected' : '';


//minus
$prezzo = ($riga_res['prezzo_vendita'] > 0) ? '<h3> &euro; '.$riga_res['prezzo_vendita'].'</h3>' : '';
$aggiungi = '<h3><a class="aggiungi" href="#" data-rel="'.$riga_res['id'].'" data-qta="1" data-status="'.$valore.'"><i class="fa fa-'.$segno.'-square-o" aria-hidden="true"></i></a></h3>';
$img = (file_exists($folder.$riga_res['id'].'.jpg')) ? $folder.$riga_res['id'].'.jpg' : $folder.'0.jpg';

/*echo '<div class="boxCat '.$class.'">';
echo '<span><h2>'.ucfirst($riga_res['nome']).'</h2>'.$aggiungi.'</span>';
echo '<a title="'.ucfirst($riga_res['nome']).': '.strip_tags(converti_txt($riga_res['note'])).' '.$prezzo.'" rel="gallery" href="'.$img.'" class="fancybox">
<img src="'.$img.'" alt="Foto" /></a>'; 
echo '</div>';*/

echo '<li class="ricetta '.$class.'"><img src="'.$img.'" title="'.ucfirst($riga_res['nome']).': '.strip_tags(converti_txt($riga_res['note'])).' '.$prezzo.'" />
'.$aggiungi.'</li>';
	
}
?>
</ul>

<p style="font-size: smaller;" id="info_allergeni">
INFORMATIVA PRESENZA ALLERGENI ALIMENTARI Reg. UE 1169/2011 <br>
Gentili ospiti, se ci sono persone con delle allergie e/o intolleranze alimentari, vi preghiamo di chiedere informazioni sul nostro cibo e sulle nostre bevande.
<br/><br/></p>

</div>


<div id="menuPortate" style="position: fixed; width: 200px; height: auto; bottom: 0;
right: 287px; z-index: 999;
background: beige;
padding: 0; display: none;">
<?php 

$portata = array('Aperitivi','Antipasti','Primi','Secondi','Contorni','Frutta','Dessert','Torte');

foreach($portata AS $chiave => $valore)
	
{ 


echo '<div class="boxCat" style="width: 100%;
height: 35px; margin: 0; background: none;"><a href="viewer.php?portata='.$chiave.'" >
<span><h2>'.ucfirst($valore).'</h2></span>';

echo '</a></div>';
	
}
?>
</div>


<div id="scroll-up" class="hideMobile"><i class="fa fa-chevron-circle-up"></i></div>

</div>

</body></html>

