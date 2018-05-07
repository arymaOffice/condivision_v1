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
<page format="58x160" orientation="P" backcolor="#FFFFFF" style="font: arial;">

<div id="container">
    <h2>GOSERVIZI</h2>

  <span class="small">
  <p>XP Trading S.R.L<br>
Via Cairoli, 33 Tricase 73039<br>
P.iva 04230180756<br>
<?php echo $DATA; ?><br>
<?php echo $merchant."-".$termID; ?></p>
  </span>
  <strong>VODAFONE PIN</strong>
   <p class="time"><?php echo $DATA; ?><br>
   Seriale: <?php echo $Serial; ?><br>
 <strong> PIN</strong> <br><strong><?php echo $PINCODE; ?></strong></p>
  <p>Prezzo: <?php echo $AMOUNT; ?> Euro<br>
  Ricarica: <?php echo $AMOUNT; ?> Euro  </p>
  <p class="small">CHIAMA GRATUITAMENTE IL 42010 E DIGITA IL CODICE PIN QUI RIPORTATO<br>
OPPURE CHIAMA IL NUMERO A PAGAMENTO 348.2002010<br>
O VAI SUL SITO VODAFONE.IT. <br>
GUARDA DATA DI SCADENZA<br>
E CONSULTA CONDIZIONI IN NEGOZIO O SU vodafone.it<br>
IVA ASSOLTA ALLA FONTE EX ART 74 COMMA 1 LETT.D DPR 633/72 <br>
DA VODAFONE ITALIA S.P.A. <br>
P.IVA 08539010010 </p>
 
 <span class="small"> 
 <p>Rif. Operazione: <?php echo $origID; ?><br />
 ARRIVEDERCI E GRAZIE </p>
 </span> 
 

</div>
</page>
