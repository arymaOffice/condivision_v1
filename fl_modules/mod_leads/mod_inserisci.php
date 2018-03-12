<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
unset($_SESSION['destinatari']);
$id = check($_GET['id']);
$potential = GRD($tabella,$id); 
$_SESSION['destinatari'] = $id;
include("../../fl_inc/headers.php"); include("../../fl_inc/testata_mobile.php");
 ?>


<body style=" background: rgb(241, 241, 241) none repeat scroll 0% 0%;">
<div id="container" >

<div id="content_scheda">
<?php if($id == 1) echo '<h1>Crea un nuovo contatto</h1>';  ?>
<?php 
$status = '';

if(isset($potential) && $id > 1) { 
		
    $colore = '';
		if($potential['status_potential'] == 0) { $colore = "class=\" msg tab_blue\""; echo '<style>#box_data_visita { display: none; </style>' ; }
		if($potential['status_potential'] == 1) { $colore = "class=\"msg tab_orange\"";  }
		if($potential['status_potential'] == 2) { $colore = "class=\"msg tab_orange\"";  }
		if($potential['status_potential'] == 3) { $colore = "class=\"msg tab_red\"";  } 
		if($potential['status_potential'] == 4)  { $colore = "class=\"msg tab_green\"";  }
		if($potential['status_potential'] == 5)  { $colore = "class=\"msg tab_orange\"";  }
		if($potential['status_potential'] == 6) { $colore = "class=\"msg tab_red\"";  } 
		if($potential['status_potential'] == 7)  { $colore = "class=\"msg tab_orange\"";  }

$status = '<span '.$colore.'>'.@$status_potential[$potential['status_potential']].'</span>'; 

} else { 
if(!isset($_GET['status_potential']) || check($_GET['status_potential']) != 1) echo '<style>#box_data_visita { display: none; </style>'; 
}

if($potential['in_use'] > 0 && $potential['in_use'] != $_SESSION['number']) { 

  echo "<h1 class=\"red\" id=\"esito\"><strong>In uso da ".$proprietario[$potential['in_use']]."</strong></h1><br >".'<a href="mod_opera.php?id='.$potential['id'].'&unlock" class="touch gray_push"><i class="fa fa-unlock-alt"></i> <br>Sblocca</a>'; 

} else { ?>


<?php 
if($id != 1){
$changestatus = ($potential['status_potential'] < 2) ? 'status_potential = 1 , `in_use` = '.$_SESSION['number'].' ,' : '' ;


$telefono = phone_format($potential['telefono'],'39');
$social = ' <span class="social_icons" style= "font-size: 100%;"><a href="https://www.linkedin.com/commonSearch?type=people&keywords='.$potential['nome'].'%20'.$potential['cognome'].'" target="_blank" title="Cerca questo contatto su Linkedin"><i class="fa fa-linkedin-square"></i></a>
<a href="https://www.facebook.com/search/top/?q='.$potential['nome'].'%20'.$potential['cognome'].'&init=mag_glass"  target="_blank" title="Cerca questo contatto su Facebook"><i class="fa fa-facebook-square"></i></a>
<a href="https://twitter.com/search?q='.$potential['nome'].'%20'.$potential['cognome'].'&src=typd"  target="_blank" title="Cerca questo contatto su Twitter"><i class="fa fa-twitter-square"></i></a>
</span>';
echo '<div class="info_dati"><h1 style="display: inline-block; margin: 0 0 5px;"><strong>'.$potential['nome'].' '.$potential['cognome'].'</strong></h1> ';
echo '<p style="margin: 5px 0px;"><i class="fa fa-phone" style="padding: 3px;"></i> <a href="tel:'.@$telefono.'" class="setAction" style="color: black;" data-gtx="'.base64_encode($tab_id).'" data-id="'.base64_encode($potential['id']).'" data-azione="2"  data-esito="2" data-note="Avvio Chiamata">'.@$telefono.'</a> <i class="fa fa-envelope-o" style="padding: 3px;"></i> <a style="color: black;" href="mailto:'.@$potential['email'].'" class="setAction" data-gtx="'.base64_encode($tab_id).'" data-id="'.base64_encode($potential['id']).'" data-azione="3"  data-esito="5"  data-note="Avvio Composizione Email">'.@$potential['email'].'</a></p>';
echo $status.$social;
?>
<!--<?php if($_SESSION['usertype'] == 0) { ?><p><a href="mod_opera.php?id=<?php echo $potential['id']; ?>&unlock" class="setAction" data-gtx="<?php echo base64_encode($tab_id); ?>" data-id="<?php echo base64_encode($potential['id']); ?>" data-azione="4"  data-esito="0" data-note="Unlocked"><i class="fa fa-unlock-alt"></i> Unlock</a></p><?php } ?>
-->
<textarea cols="3" name="note" class="updateField" id="noteRisalto" placeholder="Note:" data-rel="<?php echo $potential['id']; ?>"><?php echo strip_tags(converti_txt($potential['note'])); ?></textarea>
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
<a href="<?php echo ROOT.$cp_admin; ?>fl_modules/mod_appuntamenti/?action=23&meeting_location=0&a=crm&b=crm&potential_rel=<?php echo $potential['id']; ?>" class="touch blue_push setAction" data-gtx="<?php echo base64_encode($tab_id); ?>" data-id="<?php echo base64_encode($potential['id']); ?>" data-azione="5"  data-esito="1"  data-note="Appuntamento"><i class="fa fa-calendar"></i> <br>Crea Appuntamento</a>
<a href="<?php echo ROOT.$cp_admin; ?>fl_modules/mod_modelli/mod_catalogo.php?tipo_modello=1&POiD=<?php echo base64_encode($potential['id']); ?>&id=1" class="touch orange_push setAction" data-gtx="<?php echo base64_encode($tab_id); ?>" data-id="<?php echo base64_encode($potential['id']); ?>" data-azione="7"  data-esito="1"  data-note="Preventivo"><i class="fa fa-pencil-square-o"></i> <br>Crea Offerta</a>
<a href="<?php echo ROOT.$cp_admin; ?>fl_modules/mod_eventi/?lead_id=<?php echo $potential['id']; ?>" class="touch green_push setAction" data-gtx="<?php echo base64_encode($tab_id); ?>" data-id="<?php echo base64_encode($potential['id']); ?>" data-azione="5"  data-esito="1"  data-note="Creato Evento"><i class="fa fa-calendar"></i> <br>Crea Evento</a>
<!--<a href="mod_richiesta.php?tipo_richiesta=0&parent_id=<?php echo $potential['id']; ?>" title="Registra Chiamata" data-fancybox-type="iframe" class="fancybox_small touch blue_push"><i class="fa fa-phone"></i> <br>Call</a>

<?php if(0 || $potential['is_customer'] < 2) {?> <a href="<?php echo ROOT.$cp_admin; ?>fl_modules/mod_anagrafica/mod_inserisci.php?id=1&j=<?php echo base64_encode($potential['id']); ?>" class="touch green_push setAction" data-gtx="<?php echo base64_encode($tab_id); ?>" data-id="<?php echo base64_encode($potential['id']); ?>" data-azione="6"  data-esito="5" data-note="Conversione Cliente"><i class="fa fa-check-square-o"></i> <br>Nuovo cliente</a><?php } ?>
<?php if(0 || $potential['is_customer'] > 1) {?> <a href="<?php echo ROOT.$cp_admin; ?>fl_modules/mod_anagrafica/mod_inserisci.php?id=<?php echo $potential['is_customer']; ?>" class="touch green_push" ><i class="fa fa-check-square-o"></i> <br>Anagrafica cliente</a><?php } ?></div>
-->
<?php 

$evento = GQD('fl_eventi_hrc','*',' lead_id = '.$id.' ORDER BY data_evento DESC LIMIT 1 '); 
$selectDataRevisione = "SELECT DATE_FORMAT(data_creazione,'%d/%m/%Y %H:%i') as data FROM fl_revisioni_hrc WHERE evento_id = ".$evento['id']." ORDER BY id DESC  LIMIT 1";
$selectDataRevisione = mysql_query($selectDataRevisione,CONNECT);
$selectDataRevisione = mysql_fetch_assoc($selectDataRevisione);
$dataRevisione = (@$selectDataRevisione['data'] != '') ? @$selectDataRevisione['data'] : 'Nessuna Revisione' ;

if($evento['id'] > 1) {
echo "<div><a href=\"../mod_eventi/mod_inserisci.php?id=".$evento['id']."\" class=\"button\">Vai a Evento</a>
 ".$evento['titolo_ricorrenza']." ".mydatetime($evento['data_evento'])." (".$giorni_settimana[date("w", strtotime($evento['data_evento']))].")  | Coperti: ".($evento['numero_adulti']+$evento['numero_bambini']+$evento['numero_operatori'])." | Prezzo base: &euro; ".$evento['prezzo_base']." | Ultima Revisione: ".$dataRevisione." </div>";
}

?>

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



<?php include('../mod_basic/action_estrai.php');  ?>

<input type="hidden" name="dir_upfile" value="icone_articoli" />
<input type="hidden" name="reload" value="../mod_leads/mod_inserisci.php?id=" />

</form>

<?php } ?>

<script type="text/javascript">
/*Avvio*/
$(document).ready(function() {


$('.showCalDisp').click(function(){

  var $link;
  $link = '../mod_eventi/mod_stampa_calendario.php?anno='+$('#anno_di_interesse').val();
  for($i=0;$i<13;$i++) { if($('#mesi_di_interesse'+$i).is(':checked') == true) $link += '&mesi[]='+$i;  }
 
  $('input[name^="ambienti"]').each(function(){
     if($(this).is(':checked') == true ) $link += '&ambienti[]='+$(this).val(); 
  })
  console.log($link);
  window.open($link,0);
  
});


function loadProvince(from,where){ 

  //regione 
  var post = 'sel=provincia&filtro=&valore='+$(from).val();
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
           $(where).append('<option value="'+value+'">'+value+'</option>');
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
           $(where).append('<option value="'+value+'">'+value+'</option>');
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


function loadSelectIds(from,where){ 
  var post = 'gtx=105&numeric&sel=label&filtro=parent_id&valore='+$(from).val()+' AND tipo_voce = 1';
  var url = '../mod_basic/mod_selectLoader.php';
  var posting = $.post(url,post); 
  posting.fail(function( data ) {     });
  posting.always(function( data ) {    });
  posting.done(function( response ) {
  
  $(where).empty();
    var data = $.parseJSON(response);
     console.log(data);
    $.each(data, function(i, value) {
           $(where).append('<option value="'+i+'">'+value+'</option>');
        $(where).focus();
        });
   });
}



loadProvince(0,'#provincia');
loadSelectIds('#interessato_a','#centro_di_ricavo');

$('#interessato_a').change(function(){
  loadSelectIds('#interessato_a','#centro_di_ricavo');
});


$('#provincia').change(function(){
 	loadComuni('#provincia','#citta');
});

$('#citta').change(function(){
 	loadCap('#citta','#cap');
});




});</script>


</div>
</div></body></html>
