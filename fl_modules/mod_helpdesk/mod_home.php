<?php 
// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit; }
$_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];


?>
<div class="col-sm-2">          
<div style="margin-right: 10px;" class="xe-widget xe-progress-counter green" data-count=".num" data-from="0" data-to="520" data-suffix="k" data-duration="3">
						<a href="./?stato_hd=1">
						<div class="xe-background">
							<i class="fa fa-headphones"></i>
						</div>
						
						<div class="xe-upper">
							<div class="xe-icon">
								<i class="fa fa-headphones"></i>
							</div>
							<div class="xe-label">
								<span>Aperti</span>
								<strong class="num"><?php echo mk_count('fl_helpdesk','id != 1 AND `stato_hd` = 1  '); ?></strong>
							</div>
						</div>
						
					
                        </a>             
</div>  
</div>
<div class="col-sm-2">          
<div style="margin-right: 10px;" class="xe-widget xe-progress-counter orange" data-count=".num" data-from="0" data-to="520" data-suffix="k" data-duration="3">
						<a href="./?stato_hd=2">
						<div class="xe-background">
							<i class="fa fa-headphones"></i>
						</div>
						
						<div class="xe-upper">
							<div class="xe-icon">
								<i class="fa fa-headphones"></i>
							</div>
							<div class="xe-label">
								<span>Attesa Cliente </span>
								<strong class="num"><?php echo mk_count('fl_helpdesk','id != 1 AND `stato_hd` = 2'); ?></strong>
							</div>
						</div>
						
					
                        </a>             
</div>  
</div>
<div class="col-sm-2">          
<div style="margin-right: 10px;" class="xe-widget xe-progress-counter gray" data-count=".num" data-from="0" data-to="520" data-suffix="k" data-duration="3">
						<a href="./?stato_hd=0">
						<div class="xe-background">
							<i class="fa fa-headphones"></i>
						</div>
						
						<div class="xe-upper">
							<div class="xe-icon">
								<i class="fa fa-headphones"></i>
							</div>
							<div class="xe-label">
								<span>Chiusi </span>
								<strong class="num"><?php echo mk_count('fl_helpdesk','id != 1 AND `stato_hd` = 0'); ?></strong>
							</div>
						</div>
						
					
                        </a>             
</div>  
</div>


<br class="clear" />

<div class="filtri" id="filtri"> 
<form method="get" action="" id="fm_filtri">
<h2> Filtri</h2>  

<?php if(isset($_GET['action'])) echo '<input type="hidden" value="'.check($_GET['action']).'" name="action" />'; ?>
<?php if(isset($_GET['start'])) echo '<input type="hidden" value="'.check($_GET['start']).'" name="start" />'; ?>

<?php 

	foreach ($campi as $chiave => $valore) 
	{		
			if(in_array($chiave,$basic_filters)){// Se sono contemplati nei filtri di base li gestisce
			
			echo '<div class="filter_box">';
			
	
			
			if((select_type($chiave) == 2 || select_type($chiave) == 19 || select_type($chiave) == 9)) {
				echo '<label>'.$valore.'</label>';
				echo '<select name="'.$chiave.'" class="select"><option value="-1">Tutti</option>';
				foreach($$chiave as $val => $label) { $selected = (isset($_GET[$chiave]) && check(@$_GET[$chiave]) == $val) ? 'selected' : ''; echo '<option '.$selected.' value="'.$val.'">'.$label.'</option>'; }
				echo '</select>';
			} else { 
			$cont = (isset($_GET[$chiave])) ? check($_GET[$chiave]) : ''; 
			echo '<label>'.$valore.'</label><input type="text" name="'.$chiave.'" value="'.$cont.'" />'; 
			}
			
			echo '</div>';
			} 
		
	}
	 ?>    

    <label> Periodo da</label>
    <input type="text" name="data_da" onFocus="this.value='';" value="<?php  echo $data_da_t;  ?>" class="calendar" size="10" />
    <label> al </label>
    <input type="text" name="data_a" onFocus="this.value='';" value="<?php  echo $data_a_t;  ?>" class="calendar" size="10" />
    
    <input type="submit" value="Mostra" class="salva" id="filter_set" />
  
</form>

     
    </div>

<?php
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main);
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	$risultato = mysql_query($query, CONNECT);
?>

<table class="dati" summary="Dati">
  <tr>
    <th></th>
    <th># Id</th>
    <th>Oggetto</th>
    <th>Account</th>
    <th>Anagrafica</th>
    <th>Riferimenti Ticket</th>
    <th>Data Creazione</th>
    <th></th>
  </tr>
  <?php 
	
	if(mysql_affected_rows() == 0) { echo "</table><h3>Nessun Elemento</h3><table>";		}
	
	while ($riga = mysql_fetch_array($risultato)) 
	{
	
	$azione = (0) ? '' : '';
	$colors = array('gray','green','orange');
	$color = $colors[$riga['stato_hd']];
	
			$operatore = ($riga['operatore'] < 1 || $riga['operatore'] == $riga['account_id']) ? 'Da prendere in carico' : $stato_hd[$riga['stato_hd']].' e preso in carico da: '.$proprietario[$riga['operatore']];
		    
		    if($riga['stato_hd'] == 0) $operatore = "Chiuso alle ".mydatetime($riga['data_chiusura']);
	
			$account = GRD($tables[8],$riga['account_id']);
			$anagrafica = GRD('fl_anagrafica',@$account['anagrafica']);

			$user_check = ($account['id'] < 2) ? '' : '<a data-fancybox-type="iframe" title="Modifica Account" class="fancybox" href="../mod_account/mod_visualizza.php?external&id='.$account['id'].'">'.$account['user'].'</a><br>'.$account['motivo_sospensione'];
			$user_ball = (isset($account['attivo']) && $account['attivo'] == 1)  ? "<span class=\"c-green\"><i class=\"fa fa-user\"></i></span>" : "<span class=\"c-red\"><i class=\"fa fa-user\"></i></span>"; 
			if($account['id'] < 2) $user_ball = '';
			$concessione = (defined('AFFILIAZIONI') && AFFILIAZIONI == 1)  ? " ".@$anagrafica['numero_concessione'] : '';
			$dettaglio_anagrafica = ($anagrafica['id'] < 2) ? '' : @$anagrafica['ragione_sociale']." - P. iva ".@$anagrafica['partita_iva']."<br>".ucfirst(@$anagrafica['sede_legale'])." ".@$anagrafica['cap_sede']. " ".ucfirst($anagrafica['comune_sede']). " (".@$anagrafica['provincia_sede'].")<br><span class=\"msg blue\">".@$marchio[$anagrafica['marchio']]."</span><span class=\"msg orange\">".@$tipo_profilo[$anagrafica['tipo_profilo']]." $concessione </span>";
			
			//$sel = GQD('fl_account','id',' email = \''.$riga['email_contatto'].'\'');
			//$update = ($riga['account_id'] > 2) ? '' : 'UPDATE `fl_helpdesk` SET `account_id`= \''.$sel['id'].'\' WHERE `id` ='.$riga['id'];
			//if($riga['account_id'] < 2) mysql_query($update,CONNECT);
			
			echo "<tr>";
			echo "<td class=\"$color\"></td>";
			echo "<td>#".$riga['id']."</td>";
			echo "<td><h2><a href=\"mod_visualizza.php?id=".$riga['id']."\" title=\"".strip_tags(converti_txt($riga['messaggio']))."\" >".$riga['oggetto']."</a></h2>
			$operatore  <span class=\"msg blue\">".@$tipologia_hd[$riga['tipologia_hd']]."</span><span class=\"msg orange\">".@$priorita[$riga['priorita']]."</span></td>";	
			
			echo "<td>".$user_ball." ".$user_check."</td>";
            echo "<td>$dettaglio_anagrafica</td>";
			echo "<td>".ucfirst($riga['nominativo'])." (".$riga['email_contatto'].") / (".$riga['telefono_contatto'].")<br /><span style=\"color: #cc0000;\">Username: ".ucfirst($riga['username'])."</span></td>";		
			echo "<td>".mydatetime($riga['data_creazione'])."</td>";
			echo "<td><a href=\"mod_visualizza.php?id=".$riga['id']."\" title=\"Modifica\" > <i class=\"fa fa-search\"></i> </a></td>"; 
			echo "<td><a  href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=".$riga['id']."\" title=\"Elimina\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i></a></td>"; 
		    echo "</tr>";
	}

	
	

?>
</table>

<?php $start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0); ?>
