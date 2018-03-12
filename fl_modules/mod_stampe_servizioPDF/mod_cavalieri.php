<?php

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
$evento_id = check($_GET['evento_id']); 



if(!isset($_GET['backtop'])) {

include("../../fl_inc/headers.php");

?>
<h1>Seleziona margini di stampa</h1>

<div  style="width: auto; text-align: center; margin-top: 50px; background: white; padding: 20px;">
<form action="" method="get">

<table style="margin: 0 auto;">
<tr><td></td> <td><input type="number" value="5" name="backtop" style="width: 50px;" ></td> <td></td></tr>
<tr><td style="vertical-align: middle;"><input type="number" value="5" name="backleft" style="width: 50px;"></td>s <td style="height: 200px;">
<img style="max-height: 200px; width: auto;" src="../../fl_set/lay/documento.jpg"></td> <td style="vertical-align: middle;">
<input type="number" value="5" name="backright" style="width: 50px;"></td></tr>
<tr><td></td> <td><input type="number" value="5" name="backbottom" style="width: 50px;"></td> <td></td></tr>
</table>
<input type="radio" id="formatoP" name="formato" value="P" checked="checked"><label for="formatoP">Portrait</label>

<input type="radio" id="formatoL" name="formato" value="L"><label for="formatoL">Landscape</label>

<input type="hidden" name="evento_id" value="<?php echo $evento_id; ?>" name="margin-top" >

<select name="font">
<option value="freesans">freesans</option>
<option value="freemono">freemono</option>
<option value="freeserifbi">freeserifbi</option>
<option value="dejavusans">dejavusans</option>
<option value="dejavuserif">dejavuserif</option>
<option value="dejavuserifcondensed">dejavuserifcondensed</option>
<option value="dejavuserif" checked="checked">dejavuserif</option>
<option value="Courier">Courier </option>
<option value="calibri">calibri </option>

</select>


<input type="submit" value="Crea Cavalieri" class="button" >

</form>
</div>

<?php
mysql_close(CONNECT);
exit;
} 


$evento = GRD($tabella,$evento_id);
$filename = 'Cavalieri-evento-'.$evento_id.'.pdf';
$backtop = (isset($_GET['backtop'])) ? check($_GET['backtop']) : 5;
$backright = (isset($_GET['backright'])) ? check($_GET['backright']) : 5;
$backbottom = (isset($_GET['backbottom'])) ? check($_GET['backbottom']) : 5;
$backleft = (isset($_GET['backleft'])) ? check($_GET['backleft']) : 5;
$font = (isset($_GET['font'])) ? check($_GET['font']) : 'freemonob';


require_once('../../fl_set/librerie/html2pdf/html2pdf.class.php');


// get the HTML
ob_start(); ?>


<style type="text/css">
body { 

	border: none;
	background: #FFFFFF;
	padding: 4mm;
	color: #7A7A7A;
	font-size: 14px;
	text-align: center;
	font-family: "Times New Roman", Times, Baskerville, Georgia, serif;
	line-height: 160%;
}
.elaborathio { color: #7A7A7A;  }
h2 { font-size: 14px; margin-bottom: 5px; }
h1 { font-size: 18px; }
.preventivo {}
.preventivo label { display:  inline-block; width:  30%; margin: 2px 0 ; }
.privacytxt { font-size: 7pt; text-align: center; }




.deco-title, .small_paragraf h1, .small_paragraf h2, .small_paragraf h3, .small_paragraf h4 { 
  font-size: 9pt;
 }
.small_paragraf {   font-size: 8pt; }

.dati, .dati2 {
	border-spacing: 2px;
	border-collapse: separate;
	caption-side: top;
	width: 100%;
	min-width: 600px;
	max-width: 100%;
	height: auto;
	margin: 20px auto 20px auto;
	padding: 0px;
	vertical-align: middle;
	border: none;
	background: white;
}
.dati a {
	color: #6190D5;
}
.hometab {
	border-spacing: 2px;
	border-collapse: separate;
	caption-side: top;
	width: 45%;
	float: left;
	margin: 10px;
	height: auto;
	margin: 20px auto 20px 30px;
	padding: 0px;
	vertical-align: middle;
	font-size: 12px;
	border: none;
}
.hometab h2 {
	color: rgba(65,65,65,1);
}
.hometab td {
	vertical-align: middle;
	margin: 0px;
	padding: 1px;
	border: 1px solid #F4F4F4;
}
.hometab .bgcolor {
	background: #F2F2F2;
	padding: 0px 4px;
}
.dati th {
	text-align: left;
	height: 25px;
	vertical-align: middle;
	color: rgba(65,65,65,1);
	padding: 8px 4px;
	margin: 0px;
	font-weight: normal;
}
.dati td {
	text-align: left;
	vertical-align: middle;
	margin: 0px;
	padding: 8px 4px;
	min-height: 20px;
	max-width: 400px;
	overflow: hidden;
	text-overflow: ellipsis;
}
.dati td input {
	width: 100%;
}
.dati tr {
	line-height: auto;
}
.dati tr:nth-child(even), .dati .desc {
	background: #F4F4F4;
}
.dati2 .alternate td {
	background: #F8F8F8;
}
.dati tr:hover, .dati2 tr:hover, .dati2 .alternate:hover {
	background: #E6E6E6;
	color: #424447;
}
.dati2 td {
	vertical-align: middle;
	margin: 0px;
	padding: 4px 2px 4px 4px;
	border: none;
	color: rgba(65,65,65,1);
	border-bottom: 1px solid #ececec;
}

</style>




<?php
	

	
	if(isset($_GET['ordine'])) { if(!is_numeric($_GET['ordine'])){ exit; } else { $ordine = $ordine_mod[$_GET['ordine']]; }}
	
						
	$query = "SELECT persone.*, tavolo.*, tavolo.id AS tavoloID FROM `fl_tavoli_commensali` AS persone LEFT JOIN fl_tavoli AS tavolo ON persone.tavolo_id = tavolo.id WHERE tavolo.`evento_id` = '$evento_id' ORDER BY tavolo.`id` ASC;";
	
	$risultato = mysql_query($query, CONNECT);



?>



<?php 
	
	
	if(mysql_affected_rows() == 0) { echo "<page><h1>Nessun ospite inserito</h1></page>";		}
	$prevName = 'START';
	$tr = 0;
 	$formato = (isset($_GET['formato']) && check($_GET['formato']) == 'L') ? 'L' : 'P';
	while ($riga = mysql_fetch_array($risultato)) 
	{					
			
			
			$nome_tavolo = urldecode(strtoupper($riga['nome_tavolo']))." ".$riga['numero_tavolo_utente']."<br>".$riga['nome_tavolo_utente'];
			$id_tavolo = $riga['tavoloID'];

       		if($prevName != $id_tavolo) {
       			if($prevName != 'START') { echo "</page>"; }
       			$prevName = $id_tavolo;
       			echo '<page format="A6"  orientation="P"  style="font: arial;" backtop="'.$backtop.'mm"  backbottom="'.$backbottom.'mm" backleft="'.$backleft.'mm" backright="'.$backright.'mm">
       			 '.$layout_start.'';
       			 if(defined('LOGO_CAVALIERI')) { echo '<h3 style="text-align: center;"><img src="'.LOGO_CAVALIERI.'" alt="" style="width: 200px;" /></h3>'; } 
     			 echo '<h3 style="text-align: center; font-size: 14px;">'.$nome_tavolo.'</h3>
				 ';
			}

			echo "<div style=\"text-align: center; font-weight: normal; margin-top: 10px;\"><strong>".urldecode($riga['cognome'])." ".urldecode($riga['nome'])."</strong></div>";
	


	}
	if(mysql_affected_rows() > 0) echo "</page>"; 
	

?>



<?php 


    $content = ob_get_clean();
  	mysql_close(CONNECT);

    try
    {
        $html2pdf = new HTML2PDF('L', 'A5', 'it', true, 'UTF-8', array(0, 0, 0, 0));
        $html2pdf->pdf->SetDisplayMode('real');
        $html2pdf->setDefaultFont($font);
        $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
	    $html2pdf->Output($filename);

		//header('Location: ./scarica.php?show&file='.$folder.$filename);

	
	
    }
    catch(HTML2PDF_exception $e) {
         echo "Problema nella creazione del documento".$e;
    }
	

	exit;

?>
