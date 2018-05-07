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
<page format="58x110" orientation="P" backcolor="#FFFFFF" style="font: arial;">

<div id="container">
  
  <h2>GOSERVIZI</h2>
  <span class="small">
  <p>XP Trading S.R.L<br>
    Via Cairoli, 33 Tricase 73039<br>
    P.iva 04230180756<br>
    <?php echo $DATA; ?><br>
    <?php echo $merchant."-".$termID; ?></p>
  </span> 
    
  <p><strong>UKASH</strong><br>
  PIN CODE<br>
    <strong><?php echo $PINCODE; ?></strong><br>
    Importo: <?php echo $AMOUNT; ?> Euro</p>
 <span class="small">
    <p>I Voucher Ukash potranno essere utilizzati fino al 31.10.2015
    Dal 1° Luglio Ukash diventa Paysafecard.
Questo PIN Ukash è utilizzabile fino al 31 Ottobre 2015
<br>Emesso secondo i termini e le condizioni d'uso  notificate. <br>Vedi <a href="http://www.ukash.com">www.ukash.com</a> o chiama il  servizio clienti al numero 800 000 85 274</p>
 
 <p>Rif. Operazione: <?php echo $origID; ?><br>ARRIVEDERCI E GRAZIE
  </p>
</span> </div>
</page>
