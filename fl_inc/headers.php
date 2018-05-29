<!DOCTYPE html>
<html lang="it">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo sitename; ?></title>

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

<style type="text/css" media="all">
<?php if(isset($_GET['b'])) { ?>
.sub-<?php echo check($_GET['b']); ?> { background: #528354; color: white; }
.jqueryslidemenu ul li a.sub-<?php echo check($_GET['b']); ?>  {  color: white; !important; }
<?php } ?>
@media screen and (max-width: 600) {
#corpo { margin-left: 0px; }
}
<?php if(isset($_COOKIE['menu']) && @$_COOKIE['menu'] == 0) { ?>
#corpo { margin-left: 0px; }
#side_menu { display: none; }
<?php } ?>
<?php if(isset($_COOKIE['#menu_modulo']) && @$_COOKIE['#menu_modulo'] == 1) { ?>
#menu_modulo { display: block; }
<?php } ?>
<?php if(isset($_COOKIE['#filtri']) && @$_COOKIE['#filtri'] == 1) { ?>
.filtri { display: block; }
<?php } ?>

.poipat { z-index: 9999; }
.xzh-g3 { text-align: left;}

</style>
<?php if(isset($chat)) { ?>
<!--<script src="//cdn.poip.at/js/v0/poipat.js"></script>-->

<script>
/*
var params = location.search.split("?");
params.shift();
if (params[0])
	params = params[0].split("&");

var style = {};
params.forEach(function (param)
{
	var match = param.match(/^([^=]+)(?:=(.*))?$/);
	if (match)
	style[match[1]] = match[2] || true;
});*/


/*
var id = <?php echo $_SESSION['number']; ?>;

var users = [
	<?php 
		
	$query = "SELECT utente,data_creazione FROM fl_action_recorder WHERE data_creazione > ( NOW() - INTERVAL 30 MINUTE) GROUP BY utente";
	$risultato = mysql_query($query, CONNECT);

	while ($riga = mysql_fetch_array($risultato)) 
	{
	$account = get_account($riga['utente'],'user');
	if($account['id'] > 0) {
	 ?>
	{
		id: <?php echo $account['id']; ?>,
		name: '<?php echo $account['nominativo']; ?>',
		last_name: '',
		avatar: '//ford.bluemotive.it/fl_inc/img/bm_logo.png'
	},
	
	<?php  } } ?>

	{
		id: '1',
		username: 'Supporto',
		avatar: '//ford.bluemotive.it/fl_inc/img/bm_logo.png'
	},
];


var config = {
	id: id,
	db: users,
	title: 'Chat',
	notifications: {
		audio: true,
		dialog: true,
		title: true
	},
	style: {
		color: '#4c9ed9',
		
		right: '50px'
		
	}
};


PoipAt.init(config);*/

</script>
 <?php  } ?>   
   
<script src="<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/fl_ajax.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/function.js"></script>
<script src="<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/jquery-1.8.3.js" type="text/javascript"></script>
<script src="<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/jquery-ui.js" type="text/javascript"></script>

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

<script type="text/javascript" src="<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/jqueryslidemenu.js"></script>

<?php if(isset($dateTimePicker)) { ?>
<script src="../../fl_set/jsc/datetimepicker/build/jquery.datetimepicker.full.js"></script>
<link rel="stylesheet" type="text/css" href="../../fl_set/jsc/datetimepicker/jquery.datetimepicker.css"/>
<?php } ?>

<?php if(isset($formValidator)) { ?>
<link rel="stylesheet" href="<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/validator/validationEngine.css" type="text/css">
<script src="<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/validator/validation_ita.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/validator/validationEngine.js" type="text/javascript" charset="utf-8"></script>
<?php } ?>

<?php if(isset($fancybox))  { ?>
<script type="text/javascript" src="<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/fancybox/source/jquery.fancybox.js?v=2.0.6"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/fancybox/source/jquery.fancybox.css?v=2.0.6" media="screen" />
<?php } ?>

<script src="<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/2select/select2.min.js"></script>
<link href="<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/2select/select2.min.css" rel="stylesheet" />


<script type="text/javascript">
/*Avvio*/
$(document).ready(function() {
	
$("#file").live( 'change', function () {
                $('#upload_csv').submit();
            });
            	

$(".showcheckItems").click(function () { 

    $(".checkItemTd").fadeToggle();
    
});


$("#sideMenuToggle").click(function () { 

    $("#side_menu").toggleClass( 'closed-side','open-side');
    $("#corpo").toggleClass( 'closed-corpo','open-corpo');
    
    changeLayout();
});

function showSideMenu() {
    
//    console.log('now: ' + document.cookie);
  
//    if (window.matchMedia('(min-width: 768px)').matches) {
        $('#userPhoto').empty();
        $('#side_menu').show();
        
        if (document.cookie.indexOf('menu=0') != -1) {
            $('#userPhoto').append('<a href="<?php echo ROOT; ?>"><img src="<?php echo ROOT.$cp_admin.$cp_inc; ?>img/bm_logo.png" /></a>');
            $('#userPhoto').css('height', '5%');
            $('#userPhoto img').css('max-width', '100%');
            $('#left-side-menu').addClass('nav-custom');
            $('#side_menu a').css({'color':'#eeeeee', 'font-size':'12px'});
            if ($('#accordion').hasClass('ui-accordion')) {
                $('#accordion').accordion('destroy');
            } 
        }
        else {
            $('#userPhoto').append('<a href="<?php echo ROOT; ?>"><img src="<?php echo ROOT.$cp_admin.$cp_inc; ?>img/bm_logo_big.png" /></a>');
            $('#userPhoto').css('height', '10%');
            $('#userPhoto img').css('max-width', '50%');
            $('#left-side-menu').removeClass('nav-custom');
            $('#accordion').accordion({ animate: 200 ,collapsible: true, header: "h2",heightStyle: "content", autoHeight: false,fillSpace: false, active: false,event: "click" });
			$("#accordion a").each(function() {   
   			 //if (this.href == window.location.href) {
       				 //$(this).addClass("active-sidebar-link");
    			//}
			});
        }
//    }
//    else {
//
//        
//        $('#accordion').accordion({ animate: 200 ,collapsible: true, header: "h2",heightStyle: "content", autoHeight: false,fillSpace: false, active: 'h2#<?php //echo @$_SESSION['active'] ; ?>',event: "click" });        
//    }
}

function changeLayout(){

//    console.log('before: ' + document.cookie);
      
    if (document.cookie.indexOf('menu=0') != -1) {
        document.cookie = 'menu=1;';
    }
    else if (document.cookie.indexOf('menu=1') != -1) {
        document.cookie = 'menu=0;';
    }
    
    showSideMenu();
}

function setCookie() {
//    if ((document.cookie.indexOf('menu=0') == -1) && (document.cookie.indexOf('menu=1') == -1)) {
        document.cookie = 'menu=1;';
//    }
}

var now = new Date();
var hrs = now.getHours();
var msg = "";

if (hrs >  0) msg = "Buona prima mattina"; // REALLY early
if (hrs >  6) msg = "Buongiorno";      // After 6am
if (hrs > 12) msg = "Buon Pomeriggio";    // After 12pm
if (hrs > 17) msg = "Buona sera";      // After 5pm
if (hrs > 22) msg = "Buona notte";        // After 10pm
<?php if(isset($_SESSION['nome'])) { ?> $('#saluto').html(msg+' <?php echo $_SESSION['nome']; ?>');<?php } ?>



/*ACCORDION*/
//$("#accordion").accordion({ animate: 200 ,collapsible: true, header: "h2",heightStyle: "content", autoHeight: false,fillSpace: false, active: 'h2#<?php // echo @$_SESSION['active'] ; ?>',event: "click" });
setCookie();
showSideMenu();
    
<?php $sel_b = (isset($_GET['b'])) ? check($_GET['b']) : 1; ?>
$('*[data-sel-b="<?php echo $sel_b; ?>"]').addClass('selected');

/*Form Validator*/
<?php if(isset($formValidator)) { ?>jQuery("#scheda").validationEngine();<?php } ?>

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
       if($(this).hasClass('updateField')) { $(this).focusout(); } //Eccezione per compatibilita con update campo ajax
    }
});


$('.select2').select2();
 
$('#action_list').on('select2:select', function (evt) {


alert(this.val());

});

 
/* TABS */
var $tabsd = $( "#tabs").tabs({
selected: <?php echo (isset($_GET['t'])) ? base64_decode(check($_GET['t'])) : 0; ?> , 
show: function(event, ui) { $('.calendar:input').datepicker();  },
spinner: '<img src="<?php echo ROOT.$cp_admin.$cp_set; ?>lay/preloader.gif" alt="Caricamento" />' ,
ajaxOptions: {	error: function( xhr, status, index, anchor ) {	$( anchor.hash ).html("Non &egrave; possibile caricare il contenuto!" );}}
});

<?php if(isset($_GET['tId'])) { ?>
var index = $('#tabs a[href="#<?php echo check($_GET['tId']); ?>"]').parent().index();
$('#tabs').tabs('select', index);
<?php } ?>


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
   $(".filtri").fadeToggle("fast", "swing");	
   //setck('#filtri') memorizza menu filtri aperto o chiuso	
});

function setck(id) { //Cookie menu laterale aperto/chiuso 
if($(id).css('display').valueOf() == 'none') {
	document.cookie = id+'=1;';
	} else {
	document.cookie = id+'=0;'
	}
}



$('.open-close').click(function () {
   if($(this).next(".dsh_panel_content").css('display').valueOf() == 'block') { $(this).html('<a><i class="fa fa-angle-down" aria-hidden="true"></i></a>'); } else {  $(this).html('<a><i class="fa fa-angle-up" aria-hidden="true"></i></a>'); }	
  $(this).next(".dsh_panel_content").fadeToggle("fast", "swing");	
	event.preventDefault();
});

$('.mobile-buttons').hover(
function () {  $(this).find(".show_menu").show(); },
function () {  $(this).find(".show_menu").hide();	}
);

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

/*Dynamic Processor schede record */
$( "#invio" ).click(function( event ) {
  <?php if(isset($text_editor)) { ?> tinyMCE.triggerSave(); /* Salva testo di MCE */  <?php  } ?>
  var $form = $( "#scheda" ),
  url = $form.attr( "action" );
  var posting = $.post( '../mod_basic/save_data.php', $form.serialize() );
  $( "#results" ).empty().append( '<div class="esito orange">Salvataggio in corso...</div>' );
  posting.fail(function( data ) {    $( "#results" ).empty().append( '<div class="esito red">Errore di caricamento</div>' );       });
  posting.always(function( data ) {	 $( "#results" ).empty().append( '<div class="esito orange">Salvataggio in corso...</div>' );  });
  
 posting.done(function( response ) {
    console.log(response);
    var data = $.parseJSON(response);
	$('body,html').animate({scrollTop: 0}, 800);
	$( "#results" ).empty().append('<div class="esito '+data.class+'">'+data.esito+'</div>');
	if(data.action == 'goto') {	setTimeout(function(){ window.location.href = data.url; }, 300); };
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
	 $( "#results" ).empty().append( '<div class="esito orange">Invio in corso...</div>' );
  });
  
 posting.done(function( response ) {
    console.log(response);
    var data = $.parseJSON(response);
	$( "#results" ).empty().append('<div class="esito '+data.class+'">'+data.esito+'</div>');
  });
  event.preventDefault();
});


$( ".ajaxLink" ).click(function (event) {

var href = $(this).attr('href');
var trv = $(this).closest('tr');

var flag = confirm('Confermi?'); 

if (flag==true){ 

var posting = $.get(href+'&ajax'); 
$( ".results" ).empty().append( '<span class="msg  orange">Operazione in corso...</span>' ); 

posting.fail(function( data ) {   $( ".results" ).empty().append( '<span class="msg  red">Errore</span>' );  });
posting.always(function( data ) {  $( ".results" ).empty().append( '<span class="msg  orange">Operazione in corso...</span>' );  });
posting.done(function( response ) {  
  $( ".results" ).empty().append( '<span class="msg  green">Operazione Eseguita</span>' );
  trv.remove(); 
});

} else {  
return false; 
}
event.preventDefault();

});


/*Dynamic form processor ajax */
$( ".ajaxForm" ).on('submit',this,function( event ) {
  
  var form = $( this ),
  url = form.attr( "action" );
  var posting = $.post(url, form.serialize() ); 
  $( "#results" ).empty().append( '<div class="esito orange">Invio in corso...</div>' );
  
  posting.fail(function( data ) {   $( "#results" ).empty().append( '<div class="esito red">Errore di caricamento</div>' );  });
  posting.always(function( data ) {  $( "#results" ).empty().append( '<div class="esito orange">Creazione in corso...</div>' );  });
  posting.done(function( response ) {
    console.log(response);
    var data = $.parseJSON(response);
	$('body,html').animate({scrollTop: 0}, 800);
	$( "#results" ).empty().append('<div class="esito '+data.class+'">'+data.esito+'</div>');
	if(data.action == 'goto') {	setTimeout(function(){ window.location.href = data.url; }, 200); };
	if(data.url == '#') {	form.empty().append('<p>Invio della richiesta è stato eseguito con successo!</p>'); };
	<?php if(isset($fancybox))  { ?>if(data.action == 'popup') {	openpopup(data.url); };<?php } else { echo "alert('Popup Disabilitati');"; } ?>
	if(data.action == 'realoadParent') {	window.top.location.href = data.url;  };
   });
  event.preventDefault();
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

/*Apri un popup fancybox*/
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
  <?php if(isset($text_editor)) { ?> tinyMCE.triggerSave(); /* Salva testo di MCE */  <?php  } ?>
  var valore = $(this).val();
  var field = $(this).attr('name');
  var id = $(this).attr('data-rel');
  var obj = {gtx: '<?php echo @$tab_id; ?>'};
  if($(this).attr('data-gtx') > 1) obj['gtx'] = $(this).attr('data-gtx');
  obj[field] = valore;
  obj['id'] = id;
  var input = $(this);
  var posting = $.ajax({  url: '../mod_basic/save_data.php',  type: 'POST',  data: obj});
  posting.fail(function( data ) {  input.attr('class', 'bo-red');  });
  posting.always(function( data ) {  });
  posting.done(function( response ) {    
  console.log(response);
    var data = $.parseJSON(response);
  if(data.class == 'green') { input.attr('class', 'bo-green updateField'); }
  if(data.class == 'red') { input.attr('class', 'bo-red updateField'); }

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


<?php if(isset($fancybox))  { ?>
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
    width    : 500,
    height   : 500,
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
$(".fancybox_view_small").fancybox({
    autoSize : false,
    width    : 700,
    height   : 500,
	title : "",
	});
	/*Apri un popup fancybox*/


$(".facyboxParent").click(function() {
       
        var url = this.href;

         parent.$.fancybox({             

            'autoScale': true,
            'transitionIn': 'elastic',
            'transitionOut': 'elastic',
            'speedIn': 500,
            'speedOut': 300,
            'autoDimensions': true,
            'type' : 'iframe',
            'href' : url,    
            'centerOnScroll': true ,
            afterClose : function() { location.reload(); }    

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
<?php } ?>

<?php if(isset($dateTimePicker)) { ?>
$.datetimepicker.setLocale('it');


$('.datetimepicker').datetimepicker({format: 'd/m/Y H:i',step:15,dayOfWeekStart:1});
<?php } ?>


/*PRELOAD CONTENT */
$("#preloader").hide();
$("#container").show(); 
 
});
<?php if(isset($_SESSION['NOTIFY'])) { echo "notify('".$_SESSION['NOTIFY']."','');"; unset($_SESSION['NOTIFY']); } ?>
</script> 
 

<?php
if((!isset($_GET['advanced']) || @check($_GET['advanced']) == 0) && isset($text_editor)) { ?>
<script type="text/javascript" src="<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init({
		mode : "specific_textareas",
        editor_selector : "mceEditor",
		theme : "advanced",
 		plugins : "autoresize,safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
		width: '100%',
 		height: 400,
 		autoresize_min_height: 400,
  		autoresize_max_height: 800,
		language : "it",
		// Theme options
		<?php if($text_editor == 1) { ?>
		theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
		theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
		<?php } else { ?>
		theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,bullist,numlist,|,justifyleft,justifycenter,justifyright,justifyfull,fontsizeselect|,forecolor,backcolor,emotions",
	  	theme_advanced_buttons2 : "",
		theme_advanced_buttons3 : "",
		<?php } ?>
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
<?php }  ?>




<?php if(@$_SESSION['user'] != 'sistema'){  ?>
<?php }  ?>

<?php if(isset($smartsupp) && !isset($_GET['id']) && !isset($_GET['dir'])) { ?>
<?php }  ?>



<!-- Hotjar Tracking Code for http://ford.bluemotive.it -->
<script>
    (function(h,o,t,j,a,r){
        h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
        h._hjSettings={hjid:317165,hjsv:5};
        a=o.getElementsByTagName('head')[0];
        r=o.createElement('script');r.async=1;
        r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
        a.appendChild(r);
    })(window,document,'//static.hotjar.com/c/hotjar-','.js?sv=');
</script>



</head>
<body>