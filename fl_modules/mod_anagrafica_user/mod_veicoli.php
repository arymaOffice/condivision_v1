<?php

require_once '../../fl_core/autentication.php';
include 'fl_settings.php'; // Variabili Modulo

unset($chat);

$tab_id = 96;
$workflow_id = (isset($_GET['workflow_id'])) ? check($_GET['workflow_id']) : 48;
$parent_id = check($_GET['parent_id']);

if ($workflow_id == 16 && isset($_GET['permuta'])) {$query = "UPDATE fl_potentials SET permuta = 1 WHERE id = $parent_id";
    mysql_query($query, CONNECT);}

$tipologia_veicolo = $data_set->get_items_key("tipo_interesse");
$pagamento_veicolo = $data_set->get_items_key("pagamento_vettura");

include "../../fl_inc/headers.php";
?>

<body style=" background: rgb(241, 241, 241) none repeat scroll 0% 0%; text-align: left; padding: 20px;">
<h1>Veicoli</h1>



<?php

$query = "SELECT *,DATE_FORMAT(data_quotazione,\"%d/%m/%Y\") as dataQuotazione FROM `fl_veicoli` WHERE workflow_id = 16 AND `parent_id` = $parent_id";
$risultato = mysql_query($query, CONNECT);
if (mysql_affected_rows() == 0) {echo "<p>Nessun Veicolo</p>";} else {
    ?>

    <table class="dati">
   <tr>
   <th>Veicolo/Status/Alimentaz./Pagamento</th>
   <th>Km/Immatricolazione</th>
   <th>Targa</th>
   <th>Quotazione</th>
   <th></th>
   <th></th>
   </tr>

 <?php

    while ($riga = mysql_fetch_assoc($risultato)) {

        $ultima_quotazione = ($riga['data_quotazione'] == '0000-00-00 00:00:00') ? ' Aggiorna Quotazione' : 'Ultima quotazione :' . $riga['dataQuotazione'];

        $quotazione = " <td><a href=\"../mod_veicoli/mod_opera.php?id=" . $riga['id'] . "&eurotax&targa=" . @$riga['targa'] . "\"  class=\"ajaxLoad\" id='btquotazione" . $riga['id'] . "'><i class=\"fa fa-history\" aria-hidden=\"true\"></i>$ultima_quotazione </a>
     </td>";
        ?>


    <tr>
    <td title="<?php echo strip_tags(@$riga['descrizione']); ?>"><?php echo @$riga['marca'] . ' ' . @$riga['modello'] . ' ' . @$riga['colore']; ?><br>
      <span class="msg orange"><?php echo @$tipologia_veicolo[$riga['tipologia_veicolo']]; ?></span>
      <span class="msg blue"><?php echo @$alimentazione[$riga['alimentazione']]; ?></span>
      <span class="msg gray"><?php echo @$pagamento_veicolo[$riga['pagamento_veicolo']]; ?></span></td>
	<td><?php echo $riga['chilometri_percorsi'] . 'KM - ' . @$riga['anno_immatricolazione']; ?></span></td>
    <td><?php echo $riga['targa']; ?></span></td>
    <td><input type="text" value="<?php echo $riga['quotazione_attuale']; ?>" style="width: 80%;" name="quotazione_attuale" class="updateField" data-rel="<?php echo $riga['id']; ?>" /> &euro; </span></td>
    <?php echo $quotazione; ?>
    <td><a class="facyboxParent"  data-fancybox-type="iframe"  href="../mod_veicoli/mod_inserisci.php?external&id=<?php echo $riga['id']; ?>"> <i class="fa fa-search"></i> </a></td>
	<td><a href="../mod_basic/action_elimina.php?gtx=<?php echo $tab_id; ?>&amp;unset=<?php echo $riga['id']; ?>" title="Elimina"  onclick="return conferma_del();"><i class="fa fa-trash-o"></i></a></td>
</tr>

    <?php }} //Chiudo la Connessione    ?>

 </table>


<script type="text/javascript">


$(document).ready(function() {



$(document).on('click',".ajaxLoad",function (event) {

var href = $(this).attr('href');
var button = $(this);
var posting = $.get(href+'&ajax'); 
button.html( 'ATTENDI...' );
posting.fail(function( data ) {   $(this).html( 'Saving Error' );    });
posting.always(function( data ) {  $(this).html( 'Saving action' );  });
posting.done(function( response ) {  //button.hide();
   
   var data = $.parseJSON(response);
   button.html( data.esito );

   $('#'+data.aggiorna).html(data.valore);

   if(data.aggiorna == 'posizione') {
      var valore = data.valore;
      var split = valore.split(',');

   	var lat = split[0];
   	var lon = split[1];
   	var url = '../mod_basic/mod_map.php?lat='+lat+'&lon='+lon;

	$.fancybox.open({
            'autoScale': true,
            'transitionIn': 'elastic',
            'transitionOut': 'elastic',
            'speedIn': 500,
            'speedOut': 300,
            'autoDimensions': true,
			'type' : 'iframe',
			'href' : url,			
            'centerOnScroll': true 
				
		});
   }//fine posiszione

   if(data.aggiorna == 'versione'){

      $('#versioni').css('display','block');
      $('.modal-content').append(data.valore);
   }//fine versione

   if(data.aggiorna == 'quotazione'){
      $('#versioni').css('display','none');
      $('.modal-content').empty();
      $('#btquotazione'+data.id).html(data.esito);

   }
});

event.preventDefault();
});//fine .ajaxLoad

$('.close').click(function(){
   $('#versioni').css('display','none');
});

});
</script>  

<div id="versioni" style="display: none;position: fixed;
    z-index: 10000;
    padding-top: 100px;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.69); /* Black w/ opacity */">
     <div class="modal-content" style="background-color: #fefefe;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;">
    <span class="close" style="color: #aaaaaa;
    float: right;
    font-size: 28px;
    font-weight: bold;cursor:pointer">&times;</span>
    <h3>Seleziona versione auto</h3>
    </div>
    </div>


<?php

/* <h1>Veicoli Acquistati da noi</h1>

$query = "SELECT * FROM `fl_veicoli` WHERE workflow_id = 48 AND `parent_id` = $parent_id";
$risultato = mysql_query($query, CONNECT);
if(mysql_affected_rows() == 0){ echo "<p>Nessun Veicolo</p>"; } else {
?>

<table class="dati">
<tr>
<th>Veicolo</th>
<th>Km/Immatricolazione</th>
<th>Consegna/Pagamento</th>
<th>Targa</th>
<th>Quotazione</th>
<th></th>
</tr>

<?php

while ($riga = mysql_fetch_assoc($risultato))
{
?>

<tr>
<td title="<?php echo strip_tags(@$riga['descrizione']); ?>"><?php echo @$riga['marca'].' '.@$riga['modello'].' '.@$riga['colore']; ?><br><span class="msg orange"><?php echo @$tipologia_veicolo[$riga['tipologia_veicolo']]; ?></span><span class="msg blue"><?php echo @$alimentazione[$riga['alimentazione']]; ?></span></td>
<td><?php echo$riga['chilometri_percorsi'].'KM - '.@ mydate($riga['anno_immatricolazione']); ?></span></td>
<td><?php echo mydate($riga['data_consegna']); ?> <span class="msg green"></span></td>
<td><?php echo $riga['targa']; ?></span></td>
<td><input type="text" value="<?php echo $riga['quotazione_attuale']; ?>" style="width: 80%;" name="quotazione_attuale" class="updateField" data-rel="<?php echo $riga['id']; ?>" /> &euro; </span></td>
<td><a class="facyboxParent"  data-fancybox-type="iframe"  href="../mod_veicoli/mod_inserisci.php?external&id=<?php echo $riga['id'];?>"> <i class="fa fa-search"></i> </a></td>
<td><a href="../mod_basic/action_elimina.php?gtx=<?php echo $tab_id; ?>&amp;unset=<?php echo $riga['id'];?>" title="Elimina"  onclick="return conferma_del();"><i class="fa fa-trash-o"></i></a></td>
</tr>

<?php } } //Chiudo la Connessione

</table> */?>



<div id="results"><?php if (isset($_GET['esito'])) {
    echo '<h2 class="red">' . check($_GET['esito']) . '</h2>';
}
?></div>

 <style>

.dsh_panel input, .dsh_panel input .calendar { width: 90%; margin: 0; }
.dsh_panel select { width: 80%; }
 </style>
 <div class="dsh_panel big">
<h1 onClick="$('.dsh_panel_content').show();">Nuovo Veicolo <a href="#" class="" style="color: gray"> <i class="fa fa-plus-circle"></i>  </a></h1><span class="open-close"><a href="#"><i class="fa fa-angle-up" aria-hidden="true"></i></a></span>
<div class="dsh_panel_content" style="display: none;">

<form id="" class="ajaxForm" action="../mod_basic/save_data.php" method="post" enctype="multipart/form-data">

<div class="col-sm-5">
<input type="hidden" name="workflow_id" value="<?php echo $workflow_id; ?>" />
<input type="hidden" name="gtx" value="<?php echo $tab_id; ?>" />
<input type="hidden" name="id" value="1" />
<input type="hidden" name="goto" value="../mod_anagrafica/mod_veicoli.php?parent_id=<?php echo $parent_id; ?>" />

<!--
OPZIONE CHE CREA ANAFGRAFICA CLIENTE SE NON ESISTE E ASSOCIA VEICOLO AD ANAGRAFICA
<p>Acquisto di un veicolo da noi? <input type="checkbox" value="nuovo_cliente" name="function" id="function" /><label for="function">SI</label></p>
-->

<input type="hidden" name="parent_id" value="<?php echo $parent_id; ?>" />
<p><select name="data_acquisto" >
<option value="1">Seleziona anno...</option>
<?php $data_acquisto = array();for ($i = 1995; $i < date('Y') + 1; $i++) {
    $data_acquisto[$i] = $i;
}

foreach ($data_acquisto as $chiave => $valore) {echo '<option value="' . $chiave . '">' . $valore . '</option>';}?>
</select></p>


<p><input type="text" name="targa" value="" placeholder="Targa veicolo" /></p>

<p><select name="pagamento_veicolo" >
<option value="1">Seleziona pagamento...</option>
<?php foreach ($pagamento_veicolo as $chiave => $valore) {echo '<option value="' . $chiave . '">' . $valore . '</option>';}?>
</select></p>

<p><select name="tipologia_veicolo" >
<option value="1">Seleziona Tipologia...</option>

<?php foreach ($tipologia_veicolo as $chiave => $valore) {echo '<option value="' . $chiave . '">' . $valore . '</option>';}?>
</select></p>
<p><select name="alimentazione">
<option value="1">Seleziona Alimentazione...</option>
<?php foreach ($alimentazione as $chiave => $valore) {echo '<option value="' . $chiave . '">' . $valore . '</option>';}?>
</select></p>

</div>

<div class="col-sm-5">
<p><input type="text" name="marca" value="" placeholder="Marca" /></p>
<p><input type="text" name="modello" value="" placeholder="Modello" /></p>
<p><input type="text" name="colore" value="" placeholder="Colore" /></p>
<p><input type="text" name="descrizione" value="" placeholder="Descrizione" /></p>
<p><input type="text" name="chilometri_percorsi" value="" placeholder="Km percorsi" /></p>
<p><select name="anno_immatricolazione" >
<option value="1">Seleziona Anno immatricolazione...</option>
<?php $anno_immatricolazione = array();for ($i = 1950; $i <= date('Y'); $i++) {
    $anno_immatricolazione[$i] = $i;
}

foreach ($anno_immatricolazione as $chiave => $valore) {echo '<option value="' . $chiave . '">' . $valore . '</option>';}?>
</select></p>
<p><input type="text" name="quotazione_attuale" value="" placeholder="Quotazione Veicolo" /></p>
</div>

<input type="submit" class="button salva" value="Inserisci Veicolo" style="width: 100% " />
</form>

<br class="clear"/>
</div>
</div>


</body></html>
<?php mysql_close(CONNECT);?>