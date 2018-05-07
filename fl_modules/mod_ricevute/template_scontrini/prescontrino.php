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
<page format="58x180" orientation="P" backcolor="#FFFFFF" style="font: arial;">

<div id="container">
  
  <h2>GOSERVIZI</h2>
  
  <span class="small">
  <p>XP Trading S.R.L<br>
    Via Cairoli, 33 Tricase 73039<br>
    P.iva 04230180756</p>
  <p class="time"><?php echo $DATA; ?><br>
   <?php echo $merchant."-".$termID; ?></p>
  <p><strong> RICARICA </strong></p>
</span>
  <p>VERIFICARE SE I DATI&nbsp; SONO  CORRETTI</p>
<p align="center"><strong>NUMERO DI  TELEFONO</strong></p>
   <h2 align="center"><strong><?php echo $USERCODE; ?></strong> </h2><br>
  <h2 align="center">Importo:<?php echo $AMOUNT; ?> Euro  </h2>
  <p>  <br>
  <strong>Il presente non e'&nbsp; una  ricevuta di ricarica&nbsp;&nbsp; </strong></p>
  <p align="center">&nbsp;</p>
<p>Firma cliente</p>
  <p>&nbsp;</p>
  <p style="text-align: center;">------------------------------------------------------------</p>
  <span class="small">
</span> </div>
</page>
