<?php 

session_start();
header('Content-Type: text/html; charset=UTF-8');
ini_set('default_charset', 'utf-8');
$_SESSION['user'] = 'menu';
$_SESSION['number'] = 1;
$_SESSION['landing'] = 1;
$_SESSION['form'] = 1;
define('NOSSL',1);

require_once("../../fl_core/core.php"); 
require_once(ROOTPATH."array_statiche.php");
$client = 1;
$notifiche = 0;
$autorefresh = 0;
$nochat = 1;
$jquery = 1;
$fancybox = 1;


include("../../fl_inc/headers.php");
?>
<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet"> 
<style>

body { 
font-family: font-family: 'Raleway', sans-serif;/*'Roboto', sans-serif;*/
background: #EDEDED;
}

#container { 
	max-width: 680px;
	margin: 0 auto;
	position: relative;
	font-size: 15px !important;
	line-height: 130%;
	}
.content { background: white; padding: 20px; text-align: left; }
.logo { background: #464646; padding: 5px; text-align: center; }
}



.input_radio label {
    display: inline-block;
    width: auto;
}


.sel input[type=radio],.sel input[type=checkbox] {
    display:none; 
    margin:10px;
}
 

.sel input[type=radio] + label,.sel input[type=checkbox] + label {
    display:inline-block;
    margin: 10px 5px 10px 0;
	float: left;
	width: 28%;
    padding: 12px 12px;
    background-color: #e7e7e7;
    border-color: #ddd;
	cursor: hand;
}

.sel input[type=radio]:checked + label,.sel input[type=checkbox]:checked + label { 
    background-image: none;
    background-color: #2BAB63  ;
	color: white;
		cursor: hand;
}

@media screen and (min-width: 0) and (max-width: 600px) {
.section_left { float: none; width: 100%;  }
.section_right { float: none; width: 100%;  }
#corpo { margin: 0; padding: 0; }
label { display: block; margin: 0;}
input, select, input[type="checkbox"] + label,input.button, .button input, .button{  width: 100%; margin: 0; }
input[type="radio"] + label, input[type="checkbox"] + label, .boxbutton {
    display: block;width: 100%;}
}
h2 {
    font-size: 16px;
}
a.button  { display: block; width: 100%; color: white; margin: 4px auto; padding:  20px 10px; text-align:  left;  }
</style>
</head>


<body style="background-color:light-grey">

<div class="logo">
 <img src="logo.png"  style="max-height: 70px; max-width: 200px; width: 100% "/> </div>  
<div id="container" style="display: block;">    

<img src="header.jpg"  style="width:100%"/>
<img src="musica2.jpg"  style="width:45%"/>
<img src="mauroluzzi2.gif"  style="width:45%"/>

<div class="content">

<h2 style="color: #594e4b;">Aperitivo in Musica</h2>
<p>
Il Calderoni Martini Resort  vi aspetta Domenica 26 Novembre alle ore 18:00 per un aperitivo  in compagnia del nostro direttore artistico, il Maestro Mauro Liuzzi.</p>
Durante la serata avrete modo di ascoltare le nuove proposte musicali per il vostro giorno più bello, un gruppo di musicisti accomunati da un'esperienza pluriennale nel settore dell'organizzazione di eventi vi trascineranno tra ritmi dei jazz club,  melodie tradizionali e meravigliosi arpeggi classici.   </p><p>
Visita <a href="http://www.calderonimartiniresort.it" target="_blank">calderonimartiniresort.it</a> o <a href="http://www.mauroliuzzi.it" target="_blank">mauroliuzzi.it</a>

</p>

<br>

<a href="tel:+390803140036" class="button"><i class="fa fa-phone" aria-hidden="true"></i> Chiamaci </a>
<a href="mailto:info@calderonimartini.it" class="button"><i class="fa fa-envelope" aria-hidden="true"></i> Scrivici</a>
<a href="https://goo.gl/AHDsda" class="button"><i class="fa fa-map-marker" aria-hidden="true"></i> Raggiungi la sala </a>


<br>
<p style="text-align:  center; font-style: italic; font-weight: normal; font-size: 8px; ">
<img src="<?php echo ROOT.$cp_admin.'fl_set/lay/wethinkgreen.jpg'; ?>" alt="" style="max-width: 150px;" /><br>
<strong>La nostra impresa è dotata di un sistema di gestione dei dati che ci permette di risparmiare la stampa di alcuni documenti cartacei.</strong></p>


<p style="text-align:  center; font-style: italic; font-weight: normal; font-size: 8px; ">MURGIATURISMO s.r.l - Sede Legale: Vico Brescia, 15 – 70024 Gravina in P.(Ba) <br> Sede Op.: C.da Parisi s.n. – 70022 Altamura (Ba)
<br>Sala Ricevimenti: tel. 080 314 00 36/080 20 91 113 <br>www.calderonimartiniresort.it  info@calderonimartiniresort.it  P.Iva 05882170722</p>

</div>
</div>

</body>
</html>