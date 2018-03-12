<?php 

session_start();
header('Content-Type: text/html; charset=UTF-8');
ini_set('default_charset', 'utf-8');
$_SESSION['user'] = 'menu';
$_SESSION['number'] = 1;
$_SESSION['landing'] = 1;
$_SESSION['form'] = 1;

require_once("../../fl_core/core.php"); 
$client = 1;

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, minimal-ui">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="theme-color" content="#2196f3">
    <title><?php echo client; ?></title>
      <link rel="stylesheet" href="../build/css/framework7.material.css">
    <link rel="stylesheet" href="../build/css/framework7.material.colors.css">
    <link href="//fonts.googleapis.com/css?family=Roboto:400,300,500,700" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="css/kitchen-sink.css">
    <link rel="stylesheet" href="css/mio.css">
  
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"> 
 
<link rel="icon" href="<?php echo ROOT.$cp_admin.$cp_set; ?>css/lay/a.ico" type="image/x-icon" /> 
<link rel="shortcut icon" href="<?php echo ROOT.$cp_admin.$cp_set; ?>css/lay/a.ico" type="image/x-icon" />
<link rel="apple-touch-icon" href="<?php echo ROOT.$cp_admin.'fl_config/'.HTTP_HOST.'/'; ?>img/a.png" />

<link rel="apple-touch-startup-image" href="<?php echo ROOT.$cp_admin.$cp_set; ?>lay/spl.png" media="(max-device-width : 480px) and (-webkit-min-device-pixel-ratio : 2)">
<!-- For iPhone -->
<link rel="apple-touch-icon-precomposed" href="<?php echo ROOT.$cp_admin.'fl_config/'.HTTP_HOST.'/'; ?>img/Logo-iPhone.jpg">
<!-- For iPhone 4 Retina display -->
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo ROOT.$cp_admin.'fl_config/'.HTTP_HOST.'/'; ?>img/Logo-iPhoneRet.jpg">
<!-- For iPad -->
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo ROOT.$cp_admin.'fl_config/'.HTTP_HOST.'/'; ?>img/Logo-iPad.jpg">
<!-- For iPad Retina display -->
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo ROOT.$cp_admin.'fl_config/'.HTTP_HOST.'/'; ?>img/Logo-iPadRet.jpg">



  </head>
  <body>
    <div class="statusbar-overlay"></div>
    <div class="panel-overlay"></div>
    <div class="panel panel-left panel-cover">
      <div class="view navbar-fixed">
        <div class="pages">
          <div data-page="panel-left" class="page">
            <div class="navbar">
              <div class="navbar-inner">
                <div class="center">Menù</div>
              </div>
            </div>
            <div class="page-content">
              <div class="content-block">
                <p></p>
              </div>
              <div class="content-block-title">Selezione opzioni</div>
              <div class="list-block">
                <ul>
                  <li><a href="#" class="open-login-screen">
                      <div class="item-content">
                        <div class="item-media"><i class="icon icon-f7"></i></div>
                        <div class="item-inner">
                          <div class="item-title">Login</div>
                        </div>
                      </div></a></li>
                      <li><a href="" class="open-logout">
                      <div class="item-content">
                        <div class="item-media"><i class="icon icon-f7"></i></div>
                        <div class="item-inner">
                          <div class="item-title">Logout</div>
                        </div>
                      </div></a></li>
                </ul>
              </div>
              <div class="content-block">
                <p></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="panel panel-right panel-reveal">
      <div class="view view-right">
        <div class="pages navbar-fixed">
          <div data-page="panel-right1" class="page">
            <div class="navbar">
              <div class="navbar-inner">
                <div class="center">Right Panel</div>
              </div>
            </div>
            <div class="page-content">
              <div class="content-block">
                <p><a href="#" class="close-panel">Chiudi</a></p>
              </div>
              <div class="list-block">
                <ul>
                  <li><a href="" class="item-link">
                      <div class="item-content">
                        <div class="item-inner">
                          <div class="item-title">Right panel page 2</div>
                        </div>
                      </div></a></li>
                  <li><a href="panel-right3.html" class="item-link">
                      <div class="item-content">
                        <div class="item-inner">
                          <div class="item-title">Right panel page 3</div>
                        </div>
                      </div></a></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="views">
      <div class="view view-main">
        <div class="pages navbar-fixed">
          <div data-page="index" class="page">
            <div class="navbar">
              <div class="navbar-inner">
                <div class="center">Lead Generation App</div>
                <div class="right"><a href="#" class="open-panel link icon-only"><i class="icon icon-bars"></i></a></div>
              </div>
            </div>
            <div class="page-content">
              <div id="welkome" class="content-block-title">Benvenuto </div>
              <div class="content-block-title">Dashboard principale</div>
              <div class="list-block">
                <ul>
              
                  <li><a href="nuovo-lead.php">
                      <div class="item-content">
                        <div class="item-media"><i class="icon icon-form-name"></i></div>
                        <div class="item-inner">
                          <div class="item-title">Nuovo Contatto</div>
                        </div>
                      </div></a></li>

                    <li><a href="calendario.php">
                      <div class="item-content">
                        <div class="item-media"><i class="icon icon-form-name"></i></div>
                        <div class="item-inner">
                          <div class="item-title">Calendario</div>
                        </div>
                      </div></a></li>


                       <li><a href="nuovo-appuntamento.php">
                      <div class="item-content">
                        <div class="item-media"><i class="icon icon-form-name"></i></div>
                        <div class="item-inner">
                          <div class="item-title">Nuovo Appuntamento</div>
                        </div>
                      </div></a></li>

                      <li><a href="lista-contatti.php">
                      <div class="item-content">
                        <div class="item-media"><i class="icon icon-form-name"></i></div>
                        <div class="item-inner">
                          <div class="item-title">Visualizza lista ultimi contatti </div>
                        </div>
                      </div></a></li>
                </ul>
              </div>
              

        
      
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="login-screen" >
      <div class="view">
        <div class="navbar">
    <div class="navbar-inner">
      <div class="left"><a href="" class="back link icon-only" onclick="myApp.closeModal('.login-screen');"><i class="icon icon-back"></i></a></div>
      <div class="center">Login</div>
      
    </div>
  </div>
        <div class="page">
          <div class="page-content login-screen-content" style="background:url(icons/640x1096.jpg) top center no-repeat">
            <div class="login-screen-title"> </div><br>
              <div class="content-block">
                   <form  method="GET" enctype="application/x-www-form-urlencoded" class="list-block inset form-login" id="form-login" accept-charset="UTF-8">
                      <ul>
                        <li>
                          <div class="item-content">
                            <div class="item-media"><i class="icon icon-form-name"></i></div>
                            <div class="item-inner">
                              <div class="item-title label">username</div>
                              <div class="item-input">
                                <input type="text" name="username" id="vf_username">
                              </div>
                            </div>
                          </div>
                        </li>
                        <li>
                          <div class="item-content">
                            <div class="item-media"><i class="icon icon-form-password"></i></div>
                            <div class="item-inner">
                              <div class="item-title label">password</div>
                              <div class="item-input">
                                <input type="password" name="password" id="vf_password">
                              </div>
                            </div>
                          </div>
                        </li>
                      </ul>
                      <input type="hidden" name="usr_login" value="1">
                      <input type="hidden" name="token" value="app">
                      <div class="content-block"> <input type="submit" value="entra" class="button button-big button-fill" id="btn-submit" > </div>
                    

                <div class="list-block-label">Per continuare esegui il login.<br>Se smarrisci la password può essere reimpostata da un amministratore.</div>
              </form>
                  </div>
                </div>
          </div>
        </div>
      </div>
      <!-- Path to Framework7 Library JS-->
    <script type="text/javascript" src="../build/js/framework7.js"></script>
    <script type="text/javascript" src="js/kitchen-sink.js"></script>
    <script type="text/javascript"> showSign("sign"); </script>


  </body>
</html>