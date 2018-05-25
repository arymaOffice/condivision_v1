<?php 


session_start(); 
$cookieLifetime = 365 * 24 * 60 * 60; // A year in seconds
setcookie(session_name(),session_id(),time()+$cookieLifetime);
$jquery = 1;

if(isset($_SESSION['number'])){ header("Location: index.php"); exit; }
require('fl_core/settings.php'); 
include('fl_core/dataset/array_statiche.php');


include("fl_inc/headers.php");
?>


<style>
#container { min-width: 0;}
.salva,.button,input, select, textarea {
    	width: 99%;
	padding: 10px;
	border-color: #eee;
}
.salva,.button {    width: 100%; }
img { max-width: 100%;}
</style>


<!--<div id="up_menu">
<span class="appname">
<a href="<?php echo ROOT.$cp_admin; ?>"><img src="<?php echo LOGO; ?>" alt="<?php echo client; ?>"/></a></span>
</div>-->




<div style="color: #4C4C4C; max-width: 350px; height: auto; text-align: center; margin: 5% auto; padding: 20px 20px; background-color: white;
border-style: solid;
border-width: 1px;
border-color: #CCCCCC;
border-radius: 6px;
box-shadow: 0px 1px 5px 0px #DDD;">
<h2>Inserisci le tue credenziali per accedere<span class="subcolor"></span></h2>

<form id="fm_accesso" action="fl_core/login.php" method="post">

<div class="span_row">
<br />
<input accesskey="u" type="text" id="user" name="user" value=""  placeholder="User" class="cerca" style="width: 93%;" />
<br />

</div>

<div class="span_row">
<br />
<input accesskey="u" type="password" id="pwd" name="pwd" value="" placeholder="Password" class="cerca" style="width: 93%;" />
<input type="hidden" id="idh" name="idh" value="<?php echo $_SERVER['REMOTE_ADDR']; ?>"  />
<br />

</div>

<div class="span_row">
  <p>
  <input value="Accedi" type="submit"  class="button" />

  </p>

  <?php if(isset($_GET['esito'])) { echo '<h2 class="red" style="padding: 10px;  margin: 0 auto;">'.check($_GET['esito']).'</h2>'; } ?>
</div>
</form>
</div>
