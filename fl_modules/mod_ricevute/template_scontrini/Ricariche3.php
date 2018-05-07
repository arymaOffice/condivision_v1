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
<p>
    <strong>TRE RICARICHE</strong><br>
    RICEVUTA DI  PAGAMENTO<br>
    Conservare fino a 
    ricarica avvenuta<br>
   Scontrino non  fiscale</p>
  </span>.
  <p align="center"><strong>NUMERO DI  TELEFONO</strong><br><strong><?php echo $USERCODE; ?></strong></p>
  <p align="center">    Importo:<?php echo $AMOUNT; ?> euro* <br>
Di cui: <?php echo $AMOUNT; ?> traffico<br>
0,00 costi<br>
<strong>TID: <?php echo $RechargeID; ?></strong><br>
  <?php echo $AUTH; ?></p>
   <span class="small">
   <p>IVA ASSOLTA AI  SENSI DELL&rsquo;ART 74 DEL DPR 633/72 DA Wind Tre S.p.A. P.I. 13378520152<br>
  3 Le invierà un  messaggio di avvenuta ricarica<br>
  Per informazioni chiamare il 139
 <br>Rif. Operazione: <?php echo $origID; ?><br>ARRIVEDERCI E GRAZIE</p>
  </span>
  </div>
</page>
