

<div id="filtri" class="filtri">
<form id="form_0" class="admin_form" method="post" action="mod_opera.php" enctype="multipart/form-data">

 

<label for="elemento">Categoria:</label>

 <select name="sezione_link" id="sezione_link" >
    <?php foreach($sezione_link as $chiave => $valore){
    $selected = (isset($sezione_link_id) && $sezione_link_id == $chiave) ? 'selected="selected"' : ""; 
   echo "<option value=\"$chiave\" $selected>$valore</option>";
   
   } ?>
  </select>

<label for="relation">Sport:</label>

 <select name="relation" id="relation" onChange="form.submit()">
    <?php foreach($categorie_link as $chiave => $valore){
    $selected = (isset($relation) && $relation == $chiave) ? 'selected="selected"' : ""; 
   echo "<option value=\"$chiave\" $selected>$valore</option>";
   
   } ?>
  </select>
 <label for="cat">Tipo:</label>
 <select name="cat" id="cat">
    <?php foreach($tipologia_quote as $chiave => $valore){
   echo "<option value=\"$chiave\" $selected>$valore</option>";
   
   } ?>
  </select>
  <input type="hidden" name="periodo_quota" value="0" />
 <label for="sottocategoria">Sottocategoria:</label>
  <select name="sottocategoria" id="sottocategoria">
    <?php foreach($sottocategoria as $chiave => $valore){
   echo "<option value=\"$chiave\">$valore</option>";
   
   } ?>
  </select>
   <label for="disponibilita_link">Disponibilit&agrave;:</label>
 <select name="disponibilita_link" id="disponibilita_link">
    <?php foreach($disponibilita_link as $chiave => $valore){
   echo "<option value=\"$chiave\" $selected>$valore</option>";
   
   } ?>
  </select>
     <label for="tipo_link">Ordinamento:</label>
 <select name="tipo_link" id="tipo_link">
    <?php foreach($tipo_link as $chiave => $valore){
   echo "<option value=\"$chiave\" $selected>$valore</option>";
   
   } ?>
  </select>

   <label for="codice_link">Codice:</label>
   <input name="codice_link" type="text" class="" id="codice_link" value="" size="20" maxlength="50" />

   <label for="descrizione_link">Descrizione:</label>

<input name="descrizione_link" type="text" class="" id="link" value="" size="20" maxlength="255" />
<label for="link">Link:</label><input name="link" type="text" class="" id="link" value="http://" size="20" maxlength="255" />
<input type="submit" value="Inserisci" class="button" />
</form>  
</div>

<form id="form_1" class="admin_form" method="get" action="" enctype="multipart/form-data">

<input type="hidden" name="sezione_link_id" value="<?php echo $sezione_link_id; ?>"  />
<input type="hidden" name="disponibilita_link" value="<?php echo $disponibilita_link_id; ?>"  />

<p>  

 <select name="relation" id="relation" onChange="form.submit()">
    <?php foreach($categorie_link as $chiave => $valore){
    $selected = (isset($relation) && $relation == $chiave) ? 'selected="selected"' : ""; 
   echo "<option value=\"$chiave\" $selected>$valore</option>";
   
   } ?>
  </select>
<select name="tipologia" id="tipologia">

    <?php foreach($tipologia_quote as $chiave => $valore){
    $selected = (isset($tipologia_quota) && $tipologia_quota == $chiave) ? 'selected="selected"' : ""; 
   echo "<option value=\"$chiave\" $selected>$valore</option>";
   
   } ?>
  </select>

 <select name="tipo_link" id="tipo_link">
  <?php foreach($tipo_link as $chiave => $valore){
		$selected = (isset($tipo_link_id) && $tipo_link_id == $chiave) ? 'selected="selected"' : ""; 
   echo "<option value=\"$chiave\" $selected>$valore</option>";
   
   } ?>
  </select>
  
<input type="submit" value="Mostra" class="button" /></p>

</form> 


	
<?php 
 
	
	
	$query = "SELECT * FROM $tabella $where ORDER BY $ordine ";
	$risultato = mysql_query($query,CONNECT);
	echo mysql_error();
	?>
    <div id="class"></div>
    <table class="dati2">
 
          
    <?php
	
	if(mysql_affected_rows() == 0){echo "<tr>
        <td colspan=\"4\">Nessun Risultato</td></tr>";}
	$row = 0;
	while ($riga = mysql_fetch_array($risultato)) 
	{  
	
	
	//onclick="parentNode.removeChild(this);"
	?> 
      <tr>
<!--         <td><?php echo $sezione_link[$riga['sezione_link']]; ?></td>
-->         
      <td><?php if($riga['relation'] > 1) { echo @$categorie_link[$riga['relation']]; } ?><?php if($sezione_link_id == 46) echo " &gt; ".@$sottocategoria[$riga['sottocategoria']]; ?></td>
        <td><?php echo @$tipologia_quote[$riga['cat']]; ?></td>
        <?php if($_SESSION['usertype'] == 0){ ?> <td><?php echo $disponibilita_link[$riga['disponibilita_link']]; ?></td><?php } ?>
        <td><?php echo $tipo_link[$riga['tipo_link']]; ?></td>
		<td><input type="text" name="codice_link" data-rel="<?php echo $riga['id']; ?>" value="<?php echo $riga['codice_link']; ?>" class="updateField" /></td>
     	<td><input type="text" name="descrizione_link" data-rel="<?php echo $riga['id']; ?>" value="<?php echo $riga['descrizione_link']; ?>" class="updateField" /></td>
	    <td><input type="text" name="link" data-rel="<?php echo $riga['id']; ?>" value="<?php echo $riga['link']; ?>" class="updateField" /></td>
        <td><a href="<?php echo $riga['link']; ?>" title="Apri Link" onclick="javascript:window.open(this.href);return false;"><i class="fa fa-cloud-download"></i></a></td>
        <?php if($_SESSION['usertype'] == 0){ ?><td><a href="../mod_basic/action_elimina.php?gtx=<?php echo $tab_id; ?>&amp;unset=<?php echo $riga['id']; ?>" title="Elimina"  onclick="return conferma_del();"><i class="fa fa-trash-o"></i></a></td><?php } ?>
      
       
        
                  
      </tr>
  
	


    <?php } mysql_close(CONNECT); //Chiudo la Connessione	?>

 

 </table>
