<div id="preloader"><img src="<?php echo ROOT.$cp_admin; ?>fl_set/lay/preloader.png" /><a href="#" onClick="location.reload();" style="font-size: smaller; display: block; text-align: center;">AGGIORNA</a></div>


<div id="container">

<div id="up_menu">
<div id="menu_toggler"><a href="#" onClick="changeLayout();" title="Nascondi/Mostra Menu principale"><i class="fa fa-navicon"></i></a></div>

<span class="appname">
<a href="<?php echo ROOT.$cp_admin; ?>?a=dashboard"><img src="<?php echo LOGO; ?>" alt="<?php echo client; ?>"/></a></span>
<span class="topdx">
<?php echo $_SESSION['nominativo']; ?> <a class="logout" href="<?php echo ROOT.$cp_admin; ?>fl_core/login.php?logout" title="Sei collegato al server dalle ore: <?php echo @date("H:i",$_SESSION['time'])." come ".@$tipo[$_SESSION['usertype']]; ?>"><i class="fa fa-power-off"></i> <span class="desktop">Esci</span> </a>  


</span>
</div>


<?php if(!isset($nomsg)) { ?>
<div class="info red"></div>
<div class="info orange"></div>
<div class="info orange" ></div>
<?php } ?>

