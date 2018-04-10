<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, minimal-ui">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="theme-color" content="#2196f3">
    <title>Ford Bluemotive</title>
    <link rel="stylesheet" href="../build/css/framework7.material.css">
    <link rel="stylesheet" href="../build/css/framework7.material.colors.css">
    <link href="http://fonts.googleapis.com/css?family=Roboto:400,300,500,700" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="css/kitchen-sink.css">
    <link rel="icon" href="img/icon.png">
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
                  <li><a href="panel-right2.html" class="item-link">
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
                <div class="center">Ford Bluemotive</div>
                <div class="right"><a href="#" class="open-panel link icon-only"><i class="icon icon-bars"></i></a></div>
              </div>
            </div>
             <div class="page-content login-screen-content" style="background:url(icons/640x1096.jpg) top center no-repeat">
              <div id="welkome" class="content-block-title">Benvenuto </div>
              <div class="content-block-title">LOGIN</div>
            
            <div class="login-screen-title"> </div>
            <form id="form-login" name="form-login"style="background: white none repeat scroll 0% 0%;
width: 450px;
margin: 0 auto 0px;
padding: 20px;">
     <div class="list-block inputs-list">
                <ul>
                  <li class="item-content">
                    <div class="item-inner">
                      <div class="item-title label">Email</div>
                      <div class="item-input">
                        <input type="text" name="username" placeholder="Email o username">
                      </div>
                    </div>
                  </li>
                  <li class="item-content">
                    <div class="item-inner">
                      <div class="item-title label">Password</div>
                      <div class="item-input">
                        <input type="password" name="password" placeholder="password">
                      </div>
                    </div>
                  </li>
                </ul>
              </div>
              <input type="hidden" name="usr_login"  value="1">
              <input type="hidden" name="token"  value="app">
              <div class="content-block"><input type="submit" value="Accedi" class="button button-big"></div>
              <div class="content-block">
                <div class="list-block-label">Per continuare esegui il login.<br>Se smarrisci la password può essere reimpostata da un amministratore.<br><a href="#" class="close-panel" onClick="myApp.closeModal('.login-screen');  ">Chiudi</a></div>
              </div>
            </form>

            </div>
          </div>
        </div>
      </div>
    </div>

  <img src="" id="sign" />

    <script type="text/javascript" src="../build/js/framework7.js"></script>
    <script type="text/javascript" src="js/kitchen-sink.js"></script>
 <script type="text/javascript"> showSign("sign"); </script>


  </body>
</html><!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, minimal-ui">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="theme-color" content="#2196f3">
    <title>Ford Bluemotive</title>
    <link rel="stylesheet" href="../build/css/framework7.material.css">
    <link rel="stylesheet" href="../build/css/framework7.material.colors.css">
    <link href="http://fonts.googleapis.com/css?family=Roboto:400,300,500,700" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="css/kitchen-sink.css">
    <link rel="icon" href="img/icon.png">
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
                  <li><a href="panel-right2.html" class="item-link">
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
                <div class="center">Ford Bluemotive</div>
                <div class="right"><a href="#" class="open-panel link icon-only"><i class="icon icon-bars"></i></a></div>
              </div>
            </div>
             <div class="page-content login-screen-content" style="background:url(icons/640x1096.jpg) top center no-repeat">
              <div id="welkome" class="content-block-title">Benvenuto </div>
              <div class="content-block-title">LOGIN</div>
            
            <div class="login-screen-title"> </div>
            <form id="form-login" name="form-login"style="background: white none repeat scroll 0% 0%;
width: 450px;
margin: 0 auto 0px;
padding: 20px;">
     <div class="list-block inputs-list">
                <ul>
                  <li class="item-content">
                    <div class="item-inner">
                      <div class="item-title label">Email</div>
                      <div class="item-input">
                        <input type="text" name="user" placeholder="Email o username">
                      </div>
                    </div>
                  </li>
                  <li class="item-content">
                    <div class="item-inner">
                      <div class="item-title label">Password</div>
                      <div class="item-input">
                        <input type="password" name="password" placeholder="password">
                      </div>
                    </div>
                  </li>
                </ul>
              </div>
              <input type="hidden" name="usr_login"  value="1">
              <input type="hidden" name="token"  value="app">
              <div class="content-block"><input type="submit" value="Accedi" class="button button-big button-fill" id="btn-submit"></div>
              <div class="content-block">
                <div class="list-block-label">Per continuare esegui il login.<br>Se smarrisci la password può essere reimpostata da un amministratore.<br><a href="#" class="close-panel" onClick="myApp.closeModal('.login-screen');  ">Chiudi</a></div>
              </div>
            </form>

            </div>
          </div>
        </div>
      </div>
    </div>

  <img src="" id="sign" />

    <script type="text/javascript" src="../build/js/framework7.js"></script>
    <script type="text/javascript" src="js/kitchen-sink.js"></script>
 <script type="text/javascript"> showSign("sign"); </script>


  </body>
</html>