<?php

require_once '../../fl_core/autentication.php';
$id = check($_GET['id']);
include 'fl_settings.php'; // Variabili Modulo

$fields = $data_set->db_fields_retriever_('fl_campagne_attivita');

?>

<div id="content_scheda">
<form  action="<?php echo ROOT . $cp_admin; ?>.'fl_modules/mod_basic/action_modifica.php" method="post" enctype="multipart/form-data" >

<?php

$hidden = array('processo_id','profilo_funzione','operatore','data_aggiornamento','data_creazione');

foreach ($fields as $key => $value) {

    if (isset(${$key}) && is_array(${$key}) ) {
        echo '<br><br> <select  name="' . $key . '">';
        foreach (${$key} as $id => $label) {
            echo '<option value="' . $id . '">' . $label . '</option>';
        }
        echo '</select> <br><br>';
    } elseif(in_array($hidden,${$key})) {
        echo '<input type="hidden" value="" placeholder="">';
    }else{
        echo '<input type="text" value="" name="'.${$key}.'" placeholder="">';
    }

}

echo '<pre>';
print_r($fields);
echo '</pre>';

/*

 */

?>
<input type="hidden" name="dir_upfile" value="icone_articoli" />
 </form>
 </div>






<!-- <div id="content_scheda">

<?php //if(isset($_GET['esito'])) { $class = (isset($_GET['success'])) ? 'green' : 'red'; echo '<p class="esito '.$class.'">'.check($_GET['esito']).'</p>'; }  ?>

<div id="map-canvas"></div>
<form id="scheda" class="ajaxLinkCharge" action="<?php echo ROOT . $cp_admin; ?>.'fl_modules/mod_basic/action_modifica.php" method="post" enctype="multipart/form-data">



<?php //include('../mod_basic/action_estrai.php');  ?>

<input type="hidden" name="dir_upfile" value="icone_articoli" />


</form> -->



