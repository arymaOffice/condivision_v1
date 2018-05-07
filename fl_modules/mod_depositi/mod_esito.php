<h1> Esito Operazione </h1>
<?php

$baseref = @explode('?', @$_SERVER['HTTP_REFERER']);
$rct = $baseref[0]; 
$val = (count($baseref) > 1) ? $baseref[1] : "";
$valb = explode('#',$val);
$vars = $valb[0];
$color = (!isset($_GET['error'])) ? 'green' : 'red';

 echo "<div  class=\"esito $color \" style=\"color: white;\">".check($_GET['esito']); ?></div>

 <div style="text-align: center;"> <a href="../mod_depositi/?action=11&causale=84&a=gestione_pvr" class="button">Nuova operazione</a>

 	<a href="../mod_depositi/?mod_depositi/?a=contabilita" class="button">Depositi e Prelievi </a>

