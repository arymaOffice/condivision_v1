<?php

// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
$_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];

if($_SESSION['usertype'] != 0)  { echo "Accesso Non Autorizzato"; exit; }

function checkAttachments($estMsg){

if((@$estMsg->type !=0) && (@$estMsg->type !=1))
{
return(true); // Text and multipart parts will not be shown as attachments
}
else{
if(@$estMsg->parts) // If there's no attachments, parts inside will be checked
{
$partMsg=$estMsg->parts;
$i=0;
// Parts will be checked while no attachments found or not all of them checked
while(!(checkAttachments(@$partMsg[$i])) && ($i<sizeof(@$estMsg->parts)))
{
$i++;
}

if($i==sizeof($estMsg->parts)){
return(false);
}
else{
return(true);
}
}
else{
return(false); // If no parts and text or multipart type, no attachments
}
}
}




?>

 <table class="dati">
      <tr>
        <th>Destinatario</th>
        <th>Oggetto</th>
         <th>Allegati</th>
         <th>Leggi</th>
        <th>Elimina</th>          
        <th>Ricevuto</th>   
         <th>Peso</th>   
      </tr>
<?php
	

	

 	
$i = 1;
$totmsg = 0;	

$intestazioni1 = imap_headers($mail_conn1);
$totmsg += count($intestazioni1);


echo "<p>$totmsg messaggi in arrivo.</p>\n";	
	
$var = 0;
while (list ($k, $val) = @each ($intestazioni1))
  {
    $var = $var+1;
    $f_over = @imap_fetch_overview($mail_conn1, $var, 0);
	$struttura=imap_fetchstructure($mail_conn1, $var);
    if(checkAttachments($struttura)){$all=1;}else{$all=0;}	
	//$dimensione=ceil(($struttura->size/1024)); 
	
    while(list($k, $v) = @each($f_over))
    { 
     

	 
	  echo "<tr><td> $username1</td>\n 
      <td>" .$v->from."</td>\n</td>\n
      <td>$all</td>\n
      <td> <a href=\"?conn_id=1&read=1&amp;action=16&amp;id=".$var."\" ><i class=\"fa fa-search\"></i></a></td>\n
	  
      <td><a href=\"mod_opera.php?conn_id=1&delete=1&amp;id=".$var."\"  title=\"Elimina\" onclick=\"conferma_del();\"><i class=\"fa fa-trash-o\"></i></a></td>\n\n
	  <td>".$v->date."</td>\n
	  <td>".ceil(($v->size/1024))." kb</td></tr>\n";
	  
	  
	  
    }
  }


@imap_close($mail_conn1);

	?>
</table>

