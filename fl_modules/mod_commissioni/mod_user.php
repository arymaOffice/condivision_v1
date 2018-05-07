<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 

 
include("../../fl_inc/headers.php");?>
<body style=" background: #FFFFFF;">
<div id="print_container">

<h1>Estratto Conto</h1>   
  <span class="info_box tab_green">Saldo: <?php  echo '&euro; '.numdec(balance(0,-1),2); ?> </span>    
   <span class="info_box tab_red">Movimentazioni in sospeso: <?php  echo '&euro; '.numdec(balance(0,0),2); ?> </span>    

<?php
	

	if(isset($_GET['ordine'])) { if(!is_numeric($_GET['ordine'])){ exit; } else { $ordine = $ordine_mod[$_GET['ordine']]; }}
	
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0);
						
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	
	$risultato = mysql_query($query, CONNECT);
	//echo $query;

 		
	?>

 
 
  
  	 <table class="dati" summary="Dati" style=" width: 100%;">
        <tr>
    
       <th scope="col">Stato</th>
       <th scope="col">Utente</th>
       <th scope="col">Data</th>
       <th scope="col">Operazione</th>
       <th scope="col">Importo</th>
    <th scope="col"></th>
      </tr>
	<?php 
	
	$i = 1;
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"9\">Nessun Record Inserito</td></tr>";		}
	$tot_res = 0;
	$incomplete = 0;
	while ($riga = mysql_fetch_array($risultato)) 
	{
			$query_doc = "SELECT * FROM fl_files WHERE relation = ".$riga['proprietario']." AND cat = 8 AND contenuto = ".$riga['id']." ORDER BY titolo ASC";
 			$risultato2 = mysql_query($query_doc, CONNECT);	
			
			
			if($riga['status_pagamento'] == 0){
			$modifica_id = "<a data-fancybox-type=\"iframe\" class=\"fancybox_view\" href=\"mod_visualizza.php?&amp;id=".$riga['id']."\" title=\"Visualizza\"> <i class=\"fa fa-search\"></i> </a>"; 
			$colore = "style=\" background: #FF3A17; color: #FFF;\"";
			} else { 
			$modifica_id = "<a data-fancybox-type=\"iframe\" class=\"fancybox_view\" href=\"mod_visualizza.php?&amp;id=".$riga['id']."\" title=\"Visualizza\"> <i class=\"fa fa-search\"></i> </a>"; 
			$colore = "style=\" background: #009900; color: #FFF;\"";
			}
	
		
			echo "<tr><td $colore>".$status_pagamento[$riga['status_pagamento']]."</td>"; 
			echo "<td>".$proprietario[$riga['proprietario']]."</td>";	
			echo "<td>".mydate($riga['data_operazione'])."</td>"; 
			echo "<td>".$causale[$riga['causale']]."</td>";	
			echo "<td> &euro; ".numdec($riga['importo'],2)."</td>"; 
			
			echo "<td>$modifica_id</td>";
			
			
		    echo "</tr>";
			
			
			
	}

	echo "</table>";
	

?>	


<?php $start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,1); ?>
</div>
</body>
