<?php 

require_once('../../fl_core/autentication.php');

$id = check($_GET['id']);

$lead_id = (isset($_GET['lead_id'])) ? check($_GET['lead_id']) : 1;
$GeneraContratto = '#';
$GeneraAllegato = '#';


include('fl_settings.php'); // Variabili Modulo 

if($id > 1) { 
$evento = GRD($tabella,$id); 
$_SESSION['last_managed'] = array('id'=>$id,'name'=>$evento['titolo_ricorrenza'],'link'=>ROOT.'fl_modules/mod_eventi/mod_inserisci.php?id='.$id);
$lead_id = $evento['lead_id']; // ID del lead
$cliente = $evento['anagrafica_cliente']; //cliente 1
$cliente2 = $evento['anagrafica_cliente2']; //cliente 2
$GeneraContratto = "mod_stampa.php?id=".$id;
if($evento['tipo_evento'] != 9  && $evento['tipo_evento'] != 5) $GeneraContratto = "mod_stampa.php?modello=2&id=".$id.'&tipo='.$evento['tipo_evento'];
$GeneraAllegato =  "mod_allegato1.php?evento_id=".$id;
if($evento['tipo_evento'] != 9  && $evento['tipo_evento'] != 5) $GeneraAllegato =  "mod_allegato1.php?modello=2&evento_id=".$id;
$preventivoCollegato =  "<a href=\"../mod_modelli/mod_catalogo.php?tipo_modello=1&POiD=".base64_encode($lead_id)."&id=1\" class=\" button\" title=\"Crea Offerta\"> Crea Offerta  </a>";
}

if(isset($lead_id)) { 
	$preventivo_collegato = $data_set->data_retriever('fl_preventivi',"oggetto_preventivo,totale_preventivo",'WHERE potential_id = '.$lead_id,' id DESC ','ARRAY',' - di €');
    unset($preventivo_collegato[0]);
    unset($preventivo_collegato[-1]);
    if($evento['preventivo_collegato'] > 1 && isset($preventivo_collegato[$evento['preventivo_collegato']])) {
	$preventivoCollegato =   "<a href=\"../mod_preventivi/mod_stampa.php?id=".$evento['preventivo_collegato']."\" data-fancybox-type=\"iframe\" class=\"fancybox_view button\" title=\"Visualizza/Stampa Offerta\"> Offerta </a>";
	}

} 

$tab_div_labels = array('id'=>'Pianificazione','titolo_ricorrenza'=>'Dettaglio Evento');

if($id > 1) $tab_div_labels = array('../mod_leads/mod_richieste.php?reQiD='.$lead_id.'&evento_id=[*ID*]'=>'Attività','id'=>'Pianificazione','titolo_ricorrenza'=>'Dettaglio Evento','mod_preconto.php?evento_id='.$evento['id'].'&id=[*ID*]'=>'Pagamenti','../mod_doc_vendita/mod_user.php?anagrafica_id='.$evento['anagrafica_cliente'].'&id=[*ID*]'=>'Ricevute/Fatt.');

if(!isset($cliente) && !isset($cliente2) ){
if (($key = array_search('Pagamenti', $tab_div_labels)) !== false) {
    unset($tab_div_labels[$key]);
}
}



include("../../fl_inc/headers.php");
if(!isset($_GET['goto'])) include("../../fl_inc/testata_mobile.php");

 ?>




<body style=" background: linear-gradient(rgb(230, 227, 194), rgba(237, 227, 173, 0.7));">



<div id="container" >


<div id="content_scheda">



<?php 


$potential = GRD($tables[106],$lead_id); 

if($potential['id'] > 1){
if($id == 1){
$telefono = phone_format($potential['telefono'],'39');
$social = '';
echo '<h1><a href="../mod_leads/mod_inserisci.php?id='.$potential['id'].'"><strong>'.$potential['nome'].' '.$potential['cognome'].'</strong></a></h1>'.$social;
echo '<p>Tel: <a href="tel:'.@$telefono.'" class="setAction" data-gtx="'.base64_encode($tab_id).'" data-id="'.base64_encode($potential['id']).'" data-azione="2"  data-esito="2" data-note="Phone call started">'.@$telefono.'</a> mail: <a href="mailto:'.@$potential['email'].'" class="setAction" data-gtx="'.base64_encode($tab_id).'" data-id="'.base64_encode($potential['id']).'" data-azione="3"  data-esito="5"  data-note="Start writing">'.@$potential['email'].'</a></p>';
}

echo '<textarea cols="3" name="note" class="updateField" id="noteRisalto" placeholder="Note:" data-gtx="106" data-rel="'.$potential['id'].'">'.strip_tags(converti_txt($potential['note'])).'</textarea>';

} else { echo '<h2>Il contatto associato a questo evento è stato eliminato</h2>'; }

?>



<?php if($id > 1)  { ?>
<?php 

if($evento['id'] < 2) { mysql_close(CONNECT); unset($_SESSION['last_managed']); die('<h1>Evento cancellato o non disponibile!</h1><p><a href="../../">Torna alla dashboard</a>'); }

$icon1 = 'fa-plus-circle';
$icon2 = 'fa-plus-circle';
$name1 = 'Cliente 1';
$name2 = 'Cliente 2';
$nuovo1 = $nuovo2 = '';

if($cliente > 1) { 

$clienteInfo = GRD($tables[48],$cliente); 
if($clienteInfo['id'] > 1) {

$name1 = $clienteInfo['nome'].' '.$clienteInfo['cognome'];
if($name1 == '') $name1 = "Senza Nome";
$icon1 = 'fa-user';
} else {$name1 = "Cliente Eliminato"; $cliente = 1; $nuovo1 = '&j='.base64_encode($lead_id).'&associa_evento='.$id.'&tipo_profilo=2&anagraficaNumero=1'; }

} else { $cliente = 1; $nuovo1 = '&j='.base64_encode($lead_id).'&associa_evento='.$id.'&tipo_profilo=2&anagraficaNumero=1'; }

if($cliente2 > 1) { 
$clienteInfo2 = GRD($tables[48],$cliente2); 
if($clienteInfo2['id'] > 1) {

$name2 = $clienteInfo2['nome'].' '.$clienteInfo2['cognome'];
if(trim($name2) == '') $name2 = "Senza Nome";
$icon2 = 'fa-user';
} else {$name2 = "Cliente Eliminato"; $cliente2 = 1; $nuovo2 = '&j='.base64_encode($lead_id).'&associa_evento='.$id.'&tipo_profilo=2&anagraficaNumero=2';  }


}  else { $cliente2 = 1; $nuovo2 = '&j='.base64_encode($lead_id).'&associa_evento='.$id.'&tipo_profilo=2&anagraficaNumero=2'; }


echo '<div class="module_icon">
<a id="anagrafica_cliente" style="color: #6190D5;" data-fancybox-type="iframe" class="fancybox_view" href="../mod_anagrafica/mod_inserisci_smart.php?id='.$cliente.$nuovo1.'">
<i class="fa '.$icon1.'"></i> '.$name1.'</a>
</div>';

if($evento['tipo_evento'] == 9  || $evento['tipo_evento'] == 5) echo '<div class="module_icon">
<a id="anagrafica_cliente2" style="color: #f591a3;" data-fancybox-type="iframe" class="fancybox_view" href="../mod_anagrafica/mod_inserisci_smart.php?id='.$cliente2.$nuovo2.'">
<i class="fa '.$icon2.'"></i>'.$name2.'</a>
</div>';

 

?>






<div class="module_icon"><a href="<?php echo $GeneraContratto; ?>" title="Visualizza/Stampa Contratto" data-fancybox-type="iframe" class="fancybox_view"><i class="fa fa-file-text-o"></i> Contratto </a> </div>


<?php 

$selectDataRevisione = "SELECT DATE_FORMAT(data_creazione,'%d/%m/%Y %H:%i') as data FROM fl_revisioni_hrc WHERE evento_id = $id ORDER BY id DESC  LIMIT 1";
$selectDataRevisione = mysql_query($selectDataRevisione,CONNECT);
$selectDataRevisione = mysql_fetch_assoc($selectDataRevisione);
$dataRevisione = (@$selectDataRevisione['data'] != '') ? @$selectDataRevisione['data'] : 'Nessuna Revisione' ;
$schedaWedding = GQD('fl_ricorrenze_matrimonio','id,evento_id',' evento_id = '.$evento['id']);
$schedaWeddingId = ($schedaWedding['id'] > 1) ? $schedaWedding['id'] : '1&auto';

echo "<div><a href=\"mod_scheda_servizio.php?evento_id=".$evento['id']."&tipo_evento=".$evento['tipo_evento']."&id=$schedaWeddingId\" class=\"button\">Vai a gestione</a>
<a href=\"".$GeneraAllegato."\" data-fancybox-type=\"iframe\" class=\"fancybox_view button\" title=\"Visualizza/Stampa Allegato\"> Allegato Contratto</a>
".$preventivoCollegato."
 ".$evento['titolo_ricorrenza']." ".mydatetime($evento['data_evento'])." (".$giorni_settimana[date("w", strtotime($evento['data_evento']))].")  | Coperti: ".($evento['numero_adulti']+$evento['numero_bambini']+$evento['numero_operatori'])." | Prezzo base: &euro; ".$evento['prezzo_base']." | Ultima Revisione: ".$dataRevisione." </div>";


} else { echo '<span class="msg green">Salva la scheda per procedere con inserimento anagrafica</span>'; } ?>

<?php if(isset($_GET['esito'])) { $class = (isset($_GET['success'])) ? 'green' : 'red'; echo '<p class="esito '.$class.'">'.check($_GET['esito']).'</p>'; }  ?>


<form id="scheda" action="../mod_basic/action_modifica.php" method="post" enctype="multipart/form-data">
<?php include('../mod_basic/action_estrai.php');  


if($id == 1) {
echo '<input type="hidden" name="reload" value="../mod_eventi/mod_inserisci.php?id=" />';
} else {
echo '<input type="hidden" name="info" value="1" />';
}


?>
</form>



<?php if(check($_GET['id']) != 1 && $evento['stato_evento'] == 4) { 
echo "<a  href=\"../mod_basic/action_elimina.php?POST_BACK_PAGE=../mod_eventi/?gtx=$tab_id&amp;unset=".$id."\" title=\"Elimina\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i> Elimina </a>";
}


if(isset($_GET['lead_id'])) {
	
	$dataEvento = check($_GET['data_evento']);
	$potential = GRD($tables[106],$lead_id); 

	echo "<script type=\"text/javascript\">	
	$('#periodo_evento').val('".$potential['tipo_interesse']."');
	$('#tipo_evento').val('".$potential['interessato_a']."');
	$('#centro_di_ricavo').val('".$potential['centro_di_ricavo']."');
	$('#numero_adulti').val('".$potential['numero_persone']."');
	$('#numero_bambini').val('".$potential['numero_bambini']."');
	$('#prezzo_base').val('".$potential['prezzo_preventivato']."');
	$('#titolo_ricorrenza').val('".$potential['cognome']."');
	$('#note').val('".$potential['note']."');
	";


	if($potential['tipo_interesse'] == 102 && isset($_GET['data_evento']) ) {
	
	echo "$('#data_evento').val('".substr($dataEvento,8,2)."/".substr($dataEvento,5,2)."/".substr($dataEvento,0,4)." 21:00 ');";
	echo "$('#data_fine_evento').val('".substr($dataEvento,8,2)."/".substr($dataEvento,5,2)."/".substr($dataEvento,0,4)." 23:59 ');";
	} else {
	echo "$('#data_evento').val('".substr($dataEvento,8,2)."/".substr($dataEvento,5,2)."/".substr($dataEvento,0,4)." 13:00 ');";
	echo "$('#data_fine_evento').val('".substr($dataEvento,8,2)."/".substr($dataEvento,5,2)."/".substr($dataEvento,0,4)." 21:00 ');";

	}
	echo "</script>";
}
?>


<script type="text/javascript">
/*Avvio*/
$(document).ready(function() {


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




$('#tipo_evento').change(function(){
  loadSelectIds('#tipo_evento','#centro_di_ricavo');
});


});</script>



</div></div></body></html>
