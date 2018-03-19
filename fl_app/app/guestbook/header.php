<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set("memory_limit","20M");
date_default_timezone_set('Europe/Rome');

//Include GP config file && User class
include_once 'gpConfig.php';
include_once 'asset/classi/User.php';
$matrimonio_id=0;
$output = '';//da lasciare vuota
if(isset($_GET['code'])){
  $gClient->authenticate($_GET['code']);
  $_SESSION['token'] = $gClient->getAccessToken();
  header('Location: ' . filter_var($redirectURL, FILTER_SANITIZE_URL));
}

if (isset($_SESSION['token'])) {
  $gClient->setAccessToken($_SESSION['token']);
}

if (isset($_SESSION['matrimonio_id'])) {
  $matrimonio_id = $_SESSION['matrimonio_id'];
}
$blocco_commenti=0;
if (isset($_SESSION['blocco_commenti'])) {
  $blocco_commenti = $_SESSION['blocco_commenti'];
}


include_once("db_connect.php");

if ($gClient->getAccessToken()) {
    //Get user profile data from google
  $gpUserProfile = $google_oauthV2->userinfo->get();

    //Initialize User class
  $user = new User();

    //Insert or update user data to the database
  $gpUserData = array(
    'oauth_provider'=> 'google',
    'oauth_uid'     => @$gpUserProfile['id'],
    'first_name'    => @$gpUserProfile['given_name'],
    'last_name'     => @$gpUserProfile['family_name'],
    'email'         => @$gpUserProfile['email'],
    'gender'        => @$gpUserProfile['gender'],
    'locale'        => @$gpUserProfile['locale'],
    'picture'       => @$gpUserProfile['picture'],
    'link'          => @$gpUserProfile['link']
  );
  $userData = $user->checkUser($gpUserData);

} else {
  //controllo login form
  if(isset($_POST['email']) && isset($_POST['password']) ){
    $output = '';
    $email = filter_var(trim($_POST['email']),FILTER_SANITIZE_EMAIL);
    $password = base64_encode ($_POST['password']);
    if($email != '' && $password != ''){
      $queryLogin = $mysqli->query('SELECT id,first_name,matrimonio_id  FROM cc_users WHERE email="'.$email.'" AND password = "'.$password.'" LIMIT 1');
      if($queryLogin->num_rows > 0){
        $queryLogin = $queryLogin->fetch_assoc();
        $userData['matrimonio_id'] = $matrimonio_id = $_SESSION['matrimonio_id'] = $queryLogin['matrimonio_id']; 
         $_SESSION['id'] = $userData['id']  =  $queryLogin['id']; 
        $_SESSION['first_name'] = $userData['first_name'] = $queryLogin['first_name']; 
        $output = '';
        header("Location: index.php");
      }else{
        $output = "pieno";
        $error = "email o password non validi";
        header("Location: login.php?error=".$error);
      }

    }else{
      $output = "pieno";
      $error = "email o password vuoti";
      header("Location: login.php?error=".$error);
    }
  }else{

    //quando siamo in get
    if (@$_SESSION['matrimonio_id'] != '') {
      $userData['matrimonio_id'] = $_SESSION['matrimonio_id'];
      $userData['id'] = @$_SESSION['id'];
      $userData['first_name'] = @$_SESSION['first_name'];
      $matrimonio_id = $_SESSION['matrimonio_id'];
      if(!isset($_SESSION['id'])) { header("Location: logout.php"); exit; }
 
    }else{
      $authUrl = $gClient->createAuthUrl();
      $output = "pieno";
    }
  }
  
}

if(!empty($userData)){
 $output="";
}else{
  if((empty($userData) & (basename($_SERVER['PHP_SELF'])!='login.php'))){ 
    header("Location: login.php");
  }
}


function clean($string) {
   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
   $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

   return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
 }


 ?>
 <!DOCTYPE html>
 <html lang="it">
 <head>
  <meta charset="utf-8">
  <meta name="MobileOptimized" content="420" />
  <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no, width=device-width" />

  <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" href="asset/css/modern-business.css">  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link rel="stylesheet" type="text/css" href="asset/css/style.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

</head>
<body> 
  <script type="text/javascript">
    $(document).ready(function() {   
      var sideslider = $('[data-toggle=collapse-side]');
      var sel = sideslider.attr('data-target');
      var sel2 = sideslider.attr('data-target-2');
      sideslider.click(function(event){
        $(sel).toggleClass('in');
        $(sel2).toggleClass('out');
      });
    });
  </script>


  <header role="banner" class="navbar navbar-fixed-top navbar-inverse">

    <div class="container">  
      <div class="navbar-header">
        <button data-toggle="collapse-side" data-target=".side-collapse" data-target-2=".side-collapse-container" type="button" class="navbar-toggle pull-left"><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>
        <a href="gallery.php" class="navbar-center visible-xs"><img src="asset/img/logo_white.png" style="max-height: 45px;padding: 4px; display: block;
        margin: 0 30%;"></a>
      </div>         

      <div class="navbar-inverse side-collapse in">
        <nav role="navigation" class="navbar-collapse">
          <ul class="nav navbar-nav">  
            <li><a href="../"><span class="glyphicon glyphicon glyphicon-arrow-left"></span> Home</a></li>
            <?php if($output == ''){ ?> 
             <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
            <?php } ?>
          </ul>
          <a href="gallery.php" class="navbar-center visible-lg"><img src="asset/img/logo_white.png" style="max-height: 45px;padding: 4px; display: block;
          margin: 0 40%;"></a>
        </nav>
      </div>
    </div>
  </header>
  <div class="container side-collapse-container">

    <?php if($output == ''){ ?>  

    <?php } ?>   
    <?php if($output != ''){ ?>   
    <div style="margin:40px;text-align: center;">
      <form method="post" action="login.php">
        <label style="max-width:400px;width:100%">Login</label>
        <br>
        <input style="max-width:400px;width:100%;padding: 5px 10px;
        border-radius: 4px;border: 1px solid #e7e7e7" type="email" name="email" placeholder="Email" required="">
        <br><br>
        <input style="max-width:400px;width:100%;padding: 5px 10px;border-radius: 4px;border: 1px solid #e7e7e7" placeholder="Password" type="password" name="password" required>
        <br><br>
        <button class="btn btn-lg btn-primary" style="max-width:400px;width:100%;background: rgb(154, 181, 186);border: none;" type="submit">Accedi</button>
        <br><br>
        <?php if(@$_GET['error'] != ''){ ?>   

        <span style="padding:10px;background:red;color:white;border-radius: 4px;max-width: 400px;width:100%"><?php echo filter_var(@$_GET['error'],FILTER_SANITIZE_STRING); ?></span>
        <?php } ?>

        <br><br>
      </form>
      <a style="margin:0 auto;max-width:400px;width:100%" class="btn btn-lg btn-danger" role="button" href="https://accounts.google.com/o/oauth2/auth?response_type=code&amp;redirect_uri=http%3A%2F%2Fwww.matrimonioincloud.it%2Fapp%2Fguestbook%2F&amp;client_id=692590741999-6pe99avsiq5o3ljc5riljq804eiatigk.apps.googleusercontent.com&amp;scope=https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fuserinfo.profile+https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fuserinfo.email&amp;access_type=offline">Accedi con Google</a>
    </div>
    <?php } ?>