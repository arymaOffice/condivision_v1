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
  </span> 
    
  <p><strong>GIOCO DIGITALE</strong>
  <br>PIN CODE<br>
  <strong><?php echo $PINCODE; ?></strong><br>
	Importo: <?php echo $AMOUNT; ?> Euro</p>
    
  <p class="small">
  Questo codice pin non e' 
rimborsabile ne' riutilizzabile.
Conservare lo scontrino fino a 
ricarica avvenuta.
IVA esente ai sensi dell'art. 
74 dpr 633/72<br>
Per utilizzare questo codice vai
alla sezione Ricarica su 
<a href="http://www.giocodigitale.it">www.giocodigitale.it</a> e seleziona
&quot;Ricarica con pin&quot; 
per inserire il codice.</p>

  <p class="small">BUON DIVERTIMENTO SU  GIOCODIGITALE.IT<br>
Concessione GS N.4210<br><br>
  Rif. Operazione: <?php echo $origID; ?><br>ARRIVEDERCI E GRAZIE
  </p> </div>
</page>
