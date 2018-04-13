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

  <body>
    <div id="app">
      <!-- Left Panel with Reveal effect -->
      <div class="panel panel-left panel-reveal">
        <div class="content-block">

          <!-- Clicking on link with "panel-close" class will close panel -->
          <p>
            <a href="#" class="panel-close">Close me</a>
          </p>
          <!-- Click on link with "panel-open" and data-panel="right" attribute will open Right panel -->
          <p>
            <a href="#" data-panel="right" class="panel-close">Open Right Panel</a>
          </p>
        </div>
      </div>

      <div class="view view-main view-init">
        <div class="" data-name="home">

          <div class="navbar">
            <div class="navbar-inner">
              <div class="left">
                <a href="/" class="back link icon-only">
                  <i class="icon icon-back"></i>
                </a>
              </div>
              <div class="center">Nuovo Lead</div>
              <div class="right">
                <a href="#" class="link panel-open icon-only">
                  <i class="icon icon-bars"></i>
                </a>
              </div>
            </div>
          </div>

          <div class="page-content">

            <form id="form-lead">
              <div class="content-block list-block inputs-list">
                <div class="row">
                  <div class="tablet-50">
                    <div class="item-content">
                      <div class="item-media">
                        <i class="icon icon-form-name"></i>
                      </div>
                      <div class="item-inner">
                        <div class="item-title floating-label">Nome</div>
                        <div class="item-input">
                          <input type="text" placeholder="" name="nome" value="" required />
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="tablet-50">
                    <div class="item-content">
                      <div class="item-media">
                        <i class="icon icon-form-name"></i>
                      </div>
                      <div class="item-inner">
                        <div class="item-title floating-label">Cognome</div>
                        <div class="item-input">
                          <input type="text" placeholder="" name="cognome" value="" required />
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- .row -->

                <div class="row">
                  <div class="tablet-50">
                    <div class="item-content">
                      <div class="item-media">
                        <i class="icon icon-form-url"></i>
                      </div>
                      <div class="item-inner">
                        <div class="item-title floating-label">Città</div>
                        <div class="item-input">
                          <input type="text" placeholder="" name="citta" value="" required />
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- .tablet-50 -->
                  <div class="tablet-50">
                    <div class="item-content">
                      <div class="item-media">
                        <i class="icon icon-form-email"></i>
                      </div>
                      <div class="item-inner">
                        <div class="item-title floating-label">E-mail</div>
                        <div class="item-input">
                          <input type="email" placeholder="" name="email" />
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- .tablet-50 -->
                </div>
                <!-- .row -->

                <div class="row">
                  <div class="tablet-50">
                    <div class="item-content">
                      <div class="item-media">
                        <i class="icon icon-form-tel"></i>
                      </div>
                      <div class="item-inner">
                        <div class="item-title floating-label">Telefono</div>
                        <div class="item-input">
                          <input type="tel" placeholder="" name="telefono" value="" />
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="tablet-50">
                    <div class="item-content">
                      <div class="item-media">
                        <i class="icon icon-form-toggle"></i>
                      </div>
                      <div class="item-inner">
                        <div class="item-title label">Azienda</div>
                        <div class="item-input">
                          <label class="label-switch">
                            <input type="checkbox" value="1" name="azienda" id="azienda" />
                            <div class="checkbox"></div>
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>



                <div id="azienda_box">
                  <div class="row">
                    <div class="tablet-50">
                      <div class="item-content">
                        <div class="item-media">
                          <i class="icon icon-form-name"></i>
                        </div>
                        <div class="item-inner">
                          <div class="item-title floating-label">Società</div>
                          <div class="item-input">
                            <input type="text" placeholder="" name="ragione_sociale" value="" required />
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- .tablet-50 -->
                    <div class="tablet-50">
                      <div class="item-content">
                        <div class="item-media">
                          <i class="icon icon-form-name"></i>
                        </div>
                        <div class="item-inner">
                          <div class="item-title floating-label">Partita Iva</div>
                          <div class="item-input">
                            <input type="text" placeholder="" name="partita_iva" value="" required />
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- .tablet-50 -->
                  </div>
                  <!-- .row -->
                </div>
                <!-- #azienda_box -->

                <div class="row">
                  <div class="tablet-100">
                    <div class="item-content">
                      <div class="item-title floating-label" style="margin-bottom: 45px;margin-right: 30px">Interessato a </div>
                      <div class="item-input">
                        <div class="radio-toolbar">
                          <input name="tipo_interesse" id="radio1" value="91" type="radio">
                          <label for="radio1">Nuovo</label>

                          <input name="tipo_interesse" id="radio2" value="92" type="radio">
                          <label for="radio2">Usato</label>

                          <input name="tipo_interesse" id="radio3" value="93" type="radio">
                          <label for="radio3">Veic. Commerciali</label>

                          <input name="tipo_interesse" id="radio4" value="133" type="radio">
                          <label for="radio4">Fleet</label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- .tablet-100 -->
                </div>
                <!-- .row -->

                <br>
                <div class="row">
                  <div class="tablet-50">
                    <a href="#" class="item-link smart-select" data-back-on-select="true">
                      <select name="interessato_a">
                        <optgroup label="Auto">
                          <?php $gamma1 = array('Non selezionato', 'Ka', 'Fiesta', 'B-Max', 'Ecosport', 'Focus', 'C-Max', 'Kuga', 'Galaxy', 'S-Max', 'Mustang', 'Mondeo', 'Vignale');
foreach ($gamma1 as $key => $val) {
    echo '<option value="' . $val . '">' . $val . '</option>';
}

?>
                        </optgroup>
                      </select>
                      <div class="item-content">
                        <div class="item-media">
                          <i class="icon icon-form-checkbox"></i>
                        </div>
                        <div class="item-inner">
                          <div class="item-title">Seleziona modello Gamma</div>
                        </div>
                      </div>
                    </a>
                  </div>
                  <!-- .tablet-50 -->
                  <div class="tablet-50">
                    <a href="#" class="item-link smart-select" data-back-on-select="true">
                      <select name="interessato_a">
                        <optgroup label="Veicoli Commerciali">
                          <?php $gamma2 = array('Non selezionato', 'Fiesta Van', 'Tourneo Connect', 'Tourneo Custom', 'Tourneo Courier', 'Ranger', 'Transit Connect', 'Transit Custom', 'Transit Courier', 'Transit');
foreach ($gamma2 as $key => $val) {
    echo '<option value="' . $val . '">' . $val . '</option>';
}

?>
                        </optgroup>
                      </select>
                      <div class="item-content">
                        <div class="item-media">
                          <i class="icon icon-form-checkbox"></i>
                        </div>
                        <div class="item-inner">
                          <div class="item-title">Seleziona modello Gamma Veic. Commerciali</div>
                        </div>
                      </div>
                    </a>
                  </div>
                  <!-- .tablet-50 -->
                </div>
                <!-- .row -->

                <br>

                <div class="row">
                  <div class="tablet-100">
                    <div class="item-content">
                      <div class="item-title floating-label" style="margin-bottom: 50px;margin-right: 40px">Pagamento Vettura </div>
                      <div class="item-input">
                        <div class="radio-toolbar">
                          <input name="pagamento_vettura" id="radio10" value="1" type="radio">
                          <label for="radio10">Non Selezionato</label>

                          <input name="pagamento_vettura" id="radio9" value="104" type="radio">
                          <label for="radio9">Contanti</label>

                          <input name="pagamento_vettura" id="radio8" value="108" type="radio">
                          <label for="radio8">Bonifico</label>

                          <input name="pagamento_vettura" id="radio6" value="106" type="radio">
                          <label for="radio6">Finanziamento</label>

                          <input name="pagamento_vettura" id="radio5" value="107" type="radio">
                          <label for="radio5">Idea Ford</label>

                          <input name="pagamento_vettura" id="radio7" value="108" type="radio">
                          <label for="radio7">Noleggio</label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- .tablet-100 -->
                </div>
                <!-- .row -->

                <div class="row">
                  <div class="tablet-100">
                    <div class="item-content">
                      <div class="item-media">
                        <i class="icon icon-form-toggle"></i>
                      </div>
                      <div class="item-inner">
                        <div class="item-title label">Desidera effettuare un test drive?</div>
                        <div class="item-input">
                          <label class="label-switch">
                            <input type="checkbox" value="1" name="test_drive" id="test_drive" />
                            <div class="checkbox"></div>
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- .tablet-100 -->
                </div>
                <!-- .row -->


                <div id="test_drive_box">
                  <div class="row">
                    <div class="tablet-50">
                      <div class="item-content">
                        <div class="item-media"></div>
                        <div class="item-inner">
                          <div class="item-title ">Vettura</div>
                          <div class="item-input">
                            <select class="" name="veicolo" id="veicolo">
                            </select>

                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- .tablet-50 -->
                    <div class="tablet-50">
                      <div class="item-content">
                        <div class="item-media"></div>
                        <div class="item-inner">
                          <div class="item-title ">Sede</div>
                          <div class="item-input">
                            <select class="" name="location_testdrive" id="location_testdrive">
                            </select>

                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- .tablet-50 -->
                  </div>
                  <!-- .row -->
                  <div class="row">
                    <div class="tablet-50">
                      <div class="item-content">
                        <div class="item-media">
                          <i class="icon icon-form-calendar"></i>
                        </div>
                        <div class="item-inner">
                          <div class="item-title ">Data Test Drive </div>
                          <div class="item-input">
                            <input type="datetime-local" placeholder="" value="<?php echo date('Y-m-d') . 'T' . date('H:i'); ?>" name="data_test_drive"
                            />
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- .tablet-50 -->
                    <div class="tablet-50">
                    </div>
                    <!-- .tablet-50 -->
                  </div>
                  <!-- .row -->
                </div>
                <!-- #test_drive_box -->

                <div class="row">
                  <div class="tablet-50">
                    <div class="item-content">
                      <div class="item-media">
                        <i class="icon icon-form-toggle"></i>
                      </div>
                      <div class="item-inner">
                        <div class="item-title label">Possiede una vettura da permutare?</div>
                        <div class="item-input">
                          <label class="label-switch">
                            <input type="checkbox" value="1" name="permuta" id="permuta" />
                            <div class="checkbox"></div>
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- .tablet-50 -->
                </div>
                <!-- .row -->

                <div id="permuta_box">
                  <input type="hidden" value="92" name="tipologia_veicolo" />
                  <div class="row no-gutter">
                    <div class="tablet-33">
                      <div class="item-content">
                        <div class="item-media"></div>
                        <div class="item-inner">
                          <div class="item-title ">Marca</div>

                          <div class="item-input-wrap">
                            <input id="autocomplete-dropdown-marca" type="text" placeholder="Marca">
                          </div>


                          <!--<div class="item-input">
                <select  name="marca"  id="marca" required /> </select>
              </div>-->


                        </div>
                      </div>
                    </div>
                    <!-- .tablet-30 -->
                    <div class="tablet-33">
                      <div class="item-content">
                        <div class="item-media"></div>
                        <div class="item-inner">
                          <div class="item-title floating-label">Modello</div>
                          <div class="item-input">
                            <input type="text" placeholder="" name="modello" value="" required />
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- .tablet-30 -->
                    <div class="tablet-33">
                      <div class="item-content">
                        <div class="item-media"></div>
                        <div class="item-inner">
                          <div class="item-title floating-label">Anno imm.</div>
                          <div class="item-input">
                            <input type="text" placeholder="" name="anno_immatricolazione" value="" required />
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- .tablet-30 -->
                  </div>
                  <!-- .row -->
                  <div class="row no-gutter">
                    <div class="tablet-33">
                      <div class="item-content">
                        <div class="item-media"></div>
                        <div class="item-inner">
                          <div class="item-title floating-label">Km</div>
                          <div class="item-input">
                            <input type="text" placeholder="" name="kilometraggio" value="" required />
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- .tablet-30 -->
                    <div class="tablet-33">
                      <div class="item-content">
                        <div class="item-media"></div>
                        <div class="item-inner">
                          <div class="item-title floating-label">Alimentazione</div>
                          <div class="item-input">
                            <input type="text" placeholder="" name="alimentazione" value="" required />
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- .tablet-30 -->
                    <div class="tablet-33">
                      <div class="item-content">
                        <div class="item-media"></div>
                        <div class="item-inner">
                          <div class="item-title floating-label">Targa</div>
                          <div class="item-input">
                            <input type="text" placeholder="" name="targa" value="" required />
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- .tablet-30 -->
                  </div>
                  <!-- .row -->
                  <div class="row">
                    <div class="tablet-50">
                      <div class="item-content">
                        <div class="item-media"></div>
                        <div class="item-inner">
                          <div class="item-title floating-label">Descrizione</div>
                          <div class="item-input">
                            <input type="text" placeholder="" name="descrizione" value="" required />
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="tablet-50">
                      <div class="item-content">
                        <div class="item-media"></div>
                        <div class="item-inner">
                          <div class="item-title label"></div>
                          <div class="item-input" style="text-align: center;margin-top: 2%;">
                            <br>
                            <a href="#" id="permuta2" style="font-size: large;">Aggiungi un altro veicolo</a>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>
                <!-- #permuta_box -->

                <div id="permuta2_box">
                  <div class="row no-gutter">
                    <div class="tablet-33">
                      <div class="item-content">
                        <div class="item-media"></div>
                        <div class="item-inner">
                          <div class="item-title floating-label">Marca</div>
                          <div class="item-input">
                            <input type="text" placeholder="" name="marca" value="" required />
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- .tablet-30 -->
                    <div class="tablet-33">
                      <div class="item-content">
                        <div class="item-media"></div>
                        <div class="item-inner">
                          <div class="item-title floating-label">Modello</div>
                          <div class="item-input">
                            <input type="text" placeholder="" name="modello" value="" required />
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- .tablet-30 -->
                    <div class="tablet-33">
                      <div class="item-content">
                        <div class="item-media"></div>
                        <div class="item-inner">
                          <div class="item-title floating-label">Anno imm.</div>
                          <div class="item-input">
                            <input type="text" placeholder="" name="anno_immatricolazione" value="" required />
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- .tablet-30 -->
                  </div>
                  <!-- .row -->
                  <div class="row no-gutter">
                    <div class="tablet-33">
                      <div class="item-content">
                        <div class="item-media"></div>
                        <div class="item-inner">
                          <div class="item-title floating-label">Km</div>
                          <div class="item-input">
                            <input type="text" placeholder="" name="kilometraggio" value="" required />
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- .tablet-30 -->
                    <div class="tablet-33">
                      <div class="item-content">
                        <div class="item-media"></div>
                        <div class="item-inner">
                          <div class="item-title floating-label">Alimentazione</div>
                          <div class="item-input">
                            <input type="text" placeholder="" name="alimentazione" value="" required />
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- .tablet-30 -->
                    <div class="tablet-33">
                      <div class="item-content">
                        <div class="item-media"></div>
                        <div class="item-inner">
                          <div class="item-title floating-label">Targa</div>
                          <div class="item-input">
                            <input type="text" placeholder="" name="targa" value="" required />
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- .tablet-30 -->
                  </div>
                  <!-- .row -->
                  <div class="row">
                    <div class="tablet-50">
                      <div class="item-content">
                        <div class="item-media"></div>
                        <div class="item-inner">
                          <div class="item-title floating-label">Descrizione</div>
                          <div class="item-input">
                            <input type="text" placeholder="" name="descrizione" value="" required />
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="tablet-50">
                    </div>
                  </div>
                </div>
                <!-- #permuta2_box -->

                <div class="row">
                  <div class="tablet-50">
                    <div class="item-content">
                      <div class="item-media">
                        <i class="icon icon-form-comment"></i>
                      </div>
                      <div class="item-inner">
                        <div class="item-title floating-label">Note</div>
                        <div class="item-input">
                          <textarea class="resizable" name="note"></textarea>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- .tablet-50 -->
                  <div class="tablet-50">
                    <div class="item-content">
                      <div class="item-title floating-label" style="margin-bottom: 62px;margin-right: 25px">Priorità </div>
                      <div class="item-input">
                        <div class="radio-toolbar">
                          <input name="priorita" id="radio11" value="0" type="radio">
                          <label for="radio11" class="green">Bassa</label>

                          <input name="priorita" id="radio12" value="1" type="radio">
                          <label for="radio12" class="yellow">Media</label>

                          <input name="priorita" id="radio13" value="2" type="radio">
                          <label for="radio13" class="red">Alta</label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- .tablet-50 -->
                </div>

                <input type="hidden" value="1" name="insert_lead" />
                <input type="hidden" value="app" name="token" />
                <input name="campagna_id" type="hidden" value="9" />
                <input name="source_potential" type="hidden" value="0" />
              </div>
              <!-- .content-block -->

              <div class="content-block">
                <p class="buttons-row">
                  <a href="#" class="button button-raised button-fill color-green">Invia</a>
                </p>
              </div>
            </form>
          </div>
        </div>
      </div>



    </div>



    <div class="login-screen modal-in ">
      <div class="view">
        <div class="navbar">
          <div class="navbar-inner">
            <div class="center" style="margin: 0 auto;">LOGIN</div>

          </div>
        </div>
        <div class="page">
          <div class="page-content login-screen-content">
            <div class="login-screen-title"> </div>
            <br>
            <br>
            <br>
            <br>
            <div class="content-block">
              <form class="list-block inset form-login" id="form-login" method="POST" accept-charset="UTF-8">
                <ul>
                  <li class="item-content item-input">
                    <div class="item-media">
                      <i class="f7-icons">email</i>
                    </div>
                    <div class="item-inner" style="padding-top: 10px;">
                      <div class="item-input-wrap">
                        <input type="text" name="username" placeholder="Username">
                        <span class="input-clear-button"></span>
                      </div>
                    </div>
                  </li>
                  <li class="item-content item-input">
                    <div class="item-media">
                      <i class="f7-icons">lock</i>
                    </div>
                    <div class="item-inner" style="padding-top: 10px;">
                      <div class="item-input-wrap">
                        <input type="password" name="password" placeholder="Password">
                        <span class="input-clear-button"></span>
                      </div>
                    </div>
                  </li>
                </ul>
                <div class="content-block">
                  <input type="submit" value="Accedi" class="button button-big button-fill" id="btn-submit"> </div>


                <div class="list-block-label">Per continuare esegui il login.
                  <br>Se smarrisci la password può essere reimpostata da un amministratore.</div>
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