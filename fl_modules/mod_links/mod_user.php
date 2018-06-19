

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
 
	
	$where = "WHERE id != 1  ";
	
	
	if(isset($relation) && $relation > 1) {	$where .= " AND relation = $relation "; } else if($sezione_link_id != 46){ $where .= " AND (relation < 2 OR relation = 17)  "; }
	
	if(isset($tipologia_quota) && $tipologia_quota > 35) {	$where .= " AND cat = $tipologia_quota "; }
	if(isset($periodo_quota) && $periodo_quota > 1) {	$where .= " AND periodo_quota = $periodo_quota "; }
	if(isset($sezione_link_id) && $sezione_link_id > 1) {	$where .= " AND sezione_link = $sezione_link_id "; }
	//if($_SESSION['usertype'] > 0 && $sezione_link_id == 42 && !isset($_GET['pricarica'] )) {	$where .= " AND disponibilita_link = ".$_SESSION['usertype']; }
	//if(isset($_GET['pricarica'])) {	$where .= " AND disponibilita_link = 3"; }
	if(isset($disponibilita_link_id) && $disponibilita_link_id > 1) {	$where .= " AND disponibilita_link = $disponibilita_link_id "; }

	$query = "SELECT * FROM $tabella $where ORDER BY $ordine ";
	$risultato = mysql_query($query,CONNECT);
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
      <td><?php if($riga['relation'] > 1) { echo $categorie_link[$riga['relation']]; } ?><?php if($sezione_link_id == 46) echo " &gt; ".$sottocategoria[$riga['sottocategoria']]; ?></td>
        <td><?php echo $tipologia_quote[$riga['cat']]; ?></td>
       
        <?php if($_SESSION['usertype'] == 0){ ?> <td><?php echo $disponibilita_link[$riga['disponibilita_link']]; ?></td><?php } ?>
         <td><?php echo $tipo_link[$riga['tipo_link']]; ?></td>
        <td><?php echo $riga['descrizione_link']; ?></td>
      
        <td><a href="<?php echo $riga['link']; ?>" title="Apri Link" onclick="javascript:window.open(this.href);return false;"><i class="fa fa-cloud-download"></i></a></td>
        <?php if($_SESSION['usertype'] == 0){ ?><td><a href="../mod_basic/action_elimina.php?gtx=<?php echo $tab_id; ?>&amp;unset=<?php echo $riga['id']; ?>" title="Elimina"  onclick="return conferma_del();"><i class="fa fa-trash-o"></i></a></td><?php } ?>
      
       
        
                  
      </tr>
  
	


    <?php } mysql_close(CONNECT); //Chiudo la Connessione	?>

 

 </table>


