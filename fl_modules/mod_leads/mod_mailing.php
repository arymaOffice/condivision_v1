<?php

// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
$_SESSION['POST_BACK_PAGE'] = $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];

?>

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


<h1>Lead Management</h1>   
<div class="hidemobile" style="    height: auto;
    float: left;">
   <div class="module_icon"> <a href="<?php echo ROOT.$cp_admin; ?>fl_modules/mod_leads/?priorita_contatto=0"  style="color: #848484;"><span style="font-size: 200%;"><?php echo get_count(0,'priorita_contatto'); ?></span><br />
      Bassa</a> </div>

   <div class="module_icon"> <a href="<?php echo ROOT.$cp_admin; ?>fl_modules/mod_leads/?priorita_contatto=1" style="color: orange;"><span style="font-size: 200%;"><?php echo get_count(1,'priorita_contatto'); ?></span><br />
      Media</a> </div>

   <div class="module_icon"> <a href="<?php echo ROOT.$cp_admin; ?>fl_modules/mod_leads/?priorita_contatto=2" style="color: red;"><span style="font-size: 200%;"><?php echo get_count(2,'priorita_contatto'); ?></span><br />
      Alta</a> </div>

 
       <br class="clear">
</div>


 <div id="filtri" class="filtri">
 <h2>Filtri</h2>
<form method="get" action="" id="fm_filtri">
   <input  type="hidden" name="action" value="19" />
  <?php $data_set->do_select('VALUES',$tabella,'industry','Industry',$industry_id); ?>
         <?php if($_SESSION['usertype'] == 0) { ?>
    
       <label>  Priorita</label>
     
          <select name="priorita_contatto" id="priorita_contatto" >
   <option value="-1">Tutti</option>
        
      <?php 
              
		     foreach($priorita_contatto as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($priorita_contatto_id == $valores) ? " selected=\"selected\"" : "";
			 echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; 
			}
		 ?>
    </select> 
    
    <label>  Sorgente</label>
     
          <select name="source_potential" id="source_potential" >
   <option value="-1">Tutti</option>
        
      <?php 
              
		     foreach($source_potential as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($source_potential_id == $valores) ? " selected=\"selected\"" : "";
			 echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; 
			}
		 ?>
    </select>   <?php $data_set->do_select('VALUES',$tabella,'interessato_a','interessato_a',$interessato_a_id); ?>

  <!--   <label>  Marchio</label>
     
          <select name="marchio" id="marchio" onChange="form.submit();">
   <option value="-1">Tutti</option>
        
      <?php 
              
		     foreach($marchio as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($marchio_id == $valores) ? " selected=\"selected\"" : "";
			if($valores != 0){ echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; }
			}
		 ?>
    </select> -->

<?php $data_set->do_select('VALUES',$tabella,'in_use','operatore',$operatore_id,'','','','',$proprietario); ?>
       
    
    
<?php } ?>


        <label>  Lista</label><select name="status_potential" id="status_potential">
           
           <option value="-1">Tutti</option>
			<?php 
              
		     foreach($status_potential as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($status_potential_id == $valores) ? " selected=\"selected\"" : "";
			 echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; 
			}
		 ?>
       </select>
    
  <label>  da</label> <input type="text" name="data_da" onFocus="this.value='';" value="<?php  echo $data_da_t;  ?>"  class="calendar" size="8" /> 
  <label>  a</label>  <input type="text" name="data_a" onFocus="this.value='';" value="<?php  echo $data_a_t;  ?>" class="calendar" size="8" /> 
        
       <input type="submit" value="<?php echo SHOW; ?>" class="button" />

       </form>
     
    
      </div>
      
      
<?php


	if(isset($_GET['ordine'])) { if(!is_numeric($_GET['ordine'])){ exit; } else { $ordine = $ordine_mod[$_GET['ordine']]; }}
	
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0);
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";

	$risultato = mysql_query($query, CONNECT);
	//if($_SESSION['number'] == 1) echo $query.mysql_error();
	?>

 <p style="clear: both; text-align: right;">
<a class="button" href="mod_inserisci.php?id=1" style=" font-weight: 500;    padding: 8px 20px;
    font-size: 125%;"><i class="fa fa-plus-circle"></i> Nuovo </a></p> 
 <form action="./mod_opera.php" id="print" method="get" style="padding: 0; margin: 0;">       
	
  	 <table class="dati" summary="Dati" style=" width: 100%;">
        <tr>
     <th></th>
       <th>List</th>
         <th>Lead</th>
       <th>Note</th>
       <th>Interesse</th>
        <th>Campagna</th>
       <th>Gestisci </th>
    <th>SMS</th>  
        <th style="width: 1%;"><input onclick="checkAllFields(1);" id="checkAll"  name="checkAll" type="checkbox" style="display: block;"  /> </th>

      </tr>
	<?php 
	
	$i = 1;
	function www($url) {
	$num = '';
	if($url != '') $num = "http://".str_replace("http://","",str_replace(" ","",$url));
	return $num;
	}

	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"9\">No records</td></tr>";		}
	$tot_res = $count = 0;
	while ($riga = mysql_fetch_array($risultato)) 
	{
		$colore = "class=\"tab_blue\"";
		if($riga['status_potential'] == 0) { $colore = "class=\"tab_blue\"";  }
		if($riga['status_potential'] == 1) { $colore = "class=\"tab_orange\"";  }
		if($riga['status_potential'] == 2) { $colore = "class=\"tab_orange\"";  }
		if($riga['status_potential'] == 3) { $colore = "class=\"tab_red\"";  } 
		if($riga['status_potential'] == 4)  { $colore = "class=\"tab_green\"";  }
		
		if($riga['priorita_contatto'] == 0) { $priocolor = "gray";  }
		if($riga['priorita_contatto'] == 1) { $priocolor = "orange";  }
		if($riga['priorita_contatto'] == 2) { $priocolor = "red";  }
		$input = '';
		if($_SESSION['usertype'] < 3) { 
		$checked = ($riga['status_potential']==0) ? 'checked' : '';
		$count += ($riga['status_potential']==0) ? 1 : 0;
		$input =  '<input onClick="countFields(1);" type="checkbox" name="leads[]" value="'.$riga['id'].'" '.$checked.'  style="display: block;" />';
		}


		
		$lastAction = (get_lastAction($tab_id,$riga['id']) != NULL) ? mydatetime(get_lastAction($tab_id,$riga['id'])) : mydate($riga['data_creazione']);
		$query = 'SELECT * FROM `fl_meeting_agenda` WHERE potential_rel = '.$riga['id'].'';
		mysql_query($query);

		  /* SMS*/
		$phone = phone_format($riga['telefono'],'39');	
		$website = www($riga['website']);		
		$query = 'SELECT * FROM `fl_sms` WHERE `to` LIKE \''.$phone.'%\' ORDER BY `data_ricezione` DESC LIMIT 1';
		$sms = mysql_fetch_array(mysql_query($query));
		$send = '<a href="../mod_sms/mod_inserisci.php?action=1&id=1&to='.$phone.'&from='.crm_number.'" data-fancybox-type="iframe" class="fancybox_view"><i class="fa fa-envelope"></i></a> ';

		$smsbody =  (isset($sms['body']) && strlen($phone) > 4) ? '<br><span class="c-red"><strong>Ultimo SMS inviato:</strong> '.$sms['body'].'</span>' : '';
		$new_contract = ($riga['is_customer'] > 1) ? '../mod_anagrafica/mod_inserisci.php?id='.$riga['is_customer'].'&meeting_id=0' : '../mod_anagrafica/mod_inserisci.php?id=1&meeting_id=0&j='.base64_decode($riga['id']).'&nominativo='.$riga['nome'].' '.$riga['cognome'];
		$color_contract = ($riga['is_customer'] > 1) ? 'c-green' : '';
		
			echo "<tr><td $colore><span class=\"Gletter\"></span></td>"; 
			echo "<td>".$status_potential[$riga['status_potential']]."<br>".$lastAction."<br><strong>".$proprietario[$riga['proprietario']]."</strong></td>"; 
			echo "<td>".$riga['company']." <strong>".$riga['nome']." ".$riga['cognome']."</strong><br><span class=\"msg $priocolor\">".$priorita_contatto[$riga['priorita_contatto']]."</span><strong><a href=\"./?industry=".$riga['industry']."\"> ".strtoupper($riga['industry'])."</a></strong></td>";
			echo "<td title=\"Ultimo aggiornamento: ".@$proprietario[$riga['operatore']]." on ".mydatetime($riga['data_aggiornamento'])."\"><br>".substr(converti_txt($riga['note']),0,255)."$smsbody</td>";	
			echo "<td>".$riga['interessato_a']."</td>";
			echo "<td>".$source_potential[$riga['source_potential']]."</td>";
			echo "<td>";
			if($riga['in_use'] == 0) {
			echo "<a class=\"mobile-buttons\" href=\"mod_inserisci.php?external&action=1&amp;sezione=".@$riga['sezione']."&amp;id=".$riga['id']."&potential_rel=".$riga['id']."&nominativo=".$riga['nome']."\" title=\"Gestisci scheda ".ucfirst($riga['nome'])."\"> <i class=\"fa fa-search\"></i> </a>";
			} else {
			echo "<a class=\"mobile-buttons\" href=\"mod_inserisci.php?external&action=1&amp;sezione=".@$riga['sezione']."&amp;id=".$riga['id']."&potential_rel=".$riga['id']."&nominativo=".$riga['nome']."\" title=\"Bloccato: ".@$proprietario[$riga['in_use']]." sta chiamando il contatto\" style=\"color: red;\"> Gestito da ".@$proprietario[$riga['in_use']]."  </a>";
			}
			echo "</td>"; 
			echo "<td class=\"mobile-buttons\">$send"; 
			//echo "<a href=\"$new_contract\"><i class=\"fa fa-user $color_contract\" ></i></a>"; 
			if($_SESSION['usertype'] == 0 ) echo "<a href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=".$riga['id']."\" title=\"Cancella\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i></a>"; 
			echo "</td>";
			echo "<td style=\"text-align:center;\">$input</td>";
		    echo "</tr>";
		

			
			
	}

	echo "</table>";
	
 $start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,1); 

 
?><?php if($_SESSION['usertype'] < 3) {  
 echo '<h3 style="text-align: right; ">
	 <input type="hidden" name="vacancies_multiset" value="1" />
	<strong>Assegna <span  id="counter"> '.$count.' lead</span> </strong> a: 
 <select name="assegna_leads" id="assegna_leads">';
              
		     foreach($proprietario as $valores => $label){ // Recursione Indici di Categoria
			$selected = (@$_SESSION['proprietario_id'] == $valores) ? " selected=\"selected\"" : "";
			 echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; 
			}
		
 echo '</select> <input type="submit" value="Assegna" class="button">
</h3>';
	} ?>
</form>
mod_notifiche/mod_invia.php?checkAll=on&destinatario[]=12&destinatario[]=14