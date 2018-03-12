<?php
require_once('../../fl_core/autentication.php');
require_once('fl_settings.php');


$evento_id = check($_GET['evento_id']);				  //recupero l'id dell'evento
$evento = GRD($tabella,$evento_id);					  //mi ritorna dati dell'evento	
$tipo_tavolo = GQS('fl_tavoli_tipo','*',' id > 1');	  //mi ritona tutti i tipi di tavolo
$tipo_commensale = GQS('fl_commensali_tipo','*','1'); //mi ritorna la tipologia di commensale
$totalizzatore = GQD('`fl_tavoli_commensali` AS persone LEFT JOIN fl_tavoli AS tavolo ON persone.tavolo_id = tavolo.id','SUM(IF(persone.tipo_commensale != 6 && persone.tipo_commensale != 5,adulti,0)) AS a, SUM(IF(persone.tipo_commensale != 6 && persone.tipo_commensale != 5,bambini,0)) AS b, SUM(IF(persone.tipo_commensale != 6 && persone.tipo_commensale != 5,sedie,0)) AS s,SUM(IF(persone.tipo_commensale != 6 && persone.tipo_commensale != 5,seggioloni,0)) AS h,sum(IF(persone.tipo_commensale = 5,adulti + bambini,0)) as seraTot,sum(IF(persone.tipo_commensale = 6,adulti + bambini,0)) as opTot',' tavolo.`evento_id` = '.$evento_id);
$img = DMS_PUBLIC.'tableau/'.$evento_id.'.png';


$filename = 'Schema-tavoli-'.$evento_id.'.pdf';

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
	font-family: sans-serif;
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


<page backtop="3mm" backbottom="0mm" backleft="2mm" backright="2mm" style="">

<div class="elaborathio" style="font-size: 12px; margin: 0; width: 760px; padding: 0; text-align: center;">
  		
  		<h1 style="margin: 2px;">SCHEMA TAVOLI SALA</h1>
		<h2 style="margin: 2px;"><?php echo $evento['titolo_ricorrenza']; ?>  data <?php echo mydate($evento['data_evento']); ?></h2>
		

		<img src="<?php echo $img; ?>" alt="Schema Tavoli" style="border: 1px solid #ccc; width: 800px; height: auto;" width="780" height="990">

		<h2 style="margin: 0px;">Totale Ospiti: 
		<span id="totale-a"><?php echo $totalizzatore['a']; ?> Adulti</span> 
		<span id="totale-b"><?php echo ($totalizzatore['b'] > 0) ? ' + '.$totalizzatore['b'].' Bambini' : ''; ?></span> 
		<span id="totale-s"><?php echo ($totalizzatore['s'] > 0) ? ' + '.$totalizzatore['s'].' Sedie' : ''; ?></span> 
		<span id="totale-h"><?php echo ($totalizzatore['h'] > 0) ? ' + '.$totalizzatore['h'].' Seggiolone' : ''; ?></span> 
		<span id="totale-h"><?php echo ($totalizzatore['seraTot'] > 0) ? ' + '.$totalizzatore['seraTot'].' Serali' : ''; ?></span>
		<span id="totale-h"><?php echo ($totalizzatore['opTot'] > 0) ? ' + '.$totalizzatore['opTot'].' Operatori' : ''; ?></span> 
		</h2>
		<p style="font-size: smaller; color: red;">* Nota intolleranze e allergie. Verifica sulla lista intolleranze i dettagli per questo tavolo.</p>



</div>

</page>
<?php 


    $content = ob_get_clean();
  	mysql_close(CONNECT);

    try
    {
        $html2pdf = new HTML2PDF('P', 'A4', 'it', true, 'UTF-8', array(0, 0, 0, 0));
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
	    $html2pdf->Output($filename);

		//header('Location: ./scarica.php?show&file='.$folder.$filename);

	
	
    }
    catch(HTML2PDF_exception $e) {
         echo "Problema nella creazione del documento".$e;
    }
	

	exit;

?>


