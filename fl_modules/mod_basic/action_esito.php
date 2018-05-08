<h1> Esito Operazione </h1>
<?php

$baseref = @explode('?', @$_SERVER['HTTP_REFERER']);
$rct = $baseref[0]; 
$val = (count($baseref) > 1) ? $baseref[1] : "";
$valb = explode('#',$val);
$vars = $valb[0];

 echo "<div  class=\"esito red \" style=\"color: white;\">".check($_GET['esito']); ?></div><br /><br />
<?php if(isset($documentazione_auto) && $_SESSION['usertype'] > 0 && $_SESSION['usertype'] != 4 && !isset($_GET['error'])){?>

<a class="button" title="Documenti" href="../mod_documentazione/?modulo=0&amp;cat=<?php echo $documentazione_auto; ?>&amp;contenuto=<?php echo check($_GET['id']); ?>">CARICA DOCUMENTI</a>
<?php } else {
 if(!isset($_GET['indietro'])){ ?>
<a class="button" title="Torna Indietro" href="javascript:history.back();">&lt;&lt;Indietro</a>
<?php }
if(!isset($_GET['ok']) && !isset($_GET['error'])){ ?>
&nbsp;<a class="button" title="Ok" href="./<?php if(isset($_GET['sezione'])) echo "?sezione=".check($_GET['sezione']);?><?php if(isset($_GET['item_rel'])) echo "?item_rel=".check($_GET['item_rel']);?>" style="padding-left: 40px; padding-right: 50px;"> Ok </a>
<?php } } ?>
