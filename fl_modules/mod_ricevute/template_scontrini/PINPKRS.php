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
<page format="58x130" orientation="P" backcolor="#FFFFFF" style="font: arial;">

<div id="container">
  
  <h2>GOSERVIZI</h2>
  <span class="small">
  <p>XP Trading S.R.L<br>
    Via Cairoli, 33 Tricase 73039<br>
    P.iva 04230180756<br>
    <?php echo $DATA; ?><br>
    <?php echo $merchant."-".$termID; ?></p>
  </span> 
    
  <p><strong>POKER STAR</strong><br>PIN CODE<br>
  <strong><?php echo $PINCODE; ?></strong><br>
	Importo: <?php echo $AMOUNT; ?> Euro</p>
<span class="small">  
<p>Ricarica Cash<br>
PokerStars.it<br>
NON RIMBORSABILE</p>
  <p>IVA esente ex art. 10
    c.1 nn. 6,7 DPR 366/72<br>
 >Accedi alla sezione
    'Cassa' su
    PokerStars.it,
    seleziona l'importo,
    inserisci il codice
    della ricarica, e
    clicca su 'Invia'.<br>PokerStars applica una
    commissione di 2 euro
    su ciascuna Ricarica
    Cash ad eccezione della
    prima.
    Ad esempio, con una
    Ricarica di 50 euro
    sono accreditati sul
    tuo conto gioco
    48 euro.<br><br>Rif. Operazione: <?php echo $origID; ?><br>ARRIVEDERCI E GRAZIE
  </p>
</span> </div>
</page>
