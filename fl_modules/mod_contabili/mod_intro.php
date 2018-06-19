<?php

if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
$_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];
if($_SESSION['usertype'] > 1) exit;

?>


<?php  if(!isset($_GET['numero_settimana'])) { $_SESSION['nuova_settimana'] = $numSett = 1+$data_set->getMAX('fl_settimane_contabili','numero_settimana',''); ?>
<h3>Configura nuova settimana</h3>
	
	<form action="" method="get">
    Settimana numero: <input type="number" style="width: 50px;" name="numero_settimana" value="<?php echo $numSett; ?>">
    dal  &nbsp; <input type="text" style="width: 100px;" name="periodo_inizio" value="<?php echo date('d/m/Y',strtotime('-7 days')); ?>" class="calendar">&nbsp;  al &nbsp; <input style="width: 100px;" type="text" name="periodo_fine" value="<?php echo date('d/m/Y'); ?>" class="calendar">
   <input type="hidden" name="action" value="11">
   <input type="submit" class="button" value="Crea Settimane" />
    </form>
    
<?php } else {
	
	if(!isset($_SESSION['nuova_settimana'])) {
	$query = "SELECT id,numero_settimana, YEAR(data_creazione) AS anno FROM `$tabella` GROUP BY anno DESC, numero_settimana ASC;";
	$risultato = mysql_query($query, CONNECT);
	echo '<h2>Seleziona Settimana</h2>';
	while ($riga = mysql_fetch_array($risultato)) 
	{
		if($riga['numero_settimana'] > 0) echo '<p><a class="button" href="./?numero_settimana='.$riga['numero_settimana'].'&anno='.$riga['anno'].'">Settimana '.$riga['numero_settimana'].'/'.$riga['anno'].'</a></p>';					
	}
	} else {
	
	$numero_settimana = (isset($_GET['numero_settimana'])) ?  check($_GET['numero_settimana']) : 0 ;
	$periodo_inizio = (isset($_GET['periodo_inizio'])) ? check($_GET['periodo_inizio']) : date('d/m/Y') ;
	$periodo_fine = (isset($_GET['periodo_fine'])) ? check($_GET['periodo_fine']) :  date('d/m/Y') ;
	
	
	 ?>
<h3>Carica settimane contabili</h3>

<script type="text/javascript">
function checkAllFields(ref)
{
var chkAll = document.getElementById('checkAll');
var checks = document.getElementsByName('proprietario[]');
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
var checks = document.getElementsByName('proprietario[]');
var boxLength = checks.length;
var totalChecked = 0;
	for ( j=0; j < boxLength; j++ )
	{
		if ( checks[j].checked == true )
		totalChecked++;
	}
	$('#counter').val('Crea ' + totalChecked + ' settimane');
}

document.forms[0].addEventListener('submit', function( evt ) {
    var file = document.getElementsByClassName('uploadFile').files[0];

    if(file && file.size < 10485760) { // 10 MB (this size is in bytes)
        //Submit form        
    } else {
        alert("Troppo grande questo file");
        evt.preventDefault();
    }
}, false);

    </script>

	<form action="mod_opera.php" method="post" class="ajaxFormFiles" enctype="multipart/form-data">
<div id="results" class="green"></div>

<table class="dati" summary="Dati" style=" width: 100%;">
<tr>
 <th style="text-align: center;"><input onclick="checkAllFields(1);" id="checkAll"  name="checkAll" type="checkbox" checked /><label for="checkAll"><?php echo $checkRadioLabel; ?></label>
 <th>Account</th>
 <th>Punto Gioco</th>
 <th>Settimana</th>
 <th>Periodo</th>
 <th>Importo</th>
 <th>File</th>
</tr>
			
<?php		$count = 0; foreach($proprietario as $chiave => $valore) { 

if($chiave > 1) {
			
			$account = GRD('fl_account',$chiave);
			$anagrafica = GRD('fl_anagrafica',$account['anagrafica']);
			$user_check = '<a data-fancybox-type="iframe" title="Modifica Account" class="fancybox" href="../mod_account/mod_visualizza.php?external&id='.$account['id'].'">'.$account['user'].'</a><br>'.$account['motivo_sospensione'];
			$user_ball = ($account['attivo'] == 1)  ? "<span class=\"c-green\"><i class=\"fa fa-user\"></i></span>" : "<span class=\"c-red\"><i class=\"fa fa-user\"></i></span>"; 
			$concessione = (AFFILIAZIONI == 1)  ? " ".$anagrafica['numero_concessione'] : '';
			$count++; ?>			
			
			
		   <tr>
           <td style="text-align: center;"><input onClick="countFields(1);" type="checkbox" name="proprietario[]" id="proprietario<?php echo $chiave; ?>" value="<?php echo $chiave; ?>" checked><label for="proprietario<?php echo $chiave; ?>"><?php echo $checkRadioLabel; ?></label></td> 
		   <td><?php echo $user_ball." ".$user_check; ?></td>
           <td><?php echo $account['anagrafica'].' '.@$anagrafica['ragione_sociale'].'  - P. iva '.@$anagrafica['partita_iva']."<br>".ucfirst(@$anagrafica['sede_legale'])." ".@$anagrafica['cap_sede']. " ".ucfirst(@$anagrafica['comune_sede']). " (".$anagrafica['provincia_sede'].")<br><span class=\"msg blue\">".@$marchio[@$anagrafica['marchio']]."</span><span class=\"msg orange\">".@$tipo[@$account['tipo']]." $concessione </span>"; ?></td>
		   <td><input type="number" style="width: 50px;" name="<?php echo $chiave; ?>numero_settimana" value="<?php echo $numero_settimana; ?>"></td>
           <td>dal  <input type="text" style="width: 100px;" name="<?php echo $chiave; ?>periodo_inizio" value="<?php echo $periodo_inizio; ?>" class="calendar"> al <input style="width: 100px;" type="text" name="<?php echo $chiave; ?>periodo_fine" value="<?php echo $periodo_fine; ?>" class="calendar"></td>
           <td><input type="text" style="width: 100px;" name="<?php echo $chiave; ?>importo" value="0.00"></td>
           <td><input class="uploadFile" type="file" style="width: 200px;" name="<?php echo $chiave; ?>file" value="" placeholder="Seleziona file"accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/xls,application/xlsx, application/pdf, application/csv"></td>
           </tr>

<?php } } ?>
</table>
<?php echo '<p style="text-align: right; padding: 20px 0;">
<input type="submit" id="counter" value="Crea '.$count.'  settimane " class="button">
</p>'; ?> 
    </form>

<?php }} ?>