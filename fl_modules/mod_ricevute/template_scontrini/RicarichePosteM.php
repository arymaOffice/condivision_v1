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
<?php echo $merchant."-".$termID; ?><br>
<br>
<strong>RICARICA PosteMobile</strong><br>
  
   NUMERO DI TELEFONO <br>
   <strong><?php echo $USERCODE; ?></strong>
  <br>
  Valore: (euro) <?php echo $AMOUNT; ?></p>
  </span>
     <p align="center"><strong>TID: <?php echo $RechargeID; ?></strong><br>
    <?php echo $AUTH; ?></p>
    <span class="small">
   <p> NON RIMBORSABILE<br>
    IVA inclusa, assolta<br>
    ai sensi dell’art.<br>
    74 DPR 633/72 comma 1,<br>
    lettera d, 
    da PosteMobile S.p.A.<br>
    P. IVA 06874351007  <br>
    Per ricaricare chiama il 
  numero 40.12.12 <br>
    Info: www.postemobile.it 
    oppure chiama il 160<br>
    Rif. Operazione: <?php echo $origID; ?><br> 
    ARRIVEDERCI E GRAZIE</p></span></div>
</page>
