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
<page format="58x115" orientation="P" backcolor="#FFFFFF" style="font: arial;">

<div id="container">
  
  <h2>GOSERVIZI</h2>
  <span class="small">
  <p>XP Trading S.R.L<br>
    Via Cairoli, 33 Tricase 73039<br>
    P.iva 04230180756<br>
    <?php echo $DATA; ?><br>
    <?php echo $merchant."-".$termID; ?></p>
  </span> 
  <strong>MEDIASET PREMIUM</strong><br />

   Seriale: <?php echo $Serial; ?>

 
  <p>PIN CODE<br>
  <strong><?php echo $PINCODE; ?></strong><br>
	Valore Credito: <?php echo $AMOUNT; ?> Euro</p>
    
 <span class="small">
 
  <p>Per attivare la ricarica invia  un SMS al 3404336363<br>
con scritto &quot;ric.&quot; seguito dal numero di  tessera, <br>
da un punto e dal codice segreto,<br>
oppure chiama l'800303404 da fisso,  <br>
lo 0237045045 da mobile<br>
o visita il sito <a href="http://www.mediasetpremium.it">www.mediasetpremium.it</a><br>
Per ricevere la ricarica tieni la tessera nel box sintonizzato su  Mediaset Premium</p>

<p>Rif. Operazione: <?php echo $origID; ?><br />
ARRIVEDERCI E GRAZIE</p>

</span> 

</div>
</page>
