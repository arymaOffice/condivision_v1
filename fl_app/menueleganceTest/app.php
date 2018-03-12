<?php 
//if($_SERVER['HTTP_HOST'] != 'menutranslate.condivision.biz') header("Location: http://menutranslate.condivision.biz/'.$folderContent.'/?".$_SERVER['QUERY_STRING']);
session_start();
header('Content-Type: text/html; charset=UTF-8');
ini_set('default_charset', 'utf-8');
$_SESSION['user'] = 'menu';
$_SESSION['number'] = 1;
define('NOSSL',1);
$folderContent = '../../fl_contents_syn/';
require_once("../../fl_core/settings.php"); 
$client = (isset($_GET['id'])) ? check($_GET['id']) : check($_GET['client']);

$autorefresh = 0;

$jquery = 1;
$fancybox = 1;



function get_links($type=82,$client){
	
	$query = "SELECT * FROM fl_links WHERE anagrafica_id = $client AND link_type = $type LIMIT 1;"; 

$res = mysql_query($query, CONNECT);
	
while ($riga_res = mysql_fetch_array($res)) 
	
{ return $riga_res['link']; }
}

function get_video($type=0,$client){
	
$query = "SELECT * FROM fl_video WHERE anagrafica_id = $client AND link_type = $type LIMIT 1;"; 

$res = mysql_query($query, CONNECT);
	
while ($riga_res = mysql_fetch_array($res)) 
{ return $riga_res['link']; }

}

	
$anagrafica = GRD('fl_anagrafica',$client);	
$profilo = GRD('fl_profili',$client);	

mysql_query('UPDATE fl_profili SET visite = visite+1 WHERE id = '.$client);

?>

<!DOCTYPE html>
<html lang="it">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo @$profilo['fiendly_name'].' '.sitename; ?></title>
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
.ui-accordion .ui-accordion-header {
	cursor: pointer;
	position: relative;
	margin-top: 1px;
	padding: 12px;
	font-size: 126%;
}
.menutab { width: 99%;  }
.menutab th, .menutab td {
	text-align: left;
	vertical-align: middle;
	padding: 5px;
}
.menutab tr th:last-child, .menutab tr td:last-child {
	text-align: right;
}
/* ACCORDION */	
.ui-accordion .ui-accordion-header {
	cursor: pointer;
	position: relative;
	margin-top: 0px;
	background: #1F5287;
	color: #fff;
	padding-left: 30px;
	text-align: left;
}
.ui-state-active {
	background: #1F5287 !important;
	color: white !important;
}
.button {   width: auto;  background: #1F5287; border: none;
	color: white !important; padding: 4px; text-align: center; display: inline-block; }

.round {	-webkit-border-radius: 100px; /* Saf3+, Chrome */
	border-radius: 100px; /* Opera 10.5, IE 9 */
	-moz-border-radius: 30px; 
}

a.socialb { margin: 5px; padding: 10px 16px; }
.ui-widget-content {
	background: #252525  !important;
	color: #FFF !important;
}
/*.toload { display: hidden; }*/
.sub_sfondo { background: #F0F0F0; padding: 10px; color: black; }
.sub_sfondo a:link, a:visited { color: black; }
.translate {
	margin: 60px auto 5px;
	width: 100%;
	padding: 10px 0px;
	text-align: right;
}

.social { clear: both; text-align: center; margin: 5px auto; }
.ui-widget-content {
    background: #413F3F none repeat scroll 0% 0% !important;
    color: #FFF !important;
}
.ui-tabs .ui-tabs-panel { padding: 0; }
#tab1 { padding: 20px; }
.ui-tabs .ui-tabs-nav li {
    margin-top: 0px;
    display: block;
	font-size: 130%;
    padding: 0;
    text-align: center;
width: 15%;

}
@media screen and (min-width: 0) and (max-width: 480px) {

.menutab th, .menutab td {
    text-align: left;
    vertical-align: middle;
    padding: 5px;
}
}

/* ACCORDION */	
.ui-accordion .ui-accordion-header {
	background: #FBD772;
	color: RGBA(137, 27, 32, 0.84);
	padding-left: 30px;
}
.ui-state-active {
	background: #AD3435 !important;
	color: white !important;
}
.ui-widget-content {
	background: #6C6C6C  !important;
	color: #FFF !important;
}
/*.toload { display: hidden; }*/

.translate {
	margin: 60px auto 5px;
	width: 100%;
	padding: 10px 0px;
	text-align: right;
}

.social { clear: both; text-align: center; margin: 5px auto; }
.ui-widget-content {
    background: #413F3F none repeat scroll 0% 0% !important;
    color: #FFF !important;
}
.tumb { width: 33%; display: inline-block; max-height: 134px; overflow: hidden; margin: 3px 0;}
.ui-tabs .ui-tabs-panel { padding: 0; }
#tab1 { padding: 20px; }
.ui-tabs .ui-tabs-nav li {
    margin-top: 0px;
    display: block;
	font-size: 130%;
    padding: 0;
    text-align: center;
width: 16%;
}
.subtitle { text-align: left; font-weight: bold; text-transform: uppercase; }
.pie_pagina { padding: 20px; text-align: center; }
.round { float: none; }
<?php echo check($profilo['css_app']); ?>

</style>
<script>


$(document).ready(function(){


$("img.lazy").lazyload({
  event: "load", //scrollstop
  effect : "fadeIn"
});



/* TABS */
var $tabsd = $( "#tabs").tabs({
selected: <?php echo (isset($_GET['t'])) ? base64_decode(check($_GET['t'])) : 0; ?> , 
show: function(event, ui) { 
$('.calendar:input').datepicker();  },
spinner: '<img src="<?php echo ROOT.$cp_admin.$cp_set; ?>lay/preloader.gif" alt="Caricamento" />' ,
ajaxOptions: {	error: function( xhr, status, index, anchor ) {	$( anchor.hash ).html("Non &egrave; possibile caricare il contenuto!" );}}
});
<?php if(@$profilo['scheda_apertura'] != '') { ?>
var index = $('#tabs a[href="#<?php echo check(@$scheda_apertura[$profilo['scheda_apertura']]); ?>"]').parent().index();
$('#tabs').tabs('select', index);
<?php } ?>


$('.menu_icon').click(function () {
   $("#side_menu").fadeToggle("fast", "swing");		
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
    width    : "100%",
    height   : "100%",
	title : "",
	margin: 0,
	padding: 0
	});

/*PRELOAD CONTENT */
$("#preloader").hide();
$("#app_container").show(); 
<?php if($profilo['catalogo_apri_chiudi'] == 1) { ?>$("#accordion").accordion({ animate: 200 ,collapsible: true, header: "h2",heightStyle: "content", autoHeight: false,fillSpace: false, active: 'h2#first',event: "click" });<?php  } ?>

});


</script>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-6083912-59', 'auto');
  ga('send', 'pageview');

</script>

</header>

<body>
<div id="preloader"><img src="../fl_set/img/preloader.png" /><a href="#" onClick="location.reload();" style="font-size: smaller; display: block; text-align: center;">Caricamento</a></div>
<div id="app_container">

<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/it_IT/sdk.js#xfbml=1&version=v2.5&appId=203468399717625";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<div id="side_menu" style="display: none; color: white; z-index: 99999;">
MENU
</div>


<div id="up_menu" style="z-index: 999; max-height: 50px; height: 50px; "> 
<!--<div id="menu_toggler"><a href="#" title="Nascondi/Mostra Menu" class="menu_icon"></a></div>
-->
<?php 
if($client > 2){
if(file_exists($folderContent.'logo/'.$client.'.jpg')) { echo '<img src="'.$folderContent.'logo/'.$client.'.jpg" style=" max-height: 50px; " alt="'.$profilo['fiendly_name'].'" />'; } else { echo '<h1>'.$profilo['fiendly_name'].'</h1>'; }
} else {
 echo '<img src="../fl_config/img/a.png" style=" max-height: 50px; " alt="'.client.'" />';	
} ?>
</div>

<div class="translate">

<div class="toload" style="text-align: center;">     
<a href="#" onclick="doGTranslate('it|en');return false;" title="English" class="gflag nturl" style="background-position:-0px -0px;"><img src="//gtranslate.net/flags/blank.png" height="32" width="32" alt="English" /></a><a href="#" onclick="doGTranslate('it|fr');return false;" title="French" class="gflag nturl" style="background-position:-200px -100px;"><img src="//gtranslate.net/flags/blank.png" height="32" width="32" alt="French" /></a><a href="#" onclick="doGTranslate('it|de');return false;" title="German" class="gflag nturl" style="background-position:-300px -100px;"><img src="//gtranslate.net/flags/blank.png" height="32" width="32" alt="German" /></a><a href="#" onclick="doGTranslate('it|it');return false;" title="Italian" class="gflag nturl" style="background-position:-600px -100px;"><img src="//gtranslate.net/flags/blank.png" height="32" width="32" alt="Italian" /></a><a href="#" onclick="doGTranslate('it|pt');return false;" title="Portuguese" class="gflag nturl" style="background-position:-300px -200px;"><img src="//gtranslate.net/flags/blank.png" height="32" width="32" alt="Portuguese" /></a><a href="#" onclick="doGTranslate('it|ru');return false;" title="Russian" class="gflag nturl" style="background-position:-500px -200px;"><img src="//gtranslate.net/flags/blank.png" height="32" width="32" alt="Russian" /></a><a href="#" onclick="doGTranslate('it|es');return false;" title="Spanish" class="gflag nturl" style="background-position:-600px -200px;"><img src="//gtranslate.net/flags/blank.png" height="32" width="32" alt="Spanish" /></a>

<style type="text/css">
<!--
a.gflag {vertical-align:middle;font-size:32px;padding:1px 0;background-repeat:no-repeat;background-image:url(//gtranslate.net/flags/32.png);}
a.gflag img {border:0;}
a.gflag:hover {background-image:url(//gtranslate.net/flags/32a.png);}
-->
</style>

<br /><select onchange="doGTranslate(this);"><option value="">Select Language</option><option value="it|af">Afrikaans</option><option value="it|sq">Albanian</option><option value="it|ar">Arabic</option><option value="it|hy">Armenian</option><option value="it|az">Azerbaijani</option><option value="it|eu">Basque</option><option value="it|be">Belarusian</option><option value="it|bg">Bulgarian</option><option value="it|ca">Catalan</option><option value="it|zh-CN">Chinese (Simplified)</option><option value="it|zh-TW">Chinese (Traditional)</option><option value="it|hr">Croatian</option><option value="it|cs">Czech</option><option value="it|da">Danish</option><option value="it|nl">Dutch</option><option value="it|en">English</option><option value="it|et">Estonian</option><option value="it|tl">Filipino</option><option value="it|fi">Finnish</option><option value="it|fr">French</option><option value="it|gl">Galician</option><option value="it|ka">Georgian</option><option value="it|de">German</option><option value="it|el">Greek</option><option value="it|ht">Haitian Creole</option><option value="it|iw">Hebrew</option><option value="it|hi">Hindi</option><option value="it|hu">Hungarian</option><option value="it|is">Icelandic</option><option value="it|id">Indonesian</option><option value="it|ga">Irish</option><option value="it|it">Italian</option><option value="it|ja">Japanese</option><option value="it|ko">Korean</option><option value="it|lv">Latvian</option><option value="it|lt">Lithuanian</option><option value="it|mk">Macedonian</option><option value="it|ms">Malay</option><option value="it|mt">Maltese</option><option value="it|no">Norwegian</option><option value="it|fa">Persian</option><option value="it|pl">Polish</option><option value="it|pt">Portuguese</option><option value="it|ro">Romanian</option><option value="it|ru">Russian</option><option value="it|sr">Serbian</option><option value="it|sk">Slovak</option><option value="it|sl">Slovenian</option><option value="it|es">Spanish</option><option value="it|sw">Swahili</option><option value="it|sv">Swedish</option><option value="it|th">Thai</option><option value="it|tr">Turkish</option><option value="it|uk">Ukrainian</option><option value="it|ur">Urdu</option><option value="it|vi">Vietnamese</option><option value="it|cy">Welsh</option><option value="it|yi">Yiddish</option></select>

<script type="text/javascript">
/* <![CDATA[ */
function doGTranslate(lang_pair) {if(lang_pair.value)lang_pair=lang_pair.value;if(lang_pair=='')return;var lang=lang_pair.split('|')[1];var plang=location.pathname.split('/')[1];if(plang.length !=2 && plang.toLowerCase() != 'zh-cn' && plang.toLowerCase() != 'zh-tw')plang='it';if(lang == 'it')location.href=location.protocol+'//'+location.host+location.pathname.replace('/'+plang+'/', '/')+location.search;else location.href=location.protocol+'//'+location.host+'/'+lang+location.pathname.replace('/'+plang+'/', '/')+location.search;}
/* ]]> */
</script>

</div>

</div>




<div style="max-width: 1200px; margin: 0 auto;" >
<div id="tabs">

<ul>
<?php if($profilo['catalogo']  == 1) { ?><li><a href="#tabMenu"><?php echo $profilo['titolo_catalogo']; ?></a></li><?php } ?>
<li><a href="#bio"><i class="fa fa-info-circle" aria-hidden="true"></i></a></li>
<?php if($profilo['fotogallery']  == 1) { ?><li><a href="#fotogallery"><i class="fa fa-camera"></i></a></li><?php } ?>
<?php if($profilo['videogallery']  == 1) { ?><li><a href="#videogallery"><i class="fa fa-video-camera"></i>
</a></li><?php } ?>
<?php if($profilo['mappa'] != '') { ?><li><a href="#mappa"><i class="fa fa-map-marker"></i></a></li><?php } ?>
<?php if($profilo['pano360'] != '') { ?><li><a href="#tour360"><i class="fa fa-street-view"></i>
</a></li><?php } ?>
</ul>

<?php if($profilo['videogallery']  == 1) { ?>
<div id="videogallery" style="text-align: left;">
<?php if(get_video(0,$client) != '') {

$query = "SELECT * FROM fl_video WHERE anagrafica_id = $client AND link_type = 0 ORDER BY id DESC;"; 
$res = mysql_query($query, CONNECT);
while ($riga_res = mysql_fetch_array($res)) 
{ 
$video = str_replace('https://www.youtube.com/watch?v=','https://www.youtube.com/embed/',str_replace('https://youtu.be/','https://www.youtube.com/embed/',$riga_res['link']));

echo '<h2 style="padding: 5px;">'.$riga_res['label'].'</h2><iframe style="width: 100%; height: 300px;" src="'.$video.'" frameborder="0" allowfullscreen></iframe>'; 
 }

} ?>
</div>
<?php } ?>


<?php if($profilo['fotogallery']  == 1) { ?>
<div id="fotogallery" style="text-align: left;">
<?php
if($client > 1){
if(@$rep=opendir($folderContent.'gallery/'.$client.'/')) {
while ($file = readdir($rep)){
	if($file != '..' && $file !='.'){ 
	if (is_file($folderContent.'gallery/'.$client.'/'.$file)){
	echo '<div class="tumb"><a rel="gallery" href="'.$folderContent.'gallery/'.$client.'/'.$file.'" class="fancybox">
	<img class="lazy" data-original="'.$folderContent.'gallery/'.$client.'/'.$file.'" ></a></div>';
	}}
}  }}
?>
</div>
<?php } ?>

<div id="bio" style="background: #F1F1F1; padding: 20px;">
<?php if($client < 2){
echo "<h1>Benvenuto!</h1><p>In questo esercizio commerciale, puoi visionare il men&ugrave; in oltre 90 lingue. Scansiona il QR CODE al tavolo o all'entrata per accedere a tutte le informazioni su questa attivit&agrave;</p>";
} else { 
if(file_exists($folderContent.'logo/intro'.$client.'.jpg')) { 
echo '<img src="'.$folderContent.'logo/intro'.$client.'.jpg" style=" max-width: 100%; " alt="'.$profilo['fiendly_name'].'" />';
}
echo '<h1>'.$profilo['fiendly_name'].'</h1>';
echo converti_txt($profilo['bio']); 
$tel = ($profilo['telefono']!= "") ? $profilo['telefono'] : $anagrafica['telefono'];
$email = ($profilo['email'] != "") ? $profilo['email'] : $anagrafica['email'];
echo '<p><i class="fa fa-phone"> </i> '.$tel.'</p>';
echo '<p><i class="fa fa-envelope-o"> </i><a href="mailto:'.$email.'"> '.$email.' </a></p>';
echo '<p>'.$profilo['orario_apertura'].'</p>';

}

?>

<?php if(file_exists($folderContent.$client.'.mp3')) { ?>
<p>Ascolta audio<br><audio class="audioDemo" preload="none" autoplay controls style="width: 150px;">
  <source src="<?php echo $folderContent.$client; ?>.mp3" type="audio/mpeg">
</audio></p>
<?php } ?>
</div>


<?php if($profilo['mappa'] != '') { 
echo '<div id="mappa"><iframe src="'.$profilo['mappa'].'"  height="450" frameborder="0" style="border:0; width: 100%;" allowfullscreen></iframe></div>';
} ?>


<?php if($profilo['pano360'] != '') { 
echo '<div id="tour360"><iframe src="'.$profilo['pano360'].'" height="450" frameborder="0" style="border:0; width: 100%;" allowfullscreen></iframe></div>';
} ?>


<?php if($profilo['catalogo'] == 1) { ?>
<div id="tabMenu" >
<div id="accordion" style=" margin: 0 auto;">
<?php 
$query = "SELECT * FROM fl_catalogo WHERE anagrafica_id = $client AND parent_id = 0 ORDER BY `priority` ASC, id ASC;"; 

$res = mysql_query($query, CONNECT);
	
while ($riga_res = mysql_fetch_array($res)) 
	
{ 

echo '<h2 id="first">'.ucfirst($riga_res['titolo']).'</h2>
<table class="menutab" >';

echo '<tr>
  <td colspan="3" style="text-align: center;">'.converti_txt($riga_res['descrizione']).'</td></tr>';
$query2 = "SELECT * FROM fl_catalogo WHERE anagrafica_id = $client AND parent_id = ".$riga_res['id']." ORDER BY `priority` ASC, id ASC;"; 

$res2 = mysql_query($query2, CONNECT);
	
while ($riga_res3 = mysql_fetch_array($res2)) 
	
{ 
$prezzo = get_prezzo($riga_res3['id'],0);
if($riga_res3['testata_superiore'] != '') echo  '<tr><td style="width: 10%;"></td><td style="width: 70%;" class="subtitle"><strong>'.converti_txt($riga_res3['testata_superiore']).'</strong></td><td style="width: 20%;"></td></tr>';

 ?>
  <tr>
  <td style="width: 10%;">
    <?php if (file_exists($folderContent.'catalogo/'.$riga_res3['id'].'.jpg')) echo '<a rel="gallery" href="'.$folderContent.'catalogo/'.$riga_res3['id'].'.jpg" class="fancybox"><img src="'.$folderContent.'catalogo/'.$riga_res3['id'].'.jpg" alt="Foto" style="width: 90%; min-width: 200px; "/></a>'; ?>
	<?php echo ($riga_res3['sku'] != '' || $prezzo == 0.00) ? $riga_res3['sku'] : 'N'.$riga_res3['id']; ?></td>
    <td style="width: 70%;"><strong><?php echo ucfirst($riga_res3['titolo']); ?></strong><br>
    <?php echo converti_txt($riga_res3['descrizione']); ?></td>
    <td style="width: 20%;"><?php if( $prezzo > 0) echo  ' &euro; '.$prezzo ?></td>
  </tr>
  <?php
  if($riga_res3['pie_pagina'] != '') echo  '<tr><td colspan="3" style="text-align: left;">'.converti_txt($riga_res3['pie_pagina']).'</td></tr>';

}  
echo '</table>'; 
}

echo '<p class="pie_pagina">'.converti_txt($profilo['pie_pagina_catalogo']).'</p>';

?>
<p style="font-size: smaller;">
INFORMATIVA PRESENZA ALLERGENI ALIMENTARI Reg. UE 1169/2011 <br>
Gentili ospiti, se ci sono persone con delle allergie e/o intolleranze alimentari, vi preghiamo di chiedere informazioni sul nostro cibo e sulle nostre bevande.
La traduzione dei testi è automatica, pertanto vi invitiamo a chiedere informazioni dettagliate al nostro staff in presenza di allergie o intolleranze.
<br/><br/></p>
</div>
</div>
<?php } ?>

</div>
</div>

<?php if($client > 1){
echo '<div class="social"><h3>Follow us</h3>';
if(get_links(82,$client) != '') echo '<a href="'.get_links(82,$client).'" class="button round socialb" target="_blank"><i class="fa fa-facebook"></i></a>';
if(get_links(85,$client) != '') echo '<a href="'.get_links(85,$client).'"  class="button round socialb"  target="_blank"><i class="fa fa-link"></i></a>';
if(get_links(84,$client) != '') echo '<a href="'.get_links(84,$client).'"  class="button round socialb"  target="_blank"><i class="fa fa-instagram"></i></a>';
if(get_links(83,$client) != '') echo '<a href="'.get_links(83,$client).'"  class="button round socialb"  target="_blank"><i class="fa fa-twitter"></i></a>';
if(get_links(86,$client) != '') echo '<a href="'.get_links(86,$client).'"  class="button round socialb"  target="_blank"><i class="fa fa-google-plus"></i></a>';
if(get_links(88,$client) != '') echo '<a href="'.get_links(88,$client).'"  class="button round socialb"  target="_blank"><i class="fa fa-youtube"></i></a>';
if(get_links(89,$client) != '') echo '<a href="'.get_links(89,$client).'"  class="button round socialb"  target="_blank"><i class="fa fa-pinterest"></i></a>';
if(get_links(90,$client) != '') echo '<a href="'.get_links(90,$client).'"  class="button round socialb"  target="_blank"><i class="fa fa-tripadvisor"></i></a>';

echo '</div>';
} ?>




</div>

<br class="clear">
<h6><a href="http://www.lookit.it/" target="_blank">Lookit.it</a> - ID: <?php echo $client; ?></h6>
<br class="clear">

<?php include("../fl_inc/footer.php"); ?>