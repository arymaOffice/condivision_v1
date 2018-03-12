<?php
// Controlli di Sicurezza
require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo
$module_menu = '';
$module_title = 'Note per le date selezionate';
$new_button = '';
unset($filtri);
unset($searchbox);
include("../../fl_inc/headers.php");
$lead_id = check($_GET['lead_id']);
$date_del_lead = GQS($tables[138],'id,DATE_FORMAT(data_disponibile,"%d/%m/%Y") as data_da_selezionare,note,ambiente_id','lead_id = '.$lead_id.' ORDER BY ambiente_id ASC');
$ambienteSelect = 0;
$inputForm = '';


foreach ($date_del_lead as $value) {
   if($ambienteSelect != $value['ambiente_id']) { 
   	$inputForm .= '<span class="msg orange">Disponibilità '.$ambienti[$value['ambiente_id']].'</span>'; 
 	$ambienteSelect = $value['ambiente_id'];
 }

  $inputForm .= '<h2>'.$value['data_da_selezionare'].'<input class="updateField" style="max-width:300px"  data-rel="'.$value['id'].'" data-gtx="138" name="note" value="'.$value['note'].'"  type="text" placeholder="Note per il '.$value['data_da_selezionare'].'"></h2><br>';
}
mysql_close(CONNECT);
?>
<style>
#content_scheda{
  margin: 0 auto;
  max-width: none;
}
.updateField{

  margin: 10px;

}
</style>

  

 <div id="content_scheda" style="clear:both">
 <h1>Inserisci note</h1>
 <?php echo $inputForm; ?>
  
 

  <a href="mod_disponibilita_pdf.php?lead_id=<?php echo $lead_id; ?>" class="button"> Conferma </a>
 
  </div>






