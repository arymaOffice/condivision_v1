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
<page format="58x170" orientation="P" backcolor="#FFFFFF" style="font: arial;">

<div id="container">
  
 <h2>GOSERVIZI</h2>
  <span class="small">
  <p>XP Trading S.R.L<br>
    Via Cairoli, 33 Tricase 73039<br>
    P.iva 04230180756<br>
    <?php echo $DATA; ?><br>
    <?php echo $merchant."-".$termID; ?></p>
  </span> 
    
    <strong>AMAZON</strong>
    <p class="time"><?php echo $DATA; ?><br>
  </p>

  <p>Codice Regalo:<br>
  <strong><?php echo $PINCODE; ?></strong><br />
 
	Importo: <?php echo $AMOUNT; ?> Euro<br />
    Validità: <?php echo $Expiration; ?></p>
 <span class="small">RICEVUTA DI PAGAMENTO<br />
Conservare fino a ricarica
avvenuta.
<br />
Per utilizzare il tuo
codice visita:
www.amazon.it/pagare-in-contanti<br />
Codice regalo soggetto
ai termini e condizioni di 
utilizzo dei Buoni Regalo:
www.amazon.it/
buoni-regalo-termini-condizioni.
Buono Regalo non convertibile in
denaro ed utilizzabile solo per
acquistare prodotti su Amazon.it<br />
Una volta registrata sul proprio
account ogni somma non
utilizzata sara' disponibile
fino alla scadenza del Buono 
Regalo.<br />Il numero di Buono Regalo
sara' registrato una sola volta
nel vostro account.Emesso 
da Amazon EU Sarl.<br />
Attenzione rischio di
deterioramento;<br />
ricevuta non restituibile ne'
sostibuile.


<br />

<br />Numero Seriale <?php echo $Serial; ?> 
<br /> op.:  <?php echo $origID; ?>

<br /> ARRIVEDERCI E GRAZIE

</span> 
</div>
</page>
