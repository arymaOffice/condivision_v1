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
<page format="80x170" orientation="P" backcolor="#FFFFFF" style="font: arial;">

<div id="container">
  
  <h2>GOSERVIZI</h2>
  
  <span class="small">
  <p>XP Trading S.R.L<br>
    Via Cairoli, 33 Tricase 73039<br>
    P.iva 04230180756</p>

  <p class="time">DATA: <?php echo $DATA; ?><br>
  Merc. <?php echo $merchant."  - TermID. ".$termID; ?></p>

   
   <p><strong>TIM RICARICHE</strong></p>
</span>
  <p> Il presente scontrino costituisce ricevuta dell’avvenuto pagamento.<br>
    Conservare fino a 
    ricarica avvenuta</p>
  <p>Scontrino non fiscale</p>
  <p align="center"><strong>NUMERO DI  TELEFONO</strong><br>
    <strong><?php echo $USERCODE; ?></strong></p>
  <p align="center">Importo:<?php echo $AMOUNT; ?> Euro<br>
  </p> 
  <span class="small"><p align="center"><strong>TID: <?php echo $RechargeID; ?></strong><br>
    <?php echo $AUTH; ?></p>
  <p> La ricarica avverrà entro 24 ore
    Per informazioni chiami il servizio clienti 119</p>
  <p><span class="small">*IVA assolta ai sensi dell’art. 74 DPR 633/72</span></p>
  <p><span class="small"> TRANSAZIONE ESEGUITA<br>
    powered by Euronet</span></p>
  <p align="center">ARRIVEDERCI E GRAZIE
  </p> </span></div>
</page>
