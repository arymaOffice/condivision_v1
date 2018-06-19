<?php
// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
$_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];
?>

<table class="dati">
<tr>
  <th style="width: 1%;"></th>
  <th>Job</th>
  <th>Serial</th>
  <th>Item</th>
  <th>Country/Location</th>
  <th>Customer</th>
  <th>Owner</th>
  <th>Plant</th>
  <th>Pump Type</th>
  <th>Service </th>
  <th>ResponsabilePE </th>
  <th ></th>
</tr>
</table>
<?php 


$file="pompe.csv";
$delimiter = ";";
$echo = "<table class=\"dati\">";  //variabile da stampare
//apro il file
if (($fp = fopen($file, "r")) !== false)
{
$utenti = array("Username");
 // per ogni riga del file...
 while (($data = fgetcsv($fp, 1000, $delimiter)) !== false) {
 //...inserisco una riga nella tabella
 $echo .= "<tr>";
 $x = 0;

 
 foreach( $data as $el ) { 
 
 $split = preg_split(";",$el); 
 $style = "";
 
 
 foreach($split as $valore) {
 
 if($x == 0) {   
 
 if(!in_array($valore,$utenti)) { 
// array_push($utenti,$valore); 
 $style = "style=\"background: green;\""; 
 // Esegui lastquery
 $echo .= "<td $style>".$x." - ".$valore."</td>";
 } else {
 $echo .= "<td $style>".$x." - ".$valore."</td>";
 }
 
 } 
 
 if($x > 20) $echo .= "<td $style>".$x." - ".$valore."</td>";
  
 
 $x++; 
 }

 $lastquery = "INSERT INTO fl_clienti VALUES '".$split[1]."' ";

 
 }
 
 $echo .= "</tr>";
 }
 fclose($fp);
}
 
//restituisco la tabella
$echo .= "</table>";
echo $echo;

 

?>