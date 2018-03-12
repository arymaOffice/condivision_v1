<!DOCTYPE html>
<html lang="it">

<head>
<meta charset="utf-8" /><title>Condivision Cloud</title>

<!-- Smarthphone -->
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"> 
<meta name="apple-mobile-web-app-capable" content="yes"> 
<meta name="format-detection" content="telephone=no">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"> 
 
<link rel="icon" href="https://calderonimartini.condivision.biz/fl_set/css/lay/a.ico" type="image/x-icon" /> 
<link rel="shortcut icon" href="https://calderonimartini.condivision.biz/fl_set/css/lay/a.ico" type="image/x-icon" />
<link rel="apple-touch-icon" href="https://calderonimartini.condivision.biz/fl_config/calderonimartini.condivision.biz/img/a.png" />

<link rel="apple-touch-startup-image" href="https://calderonimartini.condivision.biz/fl_set/lay/spl.png" media="(max-device-width : 480px) and (-webkit-min-device-pixel-ratio : 2)">
<!-- For iPhone -->
<link rel="apple-touch-icon-precomposed" href="https://calderonimartini.condivision.biz/fl_config/calderonimartini.condivision.biz/img/Logo-iPhone.jpg">
<!-- For iPhone 4 Retina display -->
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="https://calderonimartini.condivision.biz/fl_config/calderonimartini.condivision.biz/img/Logo-iPhoneRet.jpg">
<!-- For iPad -->
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="https://calderonimartini.condivision.biz/fl_config/calderonimartini.condivision.biz/img/Logo-iPad.jpg">
<!-- For iPad Retina display -->
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="https://calderonimartini.condivision.biz/fl_config/calderonimartini.condivision.biz/img/Logo-iPadRet.jpg">


<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Arimo:400,700,400italic">
<link rel="stylesheet" href="https://calderonimartini.condivision.biz/fl_set/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="https://calderonimartini.condivision.biz/fl_set/css/condivision4-jquery.css" media="screen, projection, tv" />
<link rel="stylesheet" type="text/css" href="https://calderonimartini.condivision.biz/fl_set/css/condivision4.css" media="screen, projection, tv" />
<link rel="stylesheet" type="text/css" href="https://calderonimartini.condivision.biz/fl_set/css/fl_print.css" media="print" />
<link rel="stylesheet" type="text/css" href="https://calderonimartini.condivision.biz/fl_config/calderonimartini.condivision.biz/css/custom.css" media="screen, projection, tv" />

<style type="text/css" media="all">
@media screen and (max-width: 600) {
#corpo { margin-left: 0px; }
}

</style>
   
<script src="https://calderonimartini.condivision.biz/fl_set/jsc/fl_ajax.js" type="text/javascript"></script>
<script type="text/javascript" src="https://calderonimartini.condivision.biz/fl_set/jsc/function.js"></script>
<script src="https://calderonimartini.condivision.biz/fl_set/jsc/jquery-1.8.3.js" type="text/javascript"></script>
<script src="https://calderonimartini.condivision.biz/fl_set/jsc/jquery-ui.js" type="text/javascript"></script>

<script>
  if(("standalone" in window.navigator) && window.navigator.standalone){

    var noddy, remotes = false;

    document.addEventListener('click', function(event) {

    noddy = event.target;

    while(noddy.nodeName !== "A" && noddy.nodeName !== "HTML") {
    noddy = noddy.parentNode;
    }

    if('href' in noddy && noddy.href.indexOf('http') !== -1 && (noddy.href.indexOf(document.location.host) !== -1 || remotes))
    {
    event.preventDefault();
    document.location.href = noddy.href;
    }

    },false);
    }
</script>

<script type="text/javascript" src="https://calderonimartini.condivision.biz/fl_set/jsc/jqueryslidemenu.js"></script>

<script src="../../fl_set/jsc/datetimepicker/build/jquery.datetimepicker.full.js"></script>
<link rel="stylesheet" type="text/css" href="../../fl_set/jsc/datetimepicker/jquery.datetimepicker.css"/>


<script type="text/javascript" src="https://calderonimartini.condivision.biz/fl_set/jsc/fancybox/source/jquery.fancybox.js?v=2.0.6"></script>
<link rel="stylesheet" type="text/css" href="https://calderonimartini.condivision.biz/fl_set/jsc/fancybox/source/jquery.fancybox.css?v=2.0.6" media="screen" />

<script src="https://calderonimartini.condivision.biz/fl_set/jsc/2select/select2.min.js"></script>
<link href="https://calderonimartini.condivision.biz/fl_set/jsc/2select/select2.min.css" rel="stylesheet" />


<script type="text/javascript">


/*Avvio*/
$(document).ready(function() {
	
/*PRELOAD CONTENT */
$("#preloader").hide();
$("#container").show(); 
$('.select2').select2();

$(".showcheckItems").click(function () { 

    $(".checkItemTd").fadeToggle();
    
});









var now = new Date();
var hrs = now.getHours();
var msg = "";

if (hrs >  0) msg = "Buona prima mattina"; // REALLY early
if (hrs >  6) msg = "Buongiorno";      // After 6am
if (hrs > 12) msg = "Buon Pomeriggio";    // After 12pm
if (hrs > 17) msg = "Buona sera";      // After 5pm
if (hrs > 22) msg = "Buona notte";        // After 10pm
$('#saluto').html(msg+' <span class="hideMobile">Sistema</span>');


/*ACCORDION*/
$("#accordion").accordion({ animate: 200 ,collapsible: true, header: "h2",heightStyle: "content", autoHeight: false,fillSpace: false, active: 'h2#crm',event: "click" });
$('*[data-sel-b="1"]').addClass('selected');

/*Form Validator*/

/*CALENDAR*/
jQuery(function($){
	$.datepicker.regional['it'] = {
		closeText: 'Chiudi',
		prevText: '&#x3c;Prec',
		nextText: 'Succ&#x3e;',
		currentText: 'Oggi',
		monthNames: ['Gennaio','Febbraio','Marzo','Aprile','Maggio','Giugno',
			'Luglio','Agosto','Settembre','Ottobre','Novembre','Dicembre'],
		monthNamesShort: ['Gen','Feb','Mar','Apr','Mag','Giu',
			'Lug','Ago','Set','Ott','Nov','Dic'],
		dayNames: ['Domenica','Luned&#236','Marted&#236','Mercoled&#236','Gioved&#236','Venerd&#236','Sabato'],
		dayNamesShort: ['Dom','Lun','Mar','Mer','Gio','Ven','Sab'],
		dayNamesMin: ['Do','Lu','Ma','Me','Gi','Ve','Sa'],
		weekHeader: 'Sm',
		dateFormat: 'dd/mm/yy',
		firstDay: 1,
		isRTL: false,
		showMonthAfterYear: false,
		gotoCurrent: true,
		yearSuffix: ''};
	$.datepicker.setDefaults($.datepicker.regional['it']);
});
$(".calendar:input").datepicker({ changeMonth: true, changeYear: true,yearRange: '1950:2050',
    onSelect: function ()
    {      
       $(this).trigger("focusout");  //Eccezione per compatibilita con update campo ajax
    }
});

/* TABS */
var $tabsd = $( "#tabs").tabs({
selected: 0 , 
show: function(event, ui) { $('.calendar:input').datepicker();  },
spinner: '<img src="https://calderonimartini.condivision.biz/fl_set/img/preloader.png" alt="Caricamento" />' ,
ajaxOptions: {	error: function( xhr, status, index, anchor ) {	$( anchor.hash ).html("Non &egrave; possibile caricare il contenuto!" );}}
});


$(".next-tab").click(function() {
    var selected = $("#tabs").tabs("option", "selected");
    $("#tabs").tabs("option", "selected", selected + 1);
});
$(".prev-tab").click(function() {
    var selected = $("#tabs").tabs("option", "selected");
    $("#tabs").tabs("option", "selected", selected - 1);
});
				
// Slider
$('#slider').slider({
	range: true,
	values: [17, 67]
});

$('.sms-open').click(function () {
   if($("#sms-box").css('display').valueOf() == 'block') { $('.sms-open').html('Sms'); } else {  $('.sms-open').html(''); }	
   $("#sms-box").fadeToggle("fast", "swing");

});
$('#sms-close').click(function () {
   $("#sms-box").fadeToggle("fast", "swing");
   if($("#sms-box").css('display').valueOf() == 'block') { $('.sms-open').html('Sms'); } else {  $('.sms-open').html(''); }	
   event.preventDefault();
});

$('.filterToggle').click(function () {
   $(".filtri").fadeToggle("fast", "swing",setck('#filtri'));		
});

$('.dati').click(function () {
   $(".filtri").hide("fast", "swing",setck('#filtri'));		
});

$('.open-close').click(function () {
   if($(this).next(".dsh_panel_content").css('display').valueOf() == 'block') { $(this).html('<a><i class="fa fa-angle-down" aria-hidden="true"></i></a>'); } else {  $(this).html('<a><i class="fa fa-angle-up" aria-hidden="true"></i></a>'); }	
  $(this).next(".dsh_panel_content").fadeToggle("fast", "swing");	
	event.preventDefault();
});



/*SCROLL UP*/
$(window).scroll(function () { if ($(this).scrollTop() > 400) {	$('#scroll-up').fadeIn();		} else {		$('#scroll-up').fadeOut();	}	});
$('#scroll-up').click(function () {		
$('body,html').animate({scrollTop: 0}, 800);
return false;
}); $("#scroll-up").hide();

$(':file').change(function(){
    var file = this.files[0];
    var name = file.name;
    var size = file.size;
    var type = file.type;
    console.log(size);
});

$('#action_list').change(function(){
	$('.action_options').hide();
	$('#action'+$(this).val()).show();
});

/*Dynamic form processor ajax */
$( "#invio" ).click(function( event ) {
 

 tinyMCE.triggerSave();  var form = $( "#scheda" ),
url = '../mod_basic/save_data.php';
var data = new FormData(form[0]);
if($("#results").length == 0) {  form.before('<div id="results"></div>'); }

jQuery.ajax({
    url: url,
    data: data,
    cache: false,
    contentType: false,
    processData: false,
    type: 'POST',
	fail: function(data){   $( "#results" ).empty().append( '<div class="esito red">Errore di caricamento</div>' );   }, 
    always: function(data){ $( "#results" ).empty().append( '<div class="esito orange">Creazione in corso...</div>' );  }, 
   
    success: function(response){
	$("#container").show(); 
    console.log(response);
    var data = $.parseJSON(response);
	
	$('body,html').animate({scrollTop: 0}, 800);
	$( "#results" ).empty().append('<div class="esito '+data.class+'">'+data.esito+'</div>');
	if(data.action == 'goto') {	setTimeout(function(){ window.location.href = data.url; }, 100); };


    }
	
});
event.preventDefault();
});

/*Invio SMS */
$( "#invia-sms" ).click(function( event ) {
  var $form = $( "#sms-form" ),
  url = $form.attr( "action" );
  var posting = $.post( '../mod_sms/mod_opera.php', $form.serialize() );
  $( "#results" ).empty().append( '<div class="esito orange">Invio in corso...</div>' );
  posting.fail(function( data ) {
    $( "#results" ).empty().append( '<div class="esito red">Errore di caricamento</div>' );
  });
  posting.always(function( data ) {
	 $( "#results" ).empty().append( '<div class="esito orange">Elaborazione in corso...</div>' );
  });
  
 posting.done(function( response ) {
    console.log(response);
    var data = $.parseJSON(response);
	$( "#results" ).empty().append('<div class="esito '+data.class+'">'+data.esito+'</div>');
  });
  event.preventDefault();
});


/*Dynamic form processor ajax */
$( ".ajaxForm" ).on('submit',this,function( event ) {
  
  var form = $( this ),
  url = form.attr( "action" );
  var posting = $.post(url, form.serialize() ); 
  $( "#results" ).empty().append( '<div class="esito orange">Invio in corso...</div>' );
  
  posting.fail(function( data ) {   $( "#results" ).empty().append( '<div class="esito red">Errore di caricamento</div>' );  });
  posting.always(function( data ) {  $( "#results" ).empty().append( '<div class="esito orange">Elaborazione in corso...</div>' );  });
  posting.done(function( response ) {
    console.log(response);
    var data = $.parseJSON(response);
	$('body,html').animate({scrollTop: 0}, 800);
	$( "#results" ).empty().append('<div class="esito '+data.class+'">'+data.esito+'</div>');
	if(data.action == 'goto') {	setTimeout(function(){ window.location.href = data.url; }, 200); };
	if(data.url == '#') {	form.empty().append('<p>Invio del modulo eseguito con successo!</p>'); };
	if(data.action == 'popup') {	openpopup(data.url); };	if(data.action == 'realoadParent') {	window.top.location.href = data.url;  };
   });
  event.preventDefault();
});

/*Dynamic form processor ajax */
$( ".ajaxFormFiles" ).on('submit',this,function( event ) {
 
event.preventDefault();
 
var form = $( this );
url = form.attr( "action" );
var data = new FormData(form[0]);


jQuery.ajax({
    url: url,
    data: data,
    cache: false,
    contentType: false,
    processData: false,
    type: 'POST',
	fail: function(data){   $( "#results" ).empty().append( '<div class="esito red">Errore di caricamento</div>' );   }, 
    always: function(data){ $( "#results" ).empty().append( '<div class="esito orange">Creazione in corso...</div>' );  }, 
   
    success: function(response){
    console.log(response);
    var data = $.parseJSON(response);
	$('body,html').animate({scrollTop: 0}, 800);
	$( "#results" ).empty().append('<div class="esito '+data.class+'">'+data.esito+'</div>');
	if(data.action == 'goto') {	setTimeout(function(){ window.location.href = data.url; }, 200); };
	if(data.url == '#') {	form.empty().append('<p>Invio della richiesta è stato eseguito con successo!</p>'); };
	if(data.action == 'popup') {	openpopup(data.url); };	if(data.action == 'realoadParent') {	window.top.location.href = data.url;  };
    }
	
});

});

/*Dynamic form processor ajax */
$( ".loadData" ).on('submit',this,function( event ) {
  
  var form = $( this ),
  url = form.attr( "action" );
  var posting = $.post(url, form.serialize() ); 
  $( "#results" ).empty().append( '<div class="esito orange">Caricamento dati...</div>' );
  
  posting.fail(function( data ) {   $( "#results" ).empty().append( '<div class="esito red">Errore di caricamento</div>' );  });
  posting.always(function( data ) {  $( "#results" ).empty().append( '<div class="esito orange">Caricamento dati...</div>' );  });
  posting.done(function( response ) {
    console.log(response);
    var data = $.parseJSON(response);
	
	$( "#results" ).empty().append('');
	$( "#mittente" ).val(data.mittente);
	$( "#oggetto" ).val(data.oggetto);
	$( "#messaggio" ).val(data.messaggio);
	$( "#templateId" ).val(data.id);
	$('#info').html( $("#messaggio" ).value.length+' caratteri');

   });
  event.preventDefault();
});


/*Salvataggio automatico campi ajax */
$( ".updateField" ).focus(function( event ) {   
var input = $(this); 
input.attr('class', 'bo-orange');
$(window).bind('beforeunload', function(){
  return 'Uscire senza salvare?';
});
event.preventDefault();
});



$( ".updateField" ).focusout(function( event ) {
  $(window).unbind('beforeunload');
   tinyMCE.triggerSave(); /* Salva testo di MCE */    var valore = $(this).val();
  var field = $(this).attr('name');
  var id = $(this).attr('data-rel');
  var obj = {gtx: '6'};
  if($(this).attr('data-gtx') > 1) obj['gtx'] = $(this).attr('data-gtx');
  obj[field] = valore;
  obj['id'] = id;
  var input = $(this);
  console.log(obj);
  var posting = $.ajax({  url: '../mod_basic/save_data.php',  type: 'POST',  data: obj});
  posting.fail(function( data ) {  input.attr('class', 'bo-red');  });
  posting.always(function( data ) {  });
  posting.done(function( response ) {    
	console.log(response);
  var data = $.parseJSON(response);
	if(data.class == 'green') {	input.attr('class', 'bo-green updateField'); }
	if(data.class == 'red') {	input.attr('class', 'bo-red updateField'); }

  });
  event.preventDefault();
});

/*Registra azione */
$( ".setAction" ).click(function( event ) {
  var id = $(this).attr('data-id');
  var gtx = $(this).attr('data-gtx');
  var azione = $(this).attr('data-azione');
  var esito = $(this).attr('data-esito');
  var note = $(this).attr('data-note');
  var obj = {i: '1'};
  obj['gtx'] = gtx;
  obj['id'] = id;
  obj['azione'] = azione;
  obj['esito'] = esito;
  obj['note'] = note;
  console.log(obj);
  var posting = $.ajax({   url: '../mod_basic/setAction.php',    type: 'POST',    data: obj});
  posting.fail(function( data ) {  });
  posting.always(function( data ) {  });
  posting.done(function( response ) {  console.log(response);  });
});


$(".fancybox").fancybox({
    autoSize : false,
    width    : "98%",
    height   : "95%",
	title : "",	
	afterClose : function() {
        location.reload();
        return;
    },	
	helpers : {
            title : {
                type: 'inside',
                position : 'top'
            }
    }
	});
$(".fancybox_small").fancybox({
    autoSize : false,
    width    : 480,
    height   : 300,
	title : "",	
	afterClose : function() {
        location.reload();
        return;
    },	
	helpers : {
            title : {
                type: 'inside',
                position : 'top'
            }
    }
	});
$(".fancybox_view").fancybox({
    autoSize : false,
    width    : "100%",
    height   : "100%",
	title : "",
	});

$(".fancybox_view_medium").fancybox({
    autoSize : true,
    width    : "800",
    height   : "600",
    title : "",
    afterClose : function() {
      location.reload();
      return;
    }     
  });

$(".fancybox_view_small").fancybox({
    autoSize : true,
    autoScale: true,
	title : "",
	});
	/*Apri un popup fancybox*/

$(".facyboxParent").click(function() {
         parent.$.fancybox({
             

              href: this.href,
              autoSize : false,
              width    : "100%",
              height   : "100%",
              title : this.title

        });

        return false;
    });


function openpopup(url){
$.fancybox({
            'autoScale': true,
            'transitionIn': 'elastic',
            'transitionOut': 'elastic',
            'speedIn': 500,
            'speedOut': 300,
            'autoDimensions': true,
			'type' : 'iframe',
			'href' : url,			
            'centerOnScroll': true ,
			afterClose : function() {location.reload(); }	
}).click();
}

$.datetimepicker.setLocale('it');
$('.datetimepicker').datetimepicker({
  format: 'd/m/Y H:i',
  step:5,
  onChangeDateTime:function(dp,$input){
    if($input[0].name == 'start_meeting') { 
      var dateset = $input.val(); 
      $('#end_meeting').val(dateset);
    }
    if($input[0].name == 'data_evento') { 
      var dateset = $input.val(); 
      $('#data_fine_evento').val(dateset);
    }
  }

  });

$('#periodo_evento').change(function(){
   console.log($(this).val());
   var dateNew = $('#data_evento').val();
   var dateNew = dateNew.substring(0,10);

    if($(this).val() == 102) {
    $('#data_evento').val(dateNew+' 21:00');
    $('#data_fine_evento').val(dateNew+' 23:59');
    } else {
    $('#data_evento').val(dateNew+' 13:00');
    $('#data_fine_evento').val(dateNew+' 21:00');
    }
});


function creaElemento(gtx,target){

	var url = '../mod_basic/mod_opera.php';

	event.preventDefault();
 
	var divTarget = $('out_'+target); //Leggere tutti i fields con classe 'outForm' presenti nel DIV target e creare le variabili da inviare
	var data = 'creaElemento';//Leggere tutti i fields con classe 'outForm' presenti nel DIV target e creare le variabili da inviare
	data += '&pizza=1';

	jQuery.ajax({
    url: url,
    data: data,
    cache: false,
    contentType: false,
    processData: false,
    type: 'POST',
	fail: function(data){     }, 
    always: function(data){ $( target).empty();  }, 
   
    success: function(response){
    console.log(response);
    var data = $.parseJSON(response);
    //Diabilitare tutti gli outForm e chiudere divTarget
    }
	
	});



};




 
});






</script> 
 

<script type="text/javascript" src="https://calderonimartini.condivision.biz/fl_set/jsc/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init({
		mode : "textareas",
		theme : "advanced",
 		plugins : "autoresize,safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
		width: '100%',
 		height: 400,
 		autoresize_min_height: 400,
  		autoresize_max_height: 800,
		relative_urls : false,
		remove_script_host : false,
		convert_urls : true,
		language : "it",
		// Theme options
				theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,bullist,numlist,|,justifyleft,justifycenter,justifyright,justifyfull,fontsizeselect|,forecolor,backcolor",
	  	theme_advanced_buttons2 : "",
		theme_advanced_buttons3 : "",
		 		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,

		content_css : "../../fl_set/css/fl_cms.css",
	    plugin_insertdate_dateFormat : "%Y-%m-%d",
	    plugin_insertdate_timeFormat : "%H:%M:%S",
		theme_advanced_resize_horizontal : true,
		theme_advanced_resizing : true,
		apply_source_formatting : true,
		spellchecker_languages : "Italian=it"
	});	
</script>









<script src="https://calderonimartini.condivision.biz/fl_set/jsc/highcharts/highcharts.js"></script>
<script src="https://calderonimartini.condivision.biz/fl_set/jsc/highcharts/modules/exporting.js"></script>

</head>
<body><div id="up_menu" class="noprint" style="text-align:left;">
<span class="topsx"><a class="back" href="/fl_modules/mod_eventi/index.php?a=crm">  <i class="fa fa-angle-left"></i>

 </a></span>
<span class="appname">
<a href="https://calderonimartini.condivision.biz/?a=dashboard"><img src="https://calderonimartini.condivision.biz/fl_config/calderonimartini.condivision.biz/img/logo.png" alt="Calderoni Martini Resort"/></a></span>
</div>
 
<div id="preloader"><img src="https://calderonimartini.condivision.biz/fl_set/img/preloader.png" /></div>




<body style=" background: rgb(241, 241, 241) none repeat scroll 0% 0%;">
<div id="container" >
<div id="content_scheda">

<a data-fancybox-type="iframe" class="fancybox_view" href="mod_stampa.php?id=4" title="Stampa Contratto">Contratto <i class="fa fa-print"></i> </a>







<form id="scheda" action="../mod_basic/action_modifica.php" method="post" enctype="multipart/form-data">
<p><a href="/fl_modules/mod_eventi/mod_inserisci.php?id=4&label">Modifica Campi</a> </p><p><input type="hidden" name="id" id="id" value="4" /></p><p><input type="hidden" name="gtx" id="gtx" value="6" /></p><div id="results"></div><div id="tabs"><ul><li><a href="#tb_id">Scheda Evento</a></li><li><a href="#tb_anagrafica_cliente">Anagrafica Cliente</a></li><li><a href="#tb_0">Menù</a></li><li><a href="#tb_1">Allestimenti della sala</a></li><li><a href="#tb_2">Vini e Bevande</a></li><li><a href="#tb_3">Servizi Aggiuntivi</a></li></ul>
	<div id="tb_id"><div class="form_row" id="box_stato_evento"><p class="pbox"><label class="labelbox">Stato evento</label>
			<input type="radio" id="stato_evento0" name="stato_evento" value="0"  />
			<label for="stato_evento0">Da confermare</label>
			<input type="radio" id="stato_evento1" name="stato_evento" value="1" checked="checked" />
			<label for="stato_evento1">Confermato</label>
			<input type="radio" id="stato_evento2" name="stato_evento" value="2"  />
			<label for="stato_evento2">Archiviato</label>
			<input type="radio" id="stato_evento3" name="stato_evento" value="3"  />
			<label for="stato_evento3">Annullato</label></p></div><div class="form_row" id="box_lead_id"><input type="hidden" name="lead_id" id="lead_id" value="0"  />
</div><div class="form_row" id="box_customer_id"><input type="hidden" name="customer_id" id="customer_id" value="0"  />
</div><div class="form_row" id="box_contract_id"><input type="hidden" name="contract_id" id="contract_id" value="0"  />
</div><div class="form_row" id="box_tipo_evento"><p class="select_text tipo_evento" ><label for="tipo_evento">Tipo evento</label>

			<select class="select2 " name="tipo_evento" id="tipo_evento"  >
<option value="-1">Seleziona...</option>
<option value="9" selected="selected"> Ricevimento con Rito Civile</option>
<option value="8"> Comunione</option>
<option value="7"> Party</option>
<option value="6"> Banchetto</option>
<option value="5"> Ricevimento</option>
<option value="0">Non selezionata</option>
</select>
</p>
</div><div class="form_row" id="box_periodo_evento"><p class="select_text periodo_evento" ><label for="periodo_evento">Periodo evento</label>

			<select class="select2 " name="periodo_evento" id="periodo_evento"  >
<option value="-1">Seleziona...</option>
<option value="1">Non Selezionato</option>
<option value="101" selected="selected">Evento Diurno</option>
<option value="102">Evento Serale</option>
</select>
</p>
</div><div class="form_row" id="box_data_evento"><p class="input_text"><label for="data_evento">Data evento</label>
			<input type="text" name="data_evento" id="data_evento" value="22/04/2017 13:00" class="datetimepicker" />
			</p>
</div><div class="form_row" id="box_data_fine_evento"><p class="input_text"><label for="data_fine_evento">Data fine evento</label>
			<input type="text" name="data_fine_evento" id="data_fine_evento" value="22/04/2017 21:00" class="datetimepicker" />
			</p>
</div><div class="form_row" id="box_location_evento"><p class="select_text location_evento" ><label for="location_evento">Location evento</label>

			<select class="select2 " name="location_evento" id="location_evento"  >
<option value="-1">Seleziona...</option>
<option value="31" selected="selected">Sala Calderoni Martini Resort</option>
<option value="0">Non selezionata</option>
</select>
</p>
</div><div class="form_row" id="box_ambienti"><p class="select_text ambienti"><label>Ambienti</label><br><span style="text-align: right;">

			<input name="ambienti[119]" type="checkbox" id="ambienti119" value="119"  />
			<label for="ambienti119">Sala Calderoni</label>
			<input name="ambienti[120]" type="checkbox" id="ambienti120" value="120" checked="checked" />
			<label for="ambienti120">Sala Martini</label>
			<input name="ambienti[131]" type="checkbox" id="ambienti131" value="131"  />
			<label for="ambienti131">Eden Martini</label>
			<input name="ambienti[132]" type="checkbox" id="ambienti132" value="132"  />
			<label for="ambienti132">Sala Templari</label></span></p></div><h3 class="testata">Dettaglio Evento</h3>
<div class="form_row" id="box_titolo_ricorrenza">

<p class="input_text">
<label for="titolo_ricorrenza">Titolo ricorrenza  </label>
<input   class="" type="text" name="titolo_ricorrenza" id="titolo_ricorrenza"  value="Matrimonio CASABURO"  /> 
</p>

</div><div class="form_row" id="box_numero_adulti"><p class="input_text"><label for="numero_adulti">Numero adulti  </label><input   class="" type="text" name="numero_adulti" id="numero_adulti"  value="100"  /> </p>
</div><div class="form_row" id="box_numero_bambini"><p class="input_text"><label for="numero_bambini">Numero bambini  </label><input  onclick="this.value=''" class="" type="text" name="numero_bambini" id="numero_bambini"  value="0"  /> </p>
</div><div class="form_row" id="box_descrizione"><h3><label for="descrizione">Descrizione</label></h3><p class="input_text"><br /><textarea  name="descrizione"  class="mceEditor"  id="descrizione" rows="20" cols="100"  onkeyup="$('#infodescrizione').html(this.value.length+' caratteri');"></textarea><span id="infodescrizione"></span></p>
</div></div>







<div id="tb_anagrafica_cliente">




<div class="form_row" id="box_anagrafica_cliente">
<p class="select_text anagrafica_cliente" >
<label for="anagrafica_cliente">Anagrafica cliente</label>
<select name="anagrafica_cliente" id="anagrafica_cliente"  >
<option value="-1">Seleziona...</option>
<option value="ID" selected="selected">Nome e cognome</option>
</select>
</p>


<div class="nuovoElemento" id="out_anagrafica_cliente">

<p class="input_text">
<label>Nome </label>
<input type="text" name="nome" value="" placeholder="Inserisci nome" class="outForm"  /> 
</p>	

<p class="input_text">
<label>Nome </label>
<input type="text" name="nome" value="" placeholder="Inserisci nome" class="outForm"  /> 
</p>	

<p class="input_text">
<label>Nome </label>
<input type="text" name="nome" value="" placeholder="Inserisci nome" class="outForm"  /> 
</p>	

<p class="input_text">
<label>Nome </label>
<input type="text" name="nome" value="" placeholder="Inserisci nome" class="outForm"  /> 
</p>

<input type="button" value="Crea Nuovo" onclick="creaElemento(48,'anagrafica_cliente');">	

</div>

</div>



<div class="form_row" id="box_anagrafica_cliente2">
<p class="select_text anagrafica_cliente2" ><label for="anagrafica_cliente2">Anagrafica cliente2</label>
<select name="anagrafica_cliente2" id="anagrafica_cliente2"  >
<option value="-1">Seleziona...</option>
</select>
</p>
</div>




















<div class="form_row" id="box_estremi_acconto"><p class="input_text"><label for="estremi_acconto">Estremi acconto  </label><input   class="" type="text" name="estremi_acconto" id="estremi_acconto"  value=""  /> </p>
</div><div class="form_row" id="box_condizioni_aggiuntive"><h3><label for="condizioni_aggiuntive">Condizioni aggiuntive</label></h3><p class="input_text"><br /><textarea  name="condizioni_aggiuntive"  class="mceEditor"  id="condizioni_aggiuntive" rows="20" cols="100"  onkeyup="$('#infocondizioni_aggiuntive').html(this.value.length+' caratteri');"></textarea><span id="infocondizioni_aggiuntive"></span></p>
</div><div class="form_row" id="box_note"><input type="hidden" name="note" id="note" value="Ins. Da CSV"  />
</div><div class="form_row" id="box_data_creazione"><input type="hidden" name="data_creazione" id="data_creazione" value="2017-04-07"  />
</div><div class="form_row" id="box_data_aggiornamento"><input type="hidden" name="data_aggiornamento" id="data_aggiornamento" value="2017-04-07"  />
</div><div class="form_row" id="box_operatore"><input type="hidden" name="operatore" id="operatore" value="1"  />
</div><div class="form_row" id="box_proprietario"><input type="hidden" name="proprietario" id="proprietario" value="14"  />
</div></div><div id="tb_0"><iframe style=" width: 100%; border: none; height:1200px; " src="../mod_menu_portate/mod_user.php?evento_id=4"></iframe></div><div id="tb_1"><iframe style=" width: 100%; border: none; height:1200px; " src="../mod_linee_prodotti/mod_user.php?categoria_prodotto=30&evento_id=4"></iframe></div><div id="tb_2"><iframe style=" width: 100%; border: none; height:1200px; " src="../mod_linee_prodotti/mod_user.php?categoria_prodotto=32&evento_id=4"></iframe></div><div id="tb_3"><iframe style=" width: 100%; border: none; height:1200px; " src="../mod_linee_prodotti/mod_user.php?categoria_prodotto=33&evento_id=4"></iframe></div></div><p class="savetabs"><a href="#" id="invio" class="button salva"> Salva <i class="fa fa-check"></i></a></p><input type="hidden" name="reload" value="../mod_eventi/?action=17&last=" /></form>



<a  href="../mod_basic/action_elimina.php?POST_BACK_PAGE=../mod_appuntamenti/&gtx=6&amp;unset=4" title="Elimina"  onclick="return conferma_del();"><i class="fa fa-trash-o"></i> Elimina </a>

</div></div></body></html>
