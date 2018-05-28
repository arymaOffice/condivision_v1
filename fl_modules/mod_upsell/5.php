
<?php $veicolo_id = $_GET['veicolo_id']; ?>


<h1>5+ Seleziona un taglio chilometrico</h1>   

  
   <table class="dati">
   <tr>
   <th>Chilometri</th>
   <th>Prezzo listino </th>
   <th></th>
   </tr>
 
   <tr>
      <td>120.000 KM</td>
      <td>120 €</td>
      <td><a href="mod_inserisci.php?protect&veicolo_id=<?php echo $veicolo_id; ?>&prodotto_id=1&prezzo_listino=120&descrizione_prodotto=Prodotto esempio caricato demo" class="button"> Seleziona </a></td>
  </tr>
 <tr>

      <td>150.000 KM</td>
      <td>150 €</td>
      <td><a href="mod_inserisci.php?protect&veicolo_id=<?php echo $veicolo_id; ?>&prodotto_id=1&prezzo_listino=120&descrizione_prodotto=Prodotto esempio caricato demo" class="button"> Seleziona </a></td>
  </tr>
 <tr>
      <td>180.000 KM</td>
      <td>180 €</td>
      <td><a href="mod_inserisci.php?protect&veicolo_id=<?php echo $veicolo_id; ?>&prodotto_id=1&prezzo_listino=120&descrizione_prodotto=Prodotto esempio caricato demo" class="button"> Seleziona </a></td>


   </tr>
</table>