// JavaScript Document
function alerttext(id,maxnum,num){

	document.getElementById(id).style.color = (num >= maxnum) ?  "red" : "black" ; 
	
	}
	

function insertAtCaret(areaId,text) {
    
    var txtarea = document.getElementById(areaId);
    var scrollPos = txtarea.scrollTop;
    var strPos = 0;
    var br = ((txtarea.selectionStart || txtarea.selectionStart == '0') ? 
        "ff" : (document.selection ? "ie" : false ) );
    if (br == "ie") { 
        txtarea.focus();
        var range = document.selection.createRange();
        range.moveStart ('character', -txtarea.value.length);
        strPos = range.text.length;
    }
    else if (br == "ff") strPos = txtarea.selectionStart;

    var front = (txtarea.value).substring(0,strPos);  
    var back = (txtarea.value).substring(strPos,txtarea.value.length); 
    txtarea.value=front+text+back;
    strPos = strPos + text.length;
    if (br == "ie") { 
        txtarea.focus();
        var range = document.selection.createRange();
        range.moveStart ('character', -txtarea.value.length);
        range.moveStart ('character', strPos);
        range.moveEnd ('character', 0);
        range.select();
    }
    else if (br == "ff") {
        txtarea.selectionStart = strPos;
        txtarea.selectionEnd = strPos;
        txtarea.focus();
    }
    txtarea.scrollTop = scrollPos;
}


	
/* Notifiche PUSH */
function notify(message,link) {
  // Controlliamo che il browser supporti le Notifications API
  if (!("Notification" in window)) {
    //alert("Questo browser non supporta le notifiche.");
  }
 
  // Controlliamo che l'utente abbia accettato le notifiche
  else if (Notification.permission === "granted") {
    // Se va tutto bene creiamo la notifica
    var notification = new Notification(message);
	 notification.onclick = function() { 
        window.location.href = link;
    };
}
 
// Altrimenti dobbiamo chiedere il permesso all'utente
else if (Notification.permission !== 'denied') {
    Notification.requestPermission(function (permission) {
      // Se abbiamo il consenso possiamo creare la notifica
      if (permission === "granted") {
          var notification = new Notification(message);
      }
    });
  }
}



/* Menu laterale */
function changeLayout(){
	if($("#side_menu").css('display').valueOf() == 'none') {
	
  /*if($.browser.mozilla){
		$('#menu').css('margin-left','-65px');
$('#content').css('margin-left','-65px');
$('.content').css('margin-left','-10px');
	}else{
	$('#menu').css('margin-left','-85px');
$('#content').css('margin-left','-85px');
$('.content').css('margin-left','-30px');
}*/
	var mleft = ($( window ).width() > 600) ? 330 : 0; 
	$('#corpo').animate({
    marginLeft: mleft,
   	}, 100, function() {
    $("#side_menu").fadeToggle("fast", "swing");
	});
	document.cookie = 'menu=1; path=/'	
	
	} else {
	//$('#menu').css('margin-left','0px');
  //$('#content').css('margin-left','0px');
	$("#side_menu").fadeToggle("fast", "swing",function(){ 	if($(this).css('display').valueOf() == 'none') $('#corpo').animate({marginLeft: 5,}, 200);	});	
	document.cookie = 'menu=0; path=/'	
	}
}
function setck(id) { //Cookie menu laterale aperto/chiuso 
if($(id).css('display').valueOf() == 'none') {
	document.cookie = id+'=1; path=/';
	} else {
	document.cookie = id+'=0; path=/'
	}
}

function display_toggle(id){
$(id).fadeToggle("fast", "swing",setck(id));
}

function conferma(msg){
var flag = confirm(msg); 
if (flag==true){ 
return true;
} else {  
return false; 
}
}



function conferma_del(){
var flag = confirm("Sicuri di Voler Eliminare?"); 
  if(flag==true){ 
  return true;
  } else {  
  return false; 
  }
}

function elimina_tutto(form_id){
form = document.getElementById(form_id);
flag = confirm("Sicuri di voler eliminare tutta la selezione?"); 
if (flag==true){ form.submit(); } else {window.status='Cancellazione Annullata'}
}

function fullscreen(what){
window.open(what,"","fullscreen");
}


function OpenBrWindow(theURL,winName) 
{
 params  = 'width='+screen.width;
 params += ', height='+screen.height;
 params += ', top=0, left=0'
 params += ', fullscreen=yes';

 newwin=window.open(theURL,winName, params);
 if (window.focus) {newwin.focus(); }
 return false;
}

	
	/* tomas */
	
		function cambiaRicerca(valore){
			//alert(valore);
			if(valore=='semplice'){
				document.getElementById('avanzata').style.display='none';
				document.getElementById('semplice').style.display='block';
			}
			else{
				document.getElementById('avanzata').style.display='block';
				document.getElementById('semplice').style.display='none';
			}
		}

	  	function mostraCampo() {

			campo=document.getElementById('CAMPO');
			
		  	
	  		tipo=document.getElementById('CAMPO').value;
	  		nome=campo.options[campo.selectedIndex].text;
	  		//alert(tipo+" "+nome);
	  		if((tipo=='string')||(tipo=='int')){
	  			document.getElementById('mostra_campo').innerHTML ="<br />Valore campo <input type='text' name='campo-"+nome+"'/>";
	  			document.getElementById('div_data').style.display='none';
	  			document.getElementById('data_da').value='';
	  			document.getElementById('data_a').value='';
	  			
			  	}
	  		else if (tipo=='datetime') {
	  			document.getElementById('mostra_campo').innerHTML ="<input type='hidden' name='campo-"+nome+"'/>";
	  			
	  			document.getElementById('div_data').style.display='block';

		  	}
	  		else
	  		{
	  			document.getElementById('mostra_campo').innerHTML = tipo;
	  		}
	  		//document.getElementById('mostra_campo').innerHTML = tipo.value;

		  	}
	    
	

function loadProvince(from,where,empty){ 
  var post = 'sel=provincia&filtro=regione&valore='+$(from).val();
  var url = '../mod_basic/mod_selectLoader.php';
  var posting = $.post(url,post); 
  posting.fail(function( data ) {     });
  posting.always(function( data ) {    });
  posting.done(function( response ) {
   
	if(empty == 1) $(where).empty();
    var data = $.parseJSON(response);
	  $.each(data, function(i, value) {
           if($(where).val() != value) $(where).append('<option = "'+value+">"+value+"</option>");
		   if(empty == 1) $(where).focus();
        });
   });
}

function loadComuni(from,where,empty){ 
  var post = 'sel=comune&filtro=provincia&valore='+$(from).val();
  var url = '../mod_basic/mod_selectLoader.php';
  var posting = $.post(url,post); 
  posting.fail(function( data ) {     });
  posting.always(function( data ) {    });
  posting.done(function( response ) {
  
	if(empty == 1)  $(where).empty();
    var data = $.parseJSON(response);
	  $.each(data, function(i, value) {
           if($(where).val() != value) $(where).append('<option = "'+value+">"+value+"</option>");
		   if(empty == 1)  $(where).focus();
        });
   });
}

function loadCap(from,where,empty){ 
  var post = 'sel=cap&filtro=comune&valore='+$(from).val();
  var url = '../mod_basic/mod_selectLoader.php';
  var posting = $.post(url,post); 
  posting.fail(function( data ) {     });
  posting.always(function( data ) {    });
  posting.done(function( response ) {
 
	if(empty == 1)  $(where).empty();
    var data = $.parseJSON(response);
	  $.each(data, function(i, value) {
           $(where).val(value);
		   if(empty == 1) $(where).focus();
        });
   });
}



function loadSelect(gtx,from,where,filtro,sel,empty){ 
  $(where).attr('placeholder','Caricamento...'); 
  var post = 'gtx='+gtx+'&sel='+sel+'&filtro='+filtro+'&valore='+$(from).val();
  var url = '../mod_basic/mod_selectLoader.php';
  var posting = $.post(url,post); 
 
  posting.fail(function( data ) {   $(where).attr('placeholder','Errore caricamento!');    });
 
  posting.always(function( data ) {   $(where).attr('placeholder','Caricamento...');   });
  
  posting.done(function( response ) {
 
 
  if(empty == 1)  $(where).empty();
  
  $(where).attr('placeholder','');
   
    var data = $.parseJSON(response);
    $.each(data, function(i, value) {
        $(where).val(value);     
    });
    if(empty == 1) $(where).focus();
  });

}
	   
	
