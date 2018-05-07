      	

<div class="filtri"> 
    
 </div>  
	<?php 
	$_SESSION['POST_BACK_PAGE'] = "https://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];;

	if($_SESSION['usertype'] == 0) {

	if(isset($_GET['ordine'])) { if(!is_numeric($_GET['ordine'])){ exit; } else { $ordine = $ordine_mod[$_GET['ordine']]; }}


	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0);
	
	$query = "SELECT * FROM $tabella $tipologia_main ORDER BY $ordine LIMIT $start,$step";
	
	$risultato = mysql_query($query, CONNECT);
	
	?>
     <script type="text/javascript">
function checkAllFields(ref)
{
var chkAll = document.getElementById('checkAll');
var checks = document.getElementsByName('destinatario[]');
var boxLength = checks.length;
var allChecked = false;
var totalChecked = 0;
	if ( ref == 1 )
	{
		if ( chkAll.checked == true )
		{
			for ( i=0; i < boxLength; i++ )
			checks[i].checked = true;
		}
		else
		{
			for ( i=0; i < boxLength; i++ )
			checks[i].checked = false;
		}
	}
	else
	{
		for ( i=0; i < boxLength; i++ )
		{
			if ( checks[i].checked == true )
			{
			allChecked = true;
			continue;
			}
			else
			{
			allChecked = false;
			break;
			}
		}
		if ( allChecked == true )
		chkAll.checked = true;
		else
		chkAll.checked = false;
	}
	for ( j=0; j < boxLength; j++ )
	{
		if ( checks[j].checked == true )
		totalChecked++;
	}
	countFields(1);
}
  
  
  function countFields(ref)
{
var checks = document.getElementsByName('destinatario[]');
var boxLength = checks.length;
var totalChecked = 0;
	for ( j=0; j < boxLength; j++ )
	{
		if ( checks[j].checked == true )
		totalChecked++;
	}
	$('#counter').val('Scrivi a ' + totalChecked + ' mail');
}

$(function() {

    $("#scrivi").submit(function() {

        $form = $(this);

        $.fancybox({
                'title': "Invio notifica",
                'href': $form.attr("action") + "?" + $form.serialize(),
                'type': 'iframe'
        });

        return false;

    });


});

    </script>

     <?php if(isset($_GET['esito'])) { 

$class = (isset($_GET['success'])) ? 'green' : 'red'; echo '<p class="esito '.$class.'">'.check($_GET['esito']).'</p><p style="text-align: center;"></p>'; }  ?>




<form action="../mod_notifiche/mod_invia.php" id="scrivi" method="get">
    <table class="dati" summary="Dati">
      <tr>
       <th  class="center"><a href="./?ordine=3">Stato</a></th>
        <th><a href="./?ordine=2">Tipo Account</a></th>
       <th  class="center"><a href="./?ordine=5">User</a></th>
       <th><a href="./?ordine=0">Motivo Sospensione</a></th>
       <th><a href="./?ordine=1">Email</a></th>
      
      <th>Aggiornamento Password</th>
       <th>Anagrafica</th>
      
       <th>Accessi</th>
       
        <th></th>
		<th>Creato il</th>
           <th><input onclick="checkAllFields(1);" id="checkAll" style="display: block;"  name="checkAll" type="checkbox" checked /> </th>

      </tr>
          
    <?php
			$count = 0;

	if(mysql_affected_rows() == 0){echo " <tr>     <td colspan=\"10\">Nessun utente trovato</td></tr>";}
	
	while ($riga = mysql_fetch_array($risultato)) 
	{  
	if($riga['attivo'] == 0) { $alert = "Non attivo"; $colore = "style=\"background: #FF3A17; color: #FFF;\"";  } else { $alert = "Attivo"; $colore = "style=\"background: #009900; color: #FFF;\""; }
					$input = ($riga['id'] > 0 && filter_var($riga['email'], FILTER_VALIDATE_EMAIL)) ? '<input style="display: block;"  onClick="countFields(1);" type="checkbox" name="destinatario[]" value="'.$riga['id'].'" checked="checked" />' : '';
				($riga['id'] > 0 && filter_var($riga['email'], FILTER_VALIDATE_EMAIL)) ? $count++ : 0;

	$query = "SELECT * FROM `fl_anagrafica` WHERE `id` =".$riga['anagrafica']." LIMIT 1";
	$risultato2 = mysql_query($query, CONNECT);
    $profili = mysql_affected_rows();
	if($profili > 0) { $gest_profili = "<a href=\"../mod_anagrafica/mod_inserisci.php?external&id=".$riga['anagrafica']."\">Anagrafica</a>"; } else { $gest_profili = "--"; }
		$password_update = giorni($riga['aggiornamento_password']);

	
	?> 
    
     
      <tr>
      
         <td class="center" <?php echo $colore; ?>><?php echo $alert; ?></td>
          <td><?php echo $tipo[$riga['tipo']]; ?></td>
         <td class="center"><?php echo $riga['user']; ?></td>
         <td><?php echo $riga['motivo_sospensione']; ?></td>
         <td><a href="mailto:<?php echo $riga['email']; ?>" title="Invia Email" ><?php echo $riga['email']; ?></a></td>
          <td title="<?php echo mydate($riga['aggiornamento_password']); ?>"><?php echo $password_update; ?> giorni fa.</td>
         <td><?php echo $gest_profili; ?></td>
         
         <td><a href="../mod_accessi/?cerca=<?php echo $riga['user']; ?>" title="Mostra Accessi"><?php echo $riga['visite']; ?></a></td>
       
         <td><a href="./mod_visualizza.php?id=<?php echo $riga['id']; ?>" title="Dettagli Account di <?php echo $riga['nominativo']; ?>"><i class="fa fa-search"></i></a></td>
      <!--     <td><a href="../mod_basic/elimina.php?gtx=<?php echo  $tab_id; ?>&unset=<?php echo $riga['id']; ?>" title="Elimina" onclick="conferma_del();"><i class="fa fa-trash-o"></i></a></td> -->    <td><?php echo mydate($riga['data_creazione']); ?></td>
         			<td><span class="Gletter"><?php echo $input; ?> </span></td> 

  	</tr>


    <?php } //Chiudo la Connessione	?>
    
 </table>
 
 <?php 	$_SESSION['send'] = 1;
echo '<p style="text-align: right; padding: 20px 0;">
<input type="submit" id="counter" value="Scrivi a '.$count.' mail " class="button">
</p></form>'; 


$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main);  mysql_close(CONNECT); } else { include('mod_user.php'); } ?>
 