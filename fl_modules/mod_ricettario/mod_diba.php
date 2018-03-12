<?php 

require_once('../../fl_core/autentication.php');
require_once('fl_settings.php');

$record_id = check($_GET['record_id']);
$ricettaInfo = GRD('fl_ricettario',$record_id);
$tipo_prezzo = (isset($_GET['calcola'])) ? check($_GET['calcola']) : 'ultimo_prezzo';
if($ricettaInfo['tipo_ricetta'] == 0)
  
$materiaprima_id = $data_set->data_retriever('fl_materieprime','codice_articolo,descrizione,unita_di_misura',' WHERE id != 1 AND tipo_materia = 112','descrizione ASC'); //Crea un array con i valori X2 della tabella X1

$nochat;
$tab_id = 120;

include("../../fl_inc/headers.php");
 ?>
 
<body style="background: rgb(241, 241, 241) none repeat scroll 0% 0%; text-align: left; padding: 20px;">


<h1>Elenco Ingredienti</h1>
<!--
<div class="box" style="float: left; width:  45%;">

<form id="" action="./mod_opera.php" method="post" enctype="multipart/form-data">
<h3>Semilavorato</h3>
<p><select name="semilavorato_id" class="select2" style="width: 250px;" >
<?php 
     foreach($semilavorato_id as $valores => $label){ // Recursione Indici di Categoria
      echo "<option value=\"$valores\">".ucfirst($label)."</option>\r\n"; 
      }
     ?>
</select></p>
<input type="hidden" name="ricetta_id" value="<?php echo $record_id; ?>" />
<p>
Q.ta <input type="text" name="quantita" value="1.000" style="width: 60px;" /> 
</p>
<p><input type="submit" value="Inserisci" /></p>
</form>
</div>-->


<?php 
	
	$foodCost = 0;
  
	$query = "SELECT * FROM fl_ricettario_diba WHERE `ricetta_id` = $record_id ORDER BY id ASC";
	$risultato = mysql_query($query, CONNECT);
	if(mysql_affected_rows() == 0){ echo "<p>Nessun elemento</p>"; } else {
	?>
    
   <table class="dati">
   <tr>
   <th>Descrizione</th>
   <th>UM (File)</th>
   <th>UP (File)</th>
   <th>Conversione</th>
   <th>UM</th>
   <th>Quantità</th>
   <th>Prezzo Grammo</th>
   <!--<th>CU &euro;</th>-->
   <th>CP &euro;</th>
   <th></th>
   </tr>
          
 <?php
	

	while ($riga = mysql_fetch_assoc($risultato)) 
	{ 

  $materiaprima = ($riga['materiaprima_id'] > 0) ? GRD('fl_materieprime',$riga['materiaprima_id']) : GRD('fl_ricettario',$riga['semilavorato_id']);
  $descrizione = ($riga['materiaprima_id'] > 0) ? $materiaprima['descrizione'] : converti_txt($materiaprima['nome']);
  $codice_articolo = ($riga['materiaprima_id'] > 0) ? $materiaprima['codice_articolo'].'' : $materiaprima['id'];
    
  if($riga['materiaprima_id'] > 0) {
  //$quotazione = GQD('fl_listino_acquisto','valuta, prezzo_unitario, data_validita',' id_materia = '.$materiaprima['id'].' ORDER BY data_validita DESC,data_creazione DESC LIMIT 1');
  $costo =  ($materiaprima[$tipo_prezzo]*$riga['quantita'])/$materiaprima['valore_di_conversione'];
  $foodCost += $costo;

	}
  
  $prezzo_grammo = round(($materiaprima[$tipo_prezzo]/$materiaprima['valore_di_conversione'])/1000,4); 
  ?> 
  <tr>
  <td><a href="../mod_materieprime/mod_inserisci.php?id=<?php echo @$materiaprima['id']; ?>" target="_blank"><?php echo $descrizione; ?></a><br><span class="msg blue"><?php echo $codice_articolo; ?><span/></td>
  <td><?php echo @$materiaprima['importaz_um']; ?></td> 
  <td><?php echo @$materiaprima[$tipo_prezzo]; ?></td> 

  <td><input type="text" class="updateField" style="max-width: 100px;" data-rel="<?php echo @$materiaprima['id']; ?>" data-gtx="121" name="valore_di_conversione" value="<?php echo @$materiaprima['valore_di_conversione']; ?>" /></td> 
  <td>

        <select  class="updateField" name="unita_di_misura"  data-rel="<?php echo @$materiaprima['id']; ?>" data-gtx="121">
    
      <?php 
      unset($unita_di_misura[0]);

         foreach($unita_di_misura as $valores => $label){ // Recursione Indici di Categoria
      $selected = ($materiaprima['unita_di_misura'] == $label) ? " selected=\"selected\"" : "";
       if( $label != '') echo "<option value=\"$label\" $selected>".ucfirst($label)."</option>\r\n"; 
      }
     ?>
    </select> 

  
      </td>
     
      <td><input type="text" class="updateField" style="max-width: 100px;" data-rel="<?php echo $riga['id']; ?>" name="quantita" value="<?php echo $riga['quantita']; ?>" /></td>
      <td><?php echo $prezzo_grammo; ?></td> 
  	  <!--<td><input type="text" class="updateField" style="max-width: 100px;" data-rel="<?php echo @$materiaprima['id']; ?>" data-gtx="121" name="<?php echo $tipo_prezzo; ?>" value="<?php echo @$materiaprima[$tipo_prezzo]; ?>" /></td> --> 
      <td><strong><?php echo @$quotazione['valuta'].' '.@numdec( $costo ,4); ?></strong></td> 
      <td><a href="../mod_basic/action_elimina.php?gtx=<?php echo $tab_id; ?>&amp;unset=<?php echo $riga['id'];?>" title="Elimina"  onclick="return conferma_del();"><i class="fa fa-trash-o"></i></a></td>
      </tr>

    <?php } echo '</table>'; } //Chiudo la Connessione	?>


<h1>Aggiungi Ingrediente</h1>

<form id="" action="./mod_opera.php" method="post" enctype="multipart/form-data">

<table class="dati" id="inserisci_ingrediente">
<tr>
<td>
<select name="materiaprima_id" class="select2" style="width: 350px;" >
  <option value="0">Cerca ingrediente...</option>
<?php 
     foreach($materiaprima_id as $valores => $label){ // Recursione Indici di Categoria
      echo "<option value=\"$valores\">".ucfirst($label)."</option>\r\n"; 
      }
     ?>
</select>
</td>
<td><span class="msg gray">Quantità </span><input type="hidden" name="ricetta_id" value="<?php echo $record_id; ?>" />
<input type="text" name="quantita" value="1.000" style="width: 60px;" /></td>
<td><!--<input type="checkbox" name="grammi" value="1" id="gratokg" <?php if(isset($_SESSION['inserisciGrammi'])) echo 'checked'; ?>/> 
<label for="gratokg">Inserisci Gr.</label>--></td>
<td colspan="2"><input type="submit" value="Aggiungi" class="button" style="margin: 0;" /></td>
</tr>
</table>
</form>



<h2>Costo della ricetta: &euro; <?php echo  numdec($foodCost,3); ?></h2>
<!--Calcola <a href="mod_diba.php?record_id=<?php echo $record_id; ?>&calcola=ultimo_prezzo" class="msg <?php echo ($tipo_prezzo == 'ultimo_prezzo') ? 'blue' : 'gray'; ?>">Ultimo prezzo</a>
<a href="mod_diba.php?record_id=<?php echo $record_id; ?>&calcola=prezzo_medio" class="msg <?php echo ($tipo_prezzo == 'prezzo_medio') ? 'blue' : 'gray'; ?>">Costo medio</a>
--><p>Aggiornando il CU verra aggiornato <strong><?php echo $tipo_prezzo; ?></strong> del del prodotto.</p>
<a href="javascript:location.reload();" class="button">Aggiorna il costo</a>
<a href="javascript:window.print();" class="button">Stampa</a>
<a href="mod_opera.php?rev=<?php echo $record_id; ?>" class="button green">Approva Revisione </a> | Rev. <?php echo $ricettaInfo['revisione']; ?> | Aggiornamento ricetta: <?php echo mydatetime($ricettaInfo['data_aggiornamento']); ?>
<p>NB: Per aggiornare i valori dgli ingredienti inseriti, clicca il tasto TAB e attendi che scompaia il bordino colorato.</p>
<style type="text/css">
.select2-container {
    min-width: 200px;
    max-width: 1000px;
  }

</style>



<?php 
  
  $foodCost = 0;
  
  $query = "SELECT * FROM fl_ricettario_diba WHERE `ricetta_id` = $record_id ORDER BY id ASC";
  $risultato = mysql_query($query, CONNECT);
  if(mysql_affected_rows() == 0){  } else {
  ?>

          
 <?php
  $persone = 100;
  echo '<h1>Simulazione di Fabbisogno per '.$persone.' persone.</h1>
  <p>Le quantità da ordinare per questa pietanza: ';
  
  while ($riga = mysql_fetch_assoc($risultato)) 
  { 

  $materiaprima = ($riga['materiaprima_id'] > 0) ? GRD('fl_materieprime',$riga['materiaprima_id']) : GRD('fl_ricettario',$riga['semilavorato_id']);
  $descrizione = ($riga['materiaprima_id'] > 0) ? $materiaprima['descrizione'] : converti_txt($materiaprima['nome']);
  $codice_articolo = ($riga['materiaprima_id'] > 0) ? $materiaprima['codice_articolo'].'' : $materiaprima['id'];
    
  if($riga['materiaprima_id'] > 0) {
  //$quotazione = GQD('fl_listino_acquisto','valuta, prezzo_unitario, data_validita',' id_materia = '.$materiaprima['id'].' ORDER BY data_validita DESC,data_creazione DESC LIMIT 1');
  $costo =  ($materiaprima[$tipo_prezzo]*$riga['quantita'])/$materiaprima['valore_di_conversione'];
  $foodCost += $costo;
  }

  $nota = $nota2 = '';
  $unita_di_misura_prodotto = ($materiaprima['unita_di_misura'] != 'KP') ?  $materiaprima['unita_di_misura'] : 'Pezzi ';
  if($materiaprima['unita_di_misura'] == 'KP') $nota = '(Pezzatura da '.round(1000/$materiaprima['valore_di_conversione']).' Gr.)';
  $quantita = ($materiaprima['unita_di_misura'] != 'KP') ? $riga['quantita']*$persone : round($riga['quantita'])*$persone;
  if($materiaprima['unita_di_misura'] == 'KP') $nota2 = ' - Stimiamo di ricevere circa '.( (round(1000/$materiaprima['valore_di_conversione'])*$quantita) / 1000).' KG di merce';
  if($materiaprima['unita_di_misura'] == 'KP') $materiaprima['unita_di_misura'] = 'KG';

  echo $ordineDesc = "<p><strong>".$quantita." ".$unita_di_misura_prodotto." </strong> ".$nota." di ".$descrizione." - Ultimo prezzo a cui si è acquistato: &euro; ".numdec($materiaprima['ultimo_prezzo'],2).' (al '.$materiaprima['unita_di_misura'].') '.$nota2.'</p>'; 


  

     } } 



    mysql_close();  ?>

</body></html>