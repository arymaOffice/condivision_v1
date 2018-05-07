<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 

 
include("../../fl_inc/headers.php");?>

<div id="container">

<?php include('../../fl_inc/testata.php'); ?>


<?php include('../../fl_inc/menu.php'); ?>

<div id="content">  

<h1>Gestione Files</h1>
<p class="percorso"><span class="subcolor">&gt;&gt;</span>Home\Gestione Files</p>
<p class="strumenti"><a href="javascript:history.back();" title="Torna Indietro">&lt;&lt; Indietro</a> | <a href="javascript:window.print();" title="Stampa Pagina">Stampa</a></p>
<hr />
<div style="float: right;">
<form id="fm_cerca" action="" method="get">
<p id="cerca"><input name="cerca" type="text" value="<?php if(isset($_GET['cerca'])){ echo check($_GET['cerca']);} else { echo "Inserisci il Testo"; } ?>" onFocus="if(this.value=='Inserisci il Testo')this.value='';"  maxlength="200" class="txt_cerca" /><input type="submit" value="Cerca" class="button" /></p>
</form>
</div>
<br class="clear" />
<h2 style="margin-left: 30px;"><a href="<?php echo $_SESSION['last_referrer']; ?>">&lt; TORNA ALLA LISTA</a></h2>

<?php /* Inclusione Pagina */ if(isset($_GET['action'])) { include($pagine[$_GET['action']]); } else { 

if($_SESSION['usertype'] == 0 || $_SESSION['usertype'] == 4){
include("mod_home.php"); 
} else {
include("mod_user.php"); 
}
} ?>
   




<?php include("../../fl_inc/footer.php"); ?>