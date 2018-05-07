<?php 
if(isset($_GET['external'])){
require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
 
include("../../fl_inc/headers.php");


}

?>



<div class="box_div">
<?php 


if($contenuto_id == 1) { echo "<h1>Salva dati prima di inserire documenti</h1></div>"; exit; }

if($cat == 1 || $cat == 10 || $cat == 12){


?>
<?php if ($cat != 6) { ?>


<form id="form_<?php echo $txt_soggetto; ?>" class="admin_form" method="post" action="mod_opera.php" enctype="multipart/form-data">


<p><input name="relation" id="relation" type="hidden" value="<?php echo $_SESSION['number'];  ?>" />
<input name="modulo" id="modulo" type="hidden" value="<?php echo $modulo_id;  ?>" />
<input name="contenuto" id="contenuto" type="hidden" value="<?php echo $contenuto_id;  ?>" />
Categoria: <input name="cat" id="cat" type="hidden" value="<?php echo $cat;  ?>" /><?php echo $cat_multi_mod[$cat];  ?></p>
<table style="width: 100%;" >
  <tr>
    <td width="auto">
      
      <?php if ($cat == 2 || $cat == 3 || $cat == 4) { ?>
      <p>Periodo del Report: </p>
      <p>
             <select name="titolo" id="titolo">
               <?php 
			   for($i=1;$i<13;$i++){
			     (date("m") == $i) ? $selected = 'selected="selected"' : $selected = '';
                    echo '<option value="'.$mesi[$i].'" '.$selected.'>'.$mesi[$i].'</option>';
				} ?>
               </select>
              <select name="anno" id="anno">
               <?php 
			   $time = date("Y");
			   for($i=($time-1);$i<($time+4);$i++){
			   ($time == $i) ? $selected = 'selected="selected"' : $selected = '';
                    echo '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
				} ?>
               </select>
           
               </p>
           <?php  }else{ ?>
         <p>
          <label for="titolo">Titolo: (Obbligatorio)</label></p>

        <p><input name="titolo" id="titolo" type="text" class="form_color" size="60" maxlength="255" value="<?php if(isset($riga['titolo'])) { echo $riga['titolo']; } ?>" />
        
      </p>

		<?php } ?>
<p>
        <label for="link">Documento da caricare: </label><input name="upfile" type="file"  id="upfile" size="25" /></p>

      </td>
    <td width="20">

    <!--<h3>Note sul File</h3>
             <label for="status">Pubblicazione</label>
             <p>
               <select name="status" id="status">
                 <option value="0">Non Pubblicare</option>
                 <option value="1" selected="selected">Pubblicato</option>
               </select>
               
               </p></p>
             <label for="protocollo"></label>
        <label for="versione"><br />
        Versione<br />
        </label>
        <input name="versione" type="text" id="label" size="10" />
<p>
        <label for="label2">Approvato<br />
          <input type="radio" name="revisionato" id="revisionato1" value="1" />
          Si
          <input name="revisionato" type="radio" id="revisionato2" value="0" checked="checked" />
          No </label>
        </p>
      <p>
        <label for="lang">Lingua del documento:</label>
        <br />
        <select name="lang" id="lang">
          <?php foreach($languages as $chiave => $valore){
    
   echo "<option value=\"$chiave\" $selected>$valore</option>";
   
   } ?>
        </select>
      </p>
      <p></p>--></td>
  </tr>
</table>
<p><input type="submit" id="invio" value="  Carica  " class="button" /></p>

</form> <?php  } else {  ?>

<h3>Seleziona Operatore.</h3>

<form method="get" action="" id="main_select" style="text-align: right; float: left; margin: 2% 0% 10% 20px; ">

 <p>
    Operatore:
      <select name="operatore" id="operatore" onchange="form.submit();">
               <?php
			 foreach($proprietario as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($proprietario_id == $valores) ? " selected=\"selected\"" : "";
			if($valores != 0){ echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; }
			}
		 ?>
       </select></p>
    <!--<p><?php echo $text_home; ?> per il mese di:
             <select name="mese" id="mese">
               <?php
			   for($i=1;$i<13;$i++){
			     ($mese_sel == $i) ? $selected = 'selected="selected"' : $selected = '';
                    echo '<option value="'.$i.'" '.$selected.'>'.$mesi[$i].'</option>';
				} ?>
       </select></p><p> Anno:
              <select name="anno" id="anno">
               <?php
			   $time = date("Y");
			   for($i=($time-1);$i<($time+4);$i++){
			   ($anno_sel == $i) ? $selected = 'selected="selected"' : $selected = '';
                    echo '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
				} ?>
       </select></p>-->
       <p>
      <input type="hidden" value="<?php echo @$cat; ?>" name="cat" /><input name="modulo" id="modulo" type="hidden" value="<?php echo $modulo_id;  ?>" />
<input name="contenuto" id="contenuto" type="hidden" value="<?php echo $contenuto_id;  ?>" />


<?php } ?>

<?php } ?>


<br class="clear" />

</div>

<?php  

	
	if(1){
	
	

	$where = "";
	if(isset($_SESSION['number'])) {$id = check($_SESSION['number']); $where = "WHERE cat = $cat"; }
	if(isset($_GET['operatore'])) { $id = check(@$_GET['operatore']); $where = "WHERE relation = $id AND cat = $cat"; }
	if($modulo_id != 0) { $where .= " AND modulo = $modulo_id"; }
	if($contenuto_id != 0) { $where .= " AND contenuto = $contenuto_id"; }
   	
	$query = "SELECT * FROM $tabella $where ORDER BY $ordine ";
	$risultato = mysql_query($query, CONNECT);
	
	?>
    <div id="class"></div>
    <table class="dati" summary="Dati">
      <tr>
        <th scope="col">Utente</th>
        <th scope="col"> File</th>
        <th>Ultima Modifica</th>
        <th style="width: 150px;">Categoria</th>
        <th scope="col" class="tasto">Elimina</th>
    </tr>
          
    <?php
	
	if(mysql_affected_rows() == 0){echo "<tr><td colspan=\"5\">Nessun File Caricato</td></tr>";}
      
	while ($riga = mysql_fetch_array($risultato)) {

	$download_item = "http://cdn.goservizi.it/cdn_scarica.php?file=".$riga['file'];
	//$download_item = $dir_documenti.$doc_dir.$riga['file'];  ?>
    
    
     
      <tr>
        <td><?php echo @$proprietario[$riga['relation']]; ?></td>
       
        <td ><a href="<?php echo $download_item; ?>" title="Apri File"><?php echo $riga['titolo']; ?></a></td>
       
        <td ><?php echo date("d-m-Y",$riga['data']); ?></td>
      
       	<td><?php echo $cat_multi_mod[$riga['cat']]; ?></td>
       

        <td><?php echo '<a href="../mod_basic/elimina.php?gtx='.$tab_id.'&unset='.$riga['id'].'&file='.$dir_documenti.$doc_dir.$riga['file'].'" title="Elimina" class="button" onclick="conferma_del();">X</a>'; ?></td>
                  
      </tr>
  
	


    <?php } mysql_close(CONNECT); } ?>

 

 </table>

