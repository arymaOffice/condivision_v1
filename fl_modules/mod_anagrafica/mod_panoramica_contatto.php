<?php 
require_once('../../fl_core/autentication.php');
$loadSelectComuni = 1;

$view = 1;// Opzione vista
$id = ($_SESSION['usertype'] > 1) ? $_SESSION['anagrafica'] : check($_GET['id']);
if($_SESSION['usertype'] > 1) $force_id = $_SESSION['anagrafica']; 

include('fl_settings.php'); // Variabili Modulo 

if($id > 1) {
$profilo = @GRD('fl_anagrafica',@$id); 
$account = get_account($id);
}
unset($text_editor);
include("../../fl_inc/headers.php");
include("../../fl_inc/testata_mobile.php");
 ?>


<body style="background: white;">

<div id="container" style=" background: white; margin-top: 30px; " >
<p class="savetabs"><a href="#" id="invio" class="button salva">Aggiorna Anagrafica <i class="fa fa-check"></i></a></p>

<div id="results"></div>




<?php 

if(ATTIVA_ACCOUNT_ANAGRAFICA == 1 && $id > 1) {
echo '<div class="box_div"><h2>Account</h2>';
if(@$account['id'] > 0)  { 
$attivo  = ($account['attivo'] == 1) ? 'checked' : '';
$sospeso = ($account['attivo'] == 0) ? 'checked' : '';
$date = strtotime($account['aggiornamento_password']."+90 day"); 
$password_update = giorni(date('Y-m-d',$date));

$query = "SELECT * FROM `fl_accessi` WHERE id != 1 AND utente = '".$account['user']."' AND pagina = 'Login' ORDER BY id DESC LIMIT 0,10 ;";
$risultato = mysql_query($query, CONNECT);
if(mysql_affected_rows() == 0) { $lastLogin = "Nessun Accesso ultimi 3 mesi"; } else {
$riga = mysql_fetch_array($risultato);
$lastLogin  = mydatetime($riga['data_creazione']);
}
	


?>
<div class="form_row"><p class="print_p"><label>Username:</label> <span><?php echo $account['user']; ?></span></p></div>
<div class="form_row" style="clear:both;"><p class="print_p"><label>Tipo di Accesso:</label> <span><a class="msg orange"><?php echo $tipo[$account['tipo']]; ?></a></span></p></div>
<div class="form_row"><p class="print_p"><label>Data Registrazione:</label> <span><?php echo mydate($account['data_creazione']); ?></span></p></div>
<div class="form_row"><p class="print_p"><label>Ultimo Login:</label> <span><?php echo $lastLogin; ?><a data-fancybox-type="iframe" class="fancybox" title="Modifica Account" href="../mod_account/mod_visualizza.php?external&id=<?php echo $account['id']; ?>&tId=tab_accessi"><?php echo $account['visite']; ?> accessi</a></span></p></div>

<div class="form_row"><p class="print_p"><label>Email Accesso:</label> <span><?php echo $account['email']; ?></span></p></div>
<div class="form_row"><p class="print_p"><label>IP Accesso:</label> <span><?php echo ($account['ip_accesso'] == 0) ? '0' : $account['ip_accesso']; ?> - <a data-fancybox-type="iframe" class="fancybox" title="Modifica Account" href="../mod_account/mod_visualizza.php?external&id=<?php echo $account['id']; ?>&tId=tab_permessi">Permessi</a></span> </p></div>
<div class="form_row"><p class="print_p"><label>Scadenza Password:</label> <span>Tra <?php echo $password_update.' giorni'; ?> - <a data-fancybox-type="iframe" class="fancybox" title="Modifica Account" href="../mod_account/mod_visualizza.php?external&id=<?php echo $account['id']; ?>&tId=tab_password">Reimposta Password</a></span></p></div>
<br class="clear" />
<div class="form_row"><p class="print_p"><label>Stato:</label> <span>
<input <?php echo $attivo; ?> type="radio" class="updateField" value="1"  data-gtx="8" name="attivo" id="attivo1" data-rel="<?php echo $account['id']; ?>" style="display:  inline-block; width:  auto;" /><label class="updateField" value="1"  data-gtx="8" name="attivo" data-rel="<?php echo $account['id']; ?>" for="attivo1" class="green" style="margin-left: 0;">Attivo</label>
<input <?php echo $sospeso; ?>  type="radio" class="updateField" value="0"  data-gtx="8" name="attivo" id="attivo2" data-rel="<?php echo $account['id']; ?>" style="display:  inline-block;  width:  auto;" /><label  class="red" for="attivo2">Sospeso</label></span></p></div>
<div class="form_row"><p class="print_p"><label>Motivo Sospensione:</label> <span><textarea style="height: 80px;" data-gtx="8" name="motivo_sospensione" data-rel="<?php echo $account['id']; ?>" class="updateField" rows="3" ><?php echo $account['motivo_sospensione']; ?></textarea></span></p></div>
<p class="leggi"><a data-fancybox-type="iframe" class="fancybox" title="Modifica Account" href="../mod_account/mod_visualizza.php?external&id=<?php echo $account['id']; ?>">Modifica</a></p>


<?php } else { echo  "<p>Non esiste un account per questa anagrafica</p><br><p><a class=\"button\" href=\"../mod_account/mod_inserisci.php?external&anagrafica_id=".$profilo['id']."&email=".$profilo['email']."&nominativo=".$profilo['ragione_sociale']."\">Crea account</a></p>"; } echo '</div>'; }?>


<form id="scheda" action="../mod_basic/action_modifica.php" method="post" enctype="multipart/form-data">


<?php include('../mod_basic/action_panoramica.php'); ?>
<input type="hidden" name="dir_upfile" value="icone_articoli" />

</form>



<br class="clear" />
</div>
</body></html>
