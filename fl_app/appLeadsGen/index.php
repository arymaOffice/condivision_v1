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
  <!--<link href="http://fonts.googleapis.com/css?family=Roboto:400,300,500,700" rel="stylesheet" type="text/css">-->
  <link rel="stylesheet" href="css/kitchen-sink.css">
  <link rel="stylesheet" href="css/mio.css">
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
            <div class="list-block">
              <ul>
                <li><a href="" class="open-logout">
                  <div class="item-content">
                    <div class="item-media"><i class="icon icon-logout"></i></div>
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
            <div class="page-content">
              <div id="welkome" class="content-block-title"></div>
              <div class="row">
                <div class="tablet-25"></div>
                <div class="tablet-40 selectMobile">
                  <a href="#" class="item-link smart-select" data-back-on-select="true">
                    <select name="selectAcivity" class="selezionaAttivita" id="selectAcivity">
                      <option value="">Seleziona ...</option>
                    </select>

                    <div class="item-content bottoni" style="font-size: large">
                      <div class="item-inner">
                        <div class="item-title" style="color:#0b0b0b">Seleziona attività</div>
                      </div>
                    </div>

                  </a>
                </div>
                <div class="tablet-25"></div>
              </div>
              <br>
              <div class="row">
                <div class="tablet-5 prima"></div>
                <div class="tablet-45 seconda">
                  <a class="link" href="/new/">
                    <div class="item-content bottoni">
                      <div class="item-inner">
                        <div class="item-title">Nuovo Contatto</div>
                      </div>
                    </div>
                  </a>
                </div>
                <div class="tablet-40 terza">
                  <a href="/list/">
                    <div class="item-content bottoni" style="background: grey;">
                      <div class="item-inner">
                        <div class="item-title">Ultimi Contatti </div>
                      </div>
                    </div>
                  </a>
                </div>
                <div class="tablet-5 quarta" ></div>
              </div>
              <br>

              <div class="content-block">
                <!-- Buttons row as tabs controller -->
                <div class="buttons-row">
                  <!-- Link to 1st tab, active -->
                  <a href="#tab1" class="tab-link active button">Conteggio 1</a>
                  <!-- Link to 2nd tab -->
                  <a href="#tab2" class="tab-link button">Conteggio 2</a>
                  <!-- Link to 3rd tab -->
                  <a href="#tab3" class="tab-link button">Conteggio 3</a>
                </div>
              </div>
              <!-- Tabs, tabs wrapper -->
              <div class="tabs">
                <!-- Tab 1, active by default -->
                <div id="tab1" class="tab active">
                  <div class="content-block">
                    <div class="row">
                      <div class="tablet-25"></div>
                      <div class="tablet-40">
                        <div class="item-content bottoni" style="background: white">
                          <div class="item-inner">
                            <div class="item-title"></div>
                            <div class="item-input">
                              <p class="selezionaAttivita" style="width: 88%;border: solid 2px grey">
                                100 Leads
                              </p>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="tablet-25"></div>
                    </div>
                  </div>
                </div>
                <!-- Tab 2 -->
                <div id="tab2" class="tab">
                  <div class="content-block">
                    <div class="row">
                      <div class="tablet-25"></div>
                      <div class="tablet-40">
                        <div class="item-content bottoni">
                          <div class="item-inner">
                            <div class="item-title"></div>
                            <div class="item-input">
                              <p class="selezionaAttivita" style="width: 88%;">
                                100 Leads
                              </p>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="tablet-25"></div>
                    </div>
                  </div>
                </div>
                <!-- Tab 3 -->
                <div id="tab3" class="tab">
                  <div class="content-block">
                    <div class="row">
                      <div class="tablet-25"></div>
                      <div class="tablet-40">
                        <div class="item-content bottoni" style="background: white">
                          <div class="item-inner">
                            <div class="item-title"></div>
                            <div class="item-input">
                              <p class="selezionaAttivita" style="width: 88%;border: solid 2px  #2196f3">
                                100 Leads
                              </p>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="tablet-25"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="login-screen modal-in " >
      <div class="view">
        <div class="navbar">
          <div class="navbar-inner">
            <div class="center" style="margin: 0 auto;">LOGIN</div>

          </div>
        </div>
        <div class="page">
          <div class="page-content login-screen-content" >
            <div class="login-screen-title"> </div><br><br><br><br>
            <div class="content-block">
             <form class="list-block inset form-login" id="form-login" method="POST" accept-charset="UTF-8">
              <ul>
                  <li class="item-content item-input">
                    <div class="item-media">
                      <i class="icon icon-person"></i>
                    </div>
                    <div class="item-inner">
                      <div class="item-input-wrap">
                        <input type="text" placeholder="Username">
                        <span class="input-clear-button"></span>
                      </div>
                    </div>
                  </li>
                  <li class="item-content item-input">
                    <div class="item-media">
                      <i class="icon icon-lock"></i>
                    </div>
                    <div class="item-inner">
                      <div class="item-input-wrap">
                        <input type="password" placeholder="Password">
                        <span class="input-clear-button"></span>
                      </div>
                    </div>
                  </li>
              </ul>
              <div class="content-block"> <input type="submit" value="Accedi" class="button button-big button-fill" id="btn-submit" > </div>


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
  <!--<script type="text/javascript"> showSign("sign"); </script>-->


</body>
</html>
