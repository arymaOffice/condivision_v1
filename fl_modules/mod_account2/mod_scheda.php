<?php 
// Controlli di Sicurezza
require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
 
include("../../fl_inc/headers.php");
?>
<div id="content">

<?php

$query_us = "SELECT * FROM fl_account WHERE anagrafica = ".check($_GET['anagrafica_id'])." LIMIT 1;";
$riga = mysql_fetch_array(mysql_query($query_us, CONNECT));	
if(mysql_affected_rows() > 0)  { 
$force_id = $riga['id'];
$utente = $riga['user'];
?>

<h1>Dettaglio Account</h1>


<div id="tabs">
<ul>

<li><a href="#tab_account">Dati Account</a></li>
<li><a href="#tab_password">Reimposta password</a></li>
<li><a href="#tab_accessi">Accessi</a></li>

</ul>

<div id="tab_account">

<form id="scheda" action="../mod_basic/action_modifica.php" method="post" enctype="multipart/form-data">
<?php include('../mod_basic/action_estrai.php');  ?>

</form>


</div>
<div id="tab_password">
<form id="scheda2" action="./mod_opera.php" method="post" enctype="multipart/form-data">
<h2>Reimposta la password di accesso</h2>
<input type="hidden" name="modifica_pass_auto" value="<?php echo $didi; ?>">
<input type="submit" class="button" value="Reimposta password" />
</form>
<p>Condivision invia una mail all'utente con la nuova password.</p>

<p>
Versione: 2.1.2 |  Utente: <strong><?php echo $_SESSION['user']; ?></strong> |  IP: <?php echo $_SERVER['REMOTE_ADDR'] ?> | 
<strong>GDPR 2016/679 Informativa sul trattamento dei dati riguardanti il vostro account</strong>
L'accesso alla piattaforma è da intendersi personale ed esclusivamente riservato all'utente autorizzato. 
Ne è vietata la riproduzione sotto ogni forma. La vostra password scade ogni 90 giorni e va reimpostata obbligatoriamente.
In conformità ai requisiti del DL 196 del 30/6/2003, 
si informa che ogni accesso, riconoscibile da IP e username, sarà registrato e potrà essere monitorato.
Le attività eseguite nell'ambiente gestionale sono registrate per motivi di sicurezza.
</p>


</div>


<div id="tab_accessi">
<iframe style="width: 100%; border: none; height: 500px;" src="../mod_accessi/mod_scheda.php?cerca=<?php echo $utente; ?>"></iframe>

</div></div>




<?php } else { ?>
<h1>Account non attivo</h1>
<p>Torna in lista anagrafiche e clicca su "<strong>Attiva account</strong>" per la procedura di creazione account.</p>

<?php } ?>



</div>