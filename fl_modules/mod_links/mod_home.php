

<div id="filtri" class="filtri">
<form id="form_0" class="admin_form" method="post" action="mod_opera.php" enctype="multipart/form-data">



<label for="categoria_id">Categoria:</label>
<select name="categoria_id" id="categoria_id" >
<?php foreach ($categorie as $chiave => $valore) {
  echo "<option value=\"$chiave\" $selected>$valore</option>";
  
}?>
</select>


<label for="link_cat_id">Sport:</label>

<select name="link_cat_id" id="link_cat_id">
<?php foreach ($link_cat as $chiave => $valore) {
  
  echo "<option value=\"$chiave\" $selected>$valore</option>";
  
}?>
</select>

<label for="marchio_id">Marchio:</label>
<select name="marchio_id" id="marchio_id">
<?php foreach ($marchio as $chiave => $valore) {
  echo "<option value=\"$chiave\" $selected>$valore</option>";
  
}?>
</select>

<label for="codice">Codice:</label>
<input name="codice" type="text" class="" id="codice" value="" size="20" maxlength="50" />

<label for="descrizione">Descrizione:</label>

<input name="descrizione" type="text" class="" id="descrizione" value="" size="20" maxlength="255" />
<label for="link">Link:</label><input name="link" type="text" class="" id="link" value="http://" size="20" maxlength="255" />
  <input type="submit" value="Inserisci" class="button" />
  </form>
  </div>
  
  <form id="form_1" class="admin_form" method="get" action="" enctype="multipart/form-data">
  <input type="hidden" name="categoria_id" value="<?php echo check($_GET['categoria_id']); ?>">
  <select name="link_cat_id" id="link_cat_id" onChange="form.submit()">
  <?php foreach ($link_cat as $chiave => $valore) {
    $selected = ($_GET['link_cat_id'] == $chiave) ? 'selected' : '';
    echo "<option value=\"$chiave\" $selected>$valore</option>";
    
  }?>
  </select>
  </form>
  
  
  
  <?php

  $sql_link_cat_id = (isset($_GET['link_cat_id'])) ? ' AND link_cat_id =  '.check($_GET['link_cat_id']) : '' ;
  
  $query = "SELECT * FROM $tabella WHERE categoria_id = ".check($_GET['categoria_id'])." $sql_link_cat_id  ORDER BY $ordine ";
  $risultato = mysql_query($query, CONNECT);
  echo mysql_error();
  ?>
  <div id="class"></div>
  <table class="dati2">
  
  
  <?php
  
  if (mysql_affected_rows() == 0) {echo "<tr>
    <td colspan=\"4\">Nessun Risultato</td></tr>";}
    $row = 0;
    while ($riga = mysql_fetch_array($risultato)) {
      
      ?>
      <tr>

      <td><?php echo $categorie[$riga['categoria_id']]; ?></td>
      <td><?php echo $link_cat[$riga['link_cat_id']]; ?></td>
      <td><?php echo $marchio[$riga['marchio_id']]; ?></td>
      
      

      <td><input type="text" name="codice" data-rel="<?php echo $riga['id']; ?>" value="<?php echo $riga['codice']; ?>" class="updateField" /></td>
      <td><input type="text" name="descrizione" data-rel="<?php echo $riga['id']; ?>" value="<?php echo $riga['descrizione']; ?>" class="updateField" /></td>
      <td><input type="text" name="link" data-rel="<?php echo $riga['id']; ?>" value="<?php echo $riga['link']; ?>" class="updateField" /></td>
      <td><a href="<?php echo $riga['link']; ?>" title="Apri Link" onclick="javascript:window.open(this.href);return false;"><i class="fa fa-tv"></i></a></td>
      <?php if ($_SESSION['usertype'] == 0) {?><td><a href="../mod_basic/action_elimina.php?gtx=<?php echo $tab_id; ?>&amp;unset=<?php echo $riga['id']; ?>" title="Elimina"  onclick="return conferma_del();"><i class="fa fa-trash-o"></i></a></td><?php }?>
      
      
      
      
      </tr>
      
      
      
      
      <?php }
      mysql_close(CONNECT); //Chiudo la Connessione    ?>
      
      
      
      </table>
      