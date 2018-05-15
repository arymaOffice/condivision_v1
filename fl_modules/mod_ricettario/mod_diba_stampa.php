<?php 

require_once('../../fl_core/autentication.php');
require_once('fl_settings.php');
unset($text_editor);
$record_id = check($_GET['record_id']);
$ricettaInfo = GRD('fl_ricettario',$record_id);



$tipo_prezzo = (isset($_GET['calcola'])) ? check($_GET['calcola']) : 'ultimo_prezzo';
if($ricettaInfo['tipo_ricetta'] == 0)
$categoria_msg = ($ricettaInfo['categoria_ricetta'] > 1) ? "".$categoria_ricetta[$ricettaInfo['categoria_ricetta']]."" : '';


$nochat;
$tab_id = 120;

$filename = "RICETTA-".$record_id.".pdf";

require_once('../../fl_set/librerie/html2pdf/html2pdf.class.php');


ob_start(); 

 



 ?>

<style type="text/css">
body { 

  border: none;
  background: #FFFFFF;
  padding: 4mm;
  color: black; 
  font-size: 14px;
  text-align: center;
  font-family: sans-serif;
  line-height: 160%;
}
.elaborathio { color: black;  }
h3 { font-size: 12px; margin: 2px 0; }
h2 { font-size: 16px; margin: 2px 0; }
h1 { font-size: 18px; text-align: left; text-transform: uppercase; }

p { font-size: 12px; color: #7A7A7A; margin: 5px 0;  }
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
  width: 770px;
  height: auto;
  margin: 10px 0;
  padding: 0px;
  vertical-align: middle;

}
.dati td { text-align: left; vertical-align: middle;  border-top: none; border-left: none; border-bottom: 1px solid #e3e0e0; padding: 2px; }


</style>

 <page backtop="18mm" backbottom="6mm" backleft="15mm" backright="4mm">

<page_footer>

<div style=" font-style:  serif; font-size: 9px; color: #666; margin: 0 20px;">   

<p style="margin: 0px; padding: 0;><?php echo $ricettaInfo['copyright']; ?><br>
Stampato con Condivision in data: <strong><?php echo date('d/m/Y'); ?></strong> alle ore <strong><?php echo date('H:i'); ?></strong>.</p>
  <br>   <br>    <br><br>   
</div>

</page_footer>



<page_header>
<div class="elaborathio">
 <table style="width: 100%;">
    <tr>
      <td style="text-align: left; vertical-align: top; width: 65%;">
      <?php if(defined('LOGO_FATTURA')) { ?><img src="<?php echo LOGO_FATTURA; ?>" alt="" style="width: 200px; " /><?php } ?>
      </td>

      <td style="text-align: right; vertical-align: middle;  width: 25%;"><strong>SCHEDA RICETTA</strong></td>
    </tr>
  </table>
</div>
</page_header>


<?php

 echo (!file_exists($folder.$ricettaInfo['id'].'.jpg')) ? '' : '<img src="'.$folder.$ricettaInfo['id'].'.jpg" class="" style="float: right; width: auto; height: 150px;" /> ';
  
?>

<h1><?php echo $ricettaInfo['id'].' '.converti_txt($ricettaInfo['nome_tecnico']); ?></h1>
<?php echo strtoupper($portata[$ricettaInfo['portata']]." $categoria_msg")." | CODICE: ".$ricettaInfo['codice_portata']." | Porzioni: ".$ricettaInfo['porzioni']; ?>
<p>Nome Gastronomico: <?php echo strip_tags(converti_txt($ricettaInfo['nome'])); ?></p>
<br><br><br>
<h2 style="clear: both;">Elenco Ingredienti</h2>

<?php 
	
	$foodCost = 0;
  
	$query = "SELECT * FROM fl_ricettario_diba WHERE `ricetta_id` = $record_id ORDER BY id ASC";
	$risultato = mysql_query($query, CONNECT);
	if(mysql_affected_rows() == 0){ echo "<p>Nessun elemento</p>"; } else {
	?>
    
   <table class="dati">
   <tr>
   <th>Codice</th>
   <th>Descrizione</th>
   <th>UM</th>
   <th>Lordo</th>
   <?php if(!isset($_GET['prezzi'])) { ?><th>Netto</th><?php } ?>
   <?php if(isset($_GET['prezzi'])) { ?><th>Costo &euro;</th><?php } ?>
   </tr>
          
 <?php
	

	while ($riga = mysql_fetch_assoc($risultato)) 
	{ 

  $materiaprima = ($riga['materiaprima_id'] > 0) ? GRD('fl_materieprime',$riga['materiaprima_id']) : GRD('fl_ricettario',$riga['semilavorato_id']);
  $descrizione = ($riga['materiaprima_id'] > 0) ? $materiaprima['descrizione'] : converti_txt($materiaprima['nome']);
  $codice_articolo = ($riga['materiaprima_id'] > 0) ? $materiaprima['codice_articolo'].'' : $materiaprima['id'];
    
  if($riga['materiaprima_id'] > 0) {
  //$quotazione = GQD('fl_listino_acquisto','valuta, prezzo_unitario, data_validita',' id_materia = '.$materiaprima['id'].' ORDER BY data_validita DESC,data_creazione DESC LIMIT 1');
  $costo =  ($materiaprima[$tipo_prezzo]*$riga['quantita'])/$materiaprima['valore_di_conversione'];
  $foodCost += $costo;

	}
  
  $prezzo_grammo = round(($materiaprima[$tipo_prezzo]/$materiaprima['valore_di_conversione'])/1000,4); 
  ?> 
 
  <tr>
  <td style="width: 120px; font-size: 9px;"><span style="font-size: 10px;"><?php echo $codice_articolo; ?></span></td>
  <td style="width: 380px;"><?php echo $descrizione; ?></td>
      <td style="width: 50px;" ><?php  echo $materiaprima['unita_di_misura'];  ?> </td>
      <td style="width: 60px;"><?php echo $riga['quantita']; ?></td>
      <?php if(!isset($_GET['prezzi'])) { ?><td style="width: 60px;"><?php echo $riga['netto']; ?></td> <?php } ?>
      <?php if(isset($_GET['prezzi'])) { ?><td style="width: 60px;"><strong title="Prezzo al grammo <?php echo $prezzo_grammo; ?>"><?php echo @$quotazione['valuta'].' '.@numdec( $costo ,4); ?></strong></td> <?php } ?>
      </tr>

    <?php }  

    if($ricettaInfo['porzioni'] < 0.1) die("Per favore specifica una quantità di porzioni di almeno 1 unità nella scheda Ricetta");


} ?>
</table>

<p>Rev. <strong><?php echo $ricettaInfo['revisione']; ?></strong> | Aggiornamento ricetta: <strong><?php echo mydatetime($ricettaInfo['data_aggiornamento']); ?></strong> | Operatore: <strong><?php echo $proprietario[$ricettaInfo['operatore']]; ?></strong></p>

<?php if(isset($_GET['prezzi'])) { ?><h2  class="no-print">Costo della ricetta: &euro; <?php echo  numdec($foodCost,3); ?> <?php echo ($ricettaInfo['porzioni'] == 1) ? 'a porzione' : ' per '.$ricettaInfo['porzioni'].' porzioni'; ?> </h2> <?php } ?>

<?php if($ricettaInfo['note'] != '') echo '<h3>Note</h3>'.converti_txt($ricettaInfo['note']); ?>

<?php if(!isset($_GET['prezzi'])) { ?>
<?php if($ricettaInfo['preparazione'] != '') echo '<h3>Preparazione</h3>'.converti_txt($ricettaInfo['preparazione']); ?>
<?php if($ricettaInfo['cottura'] != '') echo '<h3>Cottura</h3>'.converti_txt($ricettaInfo['cottura']); ?>
<?php if($ricettaInfo['presentazione'] != '') echo '<h3>Presentazione</h3>'.converti_txt($ricettaInfo['presentazione']); ?>
<?php if($ricettaInfo['servizio'] != '') echo '<h3>Servizio</h3>'.converti_txt($ricettaInfo['servizio']); ?>
<?php } ?>


</page>



<?php


   $content = ob_get_clean();
    mysql_close(CONNECT);

    try
    {
        $html2pdf = new HTML2PDF('P', 'A4', 'it', true, 'UTF-8', array(0, 0, 0, 0));
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->setDefaultFont('freesans');
        $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
      $html2pdf->Output($filename);

    //header('Location: ./scarica.php?show&file='.$folder.$filename);

  
  
    }
    catch(HTML2PDF_exception $e) {
         echo "Problema nella creazione del documento".$e;
    }
  

  exit;

  ?>