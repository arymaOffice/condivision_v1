
<?php $_SESSION['POST_BACK_PAGE'] = $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];
 ?>

<?php if(isset($_GET['nominativo'])) echo '<h2>Appuntamento per '.check($_GET['nominativo']).'</h2>'; ?> 
 <div class="filtri" id="filtri">
<form method="get" action="" id="fm_filtri">
<h2>Filtri</h2>
    <input type="hidden" value="<?php echo $issue_id; ?>" name="issue" />
     <?php if(isset($_GET['arrived'])) { ?> <input type="hidden" value="1" name="arrived" />	<?php } ?>
        <?php if(isset($_GET['today'])) { ?> <input type="hidden" value="1" name="today" />	<?php } ?>
        <?php if(isset($_GET['action'])) { ?> <input type="hidden" value="<?php echo check($_GET['action']);?>" name="action" />	<?php } ?>

 <label> Sede</label> 
   <select name="meeting_location" id="meeting_location">
      <option value="-1">Tutti</option>
      <?php 
              
		     foreach($meeting_location as $valores => $label){ // Recursione Indici di Categoria
			$selected = (@$meeting_location_id == $valores) ? " selected=\"selected\"" : "";
			if($valores != 0){ echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; }
			}
		 ?>
    </select> 


        <?php if($_SESSION['usertype'] == 0 || $_SESSION['profilo_funzione'] == 8) { ?>
    

 <label> Persona </label> 
   <select name="proprietario" id="proprietario">
      <option value="-1">Tutti</option>
      <?php 
              
		     foreach($proprietario as $valores => $label){ // Recursione Indici di Categoria
			$selected = (@$_SESSION['proprietario_id'] == $valores) ? " selected=\"selected\"" : "";
			 echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; 
			}
		 ?>
    </select> 
      
      
 <!--  <label> Marchio </label> 
  
       <select name="marchio" id="marchio" onChange="form.submit();">

      <?php 
              
		     foreach($marchio as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($marchio_id == $valores) ? " selected=\"selected\"" : "";
			if($valores != 0){ echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; }
			}
		 ?>
    </select> -->
<?php } ?>
     <label>   giorno</label>  <input type="text" name="data_da" onChange="form.submit();" value="<?php  echo $data_da_t;  ?>"  class="calendar" size="10" /> 
       <input type="submit" value="<?php echo SHOW; ?>" class="button" />

       </form>
     
      </div>
      

<form method="get" action="">

<h2>    Scegli data      <input type="text" name="data_da" onChange="form.submit();" value="<?php  echo $data_da_t;  ?>"  class="calendar" size="10" /> 
       <input type="submit" value="<?php echo SHOW; ?>" class="button" /></h2>
</form>
      
<?php
	
	$promoter = $proprietario;

	if(isset($_GET['ordine'])) { if(!is_numeric($_GET['ordine'])){ exit; } else { $ordine = $ordine_mod[$_GET['ordine']]; }}
	
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0);
						
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	//echo $query.mysql_error();
	$risultato = mysql_query($query, CONNECT);

	?>

 
 
  
  	 <table class="dati" summary="Dati" style=" width: 100%;">
        <tr>
     <th scope="col"></th>
       <th scope="col">Ora/Data</th>
       <th scope="col">Lead</th>
       <th scope="col">Location</th>
       <th scope="col">Venditore\Rif. </th>
       <th scope="col">Esito</th>
    

      </tr>
	<?php 
	



	$i = 1;
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"9\">Nessun appuntamento</td></tr>";		}
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
		$alarm = (strlen($riga['note']) > 1) ? "<i class=\"fa fa-exclamation-triangle c-red\" aria-hidden=\"true\"  title=\"".stripcslashes(converti_txt($riga['note']))."\"></i> <i class=\"fa fa-clock-o\"></i>" : "<i title=\"".stripcslashes(converti_txt($riga['note']))."\" class=\"fa fa-clock-o\"></i>";	
		$timeDiff = dateDifference($riga['data_aggiornamento'],date('Y-m-d H:i:s'),'%h Hrs %i Min.');

		$timer = ($timeDiff == '0') ? "Appena arrivato" : " da $timeDiff ";
		$from = ($riga['issue'] == 1) ? $timer : "Esito alle ".mydatetime($riga['data_aggiornamento']);
		$from = ($riga['issue'] == 6) ? " da $timeDiff " : $from;
		$query = 'SELECT id,proprietario,start_meeting FROM fl_meeting_agenda WHERE start_meeting = \''.$riga['start_meeting'].'\' AND proprietario = \''.$riga['proprietario'].'\'';
		mysql_query($query);
		$totalmet = mysql_affected_rows(CONNECT);
		$potential = GRD($tables[106],$riga['potential_rel']);
		$class = '';
		
		$to_time = time();
		$from_time = strtotime($riga['start_meeting']." ".$riga['start_meeting']);
		$minutes = ($to_time - $from_time)/60;
		
		if( ($riga['start_meeting'] < date('Y-m-d')  && $riga['issue'] < 1 ) || ($riga['start_meeting'] == date('Y-m-d')  && $minutes > 30 && $riga['issue'] < 1) ) {
		$class = 'tab_red';
		}
		if($riga['issue'] == 1) $class = 'tab_orange';
		if($riga['issue'] == 6) $class = 'tab_blue';

		
		
			
	    /* SMS*/
		$phone = phone_format($potential['telefono'],'39');		
		$query = 'SELECT * FROM `fl_sms` WHERE `from` LIKE \''.$phone.'%\' ORDER BY `data_ricezione` DESC LIMIT 1';
		$sms = mysql_fetch_array(mysql_query($query));
		$smsbody = (isset($sms['body']) && strlen($phone) > 4) ? '<br><span class="info_class c-red"><strong>Lead Scrive:</strong>('.mydatetime($sms['data_ricezione']).') '.$sms['body'].'</span>' : '';
		$add_calendar = 'https://calendar.google.com/calendar/render?action=TEMPLATE&text=Appuntamento con: '.$potential['nome'].' '.$potential['cognome'].'&location='.$meeting_location[$riga['meeting_location']].'&details=Trovi questo appuntamento anche sul CRM&dates='.substr(str_replace('-','',$riga['start_meeting']),0,8).'T'.substr(str_replace(':','',$riga['start_meeting']),11,8).'/'.substr(str_replace('-','',$riga['start_meeting']),0,8).'T'.substr(str_replace(':','',$riga['start_meeting']),11,8).'&sf=true&pli=1';

		
		
			echo "<tr ><td $colore><span class=\"Gletter\"></span></td>"; 
			echo "<td><h2><strong>$alarm</i></strong> ".mydatetime($riga['start_meeting'])."</h2> </td>"; 
			echo "<td><strong>".$potential['nome']." ".$potential['cognome']."</strong><br> ".$phone."</td>"; 
			echo "<td><span class=\"msg green\">".$meeting_location[$riga['meeting_location']]."</span></td>"; 
			echo "<td><strong>".$promoter[$riga['proprietario']]."</strong><br>BDC Rif. ".$proprietario[$riga['callcenter']]."</td>"; 
			echo "<td><span class=\"info_class $class\">".$issue[$riga['issue']]." $from $smsbody <br><strong>Note:</strong> ".stripcslashes(converti_txt($riga['note']))."</span></td>"; 
			//echo ($_SESSION['usertype'] != 4) ?  "<td><a title=\"Start Meeting\"  class=\"small_touch blue_push\" href=\"mod_meeting.php?external&action=1&amp;sezione=".@$riga['sezione']."&amp;id=".$riga['id']."&potential_rel=".$riga['potential_rel']."\" title=\"Meeting id ".ucfirst($riga['id'])."\">Start meeting</a></td>" : "";
			echo ($_SESSION['usertype'] == 4) ?  '<td><a href="mod_opera.php?profile_rel='.$potential['id'].'&id='.$riga['id'].'&issue=1&notab" class="small_touch orange_push">Arrivato</a><a href="mod_opera.php?profile_rel='.$potential['id'].'&id='.$riga['id'].'&issue=6&notab" class="small_touch blue_push">In meeting</a><a href="mod_opera.php?profile_rel='.$potential['id'].'&id='.$riga['id'].'&issue=3&notab" class="small_touch red_push">Not Show</a></td>' : "";
			echo "<td><a href=\"mod_inserisci.php?external&action=1&amp;sezione=".@$riga['sezione']."&amp;id=".$riga['id']."&potential_rel=".$riga['potential_rel']."\" title=\"Meeting details for id: ".ucfirst($riga['id'])."\"> <i class=\"fa fa-search\"></i> </a><a target=\"_blank\" href=\"$add_calendar\" style=\"color: green;\"><i class=\"fa fa-calendar-check-o\"></i></a></td>";
		    if($_SESSION['usertype'] == 0) echo "<td><a href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=".$riga['id']."\" title=\"Elimina\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i></a></td>"; 
		
				
		    echo "</tr>";
			
			
			
	}

	echo "</table>";
	
 $start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,1); 
?>
