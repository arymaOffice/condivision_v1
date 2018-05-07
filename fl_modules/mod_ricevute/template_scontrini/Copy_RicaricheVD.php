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
<page format="80x180" orientation="P" backcolor="#FFFFFF" style="font: arial;">

<div id="container">
  
  <h2>GOSERVIZI</h2>
  
  <span class="small">
  <p>XP Trading S.R.L<br>
    Via Cairoli, 33 Tricase 73039<br>
    P.iva 04230180756</p>
  
  <p class="time">DATA: <?php echo $DATA; ?><br> 
  Merc. <?php echo $merchant."  - TermID. ".$termID; ?></p>
   
   <p>
     Il presente scontrino costituisce ricevuta dell'avvenuto pagamento.<br>
     Conservare fino a ricarica avvenuta.</p>
</span>
  <p align="center">Scontrino non  fiscale</p>
  <p align="center"><strong>NUMERO DI  TELEFONO</strong><br>
   <strong><?php echo $USERCODE; ?></strong></p>
  <p align="center">Importo: <?php echo $AMOUNT; ?> euro<br>
    Di cui: <?php echo $AMOUNT; ?> traffico<br>
  0,00 costi</p>
 
    <p>LA RICARICA VERRA' ACCREDITATA ENTRO 24 ORE</p>
 <span class="small"> <p>IVA ASSOLTA AI SENSI ART. 74 DPR633/72 DA:<br>
    Vodafone Omnitel B.V.<br>
    Partita IVA 08539010010</p>
  <p>TID: <?php echo $RechargeID; ?></p>
    Cod. Aut.:  <?php echo $AUTH; ?><br>

  <p>PER INFO CHIAMA IL 190<br>
    &lt;&lt;Le condizioni generali di contratto sono disponibili su www.190.it o presso<br>
    i negozio Vodafone&gt;&gt;<br>
  </p>
  <p>TRANSAZIONE ESEGUITA</p>
<p>ARRIVEDERCI E GRAZIE<br>
</p>
</span> </div>
</page>
