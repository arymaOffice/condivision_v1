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
<page format="58x220" orientation="P" backcolor="#FFFFFF" style="font: arial;">

<div id="container">
  
 <h2>GOSERVIZI</h2>
  <span class="small">
  <p>XP Trading S.R.L<br>
    Via Cairoli, 33 Tricase 73039<br>
    P.iva 04230180756<br>
    <?php echo $DATA; ?><br>
    <?php echo $merchant."-".$termID; ?></p>
  </span> 
    
    <strong>Ricarica  SKY</strong>
    <p class="time"><?php echo $DATA; ?><br>
   Seriale: <?php echo $Serial; ?></p>

  <p>PIN CODE<br>
  <strong><?php echo $PINCODE; ?></strong><br />
 
	Importo: <?php echo $AMOUNT; ?> Euro</p>
 <span class="small"> <p>Attivazione via telefono:<br>
- Chiama il n. 199.100.200 (2)<br>
- Digita il NUMERO SMART CARD  Sky (inserisci tutti gli zeri)<br>
-Digita il cod. di ricarica che<br>
trovi sullo scontrino. <br>
Riceverai conferma dell'avvenuta  ricarica e ti sara' comunicato il credito aggiornato.</p>
  <p>Attivazione via SMS:<br>
    Invia un SMS al n. 340.431.1111  (3) con il seguente testo:<br>
  &quot;RIC (spazio) CODICE DI  RICARICA (spazio) NUMERO SMART CARD Sky&quot;.<br>
    Ti sara' inviato un SMS con la  conferma della avvenuta ricarica e il saldo aggiornato del credito. </p>
  <p>Oppure vai su <a href="http://www.sky.it/primafilaricaricabile">www.sky.it/primafilaricaricabile</a><br>
    1)L&rsquo;abilitazione della Smart Card  al servizio Sky Primafila Ricaricabile comportera' un costo una tantum di 5€  che verrà scalato solo dalla prima ricarica<br>
    2) Tariffa max da rete fissa 15<br>
    cent/Min (IVA inclusa). Costi<br>
    cellulare legati all'operatore<br>
    3) Costi di invio sms legati  allo<br>
  operatore utilizzato</p>
  <p>Conservare il presente scontrino  con cura al riparo da fonti di luce.<br>
    Imposta assolta ex art.74 c.1 da<br>
    SKY Italia srl con unico socio<br>
  P. IVA 04619241005 </p>
  

<p>Rif. Operazione: <?php echo $origID; ?>
<br /> ARRIVEDERCI E GRAZIE
  </p>
</span> </div>
</page>
