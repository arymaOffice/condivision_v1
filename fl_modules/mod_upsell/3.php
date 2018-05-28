
<?php $veicolo_id = $_GET['veicolo_id']; ?>


<h1>3+ Seleziona un taglio chilometrico</h1>   

  
   <table class="dati">
   <tr>
   <th>Chilometri</th>
   <th>Prezzo listino </th>
   <th></th>
   </tr>
 
   <tr>
      <td>100.000 KM</td>
      <td>100 €</td>
      <td><a href="mod_inserisci.php?protect&veicolo_id=<?php echo $veicolo_id; ?>&prodotto_id=1&id=1&prezzo_listino=120&descrizione_prodotto=Prodotto esempio caricato demo" class="button"> Seleziona </a></td>
</tr>

  <tr>    <td>120.000 KM</td>
      <td>120 €</td>
      <td><a href="mod_inserisci.php?protect&veicolo_id=<?php echo $veicolo_id; ?>&prodotto_id=1&id=1&prezzo_listino=120&descrizione_prodotto=Prodotto esempio caricato demo" class="button"> Seleziona </a></td>
</tr>
  <tr>

      <td>150.000 KM</td>
      <td>150 €</td>
      <td><a href="mod_inserisci.php?protect&veicolo_id=<?php echo $veicolo_id; ?>&prodotto_id=1&id=1&prezzo_listino=120&descrizione_prodotto=Prodotto esempio caricato demo" class="button"> Seleziona </a></td>


   </tr>
</table>