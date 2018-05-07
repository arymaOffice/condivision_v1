<style type="text/css">
<!--
div#container {
	border: none;
	background: #FFFFFF;
	font-size: 3.4mm;
	text-align: center;
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
  </span> 
    
    <strong>Conferma Servizio PIN</strong>
    <p class="time"><?php echo $DATA; ?><br>
  </p>

  <p>PIN CODE<br>
  <strong><?php echo $PINCODE; ?></strong><br />
 
  Importo: <?php echo $AMOUNT; ?> Euro</p>
 <span class="small">

<br /> Ref. id:  <?php echo $origID; ?>

<br /> ARRIVEDERCI E GRAZIE

</span> 
</div>

</page>
