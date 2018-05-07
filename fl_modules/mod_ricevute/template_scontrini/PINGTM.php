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
<page format="57x130" orientation="P" backcolor="#FFFFFF" style="font: arial;">

<div id="container">
  
  <h2>GOSERVIZI</h2>  
  <span class="small">
  <p>XP Trading S.R.L<br>
    Via Cairoli, 33 Tricase 73039<br>
    P.iva 04230180756<br>
    <?php echo $DATA; ?><br>
   <?php echo $merchant."-".$termID; ?></p>
    </span>
    
    <p><strong>GT MOBILE</strong><br>PIN CODE<br>
  <strong><?php echo $PINCODE; ?></strong><br>
	Importo: <?php echo $AMOUNT; ?> Euro<br>
	Data: <?php echo $DATA; ?>
  </p>
  <p class="small">
  
Utilizzare entro il: <?php echo $Expiration; ?><br>
NON RIMBORSABILE -NON  SOSTITUIBILE<br>
IVA inclusa assolta da  Lycamobile S.r.l.</p>
  <p class="small">    P. IVA 09860601005, ex art. 74  DPR 633/72 Inserisci *131*PIN seguito dal tasto #,infine premi 'invio'.
Oppure chiama  il numero gratuito 40321&nbsp; dal tuo cellulare Lycamobile
e segui le  istruzioni Servizio Clienti: 40322 / +390200621322
(Chiamata gratuita dal tuo  cellulare GT Mobile)<br>
 Per Termini e Condizioni vedi: <a href="http://www.gtmobileitalia.it">www.gtmobileitalia.it</a>
 <br><br>Rif. Operazione: <?php echo $origID; ?><br> ARRIVEDERCI E GRAZIE
  </p>

 </div>
</page>
