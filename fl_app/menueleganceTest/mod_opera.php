

<?php

session_start();
header('Content-Type: text/html; charset=UTF-8');
ini_set('default_charset', 'utf-8');



require_once("../../fl_core/core.php"); 


	//if(isset($_GET['connect'])) {
	if(isset($_GET['id1'])) {
		//$connetti = check($_GET['connect']);
		
		$type1 = check($_GET['type1']);
		$type2 = check($_GET['type2']);
		$id1 = check($_GET['id1']);			//equivale a connetti
		$id2 = check($_GET['id2']);
		
		//$query = "INSERT INTO `fl_synapsy` (`type1`, `id1`, `type2`, `id2`, `valore`) VALUES ('$tab_id', '$connetti', '$tab_id', '".$_SESSION['synapsy']."', '1')";
		$query = "SELECT * FROM fl_synapsy WHERE type1 = '" . $type1 . "' AND type2 = '" . $type2 . "' AND id1 = '" . $id1 . "' AND id2 = '" . $id2 . "'"; //query per verificare la presenza della portata nel menu
		$risultato = mysql_query($query,CONNECT);
		
		$query = "";
		if(mysql_num_rows ($risultato) > 0){			//se la query trova la portata nel menu in synapsy effettua la cancellazione
			$query = "DELETE FROM fl_synapsy WHERE type1 = '" . $type1 . "' AND type2 = '" . $type2 . "' AND id1 = '" . $id1 . "' AND id2 = '" . $id2 . "'";
		}else{											//altrimanti effettua l'inserimento'
			$query = "INSERT INTO `fl_synapsy` (`type1`, `id1`, `type2`, `id2`, `valore`) VALUES ('$type1', '$id1', '$type2', '$id2', '1')";

		}

		//$query = "INSERT INTO `fl_synapsy` (`type1`, `id1`, `type2`, `id2`, `valore`) VALUES ('$type1', '$id1', '$type2', '$id2', '1')";
		mysql_query($query,CONNECT);
		echo mysql_insert_id();
		mysql_close(CONNECT);
		
		//header('Location: '.$_SESSION['POST_BACK_PAGE']);//da errore
		exit;
	}




	if(isset($_POST['synapsy_id'])) {
		$synapsy_id = check($_POST['synapsy_id']);
		$note = '';
		foreach($_POST as $key => $val) { if($key != 'synapsy_id' && $val != '') $note .= $val.','; }
		$note = rtrim($note,",");
		mysql_query('UPDATE fl_synapsy SET note = \''.$note.'\' WHERE id = '.$synapsy_id.' LIMIT 1',CONNECT);
		mysql_close(CONNECT);
		header('Location: '.$_SERVER['HTTP_REFERER'].'&close');
		exit;
	}


	if(isset($_GET['disaccoppia'])) {
		mysql_query('DELETE FROM fl_synapsy WHERE id = '.check($_GET['disaccoppia']).' LIMIT 1',CONNECT);
		mysql_close(CONNECT);
		header('Location: '.$_SESSION['POST_BACK_PAGE']);
		exit;
	}

	





?>

