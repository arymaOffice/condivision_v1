<style>
.col-sm-2{
	width:15%;
}
</style>

<script type="text/javascript">
function checkAllFields(ref)
{
var chkAll = document.getElementById('checkAll');
var checks = document.getElementsByName('leads[]');
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
var checks = document.getElementsByName('leads[]');
var boxLength = checks.length;
var totalChecked = 0;
	for ( j=0; j < boxLength; j++ )
	{
		if ( checks[j].checked == true )
		totalChecked++;
	}
	$('#counter').html('' + totalChecked + ' leads');
}




    </script>
<?php if ($status_potential_id >= 0) {?>

<h1><strong><?php echo mk_count('fl_potentials AS tb1  ', $where_count); ?></strong> leads in lista
  <?php if (isset($status_potential_id)) {
    echo $status_potential[$status_potential_id];
}
    ?>
  <?php
if (isset($_GET['source_potential'])) {
        foreach (@$_GET['source_potential'] as $key => $value) {
            echo $source_potential[check($value)] . ' ';
        }

    }

    ?>
  <?php echo ' ' . $new_button; ?></h1>
<?php } else {?>
<h1><strong><?php echo mk_count('fl_potentials AS tb1   ', $where_count); ?></strong> leads
  <?php
if (isset($_GET['source_potential'])) {
    if(is_array($_GET['source_potential']))
        foreach ($_GET['source_potential'] as $key => $value) {
            echo $source_potential[check($value)] . ' ';
        }
    

}

    ?>
   <?php echo ' ' . $new_button; ?></h1>



<?php }?>
<?php //if($status_potential_id != 4 && isset($_GET['type']) && check(@$_GET['type']) != 'QoS') include ("counters.php");?>
<br class="clear">
<div id="filtri" class="filtri large">
  <h2>Filtri</h2>
  <form method="get" action="" id="fm_filtri">



   <?php if (isset($_GET['action'])) {
    echo '<input type="hidden" value="' . check($_GET['action']) . '" name="action" />';
}
?>
   <?php if (isset($_GET['start'])) {
    echo '<input type="hidden" value="' . check($_GET['start']) . '" name="start" />';
}
?>

<div class="dsh_panel big" style="background:  none;" >
<h1 onclick="$('.dsh_panel_content').show();">Filtri di base </h1>
<span class="open-close"><a style="cursor: hand;"><i class="fa fa-angle-down" aria-hidden="true"></i></a></span>

<div class="dsh_panel_content" style="background:  none; ">
<br class="clear" />


    <div class="col-sm-2">
    <label> Qualificati </label>


    <select name="qualificati">
   	<option value="-1">Tutti</option>

      <?php

foreach ($qualificati as $valores => $label) { // Recursione Indici di Categoria
    $selected = ($qualificati_id == $valores) ? " selected=\"selected\"" : "";
    echo "<option value=\"$valores\" $selected>" . ucfirst($label) . "</option>\r\n";
}
?>
    </select>

    </div>

   <div class="col-sm-2">




     <label>  Priorità</label>

          <select name="priorita_contatto" >
   <option value="-1">Tutti</option>

      <?php

foreach ($priorita_contatto as $valores => $label) { // Recursione Indici di Categoria
    $selected = (isset($_GET['priorita_contatto']) && check($_GET['priorita_contatto']) == $valores) ? " selected=\"selected\"" : "";
    echo "<option value=\"$valores\" $selected>" . ucfirst($label) . "</option>\r\n";
}
?>
    </select>
         </div>
  <!-- <div class="col-sm-2">



 <?php $data_set->do_select('VALUES', $tabella, 'interessato_a', 'interessato_a', check(@$_GET['interessato_a']), '', '', '', '');?>


         </div> -->


    <div class="col-sm-2">

    <?php //if($_SESSION['usertype'] == 3 || $_SESSION['usertype'] == 0) $data_set->do_select('VALUES',$tabella,'in_use','operatore',$operatore_id,'','','','',$proprietario); ?>
      <label>Periodo da</label>
      <input type="text" name="data_da" onFocus="this.value='';" value="<?php echo $data_da_t; ?>"  class="calendar" size="8" /><br>
 </div>   <div class="col-sm-2">

      <label>Periodo a</label>
      <input type="text" name="data_a" onFocus="this.value='';" value="<?php echo $data_a_t; ?>" class="calendar" size="8" />
    </div>
    <div class="col-sm-2">

    <?php //if($_SESSION['usertype'] == 3 || $_SESSION['usertype'] == 0) $data_set->do_select('VALUES',$tabella,'in_use','operatore',$operatore_id,'','','','',$proprietario); ?>
      <label>Periodo da (syncro)</label>
      <input type="text" name="data_start" onFocus="this.value='';" value=""  class="calendar" size="8" /><br>
 </div>   <div class="col-sm-2">

      <label>Periodo a (syncro)</label>
      <input type="text" name="data_end" onFocus="this.value='';" value="" class="calendar" size="8" />
    </div>

<br clear="both">
   <?php



foreach ($campi as $chiave => $valore) {


    
   


    if (in_array($chiave, $basic_filters)) {

       

        if ((select_type($chiave) == 2 || select_type($chiave) == 19 || select_type($chiave) == 9 || select_type($chiave) == 8 || select_type($chiave) == 12  ) && $chiave != 'id') {
            //if(select_type($chiave) == 8) $$chiave = array('-1'=>'Tutti','0'='No','1'='Si');
            echo '<div class="col-sm-2" style="display: inline-block;">';
            echo '  <label>' . $valore . '</label>';
            echo '<div class="multicheckboxDiv"><label style="color: black;"><input type="radio" name="' . $chiave . '" value="-1"/>Non impostato</label>';
            foreach ($$chiave as $val => $label) {
                $selected = (isset($_GET[$chiave]) && is_array($_GET[$chiave]) && in_array($val, $_GET[$chiave])) ? 'checked' : '';
                echo '<label><input type="radio" ' . $selected . ' name="' . $chiave . '" value="' . $val . '" />' . $label . ' </label>';}
            echo '</div>';
            echo '</div>';
        } else if ($chiave != 'id') {
            $cont = (isset($_GET[$chiave])) ? check($_GET[$chiave]) : '';
            echo '<div class="col-sm-2" style="display: inline-block; ">';
            echo '<label>' . $valore . '</label><input type="text" name="' . $chiave . '" value="' . $cont . '" />';
            echo '</div>';
        }

    }




}

echo '<div class="col-sm-2" style="display: inline-block;">';
echo '  <label> Venditore </label>';
echo '<div class="multicheckboxDiv"><label style="color: black;"><input type="radio" name="venditore" value="-1"/>Non impostato</label>';
foreach ($venditore as $val => $label) {
    $selected = (isset($_GET[$chiave]) && is_array($_GET[$chiave]) && in_array($val, $_GET[$chiave])) ? 'checked' : '';
    echo '<label><input type="radio" ' . $selected . ' name="venditore" value="' . $val . '" />' . $label . ' </label>';}
echo '</div>';
echo '</div>';


echo '<div class="col-sm-2" style="display: inline-block;">';
echo '  <label> Lead Generator </label>';
echo '<div class="multicheckboxDiv"><label style="color: black;"><input type="radio" name="lead_generator" value="-1"/>Non impostato</label>';
foreach ($venditore as $val => $label) {
    $selected = (isset($_GET[$chiave]) && is_array($_GET[$chiave]) && in_array($val, $_GET[$chiave])) ? 'checked' : '';
    echo '<label><input type="radio" ' . $selected . ' name="lead_generator" value="' . $val . '" />' . $label . ' </label>';}
echo '</div>';
echo '</div>';


?>



<!--    <div class="col-sm-2">
    <label> Stato</label>
    <select name="status_potential[]" id="status_potential" multiple>

      <?php

foreach ($status_potential as $valores => $label) { // Recursione Indici di Categoria
    $selected = ($status_potential_id == $valores) ? " selected=\"selected\"" : "";
    echo "<option value=\"$valores\" $selected>" . ucfirst($label) . "</option>\r\n";
}
?>
    </select>
    </div>
    <div class="col-sm-2">
    <?php if ($_SESSION['usertype'] == 3 || $_SESSION['usertype'] == 0) {?>
    <label> Assegnato a </label>
    <select name="proprietario[]" id="proprietario" multiple>
      <option value="-1">Tutti</option>
      <?php

    foreach ($operatoribdc as $valores => $label) { // Recursione Indici di Categoria
        $selected = ($proprietario_id == $valores) ? " selected=\"selected\"" : "";
        echo "<option value=\"$valores\" $selected>" . ucfirst($label) . "</option>\r\n";
    }
    ?>
    </select>
    <?php } //$data_set->do_select('VALUES',$tabella,'industry','Industry',$industry_id); ?>
        </div>




 </div> </div>

<br class="clear">
<br class="clear">


<div class="dsh_panel big" style="background:  none;" >
<h1 onclick="$('.dsh_panel_content').show();">Veicolo Posseduto </h1>
<span class="open-close"><a style="cursor: hand;"><i class="fa fa-angle-down" aria-hidden="true"></i></a></span>

<div class="dsh_panel_content" style="background:  none; display:  none;">
<br class="clear" />

<div class="col-sm-2">
<label>Marca</label>
<?php $cont = (isset($_GET['marca'])) ? check($_GET['marca']) : '';?>
<input name="marca" value="<?php echo $cont; ?>" type="text">

</div><div class="col-sm-2">

<label>Modello</label>
<?php $cont = (isset($_GET['modello'])) ? check($_GET['modello']) : '';?>
<input name="modello" value="<?php echo $cont; ?>" type="text">
</div>
<div class="col-sm-2">

<label>Targa</label>
<?php $cont = (isset($_GET['targa'])) ? check($_GET['targa']) : '';?>
<input name="targa" value="<?php echo $cont; ?>" type="text">

</div><div class="col-sm-2" >


<label>Colore</label>
<?php $cont = (isset($_GET['colore'])) ? check($_GET['colore']) : '';?>
<input name="colore" value="<?php echo $cont; ?>" type="text">

</div>
-->
<br class="clear">


<style>

select option:checked {
    background: red;
}
</style>


</div></div>
    <input type="submit" value="<?php echo SHOW; ?>" class="button" />
  </form>
</div>