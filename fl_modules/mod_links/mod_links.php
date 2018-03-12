<?php 
	
	require_once('../../fl_core/autentication.php');
	include('fl_settings.php'); // Variabili Modulo 

	 
	
	$where = "WHERE id != 1  ";
	
	
	if(isset($relation) && $relation > 1) {	$where .= " AND relation = $relation "; } else if($sezione_link_id != 46){ $where .= " AND (relation < 2 OR relation = 17)  "; }
	
	if(isset($tipologia_quota) && $tipologia_quota > 35) {	$where .= " AND cat = $tipologia_quota "; }
	if(isset($periodo_quota) && $periodo_quota > 1) {	$where .= " AND periodo_quota = $periodo_quota "; }
	if(isset($sezione_link_id) && $sezione_link_id > 1) {	$where .= " AND sezione_link = $sezione_link_id "; }
	if($_SESSION['usertype'] > 0 && $sezione_link_id == 42 && !isset($_GET['pricarica'] )) {	$where .= " AND tipo = ".$_SESSION['usertype']; }
	if(isset($_GET['pricarica'])) {	$where .= " AND tipo = 3"; }
	
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
      <td><?php if($riga['relation'] > 1) { echo $categorie_link[$riga['relation']]; } ?><?php if($sezione_link_id == 46) echo " &gt; ".$sottocategoria[$riga['sottocategoria']]; ?></td>
        <td><?php echo $tipologia_quote[$riga['cat']]; ?></td>
       
        <?php if($_SESSION['usertype'] == 0){ ?> <td><?php echo $tipo_affiliazione[$riga['tipo']]; ?></td><?php } ?>
        <td><input type="text" name="descrizione_link" data-rel="<?php echo $riga['id']; ?>" value="<?php echo $riga['descrizione_link']; ?>" class="updateField" /></td>
        <td><input type="text" name="link" data-rel="<?php echo $riga['id']; ?>" value="<?php echo $riga['link']; ?>" class="updateField" /></td>
       
        <td><a href="<?php echo $riga['link']; ?>" title="Apri Link" onclick="javascript:window.open(this.href);return false;"><i class="fa fa-cloud-download"></i></a></td>
        <?php if($_SESSION['usertype'] == 0){ ?><td><a href="../mod_basic/action_elimina.php?gtx=<?php echo $tab_id; ?>&amp;unset=<?php echo $riga['id']; ?>" title="Elimina"  onclick="return conferma_del();"><i class="fa fa-trash-o"></i></a></td><?php } ?>
      
       
        
                  
      </tr>
  
	


    <?php } mysql_close(CONNECT); //Chiudo la Connessione	?>

 

 </table>
