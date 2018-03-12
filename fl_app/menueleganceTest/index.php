<?php 

session_start();
header('Content-Type: text/html; charset=UTF-8');
ini_set('default_charset', 'utf-8');



require_once("../../fl_core/core.php"); 
$client  = 0;
$folder = DMS_PUBLIC.'ricettario/portate/';
$autorefresh = 0;

if(!isset($_SESSION['menuId'])) $_SESSION['menuId'] = 1;
if(isset($_GET['menuId'])) $_SESSION['menuId'] = check($_GET['menuId']);

if(!isset($_SESSION['eventoId'])) $_SESSION['eventoId'] = 1;
if(isset($_GET['eventoId'])) $_SESSION['eventoId'] = check($_GET['eventoId']);

if(!isset($_SESSION['schedaId'])) $_SESSION['schedaId'] = 1;
if(isset($_GET['schedaId'])) $_SESSION['schedaId'] = check($_GET['schedaId']);

$jquery = 1;
$fancybox = 1;

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

<!-- Add mousewheel plugin (this is optional) -->
<script type="text/javascript" src="<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>

<!-- Add fancyBox main JS and CSS files -->
<script type="text/javascript" src="<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/fancybox/source/jquery.fancybox.js?v=2.0.6"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/fancybox/source/jquery.fancybox.css?v=2.0.6" media="screen" />

<!-- Add Button helper (this is optional) -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.2" />
<script type="text/javascript" src="<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/fancybox/source/helpers/jquery.fancybox-buttons.js?v=1.0.2"></script>

<!-- Add Thumbnail helper (this is optional) -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.2" />
<script type="text/javascript" src="<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/fancybox/source/helpers/jquery.fancybox-thumbs.js?v=1.0.2"></script>

<!-- Add Media helper (this is optional) -->
<script type="text/javascript" src="<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/fancybox/source/helpers/jquery.fancybox-media.js?v=1.0.0"></script>

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
.boxCat { 	  transition: visibility 0s, opacity 0.5s linear;  }


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
	
	$.ajax({
		type: "GET",
		url: url,
		data: {"type1":type1, "type2":type2, "id1":menu_id, "id2":data_rel},
		success: function(data){
			if(data_status == 0){																		//controllo lo status, se è 0 vuol dire che la portata non è stata ancota aggiunta 
				$("h3 > a[data-rel= '" + data_rel + "']").attr("data-status","1");						//setto lo status a 1
				$("h3 > a[data-rel= '" + data_rel + "'] > i" ).attr("class", "fa fa-minus-square-o");
				$("h3 > a[data-rel= '" + data_rel + "'] > i" ).closest('span').attr('style',  'background-color: #287539');
						//alert("Portata inserita con successo!");
			}else{																						//se è diverso da 0 vuol dire che la portata è stata già aggiunta quindi
				$("h3 > a[data-rel= '" + data_rel + "']").attr("data-status","0");						//setto lo status a 0
				$("h3 > a[data-rel= '" + data_rel + "'] > i" ).attr("class", "fa fa-plus-square-o");
				$("h3 > a[data-rel= '" + data_rel + "'] > i" ).closest('span').attr("style", "");
					//riporto la classe a plus, riportando l'icona a più
				//alert("Portata eliminata con successo!");
			}
		},
		error: function(xhr, status, error){
			alert("ERRORE! Portata non inserita");			
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

$("#menuPortate").hide();

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

$




orderItems(); // Ordino elementi e filtro lista all'avvio
// Applico la stessa cosa al click del tastino
$("input[name='categoria_ricetta']").click(function( event ) { 	
	var select =  $(this).val();
    localStorage.setItem("selectedCat", select);	
    var selectedCat2 = localStorage.getItem('selectedCat');
	orderItems();
});

function orderItems(){

var selectedCat = localStorage.getItem('selectedCat');
if(selectedCat > 1)  { 
	$("input[id='a"+selectedCat+"']").attr('checked', true); 
	$(".boxCat").fadeOut(100);
	$(".a"+selectedCat+"").fadeIn(500);

} else { 

	$("input[id='a1']").attr('checked', true); 
	$(".boxCat").show(500);
}


}



});


/*Aggiungi/rimuovi portata */
var menuId = '<?php echo $_SESSION['menuId']; ?>';


</script>


</head>

<body>


<div id="preloader"><img src="../fl_set/img/preloader.png" /><a href="#" onClick="location.reload();" style="font-size: smaller; display: block; text-align: center;">Caricamento</a></div>



<div id="app_container">



<div  class="botton-up hideMobile"><a style="color: white;" data-fancybox-type="iframe" class="fancybox" href="../../fl_modules/mod_menu_portate/mod_configura.php?preview&menuId=<?php echo $_SESSION['menuId']; ?>"><i class="fa fa-print" aria-hidden="true"></i> Riepilogo </a></div>
<div  class="botton-up3 hideMobile"><a style="color: white;" data-fancybox-type="iframe" class="fancybox" href="../../fl_modules/mod_linee_prodotti/mod_user.php?categoria_prodotto=32&evento_id=<?php echo $_SESSION['eventoId']; ?>"></i> Vini e Bevande </a></div>


<div id="up_menu" class="noprint" style="text-align:left;">

<span class="topsx" style="top: 10%;"><a class="back" href="../../fl_modules/mod_eventi/mod_scheda_servizio.php?evento_id=<?php echo $_SESSION['eventoId']; ?>&id=<?php echo $_SESSION['schedaId']; ?>&t=<?php echo base64_encode(1); ?>">  <i class="fa fa-angle-left"></i></a></span>

<span class="appname"><a href="<?php echo (isset($_SESSION['POST_BACK_PAGE'])) ? $_SESSION['POST_BACK_PAGE'] : 'javascript:history.back();'; ?>"><img src="<?php echo LOGO; ?>" alt="<?php echo client; ?>"/></a></span>

<span class="topdx">

</span>
 </div>



<div style="width: auto; margin: 50px 20px; background: white; padding: 20px;" >
<?php 

$portata = array('Aperitivi','Antipasti','Primi','Secondi','Contorni','Frutta','Dessert','Torte');

foreach($portata AS $chiave => $valore)
	
{ 


$img = (file_exists($folder.$chiave.'.jpg')) ? $folder.$chiave.'.jpg' : $folder.'0.jpg';

echo '<div class="boxCat" style="width: 19vw;
height: 20vh;"><a href="portate.php?portata='.$chiave.'" >
<span><h2>'.ucfirst($valore).'</h2></span>';
echo '<img src="'.$img.'" alt="Foto" />'; 
echo '</a></div>';
	
}
?>
</div>



</div>


</body></html>

