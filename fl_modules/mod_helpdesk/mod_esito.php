<?php 


$_SESSION['request'] = check($_GET['newRequest']); 

if(isset($_SESSION['request']) && !isset($_SESSION['lastSentRequest'])) {
$request = GRD($tabella,$_SESSION['request']);

$messaggio = '<h2>'.$request['oggetto'].'</h2>';
$messaggio .= '<p>'.converti_txt($request['messaggio']).'</p>';
if($request['ordine_id'] != '') $messaggio .= '<p>Ordine id: '.$request['ordine_id'].'</p>';
$messaggio .= '<p>Priorit&agrave;: '.$priorita[$request['priorita']].'</p>';
$messaggio .= '<p>Categoria: '.@$tipologia_hd[$request['tipologia_hd']].'</p>';
$messaggio .= '<p>Nome: '.$request['nominativo'].'</p>';
$messaggio .= '<p>Email: '.$request['email_contatto'].'</p>';
$messaggio .= '<p>Telefono: '.$request['telefono_contatto'].'</p>';
$messaggio .= '<p>Data Apertura: '.mydatetime($request['data_creazione']).'</p>';
$messaggio = str_replace("[*CORPO*]",$messaggio,mail_template); 

$_SESSION['lastSentRequest'] = $_SESSION['request'];
smail($request['email_contatto'],"Nuovo Ticket ID: #".$_SESSION['request'],$messaggio);
smail(mail_admin,"Nuovo Ticket ID: #".$_SESSION['request'],$messaggio);
smail('fersinoandrea@gmail.com',"Nuovo Ticket ID: #".$_SESSION['request'],$messaggio);
} else {  }

?>

<h2 style="padding: 10px; text-align: center; width: 100%; "> Apertura Nuovo Ticket  </h2>
<span class="msg green" style="padding: 10px; text-align: center; width: 100%; "><i style="font-size: 300%; padding: 10px; text-align: center;" class="fa fa-check-square-o" aria-hidden="true"></i>
<br />La tua richiesta è stata inoltrata al reparto di competenza</span>
<p class="msg gray" style="padding: 10px; text-align: center; width: 100%; "><i class="fa fa-envelope-o" aria-hidden="true"></i> Una copia della richiesta ti è stata spedita alla mail indicata. Controlla nello Spam se non la ricevi.</p>

<span style="padding: 10px; text-align: center; width: 100%; ">

<p>Ti risponderemo il più presto possibile, puoi eseguire una nuova richiesta cliccando sul tasto + </p>
<p><a class="button"  href="./">Torna alle Richieste </a><br></p>

</span>