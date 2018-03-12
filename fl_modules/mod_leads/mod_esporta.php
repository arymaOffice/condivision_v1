<?php 

	require_once('../../fl_core/autentication.php');
    require_once '../../fl_set/librerie/MailUp/MailUpClient.php';
	
   //Mandare une messaggio ad un gruppo
   //Console/Group/@id_Group/Email/@id_Message/Send
   
    
    $MAILUP_CLIENT_ID = "9233bbfc-57d1-409e-aa64-90ba3a6b099a";
    $MAILUP_CLIENT_SECRET = "a2d21342-b278-4f36-a920-ce4794606a89";
    $MAILUP_CALLBACK_URI =  $_SERVER['REQUEST_URI'];
	$MAILUP_USER = 'm86744_02';
	$MAILUP_PASSWORD = 'Authos2015$'; 

    // Initializing MailUpClient
    $mailUp = new MailUpClient($MAILUP_CLIENT_ID, $MAILUP_CLIENT_SECRET, $MAILUP_CALLBACK_URI);
    
    if ($mailUp->getAccessToken() == NULL) { 
        $mailUp->logOnWithPassword($MAILUP_USER,$MAILUP_PASSWORD);
    }
	$token = $mailUp->getAccessToken();



	include('fl_settings.php'); // Variabili Modulo 

	include("../../fl_inc/headers.php");   
   
    
    // Running Examples
    $exampleResult = "";
    $groupId = -1;
    $emailId = -1;
    
    if (isset($_SESSION["groupId"])) $groupId = $_SESSION["groupId"];
    if (isset($_SESSION["emailId"])) $emailId = $_SESSION["emailId"];
    
    if (isset($_SESSION['view']) && isset($_POST['groupId'])) { 
        try {
            
            $groupId = check($_POST['groupId']);
			$groupNew = check($_POST['nuovoGruppo']);
			
            if ($groupId == -1 && $groupNew != '') {
                $groupId = 1;
                $url = $mailUp->getConsoleEndpoint() . "/Console/List/1/Group";
                $groupRequest = "{\"Deletable\":true,\"Name\":\"$groupNew\",\"Notes\":\"Creato da Crm il ".date('d/m/Y H:i')."\"}";
                $result = $mailUp->callMethod($url, "POST", $groupRequest, "JSON");
                $result = json_decode($result);  
                $groupId = $result->idGroup;
            }
            $_SESSION["groupId"] = $groupId;
            
            /* Richiesta lista campi dinamici disponibili
            $url = $mailUp->getConsoleEndpoint() . "/Console/Recipient/DynamicFields";
            $result = $mailUp->callMethod($url, "GET", null, "JSON");
           	*/

            
			// Inserimento Recipients
 			$recipientRequest = '';
        	$Recipients = mysql_query($_SESSION['view'], CONNECT);
			while ($riga = mysql_fetch_array($Recipients)) 
			{
				if(strlen($riga['email']) > 5) $recipientRequest .= "{\"Email\":\"".$riga['email']."\",\"Name\":\"".$riga['nome']."\",\"Fields\":[{\"Description\":\"String description\",\"Id\":1,\"Value\":\"".$riga['nome']."\"}],\"MobileNumber\":\"".$riga['telefono']."\",\"MobilePrefix\":\"\"},";
			}
	

            $url = $mailUp->getConsoleEndpoint() . "/Console/Group/" . $groupId . "/Recipients";
            $recipientRequest = "[$recipientRequest]";
		    $result = $mailUp->callMethod($url, "POST", $recipientRequest, "JSON");
            $importId = $result;
            
            
            // Check the import result
            $url = $mailUp->getConsoleEndpoint() . "/Console/Import/" . $importId;
            $result = $mailUp->callMethod($url, "GET", null, "JSON");
           
            unset($_SESSION['view']);
			unset($_SESSION['groupId']);
			
        } catch (MailUpException $ex) {
            $exampleResult = "Error " . $ex->getStatusCode() . ": " . $ex->getMessage();
        }
    }
    

?>



<style>
.form_row, .salva { width: 100%; }
.input_text label, .labelbox, .select_text label {
    display: inline-block;
    width: 25%;
    font-size: 20px;
    margin: -23px 8px 0 0;
    position: relative;
    text-align: right;
    padding-right: 20px;
    color: #999;
}
.input_text { border: none;}
.input_text input,textarea {
    width: 100%;
    font-size: 0.9em;
    border: none;
    padding: 10px;
    border-bottom: 1px solid;
    height: 50px;
    background: none;
}
.input_text textarea { height: 180px; background: white;}
</style>
<body style="width: 100%; background: white;">

<div style="width: 100%; background: white;">


<div id="results"></div>
<?php if(isset($importId)) {  echo "<h1>Importazione Eseguita: id $importId</h1>"; } else {  ?>
<form action="mod_esporta.php" method="POST">

<p id="pAuthorization"><?php //echo $token; ?></p><br /><br />

<h1>
<?php 

    $noway = 0;
	
	if(isset($_SESSION['destinatari'])){ 

	foreach($_SESSION['destinatari'] as  $valore){
		$destinatari[] = $valore;
		} 
		$query = "SELECT id,telefono,email,nome,cognome FROM `$tabella` WHERE `id` IN (" . implode(',', array_map('intval', $destinatari)) . ") ;";
	} else {
	$query = "SELECT $select FROM `$tabella` $tipologia_main;";
	}	
	
	$_SESSION['view'] = $query;
	
	$risultato = mysql_query($query, CONNECT);
	while ($riga = mysql_fetch_array($risultato)) 
	{
		if(strlen($riga['email']) > 5) { $destinatari[] =  $riga['id'].':'.$riga['email']; } else { $noway++; }
	}
	echo mysql_affected_rows()-$noway;

 ?> persone <?php if( $noway > 0) echo  '('.$noway.' senza email)'; ?>
 
 </h1>
<?php 

			$url = $mailUp->getConsoleEndpoint() . "/Console/List/1/Groups";
            $result = $mailUp->callMethod($url, "GET", null, "JSON");
            $result = json_decode($result);
            $arr = $result->Items;
            $select = '<select name="groupId"><option value="-1">Seleziona...</option>';
			for ($i = 0; $i < count($arr); $i++) {
				$group = $arr[$i];
                 $select .=  '<option value="'.$group->idGroup.'">'.$group->Name.'</option>';
            }
			$select .= '</select>';
			echo $select; 
			?><br><br>
            
oppure crea un nuovo gruppo <br><br>
<input type="text" name="nuovoGruppo" value="" placeholder="Nuovo gruppo" /><br><br>
<input type="submit" value="Importa contatti" class="button" />

</form>
 <?php } ?>
<br><br><br>
<a href="http://login.mailup.it/" target="_blank">Apri MailUp</a>
</div></body>
</body>
</html>
