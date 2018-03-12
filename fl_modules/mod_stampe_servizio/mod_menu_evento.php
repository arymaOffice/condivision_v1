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
<tr><td style="vertical-align: middle;"><input type="number" value="5" name="backleft" style="width: 50px;"></td> <td style="height: 200px;"><img style="max-height: 200px; width: auto;" src="../../fl_set/lay/documento.jpg"></td> <td style="vertical-align: middle;"><input type="number" value="5" name="backright" style="width: 50px;"></td></tr>
<tr><td></td> <td><input type="number" value="5" name="backbottom" style="width: 50px;"></td> <td></td></tr>
</table>
<select name="font">
<option value="freesans">freesans</option>
<option value="freemono">freemono</option>

<option value="freeserifbi">freeserifbi</option>

<option value="dejavusans">dejavusans</option>

<option value="dejavuserif">dejavuserif</option>


<option value="dejavuserifcondensed">dejavuserifcondensed</option>
<option value="dejavuserif">dejavuserif</option>
<option value="Courier">Courier </option>
<option value="calibri">calibri </option>

</select>
<br>
<label><input type="checkbox" name="html" value="1" style="display: inline;"  > Crea solo testi</label>

<br>
<input type="hidden" name="evento_id" value="<?php echo $evento_id; ?>">
<input type="submit" value="Crea Menù" class="button" >

</form>
</div>

<?php

mysql_close(CONNECT);
exit;
} 


$evento = GRD($tabella,$evento_id);
$menu = GQD('fl_menu_portate','id,descrizione_menu','evento_id = '.$evento_id);
$ricorrenza = GQD('fl_ricorrenze_matrimonio','*','evento_id = '.$evento_id);
$menuId = $menu['id'];
$font = (isset($_GET['font'])) ? check($_GET['font']) : 'freemonob';
$filename = 'Cavalieri-evento-'.$evento_id.'.pdf';
$backtop = (isset($_GET['backtop'])) ? check($_GET['backtop']) : 5;
$backright = (isset($_GET['backright'])) ? check($_GET['backright']) : 5;
$backbottom = (isset($_GET['backbottom'])) ? check($_GET['backbottom']) : 5;
$backleft = (isset($_GET['backleft'])) ? check($_GET['backleft']) : 5;



if(!isset($_GET['html']))  require_once('../../fl_set/librerie/html2pdf/html2pdf.class.php');



ob_start(); 
if(!isset($_GET['html'])) { include(menu_eventoPDF); }
if(isset($_GET['html'])) { include(menu_evento); }
$content = ob_get_clean();
if(isset($_GET['html'])) { 
echo $content; 
mysql_close(CONNECT);
exit;

}


   try
    {
        $html2pdf = new HTML2PDF('L', 'A4', 'it', true, 'UTF-8', array(0, 0, 0, 0));
        $html2pdf->setDefaultFont($font);
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($content);
	    $html2pdf->Output($filename);

	
    }
    catch(HTML2PDF_exception $e) {
         echo "Problema nella creazione del documento".$e;
    }
	

	exit;

?>
