<?php
require_once '../../fl_core/autentication.php';
include 'fl_settings.php'; // Variabili Modulo

//recupero evento
$evento_id = ($_GET['evento_id'] != '') ? check($_GET['evento_id']) : 0;

//html da mostrare
$html = '<h1 style="text-align:center;">Schema tavoli creati per l\'evento </h1><br><br>';

$schemi_presenti = GQS('fl_tavoli', 'DISTINCT(layout_id) as ly_id ', 'evento_id = ' . $evento_id); //vari schemi già creati
$ambienti_da_rimuovere = array();
foreach ($schemi_presenti as $value) {
    $layout_info = GQS('fl_tavoli_layout', '*', 'id = ' . $value['ly_id']);
    $ambiente_info_layout = GQS('fl_ambienti', '*', 'id = ' . $layout_info[0]['ambiente_id']); //info sull'ambiente
    array_push($ambienti_da_rimuovere,$layout_info[0]['ambiente_id']);
     $html.= '<a href="mod_layout.php?evento_id='.$evento_id.'&ambiente_id='.$layout_info[0]['ambiente_id'].'&orientamento='.$layout_info[0]['orientamento'].'" class="button impaginazione">'.$ambiente_info_layout[0]['nome_ambiente'].'</a><br>';
}


$html.='<br><br><h1 style="text-align:center;">Schema tavoli da creare per l\'evento </h1><br><br>';


//controllo quanti ambienti ci sono
$ambienti_explode = explode(',', $_GET['ambiente_id']);
$ambienti = array_diff($ambienti_explode,$ambienti_da_rimuovere);
if (count($ambienti) > 1) {
    foreach ($ambienti as $ambiente) {

        $ambiente_id = check($ambiente);
        $ambiente_info = GQS('fl_ambienti', '*', 'id = ' . $ambiente_id); //info sull'ambiente
        $ambiente_layout = GQS('fl_tavoli_layout', '*', 'evento_id = 0 AND ambiente_id = ' . $ambiente_id); //info sull'ambiente

        $options = '<option value="-1">Seleziona ...</option>';
        foreach ($ambiente_layout as $value) {
            $options .= '<option value="' . $value['id'] . '">' . $value['nome_layout'] . '</option>';
        }

        $html .= '<div style="width:60%;display: inline-block;float: left;"><form method="get" action="mod_opera.php"><h2 style="text-align:center;">' . $ambiente_info[0]['nome_ambiente'] . '</h2>';
        $html .= '<input value="'.$ambiente_id.'" name="ambiente_id" type="hidden"><input value="'.$evento_id.'" name="evento_id" type="hidden"><div class="form_row" id="box_template_id"><p class="select_text template_id"><label for="template_id">Template Evento</label>
        <select  name="template_id" id="template_id">' . $options . '</select></div>';
        $html .= '<input type="submit" value="Crea da template" class="button impaginazione"></form></div>
        <div style="width:30%;display: inline-block;float: left;"><h2 style="text-align:center;">' . $ambiente_info[0]['nome_ambiente'] . '</h2>
        <form method="GET" action="mod_layout.php"><input type="hidden" name="ambiente_id" value="'.$ambiente_id.'">
        <input type="hidden" name="evento_id" value="'.$evento_id.'">
        <div class="form_row" id="box_orientamento">
        <p class="input_text" style="text-align:left !important;"><label for="orientamento">Orientamento</label>


        <input name="orientamento" type="radio" id="orientamento0" value="0" required>
        <label for="orientamento0" style="width: auto;">Verticale</label>
        <input name="orientamento" type="radio" id="orientamento1" value="1" required>
        <label for="orientamento1" style="width: auto;">Orizzontale</label>
        </p>
        </div>
        <input type="hidden" value="1" name="layout">
            <input type="hidden" value="-2" name="template_id">
        <input type="submit" value="Crea schema vuoto" class="button"></form>
        </form>
        </div><br><br><br>';

    }

} else {
    $ambiente_id = check($_GET['ambiente_id']);
    $ambiente_info = GQS('fl_ambienti', '*', 'id = ' . $ambiente_id); //info sull'ambiente
    $ambiente_layout = GQS('fl_tavoli_layout', '*', 'evento_id = 0 AND ambiente_id = ' . $ambiente_id); //info sull'ambiente

    $options = '<option value="-1">Seleziona ...</option>';
    foreach ($ambiente_layout as $value) {
        $options .= '<option value="' . $value['id'] . '">' . $value['nome_layout'] . '</option>';
    }

    $html .= '<div style="width:60%;display: inline-block;float: left;"><form method="get" action="mod_opera.php"><h2 style="text-align:center;">' . $ambiente_info[0]['nome_ambiente'] . '</h2>';
    $html .= '<input value="'.$ambiente_id.'" name="ambiente_id" type="hidden"><input value="'.$evento_id.'" name="evento_id" type="hidden"><div class="form_row" id="box_template_id"><p class="select_text template_id"><label for="template_id">Template Evento</label>
    <select  name="template_id" id="template_id">' . $options . '</select></div>';
    $html .= '<input type="submit" value="Crea da template" class="button impaginazione"></form></div>
    <div style="width:30%;display: inline-block;float: left;">
    <form method="GET" action="mod_layout.php"><h2 style="text-align:center;">' . $ambiente_info[0]['nome_ambiente'] . '</h2><input type="hidden" name="ambiente_id" value="'.$ambiente_id.'">
    <input type="hidden" name="evento_id" value="'.$evento_id.'">
    <div class="form_row" id="box_orientamento">
    <p class="input_text" style="text-align:left !important;"><label for="orientamento">Orientamento</label>


    <input name="orientamento" type="radio" id="orientamento0" value="0" required>
    <label for="orientamento0" style="width: auto;">Verticale</label>
    <input name="orientamento" type="radio" id="orientamento1" value="1" required>
    <label for="orientamento1" style="width: auto;">Orizzontale</label>
    </p>
    </div>
    <input type="hidden" value="-2" name="template_id">
    
    <input type="hidden" value="1" name="layout">
    <input type="submit" value="Crea schema vuoto" class="button"></form>
    </form>
    </div><br><br><br>';
}


include "../../fl_inc/headers.php";

?>

<style>
.impaginazione{
    display: inline-block !important;
    float: left;
    margin-left: 46% !important;
    margin-right: 150px !important;
    height: 27px;
    padding: 6px;
}

.myh1{
    text-align: center;
    background: rgb(234, 92, 24);
    padding: 10px;
    margin: 0 30%;
    color: white;
    border-radius: 4px;
    text-align:center;
}
</style>

<div style="margin:3%;">
<h1 style="text-align:center;">Creazione schema tavoli </h1><br>
<div id="tb_id" aria-labelledby="ui-id-1" class="ui-tabs-panel ui-widget-content ui-corner-bottom" role="tabpanel" aria-expanded="true" aria-hidden="false" style="height:90vh;">

    <?php echo $html; ?>

</div>
