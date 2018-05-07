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
<page format="58x200" orientation="P" backcolor="#FFFFFF" style="font: arial;">

<div id="container">
  
  <h2>GOSERVIZI</h2>
  <span class="small">
  <p>XP Trading S.R.L<br>
    Via Cairoli, 33 Tricase 73039<br>
    P.iva 04230180756<br>
    <?php echo $DATA; ?><br>
    <?php echo $merchant."-".$termID; ?></p>
  </span> 
<p><strong>PAYSAFE</strong><br>
  PIN CODE<br>
    <strong><?php echo $PINCODE; ?></strong><br>
    Importo: <?php echo $AMOUNT; ?> Euro<br>
    Commisione: 0 Euro<br>
  Totale: <?php echo $AMOUNT; ?> Euro</p>
  <p class="small"><a href="http://www.paysafecard.com">www.paysafecard.com</a><br>
- Selezionare shop e prodotto<br>
- Cliccare su pagamento con  paysafecard<br>
- Immettere il codice PIN - e'  tutto!</p>
  <p class="small">Hotline: +39 800 789 967<br>
    E-Mail:&nbsp; <a href="mailto:info@paysafecard.com">info@paysafecard.com</a><br>
    Consultazione saldo: <a href="http://www.paysafecard.com">www.paysafecard.com</a></p>
  <p class="small">La rivendita professionale di  paysafecard e' proibita.<br>
    La presente carta prepagata e'  trasferibile.<br>
    Valgono le CGA per gli  utilizzatori delle carte,<br>
    consultabili al sito <a href="http://www.paysafecard.com/it">www.paysafecard.com/it</a>.<br>
    paysafecard viene emessa e  gestita come mezzo di pagamento da Prepaid Services Company Ltd.</p>
  <p class="small">ATTENZIONE! Se vi viene chiesto  di pagare con paysafecard per sbloccare il vostro computer, chiamate il numero  800 789967 - Si tratta di un VIRUS sul vostro computer!<br>
Per ulteriori informazioni consultate <a href="http://www.paysafecard.com/it/sicurezza/">www.paysafecard.com/it/sicurezza/</a>
 </p>
 <span class="small"><p>Rif. Operazione: <?php echo $origID; ?><br>ARRIVEDERCI E GRAZIE
  </p></span> </div>
</page>
