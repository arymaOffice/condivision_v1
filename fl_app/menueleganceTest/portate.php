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

require('../../fl_core/class/ARY_dataInterface.class.php'); //Classe di gestione dei dati 
$data_set = new ARY_dataInterface();
$categoria_ricetta = $data_set->get_items_key("categoria_ricetta");//Crea un array con gli elementi figli dell'elemento con tag X1	


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
	<script type="text/javascript" src="<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/function.js"></script>

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
	var data_qta = $(this).attr("data-qta");
	var data_type = $(this).attr("data-type");															//Tipo di ricetta
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
				if(data_type == 1) selectOptions('composer.php?synapsy_id='+data);
				if(data_type == 2) selectOptions('options.php?synapsy_id='+data);
				console.log(data);
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


			function selectOptions(url)
			{
        // note the use of "this" rather than a function argument
        $.fancybox.open({
        	autoSize : false,
        	margin: 0,
        	padding: 0,
        	type: 'iframe', 
        	href : url
        });
    }


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
		<div  class="botton-up2 hideMobile"><a style="color: white;" href="#"><i class="fa fa-list" aria-hidden="true"></i> Portate </a></div>
		<div  class="botton-up3 hideMobile"><a style="color: white;" data-fancybox-type="iframe" class="fancybox" href="../../fl_modules/mod_linee_prodotti/mod_user.php?categoria_prodotto=32&evento_id=<?php echo $_SESSION['eventoId']; ?>"></i> Vini e Bevande </a></div>



		<div id="up_menu" class="noprint" style="text-align:left;">

			<span class="topsx" style="top: 10%;"><a class="back" href="../../fl_modules/mod_eventi/mod_scheda_servizio.php?evento_id=<?php echo $_SESSION['eventoId']; ?>&id=<?php echo $_SESSION['schedaId']; ?>&t=<?php echo base64_encode(1); ?>">  <i class="fa fa-angle-left"></i></a></span>

			<span class="appname"><a href="<?php echo (isset($_SESSION['POST_BACK_PAGE'])) ? $_SESSION['POST_BACK_PAGE'] : 'javascript:history.back();'; ?>"><img src="<?php echo LOGO; ?>" alt="<?php echo client; ?>"/></a></span>

			<span class="topdx">
				<a href="index.php">
					<i class="fa fa-address-card" aria-hidden="true"></i>
					Categorie</a>
				</span>

			</div>


			<div style="width: auto; margin: 50px 20px; background: white; padding: 20px; position: relative;" >

				<p>
					<?php if($portata_id < 4) {
						foreach($categoria_ricetta AS $key => $val) { if($key < 2 ) { $val = 'Tutti';  } ?>

						<input type="radio" value="<?php echo $key; ?>" id="a<?php echo $key; ?>" name="categoria_ricetta"><label for="a<?php echo $key; ?>"><?php echo $val; ?></label>

						<?php  }} ?>
					</p>
					<?php 


					$query = "SELECT * FROM fl_ricettario WHERE variante < 1 AND portata = $portata_id;"; 
					$res = mysql_query($query, CONNECT);
					$counter = 0;	

					while ($riga_res = mysql_fetch_array($res)) 

					{ 

						$isInMenu = GQD('fl_synapsy','id,valore',' type1 = 123 AND id1 = '.$_SESSION['menuId'].' AND type2 = 119 AND id2 = '.$riga_res['id']);
						$valore = (isset($isInMenu['valore'])) ? $isInMenu['valore'] : 0;
						$segno = (isset($isInMenu['valore'])) ? 'minus' : 'plus';
						$scelto = (isset($isInMenu['valore'])) ? 'background-color: #287539' : '';


//minus
						$prezzo = ($riga_res['prezzo_vendita'] > 0) ? '<h3> &euro; '.$riga_res['prezzo_vendita'].'</h3>' : '';
						$aggiungi = '<h3><a href="#" data-rel="'.$riga_res['id'].'" data-type="'.$riga_res['tipo_ricetta'].'"  data-qta="1" data-status="'.$valore.'"><i class="fa fa-'.$segno.'-square-o" aria-hidden="true"></i></a></h3>';
						$img = (file_exists($folder.$riga_res['id'].'.jpg')) ? $folder.$riga_res['id'].'.jpg' : $folder.'0.jpg';

						echo '<div class="boxCat a'.$riga_res['categoria_ricetta'].'" >';
						echo '<span style="'.$scelto.'"><h2> '.$riga_res['categoria_ricetta'].' '.ucfirst($riga_res['nome']).'</h2>'.$aggiungi.'</span>';
//echo '<a title="'.ucfirst($riga_res['nome']).': '.strip_tags(converti_txt($riga_res['note'])).' '.$prezzo.'" rel="gallery" href="'.$img.'" class="fancybox">
						echo "<a title=\"".ucfirst($riga_res['nome'])."\" href=\"#\" onclick=\"OpenBrWindowA('".ROOT.$cp_admin."fl_app/menueleganceTest/viewer.php?portata=".$portata_id."&startSlide=".$counter."','Componi','fullscreen scrollbars'); return false;\">";
						echo '<img src="'.$img.'" alt="Foto" /></a>'; 
						echo '</div>';

						$counter++;

					}
					?>


					<p style="font-size: smaller;" id="info_allergeni">
						INFORMATIVA PRESENZA ALLERGENI ALIMENTARI Reg. UE 1169/2011 <br>
						Gentili ospiti, se ci sono persone con delle allergie e/o intolleranze alimentari, vi preghiamo di chiedere informazioni sul nostro cibo e sulle nostre bevande.
						<br/><br/></p>

					</div>


					<div id="menuPortate" style="
					position: fixed;
					width: 200px;
					height: auto;
					bottom: 40px;
					right: 326px;
					z-index: 999;
					background: beige none repeat scroll 0% 0%;
					padding: 0px;
					display: block;
					">
					<?php 

					$portata = array('Aperitivi','Antipasti','Primi','Secondi','Contorni','Frutta','Dessert','Torte');

					foreach($portata AS $chiave => $valore)

					{ 


						echo '<div class="boxCat2" style="width: 100%;
						height: 35px; margin: 0; background: none;"><a href="portate.php?portata='.$chiave.'" >
						<span><h2>'.ucfirst($valore).'</h2></span>';
						echo '</a></div>';

					}
					?>




				</div>


				<div id="scroll-up" class="hideMobile"><i class="fa fa-chevron-circle-up"></i></div>

			</div>
			<button id="a">+</button>
			<button id="b">-</button>
			<script type="text/javascript">
				var newwin ;
				function OpenBrWindowA(theURL,winName) 
				{
					params  = 'width='+screen.width;
					params += ', height='+screen.height;
					params += ', top=0, left=0'
					params += ', fullscreen=yes';

					newwin=window.open(theURL,winName, params);


					if (window.focus) {newwin.focus(); }

					return false;
				}

				$('#a').click(function(){
					newwin.avanti();
				});
				$('#b').click(function(){
					
					newwin.indietro();
				});

			</script>
		</body></html>

