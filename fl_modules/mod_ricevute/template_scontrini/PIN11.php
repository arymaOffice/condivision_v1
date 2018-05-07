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
    
    <strong>Ricarica OnNet</strong>
    <p class="time"><?php echo $DATA; ?><br>
  </p>

  <p>PIN CODE<br>
  <strong><?php echo $PINCODE; ?></strong><br />
 
	Importo: <?php echo $AMOUNT; ?> Euro</p>
 <span class="small"> Per i servizi OnNetda fisso e mobile:02.89.727 Per chiamate int. e naz. da fisso e mobile:
800.580.280 da mobile Wind:800.99.33.96 Info su www.on-net.biz
<br />Servizio Clienti199.30.20.20<br />
IVA inclusa, assolta da Intermatica S.p.A.P. IVA 05389281006Ex art 74 DPR 633/72
<br />NON RIMBORSABILE<br />
Scadenza: 90 gg dalla prima chiamata<br />
Prima del numero digita prefisso internazionale Es. Italia 0039
<br />Numero Seriale <?php echo $Serial; ?> 
<br /> op.:  <?php echo $origID; ?>

<br /> ARRIVEDERCI E GRAZIE

</span> 
</div>
</page>
