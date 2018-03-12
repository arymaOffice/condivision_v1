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
<style>

body { 
font-family: 'Roboto', sans-serif;
background: #EDEDED;
}

#container { 
	max-width: 680px;
	margin: 0 auto;
	position: relative;
	font-size: 13px !important;
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
</style>
</head>


<body style="background-color:light-grey">

<div class="logo">
<img src="<?php echo LOGO; ?>" alt="<?php echo client; ?>" style="max-height: 70px; max-width: 200px; width: 100% "/> </div>  
<div id="container" style="display: block;">    

<img src="header.jpg"  style="width:100%"/>

<div class="content">

		<h2 style="color: #686bc1;">Desideriamo conoscere il Suo parere</h2>
				<p>Il questionario proposto è privato e rapido da compilare.</p>
					<p>Il Suo giudizio è fondamentale, la invitiamo a partecipare ad un sondaggio sulla sua recente visita presso la nostra struttura al fine di migliorare la qualità dei nostri servizi,La ringraziamo in anticipo per la sua collaborazione.</p>
					<p><strong>* Campo obbligatorio</strong></p>
					<br>


 <?php 

if(isset($_GET["esito"])) { 

$colore = (isset($_GET['error_code'])) ? 'tab_red' :  'tab_green';

echo "<h2 class=\"$colore\" style=\"color: white;\">".check(@$_GET['esito'])."</h2><br>"; 

?>
<a class="button" href="javascript:history.back();">OK</a>
<br><br><br><br>

<?php } else { ?>

<div id="results"></div>

<form class="ajaxForm"  method="post" action="invia.php" enctype="multipart/form-data">


						<p><strong>Come giudica l'accoglienza ricevuta *</strong></p>
						
						<?php 
						foreach($domanda1_risposte as $chiave => $valore){
						echo '<input type="radio" name="domanda1" id="domanda1'.$chiave.'" value="'.$chiave.'" >
							<label for="domanda1'.$chiave.'">'.$valore.'</label>';
						} ?>
						


				<p><strong>Come ritiene le informazioni ricevute *</strong></p>
					<?php 
								foreach($domanda2_risposte as $chiave => $valore){
						echo '<input type="radio" name="domanda2" id="domanda2'.$chiave.'" value="'.$chiave.'">
							<label for="domanda2'.$chiave.'">'.$valore.'</label>';
						} ?>


<br><br>
<p>Pensa di prenotare presso la struttura? *</p>
<?php 

						foreach($domanda3_risposte as $chiave => $valore){
						$display_motivazioni = ($chiave == 0) ? 'onclick="$(\'#motivazioni\').hide();"' :  'onclick="$(\'#motivazioni\').show();"';
						echo '<input type="radio" name="domanda3" id="domanda3'.$chiave.'" value="'.$chiave.'" '.$display_motivazioni .'>
							<label for="domanda3'.$chiave.'">'.$valore.'</label>';
						} ?>
<br>
<br>


<div id="motivazioni" style="display: none;">
<p>Indicare i motivi della sua scelta *</p>
<?php 
												foreach($domanda4 as $chiave => $valore){
						$display_Altro = ($valore != 'Altro') ? '' :  'onclick="$(\'#altro\').show();"';
						$altro = ($valore != 'Altro') ? '' : '<input id="altro" style="display: none;" type="text" name="domanda4[]" value="" placeholder="Indicare motivazione"  />';
						
						echo '<p><label for="domanda4'.$chiave.'" style="display: block;" '.$display_Altro.'><input type="checkbox" name="domanda4[]" id="domanda4'.$chiave.'" value="'.$valore.'" style="display: inline-block; float: left; clear: both;">
							<span style="margin-top: 11px; display: block; float: left;">'.$valore.' '.$altro.'</span></label></p>';
						} ?>

<br class="clear">
<br>
</div>

<p><strong>Come ci ha conosciuto?  *</strong></p>

<select name="domanda5" required>
<option value="-1" selected="selected" disabled>Seleziona...</option>
<?php 
											foreach($domanda5_risposte as $chiave => $valore){
						echo '<option value="'.$chiave.'">'.$valore.'</option>';
						} ?>
</select>
<br>
<br>

<p><strong>Eventuali suggerimenti per migliorare i nostri servizi</strong></p>  
<textarea name="commento" style="width:70%;height:40% border: none;
border-bottom: 1px solid;" placeholder="Scrivi qui eventuali suggerimenti..."></textarea>
<br>
<br>
<input type="hidden" name="lead_rel" value="<?php echo (isset($_GET['p'])) ? check($_GET['p']) : 0; ?>" >
<input type="hidden" value="Rilascio Feedback" name="oggetto"  />
<input type="submit" value="Invia" class="button">
</form>

<?php }  ?>
<br clear="all" style="clear: both;" /><br clear="all" style="clear: both;" />

<small><p>Inviando il presente form conferma di rilasciare il suo feedback/suggerimento per il solo uso statistico<br> interno. Nessun dato verrà divulgato in nessun caso.</p></small>
<small>I dati personali saranno trattati ai sensi del D. Lgs 196/03 nel rispetto delle norme vigenti ilcodice della privacy. Pertanto, ne potrà in ogni momento essere richiesta la cancellazione concomunicazione da inviarsi all'indirizzo: privacy@test.it</small>

<img src="<?php echo ROOT.$cp_admin.'fl_set/lay/wethinkgreen.jpg'; ?>" alt="" style="max-width: 150px;" /><br>
<strong>La nostra impresa è dotata di un sistema di gestione dei dati che ci permette di risparmiare la stampa di alcuni documenti cartacei.<br><br>
A tutti i nostri clienti offriamo la possibilità di compilare liste ospiti ed altre informazioni direttamente online o via mail abbattendo lo spreco di carta fino al 60%.
</strong><br>


<p style="text-align:  center; font-style: italic; font-weight: normal; ">2017 - Note legali - Privacy</p>

</div>
</div>

</body>
</html>