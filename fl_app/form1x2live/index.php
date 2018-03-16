<?php
//controllo se ci sono messaggi
$a = '';
$color = '';
$message = '';

if(isset($_GET['col']) && isset($_GET['esito'])){

  $color = ($_GET['col']) ? '#ad1e24' :'green' ;
  $message = filter_var($_GET['esito'],FILTER_SANITIZE_STRING);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="css/materialize.css">
  <link rel="stylesheet" href="css/style.css">
  <title>1X2 Live</title>
</head>

<body>
  <div class="container valign-wrapper">
    <div id="register" class="row">
      <div id="logo" class="form col s12">
        <div class="logo-container">
          <img src="images/1x2live-logo.jpg" alt="">
        </div>
      </div>
    
      <div class="form col s12">
        <div class="message center-align">
          <?php if($message != ''){ ?>
          <p style="color:<?php echo $color; ?>;padding:10px">
            <?php echo $message; ?>
          </p>
          <?php }?>
          <p style="padding:10px">MESSAGE HERE!</p>
        </div>

          <form action="../../fl_api/register_user.php" method="POST">
            <div class="center-align">
              <div class="input-field">
                <input id="name" name="name" type="text" class="validate" required>
                <label class="active" for="name">Nome</label>
              </div>
            </div>

            <div class="center-align">
              <div class="input-field">
                <input id="surname" name="surname" type="text" class="validate" required>
                <label class="active" for="surname">Cognome</label>
              </div>
            </div>

            <div class="center-align">
              <div class="input-field">
                <input id="email" name="email" type="email" class="validate" required>
                <label class="active" for="email">E-mail</label>
              </div>
            </div>

            <div class="center-align">
              <div class="input-field">
                <input id="phone" name="phone" type="tel" class="validate" required>
                <label class="active" for="phone">Telefono</label>
              </div>
            </div>
            <div class="center-align">
              <div class="input-field">
                <button class="btn waves-effect waves-light btn-large submit" type="submit">
                  INVIA
                </button>
              </div>
            </div>
          </form>

      </div>
    </div>
  </div>
  <script src="js/materialize.js"></script>
</body>

</html>