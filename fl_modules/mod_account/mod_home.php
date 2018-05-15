

<div class="filtri" id="filtri"> 

<form method="get" action="" id="fm_filtri">
<h2> Filtri</h2>  

   <?php 
 
	foreach ($campi as $chiave => $valore) 
	{		
			if(in_array($chiave,$basic_filters)){
			
			echo '<div class="filter_box">';
			
			if(select_type($chiave) != 9 && select_type($chiave) != 19 && select_type($chiave) != 5 && select_type($chiave) != 2 && $chiave != 'id') { $cont = (isset($_GET[$chiave])) ? check($_GET[$chiave]) : ''; echo '<label>'.$valore.'</label><input type="text" name="'.$chiave.'" value="'.$cont.'" placeholder="'.$valore.'" />'; }
			
			if((select_type($chiave) == 2 || select_type($chiave) == 19 || select_type($chiave) == 9) && $chiave != 'id') {
				echo '  <label>'.$valore.'</label>';
				echo '<select name="'.$chiave.'" class="select"><option value="-1">Tutti</option>';
				foreach($$chiave as $val => $label) { $selected = (isset($_GET[$chiave]) && check(@$_GET[$chiave]) == $val) ? 'selected' : ''; echo '<option '.$selected.' value="'.$val.'">'.$label.'</option>'; }
				echo '</select>';
			}
			echo '</div>';
			
			} 
		
	}
	 ?>    
    <input type="submit" value="Mostra" class="salva" id="filter_set" />

</form>

</div>


<?php 

	$_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];

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
	$('#counter').val('Invia ' + totalChecked + ' notifiche');
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
    <th  class="center"><a href="./?ordine=3"></a></th>
        <th style="text-align: center;"><input onclick="checkAllFields(1);" id="checkAll"  name="checkAll" type="checkbox" /><label for="checkAll"><?php echo $checkRadioLabel; ?></label>
<th><a href="./?ordine=0">Nome</a></th>
    <th><a href="./?ordine=2">Tipo Account</a></th>
    <th><a href="./?ordine=1">Email</a></th>
    <th>Scadenza Password</th>
<!--    <th>Anagrafica</th>
-->    <th>Accessi</th>
    <th></th>
    <th>Creato il</th>
    </th>
  </tr>
  <?php
			$count = 0;

	if(mysql_affected_rows() == 0){echo " <tr>     <td colspan=\"10\">Nessun utente trovato</td></tr>";}
	
	
	//if(!isset($riga['processo_id'])) { mysql_query('ALTER TABLE `fl_account` ADD `persona_id` INT NULL AFTER `tipo` ',CONNECT); echo "DB Account Aggiornato!"; }
	 
	
	while ($riga = mysql_fetch_array($risultato)) 
	{  
	if($riga['attivo'] == 0) { $alert = "Non attivo"; $colore = "style=\"background: #DA3235; color: #FFF;\"";  } else { $alert = "Attivo"; $colore = "style=\"background: #3DA042; color: #FFF;\""; }
	$input = ($riga['id'] > 0 && filter_var($riga['email'], FILTER_VALIDATE_EMAIL)) ? '<input onClick="countFields(1);" type="checkbox" id="'.$riga['id'].'" name="destinatario[]" value="'.$riga['id'].'" /><label for="'.$riga['id'].'">'.$checkRadioLabel.'</label>' : '';
	
	$persona = @GRD('fl_persone',$riga['persona_id']);
	$profili = 0;
	if($profili > 0) { $gest_profili = "<a href=\"../mod_anagrafica/mod_inserisci.php?external&id=".$riga['anagrafica']."\">Anagrafica</a>"; } else { $gest_profili = "--"; }
	if($persona['id'] > 0) { $gest_persona = "&gt; <a href=\"../mod_persone/mod_inserisci.php?external&id=".$persona['id']."\">".$profilo_funzione[$persona['profilo_funzione']]."</a>"; } else { $gest_persona = "--"; }
	
	$date = strtotime($riga['aggiornamento_password']."+90 day"); 
	$password_update = giorni(date('Y-m-d',$date));	
	?>
  <tr>
    <td class="center" <?php echo $colore; ?>></td>
        <td style="text-align: center;"><?php echo $input; ?></td>

      <td><?php echo ucfirst($riga['nominativo']); ?></td>
      <td><span class="msg " <?php echo $colore; ?>><?php echo $alert; ?></span><span class="msg blue"><?php echo $tipo[$riga['tipo']]; ?><?php echo $gest_persona; ?></span><br>
      <?php echo $riga['motivo_sospensione']; ?></td>
 
  
    <td><a href="mailto:<?php echo $riga['email']; ?>" title="Invia Email" ><?php echo $riga['email']; ?></a></td>
    <td title="<?php echo mydate($riga['aggiornamento_password']); ?>"><?php echo $password_update; ?> giorni</td>
    <!--<td><?php echo $gest_profili; ?></td>-->
    <td><a href="../mod_accessi/?cerca=<?php echo $riga['user']; ?>" title="Mostra Accessi"><?php echo $riga['visite']; ?></a></td>
   <td> <a href="./mod_visualizza.php?id=<?php echo $riga['id']; ?>" title="Dettagli Account di <?php echo $riga['nominativo']; ?>"><i class="fa fa-search"></i></a>
      <?php if($_SESSION['usertype'] < 1){ ?>
      <a href="../mod_basic/action_elimina.php?gtx=<?php echo  $tab_id; ?>&unset=<?php echo $riga['id']; ?>" title="Elimina" onclick="return conferma_del();"><i class="fa fa-trash-o"></i></a>
    <?php } ?>
      </td>
    <td><?php echo mydate($riga['data_creazione']); ?></td>
  </tr>
  <?php } //Chiudo la Connessione	?>
</table>
<?php 	$_SESSION['send'] = 1;
echo '<p style="text-align: right; padding: 20px 0;">
<input type="submit" id="counter" value="Invia '.$count.' notifiche " class="button">
</p></form>'; 


$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main);  mysql_close(CONNECT); } else { include('mod_user.php'); } ?>
<br class="clear">
<p>
Versione: 2.1.2 |  Utente: <strong><?php echo $_SESSION['user']; ?></strong> |  IP: <?php echo $_SERVER['REMOTE_ADDR'] ?> | 
<strong>GDPR 2016/679 Informativa sul trattamento dei dati riguardanti il vostro account</strong>
L'accesso alla piattaforma è da intendersi personale ed esclusivamente riservato all'utente autorizzato. 
Ne è vietata la riproduzione sotto ogni forma. La vostra password scade ogni 90 giorni e va reimpostata obbligatoriamente.
In conformità ai requisiti del DL 196 del 30/6/2003, 
si informa che ogni accesso, riconoscibile da IP e username, sarà registrato e potrà essere monitorato.
Le attività eseguite nell'ambiente gestionale sono registrate per motivi di sicurezza.
</p>