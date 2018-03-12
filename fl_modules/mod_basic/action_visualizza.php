<?php 

if(isset($_GET['id'])) { $id = (is_numeric($_GET['id'])) ? check($_GET['id']) : exit;  } 
if(isset($force_id)){ $id = check($force_id); } 
if(isset($_GET['n'])) $id = 1;

$id = (isset($id)) ? $id : 1;



$where = "WHERE id = '$id' ";

if(!isset($posta)){
$sika = "UPDATE fl_conferme SET letto = 1,data_letto = '".time()."',ip = '".$_SERVER['REMOTE_ADDR']."' WHERE utente = ".$_SESSION['number']." AND jorel = 0 AND relation = $id AND letto = 0 LIMIT 1";
} else {
$sika = "UPDATE fl_posta SET letto = 1,data_letto = '".time()."' WHERE id = $id AND destinatario != 0 AND letto = 0 LIMIT 1";
}

@mysql_query($sika,CONNECT);

if(mysql_affected_rows() == 1) $nuovo = 1;


if(isset($newid)) { $id = (is_numeric($newid)) ? check($newid) : exit; $where = "WHERE id = $id ";}	
	
$counter = 0;
	
if(isset($nuovo)) echo "<div class=\"verde\"><h4>Questo messaggio &eacute; stato letto per la prima volta.</h4></div>";
	

$query = "SELECT * FROM `$tabella` $where LIMIT 1;";


if(isset($tab_div_labels)) { 
	$tabs_div = array();	
	foreach($tab_div_labels as $chiave => $valore) { 
	$tabs_div[$chiave] =$valore;
	}

}
	
	
	$risultato = mysql_query($query, CONNECT);
	echo '<div style="background: white; padding: 10px;">';
	while ($riga = mysql_fetch_array($risultato)) 
	{
	
	
		if(isset($riga['destinatario'])) { 
		if($_SESSION['usertype'] > 0 && $riga['destinatario'] != $_SESSION['number'] && $riga['mittente'] != $_SESSION['number']) {echo "Contenuto non trovato."; exit;  }
		}
		
		foreach($riga as $chiave => $valore){
		if(isset($tabs_div)) { if(isset($tabs_div[$chiave])) echo '<h2 id="txt_head_'.$chiave.'" class="txt_header">'.$tabs_div[$chiave].'</h2>'; }		
		
		//Righe per il form		
		if(is_to_use($chiave) == 1){
		
			$size = (is_numeric($valore)) ? 6 : 255;
			
			
			
			$disabled = "";
			if($chiave == "sezione" && $valore > 1){ $disabled = "disabled";  }
			if($chiave == "categoria" && @$rel == 0){ $disabled = "disabled";  }
			
			switch(select_type($chiave)){
			
				
			
			case 1:		// Input Text	
			echo "<p class=\"print_p\" id=\"txt_$chiave\"><label>".record_label($chiave,CONNECT,1).":</label> <span>$valore</span></p>\r\n";
			break;
			
			
			case 2:		 // Select Box	
			echo "<p class=\"print_p\" id=\"txt_$chiave\"><label>".record_label($chiave,CONNECT,1).":</label><span>";			
			foreach($$chiave as $valores => $label){ // Recursione Indici di Categoria
			if($valore == $valores) {echo "".ucfirst($label)."\r\n";}
			}
			echo "</span></p>\r\n";
			break;
			
					
			
			case 3:      // Text Area
			echo "<p class=\"print_p\" id=\"txt_$chiave\"><label>".record_label($chiave,CONNECT,1).":</label></p><div class=\"$chiave\">".converti_txt($valore)."</div>\r\n";
			break;
			case 4:		// Input Text	
			echo "<p class=\"print_p\" id=\"txt_$chiave\"><label>".record_label($chiave,CONNECT,1).":</label> $valore</p>\r\n";
			break;
			

			
			case 6:
		    $cheking = ($valore == 1) ? "checked=\"checked\"" : "";
			echo "<p class=\"print_p\" id=\"txt_$chiave\"><label>".record_label($chiave,CONNECT,1).":</label></p>";
			break;
			
			case 19:		 // Select Box	
			echo "<p class=\"print_p\" id=\"txt_$chiave\"><label>".record_label($chiave,CONNECT,1).":</label>";			
			foreach($$chiave as $valores => $label){ // Recursione Indici di Categoria
			if($valore == $valores) {echo "".ucfirst($label)."\r\n";}
			}
			echo "</p>\r\n";
			break;

			case 20:		// Input Text
			$valore = ($valore != '0000-00-00 00:00:00') ? mydate($valore) : '-';	
			echo "<p class=\"print_p\" id=\"txt_$chiave\"><label>".record_label($chiave,CONNECT,1).":</label> <span>$valore</span></p>\r\n";
			break;

									
			case 25:      // Text Area
			echo "<h2>".$valore."</h2>";
	      
			break;
			
			case 26:      // Text Area
			echo "<p class=\"date\"><label>".record_label($chiave,CONNECT,1).":</label> <span>".date("d/m/Y - H:i",$valore)."</span></p>";
	      
			break;
			
			}}
			
			
			
			
		}	
	}
	echo '</div>';
?>