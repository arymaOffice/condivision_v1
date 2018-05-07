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
  </span> 
    
  
    
  <p><strong>Ricarica WilliamHill.it</strong><br>PIN CODE<br>
    <strong><?php echo $PINCODE; ?></strong><br>
    Importo: <?php echo $AMOUNT; ?> Euro<br>
  </p>
  <span class="small"> <p>NON RIMBORSABILE<br>
Validita': 3 Mesi<br>
dalla data di emissione.</p>
  <p>IVA esente ex art. 10 c.1 nn. 6<br>
    e 7 DPR 366/72</p>
  <p>Deposita su <a href="http://www.williamhill.it">www.williamhill.it</a>.
    Accedi alla Cassa, inserisci 
    l'importo ed il numero della
    ricarica, clicca su 'Deposita'.
    Il tuo conto gioco William Hill
    sara' accreditato immediatamente<br>
    N.B. Per utilizzare questa
    ricarica e' necessario che il
    titolare del conto di gioco
    abbia provveduto all'invio del
    documento d'identita' a 
    William Hill.<br>Per assistenza chiamaci
al: 800 977 631<br><br>Rif. Operazione: <?php echo $origID; ?><br> ARRIVEDERCI E GRAZIE
  </p>
</span> </div>
</page>
