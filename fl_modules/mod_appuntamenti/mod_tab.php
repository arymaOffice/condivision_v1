<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 

include("../../fl_inc/headers.php"); include("../../fl_inc/testata_mobile.php");
 ?>



<div id="container" style=" text-align: left;">
<div class="info red" style="display: block;">
</div>
<h1>Meeting Agenda</h1>  
<?php if(isset($_GET['nominativo'])) echo '<h2>Set a meeting for '.check($_GET['nominativo']).'</h2>'; ?> 
 <div class="filtri">
<form method="get" action="" id="fm_filtri">
  
  <span style="position: relative;">
    <input type="hidden" value="<?php echo $issue_id; ?>" name="issue" />
   Promoter: 
   <select name="operatore" id="operatore" onChange="form.submit();">
      <option value="-1">Select All</option>
      <?php 
              
		     foreach($promoter as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($proprietario_id == $valores) ? " selected=\"selected\"" : "";
			if($valores != 0){ echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; }
			}
		 ?>
    </select> 
    
      day <input type="text" name="data_da" onChange="form.submit();" value="<?php  echo $data_da_t;  ?>"  class="calendar" size="10" /> 
       <input type="submit" value="<?php echo SHOW; ?>" class="button" />

       </form>
     
      </div>
      
       
<?php
	

	if(isset($_GET['ordine'])) { if(!is_numeric($_GET['ordine'])){ exit; } else { $ordine = $ordine_mod[$_GET['ordine']]; }}
	
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0);
						
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	
	$risultato = mysql_query($query, CONNECT);
	//echo $query;

 		
	?>

 
 
  
  	 <table class="dati" summary="Dati" style=" width: 100%;">
        <tr>
     <th scope="col"></th>
       <th scope="col">Date</th>
       <th scope="col">Time</th>
       <th scope="col">Potential</th>
       <th scope="col">Seller</th>
       <th scope="col">Issue</th>
    

      </tr>
	<?php 
	



	$i = 1;
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"9\">No records</td></tr>";		}
	$tot_res = 0;
	while ($riga = mysql_fetch_array($risultato)) 
	{
		
		$colore = '';
		if($riga['issue'] == 0) { $colore = "class=\"tab_blue\"";  }
		if($riga['issue'] == 1) { $colore = "class=\"tab_orange\"";  }
		if($riga['issue'] == 2) { $colore = "class=\"tab_green\"";  }
		if($riga['issue'] == 3 || $riga['issue'] == 4)  { $colore = "class=\"tab_red\"";  } 
		if($riga['issue'] > 4 ) { $colore = "class=\"tab_orange\"";  }
		if($riga['issue'] == 6 ) { $colore = "class=\"tab_blue\"";  }
		
		$timeDiff = dateDifference($riga['data_aggiornamento'],date('Y-m-d H:i:s'),'%h Hrs %i Min.');

		$timer = ($timeDiff == '0') ? "Just now" : " from $timeDiff ";
		$from = ($riga['issue'] == 1) ? $timer : "Issued on ".mydatetime($riga['data_aggiornamento']);
		$from = ($riga['issue'] == 6) ? " from $timeDiff " : $from;
		$query = 'SELECT id,proprietario,meeting_date FROM fl_meeting_agenda WHERE meeting_date = \''.$riga['meeting_date'].'\' AND proprietario = \''.$riga['proprietario'].'\'';
		mysql_query($query);
		$totalmet = mysql_affected_rows(CONNECT);
		$potential = get_potential($riga['potential_rel']);
		$class = '';
		if( ($riga['meeting_date'] < date('Y-m-d')  && $riga['issue'] < 1 ) || ($riga['meeting_date'] == date('Y-m-d')  && intval(substr($riga['meeting_time'],0,2)) < date('H') && $riga['issue'] < 1) ) {
		$class = 'tab_red';
		}
		if($riga['issue'] == 1) $class = 'tab_orange';
		if($riga['issue'] == 6) $class = 'tab_blue';
		
		
		
			
	    /* SMS*/
		$phone = phone_format($potential['telefono']);		
		$query = 'SELECT * FROM `fl_sms` WHERE `from` LIKE \''.$phone.'%\' ORDER BY `data_ricezione` DESC LIMIT 1';
		$sms = mysql_fetch_array(mysql_query($query));
		$smsbody = (isset($sms['body']) && strlen($phone) > 4) ? '<br><strong>Potential wrote:</strong> '.$sms['body'] : '';

		
		
			echo "<tr ><td $colore><span class=\"Gletter\"></span></td>"; 
			echo "<td><strong>".mydate($riga['meeting_date'])."</strong></td>"; 
			echo "<td><strong>".$riga['meeting_time']."</strong></td>";	
			echo "<td>".$potential['nome']." ".$potential['cognome']."</td>"; 
			echo "<td>".$promoter[$riga['proprietario']]." (".$totalmet.")</td>"; 
			echo "<td class=\"$class\">".$issue[$riga['issue']]." $from  $smsbody</td>"; 
			echo ($_SESSION['usertype'] != 4) ?  "<td><a title=\"Start Meeting\" data-fancybox-type=\"iframe\" class=\"fancybox small_touch blue_push\" href=\"mod_meeting.php?external&action=1&amp;sezione=".@$riga['sezione']."&amp;id=".$riga['id']."&potential_rel=".$riga['potential_rel']."\" title=\"Meeting id ".ucfirst($riga['id'])."\">Start meeting</a></td>" : "";
			echo ($_SESSION['usertype'] == 4) ?  '<td><a href="mod_opera.php?profile_rel='.$potential['id'].'&id='.$riga['id'].'&issue=1&notab" class="small_touch orange_push">Arrived</a><a href="mod_opera.php?profile_rel='.$potential['id'].'&id='.$riga['id'].'&issue=6&notab" class="small_touch blue_push">In meeting</a><a href="mod_opera.php?profile_rel='.$potential['id'].'&id='.$riga['id'].'&issue=3&notab" class="small_touch red_push">Not Show</a></td>' : "";
			echo "<td><a data-fancybox-type=\"iframe\" class=\"fancybox\" href=\"mod_inserisci.php?external&action=1&amp;sezione=".@$riga['sezione']."&amp;id=".$riga['id']."&potential_rel=".$riga['potential_rel']."\" title=\"Meeting details for id: ".ucfirst($riga['id'])."\"> <i class=\"fa fa-search\"></i> </a></td>";
		    if($_SESSION['usertype'] == 0) echo "<td><a href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=".$riga['id']."\" title=\"Delete\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i></a></td>"; 
		
				
		    echo "</tr>";
			
			
			
	}

	echo "</table>";
	
 $start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,1); 
?>
</body></html>