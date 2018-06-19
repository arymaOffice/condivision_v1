<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
unset($_SESSION['destinatari']);
$id = check($_GET['id']);


$tabella     = $tables[$tab_id];

if($id != 1){
$changeproprietario =  (isset($_GET['aAt'])) ? '`status_potential` = 1, `proprietario` = '.$_SESSION['number'].' ,  `operatore` =  '.$_SESSION['number'] : ' `operatore` =  '.$_SESSION['number'].'';
$query = "UPDATE `$tabella` SET  $changeproprietario WHERE id = $id;";
mysql_query($query,CONNECT);
}


$potential = GRD($tabella,$id); 
//if($id > 1 && $potential['is_customer'] > 1) { $tab_div_labels['../mod_anagrafica/mod_veicoli.php?potential_id=[*ID*]&parent_id='.$potential['is_customer']] = 'Veicoli Acquistati'; }
//if($id > 1 && $potential['is_customer'] < 2) {
$tab_div_labels['../mod_anagrafica/mod_veicoli.php?workflow_id='.$tab_id.'&parent_id=[*ID*]'] = 'Veicoli'; //}
if($id > 1 && $potential['id_cliente_drakkar'] > 1) { $tab_div_labels['../mod_basic/mod_questions.php?workflow_id='.$tab_id.'&record_id=[*ID*]'] = 'Recall Consegna'; }
if($id > 1 && $potential['id_cliente_drakkar'] < 2) { $tab_div_labels['../mod_basic/mod_questions_preventivi.php?workflow_id=69&record_id=[*ID*]'] = 'Recall Preventivi'; }


$_SESSION['destinatari'] = $id;



include("../../fl_inc/headers.php"); include("../../fl_inc/testata_mobile.php");
 ?>


<body style=" background: rgb(241, 241, 241) none repeat scroll 0% 0%;">
<div id="container" >
<div id="content_scheda">
<?php 
$status = '';

if(isset($potential) && $id > 1) { 
		$colore = '';
		if($potential['status_potential'] == 0) { $colore = "class=\" msg tab_blue\"";  }
		if($potential['status_potential'] == 1) { $colore = "class=\"msg tab_orange\"";  }
		if($potential['status_potential'] == 2) { $colore = "class=\"msg tab_orange\"";  }
		if($potential['status_potential'] == 3) { $colore = "class=\"msg tab_red\"";  } 
		if($potential['status_potential'] == 4)  { $colore = "class=\"msg tab_green\"";  }
		if($potential['status_potential'] == 5)  { $colore = "class=\"msg tab_orange\"";  }
		if($potential['status_potential'] == 6) { $colore = "class=\"msg tab_red\"";  } 
		if($potential['status_potential'] == 7)  { $colore = "class=\"msg tab_orange\"";  }

$status = '<span '.$colore.'>'.@$status_potential[@$potential['status_potential']].'</span>'; }  ?>


<?php if($potential['in_use'] > 0 && $potential['in_use'] != $_SESSION['number']) { echo "<h1 class=\"red\" id=\"esito\"><strong>In uso da ".$proprietario[$potential['in_use']]."</strong></h1><br >".'<a href="mod_opera.php?id='.$potential['id'].'&unlock" class="touch gray_push"><i class="fa fa-unlock-alt"></i> <br>Sblocca</a>'; 

} else { ?>


<?php 

if($id != 1){




$telefono = phone_format($potential['telefono'],'39');
$telefonoAlternativo = phone_format($potential['telefono_alternativo'],'39');




$lead_generator_string =($potential['lead_generator'] > 1) ?  @$proprietario[$potential['lead_generator']] : 'Nessuno';
$callcenter =($potential['proprietario'] > 1) ?  @$proprietario[$potential['proprietario']] : 'Nessuno';
$venditore_string = ($potential['venditore'] > 1) ? @$proprietario[$potential['venditore']] : 'Nessuno';


echo '<div class="info_dati"><h1 style="display: inline-block; margin: 0 0 5px;" class="nominativo">'.$potential['id'].' <strong>'.$potential['nome'].' '.$potential['cognome'].'</strong></h1> ';
echo '<span style="margin: 5px 0px;"><i class="fa fa-phone" style="padding: 3px;"></i> <a href="tel:'.@$telefono.'" class="setAction" style="color: black;" data-gtx="'.base64_encode($tab_id).'" data-id="'.base64_encode($potential['id']).'" data-azione="2"  data-esito="2" data-note="Avvio Chiamata">'.@$telefono.'</a> <i class="fa fa-phone" style="padding: 3px;"></i> <a href="tel:'.@$telefonoAlternativo.'" class="setAction" style="color: black;" data-gtx="'.base64_encode($tab_id).'" data-id="'.base64_encode($potential['id']).'" data-azione="2"  data-esito="2" data-note="Avvio Chiamata">'.@$telefonoAlternativo.'</a> <i class="fa fa-envelope-o" style="padding: 3px;"></i> <a style="color: black;" href="mailto:'.@$potential['email'].'" class="setAction" data-gtx="'.base64_encode($tab_id).'" data-id="'.base64_encode($potential['id']).'" data-azione="3"  data-esito="5"  data-note="Avvio Composizione Email">'.@$potential['email'].'</a></span> <br>';


$synapsy = '';
    
$query = 'SELECT * FROM `fl_synapsy` WHERE (`type1` = '.$tab_id.' OR `type2` = '.$tab_id.') AND (`id1` = '.$id.' OR `id2` = '.$id.')';
    
$parentele = mysql_query($query);
if(mysql_affected_rows() > 0) {
      $synapsy = '<span class="msg"><i class="fa fa-link"></i></a>';
      while ($parente = mysql_fetch_array($parentele)){   
      $record_rel = ($parente['id1'] == $id) ? $parente['id2'] : $parente['id1'];
      $nominativocorrelato = GRD($tabella,$record_rel);
      $synapsy .= ' <a href="../mod_leads/mod_inserisci.php?id='.$record_rel .'">'.$nominativocorrelato['nome'].' '.$nominativocorrelato['cognome'].'</a> <a href="mod_opera.php?disaccoppia='.$parente['id'].'" class="c-red">[x]</a>'; }
      $synapsy .= '</span>'; 
    }
    if(isset($_SESSION['synapsy'])) {
       $synapLead = ($_SESSION['synapsy'] != $id) ? ' <a href="mod_opera.php?connect='.$id.'" style="color: #E84B4E;"><i class="fa fa-link" aria-hidden="true"></i></a>' : ' Collegamento Lead (Vai in lista lead per scegliere un lead!) ' ;
    } else {
       $synapLead = ' <a href="mod_opera.php?synapsy='.$id.'"> Associa un lead <i class="fa fa-link" aria-hidden="true"></i></a>';
    }


echo $status.$synapsy.$synapLead.' <br><br> <strong>LEAD GENERATOR</strong>: '.$lead_generator_string.' <br><br><strong>BDC</strong>:'.$callcenter.' <br><br><strong>CONSULENTE VENDITA</strong>:'.$venditore_string;
?>
<!--<?php if($_SESSION['usertype'] == 0) { ?><p><a href="mod_opera.php?id=<?php echo $potential['id']; ?>&unlock" class="setAction" data-gtx="<?php echo base64_encode($tab_id); ?>" data-id="<?php echo base64_encode($potential['id']); ?>" data-azione="4"  data-esito="0" data-note="Unlocked"><i class="fa fa-unlock-alt"></i> Unlock</a></p><?php } ?>
-->

</div>
<div id="set-buttons">
<!--<a href="mod_richiesta.php?tipo_richiesta=0&parent_id=<?php echo $potential['id']; ?>" title="Registra Chiamata" data-fancybox-type="iframe" class="fancybox_small touch blue_push"><i class="fa fa-phone"></i> <br>Call</a>
<a href="mod_richiesta.php?tipo_richiesta=1&parent_id=<?php echo $potential['id']; ?>" title="Registra Invio Email" data-fancybox-type="iframe" class="fancybox_small touch blue_push"><i class="fa fa-envelope"></i> <br>Inviata Mail</a>
<a href="mod_richiesta.php?tipo_richiesta=3&parent_id=<?php echo $potential['id']; ?>"  data-fancybox-type="iframe" class="fancybox_small touch red_push setAction" title="Registra Quotazione Rifiutata"><i class="fa fa-hand-o-left"></i> <br>Non Interessato</a>
<a href="mod_richiesta.php?tipo_richiesta=4&parent_id=<?php echo $potential['id']; ?>"  data-fancybox-type="iframe" class="fancybox_small touch red_push setAction" title="Registra Perdita"><i class="fa fa-thumbs-down"></i> <br>Concorrenza</a>
<a href="mod_richiesta.php?tipo_richiesta=5&parent_id=<?php echo $potential['id']; ?>"  data-fancybox-type="iframe" class="fancybox_small touch green_push setAction" title="Regitra Vittoria!"><i class="fa fa-check-square-o"></i> <br>Vendita</a>


<a href="skype:<?php echo @$telefono; ?>?call" class="touch blue_push setAction" data-gtx="<?php echo base64_encode($tab_id); ?>" data-id="<?php echo base64_encode($potential['id']); ?>" data-azione="4" data-esito="2" data-note="Chiamata Eseguita"><i class="fa fa-phone"></i> <br>Chiama</a>
<a href="mod_opera.php?id=<?php echo $potential['id']; ?>&notanswered=1" class="touch orange_push setAction" data-gtx="<?php echo base64_encode($tab_id); ?>" data-id="<?php echo base64_encode($potential['id']); ?>" data-azione="2"  data-esito="3" data-note="Non Risponde"><i class="fa fa-hand-o-left"></i> <br>Non Risponde</a>
<a href="mod_opera.php?id=<?php echo $potential['id']; ?>&status_potential=3" class="touch red_push setAction" data-gtx="<?php echo base64_encode($tab_id); ?>" data-id="<?php echo base64_encode($potential['id']); ?>" data-azione="2"  data-esito="4" data-note="CAMPAGNA ID"><i class="fa fa-thumbs-down"></i> <br>Non Interessato</a>
-->
<a href="<?php echo ROOT.$cp_admin; ?>fl_modules/mod_appuntamenti/mod_user.php?j=<?php echo base64_encode($potential['id']); ?>" class="touch blue_push"><i class="fa fa-calendar"></i> <br>Appuntamento</a>
<a href="<?php echo ROOT.$cp_admin; ?>fl_modules/mod_preventivi/mod_inserisci.php?POiD=<?php echo base64_encode($potential['id']); ?>&id=1" class="touch orange_push " data-gtx="<?php echo base64_encode($tab_id); ?>" data-id="<?php echo base64_encode($potential['id']); ?>" data-azione="7"  data-esito="1"  data-note="Preventivo"><i class="fa fa-pencil-square-o"></i> <br>Preventivo</a>
<?php if($potential['is_customer'] < 2) {?> <a href="<?php echo ROOT.$cp_admin; ?>fl_modules/mod_anagrafica/mod_inserisci.php?id=1&j=<?php echo base64_encode($potential['id']); ?>" class="touch green_push " data-gtx="<?php echo base64_encode($tab_id); ?>" data-id="<?php echo base64_encode($potential['id']); ?>" data-azione="6"  data-esito="5" data-note="Conversione Cliente"><i class="fa fa-check-square-o"></i> <br>Converti in cliente</a><?php } ?>
<?php if($potential['is_customer'] > 1) {?> <a href="<?php echo ROOT.$cp_admin; ?>fl_modules/mod_anagrafica/mod_inserisci.php?id=<?php echo $potential['is_customer']; ?>" class="touch green_push" ><i class="fa fa-check-square-o"></i> <br>Anagrafica cliente</a><?php } ?></div>
<textarea cols="3" name="note" class="updateField" id="noteRisalto" placeholder="Note:" data-rel="<?php echo $potential['id']; ?>"><?php echo strip_tags(converti_txt($potential['note'])); ?></textarea>
<?php } ?>





<?php if(isset($_GET['esito'])) { $class = (isset($_GET['success'])) ? 'green' : 'red'; echo '<p class="esito '.$class.'">'.check($_GET['esito']).'</p>'; }  ?>

<script type="text/javascript">
  function iframeLoaded(idIframe) {
      var iFrameID = document.getElementById(idIframe);
      if(iFrameID) {
            // here you can make the height, I delete it first, then I make it again
            iFrameID.height = "";
            iFrameID.height = iFrameID.contentWindow.document.body.scrollHeight + "px";
      }   
  }
</script>   
<div id="map-canvas"></div>



<form id="scheda" action="../mod_basic/action_modifica.php" method="post" enctype="multipart/form-data">
<?php if(isset($_GET['copy_record'])) { echo '<input type="hidden" name="copy_record" value="1" />
<div class="msg orange">ATTENZIONE! Stai creando una copia di questo elemento</div>' ;
} ?>

<?php 
if($id != 1){
$_SESSION['destinatari'] = array($id);
?>
<div id="strumenti_crm" >
<a href="mod_richiesta.php?tipo_richiesta=0&parent_id=<?php echo $potential['id']; ?>" title="Registra Chiamata" data-fancybox-type="iframe" class="fancybox_small touch blue_push"><i class="fa fa-phone"></i> <br>Call</a>
<a href="mod_invia_mail.php<?php echo "?".$_SERVER['QUERY_STRING']; ?>" title="Registra Invio Email" data-fancybox-type="iframe" class="fancybox_small touch blue_push"><i class="fa fa-envelope"></i> <br>Invia Mail</a>
<a href="mod_invia_sms.php<?php echo "?".$_SERVER['QUERY_STRING']; ?>" data-fancybox-type="iframe" class="fancybox_small touch blue_push"><i class="fa fa-envelope"></i> <br>Invia SMS</a>
<!--<a href="mod_richiesta.php?tipo_richiesta=2&parent_id=<?php echo $potential['id']; ?>"  data-fancybox-type="iframe" class="fancybox_small touch orange_push setAction" title="Imposta Follow Up"><i class="fa fa-calendar"></i> <br>Follow up</a>
--><a href="mod_richiesta.php?status_potential=3&tipo_richiesta=3&parent_id=<?php echo $potential['id']; ?>"  data-fancybox-type="iframe" class="fancybox_small touch red_push setAction" title="Non interessato"><i class="fa fa-hand-o-left"></i> <br>Non interessato</a>
<a href="mod_richiesta.php?status_potential=6&tipo_richiesta=4&parent_id=<?php echo $potential['id']; ?>"  data-fancybox-type="iframe" class="fancybox_small touch red_push setAction" title="Passato a Concorrenza"><i class="fa fa-thumbs-down"></i> <br>Concorrenza</a>
<a href="mod_richiesta.php?tipo_richiesta=2&parent_id=<?php echo $potential['id']; ?>"  data-fancybox-type="iframe" class="fancybox_small touch orange_push setAction" title="Imposta Follow Up"><i class="fa fa-share"></i><br>Follow up</a>
</div>
<?php }  ?>
<?php include('../mod_basic/action_estrai.php');  ?>

<?php if($id == 1 && defined('assegnazione_automatica')){  ?><input type="hidden" name="function" value="assegnazione_automatica" /><?php } ?>


</form>

<?php } ?>

<script type="text/javascript">
/*Avvio*/
$(document).ready(function() {

function loadProvince(from,where){ 

  var post = 'sel=provincia&filtro=regione&valore='+$(from).val();
  if(from == 0) post = 'sel=provincia&filtro=regione';
  var url = '../mod_basic/mod_selectLoader.php';
  var posting = $.post(url,post); 
  posting.fail(function( data ) {     });
  posting.always(function( data ) {    });
  posting.done(function( response ) {
    console.log(post);
	if(from != 0) $(where).empty();
    var data = $.parseJSON(response);
	  $.each(data, function(i, value) {
           $(where).append('<option = "'+value+">"+value+"</option>");
		   $(where).focus();
        });
   });
}

function loadComuni(from,where){ 
  var post = 'sel=comune&filtro=provincia&valore='+$(from).val();
  var url = '../mod_basic/mod_selectLoader.php';
  var posting = $.post(url,post); 
  posting.fail(function( data ) {     });
  posting.always(function( data ) {    });
  posting.done(function( response ) {
   console.log(post);
	$(where).empty();
    var data = $.parseJSON(response);
	  $.each(data, function(i, value) {
           $(where).append('<option = "'+value+">"+value+"</option>");
		   $(where).focus();
        });
   });
}

function loadCap(from,where){ 
  var post = 'sel=cap&filtro=comune&valore='+$(from).val();
  var url = '../mod_basic/mod_selectLoader.php';
  var posting = $.post(url,post); 
  posting.fail(function( data ) {     });
  posting.always(function( data ) {    });
  posting.done(function( response ) {
    //console.log(response);
	$(where).empty();
    var data = $.parseJSON(response);
	  $.each(data, function(i, value) {
           $(where).val(value);
		   $(where).focus();
        });
   });
}




loadProvince(0,'#provincia');

$('#provincia').change(function(){
 	loadComuni('#provincia','#citta');
});

$('#citta').change(function(){
 	loadCap('#citta','#cap');
});




});</script>


</div>
</div></body></html>
