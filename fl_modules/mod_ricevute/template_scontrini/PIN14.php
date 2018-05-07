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
    
    <strong>Ricarica Fastweb</strong>
    <p class="time"><?php echo $DATA; ?><br>
  </p>

  <p>PIN CODE<br>
  <strong><?php echo $PINCODE; ?></strong><br />
 
	Importo: <?php echo $AMOUNT; ?> Euro</p>
 <span class="small">NON RIMBORSABILE <br />
 Importi IVA inclusa,assolta da FASTWEB SpA <br />
 P.IVA 12878470157<br />
 ex art. 74 DPR 633/72.<br />
Per ricaricare chiama il 4046 <br />
o vai nella tua MyFastPagee segui le istruzioni. <br />
Info: www.fastweb.itoppure chiama 192.193

<br />

<br />Numero Seriale <?php echo $Serial; ?> 
<br /> op.:  <?php echo $origID; ?>

<br /> ARRIVEDERCI E GRAZIE

</span> 
</div>
</page>
