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
<page format="58x120" orientation="P" backcolor="#FFFFFF" style="font: arial;">

<div id="container">
  
  <h2>GOSERVIZI</h2>  
  <span class="small">
  <p>XP Trading S.R.L<br>
    Via Cairoli, 33 Tricase 73039<br>
    P.iva 04230180756<br>
    <?php echo $DATA; ?><br>
   <?php echo $merchant."-".$termID; ?></p>
    
    
    <strong>RICARICA TELECOM</strong><br><p class="time"><?php echo $DATA; ?><br>
   Seriale: <?php echo $Serial; ?><br>
   NON RIMBORSABILE</p>
  </span>
  <p align="center">PIN CODE<br>
    <strong><?php echo $PINCODE; ?></strong><br>
    Importo:<?php echo $AMOUNT; ?> Euro<br>
  </p>
 
  <p class="small"><?php if($OFFERCODE != '') include(dirname(__FILE__).'/subtemplate/'.$OFFERCODE.'.html'); ?></p>
<span class="small">
 <p align="center">Rif. Operazione: <?php echo $origID; ?><br>
 ARRIVEDERCI E GRAZIE</p></span> </div>
  
</page>
