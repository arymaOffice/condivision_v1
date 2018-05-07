<style type="text/css">
<!--
div#container {
	border: none;
	background: #FFFFFF;
	padding: 3mm;
	font-size: 3.7mm;
	text-align: center;
}
h1 {
	padding: 0;
	margin: 0;
	color: #2E89D0;
	font-size: 7mm;
}
h2 {
	padding: 0;
	margin: 0;
	color: #222222;
	font-size: 5mm;
	position: relative;
}
.small {
	font-size: 3.0mm;
	text-align: center;
	width: 100%;
}
-->
</style>
<page format="58x140" orientation="P" backcolor="#FFFFFF" style="font: arial;">

<div id="container">
  <h2>GOSERVIZI</h2>
  <span class="small">
  <p>XP Trading S.R.L<br>
    Via Cairoli, 33 Tricase 73039<br>
    P.iva 04230180756<br>
    <?php echo $DATA; ?><br>
    <?php echo $merchant."-".$termID; ?></p>
  </span> <strong>LYCA MOBILE</strong>
  <p class="time"><?php echo $DATA; ?><br>
    Seriale: <?php echo $Serial; ?></p>
  <p>PIN CODE<br>
    <strong><?php echo $PINCODE; ?></strong><br>
    Importo: <?php echo $AMOUNT; ?> Euro</p>
   <span class="small">
  <p><strong>Ricarica il tuo credito</strong><br />
  1. componi *192* PIN, seguito  dal tasto #, infine premi Invio Oppure
    2.<br>
    Chiama il numero gratuito 4192  (oppure 92#) dal tuo cellulare LYCAMOBILE <br>
    e segui le istruzioni&nbsp; Servizio  Clienti: 93# / +390681100107 <br>
    (Chiamata gratuita dal tuo cellulare  LYCAMOBILE)<br>IVA ASSOLTA AI SENSI DELL'ART.74 DPR&nbsp; 633/72 DA  LYCAMOBILE S.r.l. - P.IVA 0986061005&nbsp; 
<br>Per Termini e Condizioni vedi: <a href="http://www.lycamobile.it">www.lycamobile.it</a>&nbsp; Non Rimborsabile  Non Sostituibile <br>
  Rif. Operazione: <?php echo $origID; ?><br>
    ARRIVEDERCI E GRAZIE </p>
  </span> </div>
</page>
