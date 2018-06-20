<?php

// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
$_SESSION['POST_BACK_PAGE'] = "https://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];;
$giorni = (isset($_GET['giorni'])) ? check($_GET['giorni']) : 3;
$operazione =  (isset($_GET['operazione'])) ? check($_GET['operazione']) : 0;
?>

<h1>Report</h1>

<div class="filtri"> 
<form method="get" action="" id="fm_filtri">
 
 Utenti che hanno eseguito 
 <select name="operazione">
 
 <option value="0" <?php if($operazione==0) echo 'selected'; ?> >Ultimo login</option>
  <option value="1" <?php if($operazione==1) echo 'selected'; ?> >Ultima transazione</option>
</select>

 <input type="number" value="1" name="giorni" style="width: 30px;"/>
 giorni fa. 
 <input type="hidden" value="18" name="action"/>
 
     <input type="submit" value="Mostra" class="button" />
  
</form>

     
    </div>
    
    <div id="info_txt"></div>
<?php
	

	if($operazione == 0) {					
	$query = "SELECT * , MAX( fl_accessi.data_creazione ) AS data_max
	FROM fl_accessi,fl_admin WHERE fl_accessi.utente = fl_admin.user
	GROUP BY fl_accessi.utente ORDER BY data_max ASC";
	} else {
	$query = "SELECT * , MAX( fl_pagamenti.data_creazione ) AS data_max
	FROM fl_pagamenti,fl_admin WHERE fl_pagamenti.proprietario = fl_admin.id
	GROUP BY fl_pagamenti.proprietario ORDER BY data_max ASC";
	}
	
	$risultato = mysql_query($query, CONNECT);
	//echo $query.mysql_error();

 		
	?>
    
             <script type="text/javascript">
function checkAllFields(ref)
{
var chkAll = document.getElementById('checkAll');
var checks = document.getElementsByName('destinatario[]');
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
var checks = document.getElementsByName('destinatario[]');
var boxLength = checks.length;
var totalChecked = 0;
	for ( j=0; j < boxLength; j++ )
	{
		if ( checks[j].checked == true )
		totalChecked++;
	}
	$('#counter').val('Scrivi a ' + totalChecked + ' mail');
}

$(function() {

    $("#scrivi").submit(function() {

        $form = $(this);

        $.fancybox({
                'title': "Invio notifica",
                'href': $form.attr("action") + "?" + $form.serialize(),
                'type': 'iframe'
        });

        return false;

    });


});

    </script>
<form action="../mod_notifiche/mod_invia.php" id="scrivi" method="get">

<table class="dati" summary="Dati" style=" width: 100%;">
<tr>
  <th>Account</th>
  <th>Email</th> 
  <th>Ultima operazione</th>
   <th><input onclick="checkAllFields(1);" id="checkAll" style="display: block;"  name="checkAll" type="checkbox" checked /> </th>
</tr>
<?php 
	
		$count = 0;
	
	while ($riga = mysql_fetch_array($risultato)) 
	{					
			
				$giorno = giorni(mydate2($riga['data_max']));
				$input = ($riga['id'] > 0 && filter_var($riga['email'], FILTER_VALIDATE_EMAIL)) ? '<input style="display: block;"  onClick="countFields(1);" type="checkbox" name="destinatario[]" value="'.$riga['id'].'" checked="checked" />' : '';
				($riga['id'] > 0 && filter_var($riga['email'], FILTER_VALIDATE_EMAIL)) ? $count++ : 0;

		
				if($giorno+$giorni <= 0) {
				
				$day = ($giorno < 0) ? "$giorno  giorni fa. <br>".mydate($riga['data_max']) : 'Oggi';
				echo "<tr>"; 			
				echo "<td>".$riga['user']."</td>"; 	
				echo "<td>".$riga['email']."</td>"; 		
				echo "<td>$day</td>";
				echo "<td><span class=\"Gletter\">$input </span></td>"; 
				echo "</tr>"; 
				}
	
	}

	
	echo "</table>";
$_SESSION['send'] = 1;
echo '<p style="text-align: right; padding: 20px 0;">
<input type="submit" id="counter" value="Scrivi a '.$count.' mail " class="button">
</p></form>'; 
	

?>
