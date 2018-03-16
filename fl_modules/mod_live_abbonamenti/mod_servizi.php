<?php
require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 


include("../../fl_inc/headers.php");

$id = check($_GET['record_id']);

$tutti_servizi = GQS('fl_servizi','id,label,DATE_FORMAT(data_creazione,"%d/%m/%Y") as data','id != 1');

$servizi_gia_presenti = GQS('fl_ser_abb','id_ser',' id_abb = '.$id);

$html = '<table style="margin: 20px auto;"><tr><th>Nome</th><th>Data Creazione</th></tr>';


foreach($tutti_servizi as $key => $value){

    $checked = '';

    if (in_array(array('id_ser' =>$value['id']), $servizi_gia_presenti)) { $checked = 'checked'; }


    $html .='<tr><td><input required="required" type="checkbox" id="servizi' . $value['id'] . '" name="servizi[]" value="' . $value['id'] . '" '.$checked.' ><label for="servizi' .$value['id'] . '">' . $value['label'] . '</label></td><td>'.$value['data'].'</td></tr>';
}

$html .= '</table>';

?>
<style>
p.modificato{
    padding: 20px;
margin: 30px;
font-size: 16px;
font-weight: bold;
}
</style>
<h1 style="text-align:center;">Seleziona i servizi associati all'abbonamento</h1><br><br>
<?php if(@$_GET['esito'] == 1){ echo '<p class="green modificato" >'.@$_GET['info_txt'].'</p>';}elseif(@$_GET['esito'] == 0){
    echo '<p class="red modificato">'.@$_GET['info_txt'].'</p>';
} ?>
<br>
<br>
<form method="POST" action="mod_opera.php" id="ser">
<input type="hidden" value="<?php echo $id; ?>" name="id_abb">
<input type="hidden" value="servizi_add" name="servizi_add">
<?php echo $html; ?>
<br>
<a href="#" class="button" onclick="$('#ser').submit()" >Aggiungi Servizi</a>
</form>

