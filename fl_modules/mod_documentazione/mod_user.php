<?php 
require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
 
include("../../fl_inc/headers.php");

?>


<body style=" background: #FFFFFF;">
<div id="container" style=" text-align: left;">

<?php 


if($contenuto_id == 1) { echo "<h1>Salva dati prima di inserire documenti</h1></div>"; exit; }


if ($cat == 1 || $cat == 3 || $cat == 2 || $cat == 4 || $cat == 6 || $cat == 8 || $cat == 9 || $cat == 17  || $cat == 18) { ?>

<div class="box_div">
<form id="form_<?php echo $txt_soggetto; ?>" class="admin_form" method="post" action="../mod_documentazione/mod_opera.php" enctype="multipart/form-data">
    <input name="modulo" id="modulo" type="hidden" value="<?php echo $modulo_id;  ?>" />
    <input name="contenuto" id="contenuto" type="hidden" value="<?php echo $contenuto_id;  ?>" />
   <input name="relation" id="relation" type="hidden" value="<?php echo $_SESSION['number'];  ?>" />
       <input name="cat" id="cat" type="hidden" value="<?php echo $cat;  ?>" />
     <?php if(isset($_GET['external']))  { ?> <input type="hidden" value="1" name="external" /><?php } ?>
 
		<?php if ($cat == 2 || $cat == 3 || $cat == 4 || $cat == 5) { 
          echo $cat." non gestito";
          } else if($cat == 18){ ?>
       
         <h2 style="margin: 0;">Che tipo di documento vuoi caricare?</h2>
          
         <p>  <select name="titolo">
           <?php 
		   
		   $tipologie_doc = array();
		   $tipologie_doc['Documento di Riconoscimento'] = 'Documento di Riconoscimento';
		   $tipologie_doc['Codice Fiscale'] = 'Codice Fiscale';
		   $tipologie_doc['Visura Camerale o Atto costitutivo'] = 'Visura Camerale o Atto costitutivo';
		   $tipologie_doc['Certif. Attribuzione Codice Fiscale / P.iva'] = 'Certif. Attribuzione Codice Fiscale / P.iva';
		   $tipologie_doc['Contratto'] = 'Contratto';
				   
		   foreach($tipologie_doc as $chiave => $valore) {
			   
			   echo '<option value="'.$chiave.'">'.$valore.'</option>'; 
			   
			   } ?>
          </select></p>
<p><a href="">Download Contratto da firmare</a></p>
            
            
          
          
          <?php } else if($cat == 8) { ?>
          <span class="Stile2">ATTENZIONE!</span><br />
          Non verranno accettate ricevute di bonifico con data valuta
          differente da quella dell&acute;esecuzione del bonifico stesso, inoltre se
          verranno  inserite  ricevute non conformi o alterate  verr&agrave; esperita
          ogni utile azione stragiudiziale e giudiziale volta al recupero delle
          somme a qualsiasi titolo.
          </p>
          <input name="titolo" id="titolo" type="hidden" class="form_color" size="60" maxlength="255" value="Ricevuta" />
          <?php } else { ?>
          <p>
            <label for="titolo">Titolo:</label>
          </p>
          <p>
            <input name="titolo" id="titolo" type="text" class="form_color" size="60" maxlength="255" value="<?php if(isset($riga['titolo'])) { echo $riga['titolo']; } ?>" />
            <span class="Stile1"><strong><br />
            <span class="Stile4">Note:</span></strong><span class="Stile4"> Obbligatorio.</span></span></p>
          <?php } ?>
          <p>
            <label for="link">Seleziona un file: </label>
            <input type="hidden" value="<?php echo @$cat; ?>" name="cat" />
            <input name="upfile" type="file"  id="upfile" size="30" />
        </p>
        <p style=" text-align: left; ">
      <input type="submit" id="invio" value="  Carica  " class="button salva" style="padding: 5px 20px;"  onclick="setStyle('loading','display','block')" />
    </p>
    <p id="loading" class="red" style="display: none;"> Caricamento...!</p>
     <?php  if(isset($_GET['esito'])) { echo ' <p class="red">'.check($_GET['esito']).'</p>';  } ?>
    </div>
       
    
    
      <?php 
	
	
	 

	
	$where = "";
	if(isset($_SESSION['number'])) {$id = check($_SESSION['number']); $where = "WHERE cat = $cat"; }
	if(isset($_GET['operatore'])) { $id = check(@$_GET['operatore']); $where = "WHERE relation = $id AND cat = $cat"; }
	
	
	if(isset($_GET['mode'])) { $id = check($_SESSION['number']); $where = "WHERE cat = $cat"; }
	if($modulo_id != 0) { $where .= " AND modulo = $modulo_id"; }
	if($contenuto_id != 0) { $where .= " AND contenuto = $contenuto_id"; }
	
	$query = "SELECT * FROM $tabella $where ORDER BY $ordine ";
    $blocco_doc = 0;
	
	if($cat == 6){
	$query_doc = "SELECT * FROM fl_attivazioni WHERE id = $contenuto_id"; 	
	$risultato2 = mysql_query($query_doc, CONNECT);	
	$stato_x = mysql_fetch_array($risultato2);
	$blocco_doc  = $stato_x['identificato'];
	}
	if($cat == 18){
	$query_doc = "SELECT * FROM fl_anagrafica WHERE id = $contenuto_id"; 	
	$risultato2 = mysql_query($query_doc, CONNECT);	
	$stato_x = mysql_fetch_array($risultato2);
	$blocco_doc  = $stato_x['status_anagrafica'];
	}
	
	$risultato = mysql_query($query,CONNECT);

	?>

<table class="dati">
  <tr>
    <th>Id</th>
    <th> File</th>
    <th>Ultima Modifica</th>
    <th>Categoria</th>
    <th>Elimina</th>
  </tr>
  <?php
	
	if(mysql_affected_rows() == 0){echo "<tr><td colspan=\"5\">Nessun File Caricato</td></tr>";} 
	
	$files = 0;
	while ($riga = mysql_fetch_array($risultato)) 
	{
	$files++;
	$download_item = "http://cdn.goservizi.it/?file=".$riga['file'];
		
	if($blocco_doc < 2 || $_SESSION['number'] == 1){ $doc_info = '<a href="../mod_basic/action_elimina.php?gtx='.$tab_id.'&unset='.$riga['id'].'&file='.$dir_documenti.$doc_dir.$riga['file'].'" title="Elimina" class="button" onclick="conferma_del();">X</a>';  } else { $doc_info = ""; }
 	 ?>
  <tr>
    <td><?php echo $riga['relation']; ?></td>
    <td><a href="<?php echo $download_item; ?>" title="Apri File" target="_parent"><?php echo $riga['titolo']; ?></a></td>
    <td><?php echo date("d-m-Y",$riga['data']); ?></td>
    <td class="bottone"><?php echo $cat_multi_mod[$riga['cat']]; ?></td>
    <td><?php echo $doc_info; ?></td>
  </tr>
  <?php }  ?>
</table>
<div class="box_div"><?php echo '<p>CDN: http://cdn.goservizi.it/</p>'; ?></div>

<?php //if($files > 1 && $cat == 6) { echo alert('LA RICHIESTA RISULTA COMPLETA! Puoi cliccare su <Torna alla lista o modifica/aggiungi altri files.'); } mysql_close(CONNECT); //Chiudo la Connessione	?>
<?php //if($files > 3 && $cat == 17) { echo alert('LA RICHIESTA HA ALMENO 3 FILES! Se non devi caricare ulteriori documenti <Torna alla lista o modifica/aggiungi altri files.'); } mysql_close(CONNECT); //Chiudo la Connessione	?>
<?php //if($files > 5 && $cat == 18) { echo alert('LA RICHIESTA HA ALMENO 5 FILES! Se non devi caricare ulteriori documenti <Torna alla lista o modifica/aggiungi altri files.'); } mysql_close(CONNECT); //Chiudo la Connessione	?>


  </td>
      </tr>
    </table>
    
  </form>
</div>
<?php } else { echo "<h2>Documenti Disponibili</h2><p>Questo Folder contiene i documenti caricati dalla sede centrale, condivisi con il tuo account.<br /> (potrebbe essere necessario inserire una password di protezione).</p>"; }?>
