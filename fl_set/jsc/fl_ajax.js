		
/* Connessione Ajax */
function fl_connect() {
			var
				httpx = null,
				browser = navigator.userAgent.toUpperCase();
				
			if(typeof(XMLHttpRequest) === "function" || typeof(XMLHttpRequest) === "object")
			
			httpx = new XMLHttpRequest(); /* Connessione Standard */
		
		else if(window.ActiveXObject && browser.indexOf("MSIE 4") < 0) {
				if(browserUtente.indexOf("MSIE 5") < 0)
					httpx = new ActiveXObject("Msxml2.XMLHTTP");
				else
					httpx = new ActiveXObject("Microsoft.XMLHTTP");
			}
			return httpx;
		};	
		
		<!--//

var testo_src = { unavariabile: ''}


function fl_verifica_address(indirizzo,citta,cap) {

  var  http = fl_connect();
 
  if(http) {

	var url = "../mod_basic/fl_cap.php";
	var params = "address="+$('#'+cap).val()+" "+$('#'+indirizzo).val()+"&city="+$('#'+citta).val();
	http.open("POST", url, true);
	
	http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	http.setRequestHeader("Content-length", params.length);
	http.setRequestHeader("Connection", "close");
	
	http.onreadystatechange = function() {
	if(http.readyState == 2) {

	}
	
	if(http.readyState == 4 && http.status == 200) {
		
     console.log(http.responseText);
	 obj = JSON.parse(http.responseText);
	 if(obj.location_type != '') $('#'+indirizzo).val(obj.location_type);
	 if(obj.postal_code != '') $('#'+cap).val(obj.postal_code);
	 
	 $('#box_'+cap).after('<div class="tab_red" style="padding: 4px;">Rilevato: '+obj.formatted_address+'</div>');
	}
	}
	http.send(params);

  }
   
  
} 

function fl_get_coordinate(indirizzo,citta,cap,provincia,lat,lon) {


  var  http = fl_connect();
  if(http) {

	var url = "../mod_basic/fl_cap.php";
	var params = "address="+$('#'+cap).val()+" "+$('#'+indirizzo).val()+"&city="+$('#'+citta).val()+"&provincia="+$('#'+provincia+' option:selected' ).text();
	http.open("POST", url, true);
	console.log(params);
	http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	http.setRequestHeader("Content-length", params.length);
	http.setRequestHeader("Connection", "close");
	
	http.onreadystatechange = function() {
	if(http.readyState == 2) {
	//(confirm("Want to load CV?")) ? "" : http.abort() ;

	}
	
	if(http.readyState == 4 && http.status == 200) {
		
     console.log(http.responseText);
	 obj = JSON.parse(http.responseText);

	var url = '../mod_basic/mod_locator.php?label_indirizzo='+indirizzo+'&label_cap='+cap+'&label_lat='+lat+'&label_lon='+lon+'&lat='+obj.lat+'&lon='+obj.lon+'&indirizzo='+obj.location_type+'&cap='+obj.postal_code+"&city="+$('#'+citta).val()+"&provincia="+$('#'+provincia+' option:selected' ).text();

   $.fancybox.open({
        'type': 'iframe', 
        'width' : 1000,
        'height' : 600,
        'autoDimensions' : false,
        'autoScale' : false,
        'href' : url
    });
	
	}
	}
	http.send(params);

  }
   
  
} 



function load_cliente() {

 	
  var  http = fl_connect();
  var  usaLink = true;  
 
  if(http) {

	var url = "../mod_basic/get_anagrafica.php";
	var params = "id="+$('#anagrafica_id').val();
	http.open("POST", url, true);
	
	http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	
	http.onreadystatechange = function() {
	if(http.readyState == 2) {
	(confirm("Caricare dati cliente?")) ? "" : http.abort() ;

	}
	
	if(http.readyState == 4 && http.status == 200) {
		
	 obj = JSON.parse(http.responseText);
	 $('#ragione_sociale').val(obj.ragione_sociale);
	 $('#indirizzo').val(obj.indirizzo);
	 $('#partita_iva').val(obj.partita_iva);
	 $('#codice_fiscale').val(obj.codice_fiscale_legale);
	 $('#paese').val(100000100);
	
	}
	}
	http.send(params);
	usaLink = false;
  }
   
  
} 


function caricaProprietario(testo,elemento,input) {
var  elementox = getElement(elemento);
if(testo.length > 3 && testo != testo_src.unavariabile && testo != testo_src.unavariabile+" "){
testo_src.unavariabile = testo;
return fl_select(testo,elemento,input);
} else {
elementox.style['display'] = "none";
return false;
}}

function fill_selection(valore,elemento,value,input){
 var elementox = getElement(elemento);
 var input = getElement(input);
 elementox.style['display'] = "none";
 input.value = valore;
	
	
	}
function fl_select(testo,elemento,input) {

 	
  var  http = fl_connect();
  var  usaLink = true;  
  var  elementox = getElement(elemento);
 
  if(http) {

	var url = "../mod_basic/get_select.php";
	var params = "elemento="+elemento+"&input="+input+"&cerca="+testo;
	http.open("POST", url, true);
	
	http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	http.setRequestHeader("Content-length", params.length);
	http.setRequestHeader("Connection", "close");
	
	http.onreadystatechange = function() {
	if(http.readyState == 2) {
	//(confirm("Sicuro di Voler Procedere?")) ? "" : http.abort() ;
	//alert(readyState[http.readyState]);
	}
	
	if(http.readyState == 4 && http.status == 200) {
			elementox.style['display'] = "block";
			elementox.innerHTML = http.responseText;
			
		}
	}
	http.send(params);
	usaLink = false;
  }
   
  return usaLink;
} 



function caricaTesto(testo) {
var  elemento = getElement("contenuto-dinamico");
if(testo.length > 3 && testo != testo_src.unavariabile && testo != testo_src.unavariabile+" "){
testo_src.unavariabile = testo;
return fl_cerca(testo,elemento);
} else {
elemento.style['display'] = "none";
return false;
}}


function insert_record(href){

  var  http = fl_connect();
  var  usaLink = true;  
  var  elementox = getElement("info");

	

    if(http) {

	var url = "ajax.php?"+href;
	var params = href;
	http.open("POST", url, true);
	
	http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	http.setRequestHeader("Content-length", params.length);
	http.setRequestHeader("Connection", "close");
	
	http.onreadystatechange = function() {
		
		if(http.readyState == 2) {
		//(confirm("Sicuro di Voler Procedere?")) ? "" : http.abort() ;
		//alert(readyState[http.readyState]);
		elementox.innerHTML = "Verifica in corso...";
		}
		if(http.readyState == 4 && http.status == 200) {
				elementox.style['display'] = "block";
				elementox.innerHTML = http.responseText;
				window.location.href = './';
			
		} else { elementox.innerHTML = readyState[http.readyState]; }
	}
	
	http.send(params);
	usaLink = false;
	
    } // edn if http
   
   return usaLink;
}
	
	
function fl_cerca(testo,elemento) {

 	
  var  http = fl_connect();
  var  usaLink = true;  
  
 
  if(http) {

	var url = "../mod_basic/ricerca.php";
	var params = "cerca="+testo;
	http.open("POST", url, true);
	
	http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	http.setRequestHeader("Content-length", params.length);
	http.setRequestHeader("Connection", "close");
	
	http.onreadystatechange = function() {
	if(http.readyState == 2) {
	//(confirm("Sicuro di Voler Procedere?")) ? "" : http.abort() ;
	//alert(readyState[http.readyState]);
	}
	
	if(http.readyState == 4 && http.status == 200) {
			elemento.style['display'] = "block";
			elemento.innerHTML = http.responseText;
		}
	}
	http.send(params);
	usaLink = false;
  }
   
  return usaLink;
} 

//-->

function load_content(url,elemento_id) { 

  var  http = fl_connect();
  var  content = true; 
  var  elemento = getElement(elemento_id);

  if(http) {

	var params = url;
	http.open("POST", url, true);
	//alert(params);
	http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	http.setRequestHeader("Content-length", params.length);
	http.setRequestHeader("Connection", "close");
	
	http.onreadystatechange = function() {
	
	if(http.readyState < 3) {
	
	elemento.innerHTML = '<p style="color: red;">Stato: '+readyState[http.readyState]+'</p>';
	
	}	
	
	if(http.readyState == 3) {
			
	elemento.style['display'] = "block";
	elemento.innerHTML = '<p style="color: yellow;">Stato: '+readyState[http.readyState]+'</p>';			

	var myData = http.responseText;			
	var my = myData.split(',');
				
	}
	if(http.readyState == 4 && http.status == 200) {
			
	var myData = http.responseText;
	elemento.innerHTML = '<p style="color: green;">'+myData+'</p>';			

			
	}
	}	
	
	http.send(params);
	content = false;
  }
   
  return content;
} 


function fl_aj_content(url,elemento_id) {

 	
  var  http = fl_connect();
  var  content = true; 
  var  elemento = getElement(elemento_id);

  if(http) {

	var params = parametri;
	http.open("POST", url, true);
	
	http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	http.setRequestHeader("Content-length", params.length);
	http.setRequestHeader("Connection", "close");
	
	http.onreadystatechange = function() {
	
	if(http.readyState < 3) {
	elemento.innerHTML = '<p style="color: red;">Stato: '+readyState[http.readyState]+'</p>';
	
	}	
	if(http.readyState == 3) {
			
	elemento.style['display'] = "block";
	elemento.innerHTML = '<p style="color: yellow;">Stato: '+readyState[http.readyState]+'</p>';			
	var myData = http.responseText;
			
	var my = myData.split(',');
				
	}
	if(http.readyState == 4 && http.status == 200) {
			
	var myData = http.responseText;
			

	elemento.innerHTML = '<p style="color: green;">'+myData+'</p>';			

			
	}
	}
	
	
	http.send(params);
	content = false;
  }
   
  return content;
} 


function getElement(id_elemento) {
			var element;
			if(document.getElementById)
				element = document.getElementById(id_elemento);
			else
				element = document.all[id_elemento];
			return element;
		};
	



		var readyState = new Object();
		readyState[0] = "Inattivo";
		readyState[1] = "Inizializzato";
		readyState[2] = "Richiesta";
		readyState[3] = "Risposta";
		readyState[4] = "Completato";
		
		var statusTxt = new Array();
		statusTxt[100] = "Continue";
		statusTxt[101] = "Switching Protocols";
		statusTxt[200] = "OK";
		statusTxt[201] = "Created";
		statusTxt[202] = "Accepted";
		statusTxt[203] = "Non-Authoritative Information";
		statusTxt[204] = "No Content";
		statusTxt[205] = "Reset Content";
		statusTxt[206] = "Partial Content";
		statusTxt[300] = "Multiple Choices";
		statusTxt[301] = "Moved Permanently";
		statusTxt[302] = "Found";
		statusTxt[303] = "See Other";
		statusTxt[304] = "Not Modified";
		statusTxt[305] = "Use Proxy";
		statusTxt[306] = "(unused, but reserved)";
		statusTxt[307] = "Temporary Redirect";
		statusTxt[400] = "Bad Request";
		statusTxt[401] = "Unauthorized";
		statusTxt[402] = "Payment Required";
		statusTxt[403] = "Forbidden";
		statusTxt[404] = "Not Found";
		statusTxt[405] = "Method Not Allowed";
		statusTxt[406] = "Not Acceptable";
		statusTxt[407] = "Proxy Authentication Required";
		statusTxt[408] = "Request Timeout";
		statusTxt[409] = "Conflict";
		statusTxt[410] = "Gone";
		statusTxt[411] = "Length Required";
		statusTxt[412] = "Precondition Failed";
		statusTxt[413] = "Request Entity Too Large";
		statusTxt[414] = "Request-URI Too Long";
		statusTxt[415] = "Unsupported Media Type";
		statusTxt[416] = "Requested Range Not Satisfiable";
		statusTxt[417] = "Expectation Failed";
		statusTxt[500] = "Internal Server Error";
		statusTxt[501] = "Not Implemented";
		statusTxt[502] = "Bad Gateway";
		statusTxt[503] = "Service Unavailable";
		statusTxt[504] = "Gateway Timeout";
		statusTxt[505] = "HTTP Version Not Supported";
		statusTxt[509] = "Bandwidth Limit Exceeded";