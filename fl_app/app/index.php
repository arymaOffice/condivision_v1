<?php require_once 'code/config.php';?>
<!DOCTYPE html>
<html lang="it">
<head>
  <title>Matrimonio in Cloud - Digital Wedding App</title>
  <!-- META TAGS -->

  <meta charset="UTF-8">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="format-detection" content="telephone=no">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <meta name="description" content="">
  <meta name="keywords" content="Matrimonio in Cloud, Condivision wedding, digital wedding, matrimonio in cloud">

  <!-- FAVICON -->
  <link rel="shortcut icon" href="https://www.matrimonioincloud.it/condivision/fl_set/css/lay/a.ico" type="image/x-icon" />
  <link rel="apple-touch-icon" href="https://www.matrimonioincloud.it/condivision/fl_config/www.matrimonioincloud.it/img/a.png" />
  <link rel="apple-touch-startup-image" href="https://www.matrimonioincloud.it/condivision/fl_set/lay/spl.png" media="(max-device-width : 480px) and (-webkit-min-device-pixel-ratio : 2)">
  <!-- For iPhone -->
  <link rel="apple-touch-icon-precomposed" href="https://www.matrimonioincloud.it/condivision/fl_config/www.matrimonioincloud.it/img/Logo-iPhone.jpg">
  <!-- For iPhone 4 Retina display -->
  <link rel="apple-touch-icon-precomposed" sizes="114x114" href="https://www.matrimonioincloud.it/condivision/fl_config/www.matrimonioincloud.it/img/Logo-iPhoneRet.jpg">
  <!-- For iPad -->
  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="https://www.matrimonioincloud.it/condivision/fl_config/www.matrimonioincloud.it/img/Logo-iPad.jpg">
  <!-- For iPad Retina display -->
  <link rel="apple-touch-icon-precomposed" sizes="144x144" href="https://www.matrimonioincloud.it/condivision/fl_config/www.matrimonioincloud.it/img/Logo-iPadRet.jpg">
  <meta name="mobile-web-app-capable" content="yes">

  <!-- CSS FILES -->
  <link type="text/css" rel="stylesheet" href="css/normalize.css">
  <link type="text/css" rel="stylesheet" href="css/font-awesome.min.css">
  <link type="text/css" rel="stylesheet" href="css/style.css">
  <link type="text/css" rel="stylesheet" href="css/slick.css">
  <link type="text/css" rel="stylesheet" href="css/lightgallery.css">
  <link type="text/css" rel="stylesheet" href="css/colors.css">
  <link type="text/css" rel="stylesheet" href="css/media.css?123">
  <link type="text/css" rel="stylesheet" href="css/table.css?123">
  <link href="https://fonts.googleapis.com/css?family=Gochi+Hand" rel="stylesheet">

  <!--[if lt IE 9]>
  <link type="text/css" rel="stylesheet" href="css/ie.css">
  <script type="text/javascript" src="js/html5.js"></script>
  <![endif]-->


  <!-- GOOGLE MAP - You need to get an api key from Google. For more information, please read the help documentation. -->
  <script type="text/javascript" src="https://maps.google.com/maps/api/js?key=AIzaSyAvmX2UnMBavI18BeBC_d0gyLYtePxztbs"></script>
  <!-- NOSCRIPT -->
  <noscript>
    <link href="css/nojs.css" rel="stylesheet" type="text/css">
  </noscript>



  <style type="text/css">

  body,h1,h2,.isg-intro p.isg-subtitle,.isg-loading-text,.isg-loading-sub-text,.isg-rotated-text-inner,#countdown li p  { font-family: 'Gochi Hand', cursive;  }
  .isg-mobile-logo img { border: 5px solid #b77c9c; }
  /* Condivision CSS */
  .isg-logo img { border-radius: 100%;  width: 140px; height: auto;}

  /* COLORI BASE TEMA */
  .color1 { background: #B77C9C !important; }
  .color2 { background: <?php echo $color2; ?> !important;  }
  .color3 { background: #D691B7 !important;  }
  .color4 { background: rgb(154, 181, 186) !important; }
  .color5 { background: #ABBCB8 !important; }
  .color6 { background: #B5C0BE  !important; }

  .button {
    padding: 10px;
    clear: both;
    display: inline-block;
  }

  #isg-page-loading, .isg-menu a {
    color: #ffffff;
    background-color: #CADDD8 !important;
  }


  .isg-menu a:after,
  .isg-menu li:hover a,
  .isg-menu li.current a,
  .isg-menu li:hover a:after,
  .isg-menu li.current a:after {
    color: #ffffff;
    background-color: #CADDD8;
  }





</style>
</head>

<body>
  <!-- JAVASCRIPT FILES -->
  <script type="text/javascript" src="js/jquery-3.1.0.min.js"></script>


  <!-- PAGE LOADING ANIMATION -->
  <div id="isg-page-loading">
    <div class="isg-loading-table">
      <!-- PAGE LOADING TEXTS -->
      <div class="isg-loading-cell">
        <div class="isg-loading-text" data-content="<?php echo $appName; ?>"><?php echo $appName; ?></div>
        <div class="isg-loading-sub-text"><?php echo $appSubTitle; ?></div>
      </div>
    </div>
    <!-- PAGE LOADING BAR -->
    <div class="isg-loading-bar"></div>
  </div>



  <!-- OVERLAY -->
  <div id="isg-overlay"></div>
  <!-- VISIBLE PAGE CONTENT -->

  <div id="isg-main" class="toCapture">
    <!-- MENU -->
    <nav id="isg-hidden-menu">
      <ul class="isg-menu">
        <!--<li class="current"><a href="#portfolio" data-hover="<?php echo $mod_1_title; ?>"><?php echo $mod_1_title; ?></a></li>-->
        <li><a href="#about" data-hover="Condividi">Condividi</a></li>
        <li><a href="#rsvp" data-hover="Accedi">Accedi</a></li>
      </ul>
    </nav>
    <canvas id="capture"></canvas>

       <!-- HEADER -->
       <header id="isg-header">
        <div class="isg-hamburger data-html2canvas-ignore" data-html2canvas-ignore>
          <span>Toggle Menu</span>
        </div>
        <a id="isg-down-arrow data-html2canvas-ignore" data-html2canvas-ignore href="#">
          <i class="fa fa-arrow-circle-o-down"></i>
        </a>
        <div class="isg-intro">
          <!-- MOBILE LOGO -->
          <div class="isg-mobile-logo">
            <img src="./images/logo.jpg" alt="Logo" />
          </div>

          <!-- LOGO -->
          <p class="isg-logo">
            <img src="./images/logo.jpg" alt="Logo" />
          </p>

          <h1><?php echo $appName; ?></h1>
          <p class="isg-subtitle"><?php echo $appSubTitle; ?></p>
          <!-- HEADER ICONS -->
          <div id="timer">
            <h2 style="font-size: 1.0em;padding: 0;margin: 5% 0 0 0;color: white;"><i class="fa fa-heart-o" aria-hidden="true"></i> </h2>

            <ul id="countdown">
              <li>
                <span class="days">00</span>
                <p class="timeRefDays" style="font-size: 12px;">Giorni</p>
              </li>
              <li>
                <span class="hours">00</span>
                <p class="timeRefHours"  style="font-size: 12px;">Ore</p>
              </li>
              <li>
                <span class="minutes">00</span>
                <p class="timeRefMinutes"  style="font-size: 12px;">Minuti</p>
              </li>
              <li>
                <span class="seconds">00</span>
                <p class="timeRefSeconds"  style="font-size: 12px;">Secondi</p>
              </li>
            </ul>
          </div>

          <ul class="isg-header-icons data-html2canvas-ignore" data-html2canvas-ignore>


            <li class="isg-social data-html2canvas-ignore" data-html2canvas-ignore>
              <a href="#" onclick="takeScreen()" class="fa fa-facebook-f data-html2canvas-ignore" data-html2canvas-ignore >Facebook</a>
            </li>
            <li class="isg-social">
              <a href="#" onclick="takeScreen()" class="fa fa-facebook-f female data-html2canvas-ignore" data-html2canvas-ignore >Facebook</a>
            </li>

          </ul>


        </div>
      </header>
    <!-- BOXES -->
    <div id="isg-boxes">


      <!-- ABOUT BOX -->
      <div class="isg-box one-third color1">
        <a id="about-link" href="#about" class="isg-panel-open" id="isg-box1">
          <div class="isg-box-content">
            <h2><?php echo $mod_1_title; ?></h2>
            <p><?php echo $mod_1_subtitle; ?></p>
            <b><?php echo $mod_1_call_to_action; ?></b>
            <i class="fa fa-info isg-box-icon"></i>
            <div class="isg-rotated-text"><span class="isg-rotated-text-inner"><?php echo $mod_1_title; ?></span></div>
          </div>
        </a>
      </div>


      <!-- GALLERY  BOX -->
      <div class="isg-box one-third color2" id="isg-box2">
        <a id="gallery-link" href="#portfolio" class="isg-panel-open">
          <div class="isg-box-content">
            <h2><?php echo $mod_2_title; ?></h2>
            <p><?php echo $mod_2_subtitle; ?></p>
            <b><?php echo $mod_2_call_to_action; ?></b>
            <i class="fa fa-photo isg-box-icon"></i>
            <div class="isg-rotated-text"><span class="isg-rotated-text-inner"><?php echo $mod_2_title; ?></span></div>
          </div>
        </a>
      </div>


      <!-- RSVP BOX -->
      <div class="isg-box one-third color3" id="isg-box3">
        <a id="about-link" href="#rsvp" onclick="$('#ajax-form1').css('display','block'); " class="isg-panel-open">
          <div class="isg-box-content">
            <h2><?php echo $mod_3_title; ?></h2>
            <p><?php echo $mod_3_subtitle; ?></p>
            <b><?php echo $mod_3_call_to_action; ?></b>
            <i class="fa fa-cutlery isg-box-icon"></i>
            <div class="isg-rotated-text"><span class="isg-rotated-text-inner"><?php echo $mod_3_title; ?></span></div>
          </div>
        </a>
      </div>


      <!-- WISH BOX -->
      <div class="isg-box one-third color4" id="isg-box4">
        <a id="wish-link" href="#wishlist" class="isg-panel-open">
          <div class="isg-box-content">
            <h2><?php echo $mod_4_title; ?></h2>
            <p><?php echo $mod_4_subtitle; ?></p>
            <b><?php echo $mod_4_call_to_action; ?></b>
            <i class="fa fa-info isg-box-icon"></i>
            <div class="isg-rotated-text"><span class="isg-rotated-text-inner"><?php echo $mod_4_title; ?></span></div>
          </div>
        </a>
      </div>


      <!-- CONTACT BOX -->
      <div class="isg-box one-third color5" id="isg-box5">
        <a id="contact-link" href="#contact" class="isg-panel-open">
          <div class="isg-box-content">
            <h2><?php echo $mod_5_title; ?></h2>
            <p><?php echo $mod_5_subtitle; ?></p>
            <b><?php echo $mod_5_call_to_action; ?></b>
            <i class="fa fa-send isg-box-icon"></i>
            <div class="isg-rotated-text"><span class="isg-rotated-text-inner"><?php echo $mod_5_title; ?></span></div>
          </div>
        </a>
      </div>


      <!-- GUEST BOOK BOX -->
      <div class="isg-box one-third color6" id="isg-box6">
        <a id="resume-link"  href="./guestbook/">
          <div class="isg-box-content">
            <h2><?php echo $mod_6_title; ?></h2>
            <p><?php echo $mod_6_subtitle; ?></p>
            <b><?php echo $mod_6_call_to_action; ?></b>
            <i class="fa fa-info isg-box-icon"></i>
            <div class="isg-rotated-text"><span class="isg-rotated-text-inner"><?php echo $mod_6_title; ?></span></div>
          </div>
        </a>
      </div>


      <!-- GOOGLE MAP BOX -->
      <div class="isg-box full"  data-bgcolor="#ccc">
        <!-- GOOGLE MAP -->
        <div id="isg-google-map" class="isg-google-map"></div>
      </div>
    </div>


    <!-- FOOTER -->
    <footer id="isg-footer">
      <div class="isg-footer-inner">
        <p><?php echo $appName; ?> <i class="fa fa-copyright"></i>  - <?php echo $brand; ?> <span class="isg-footer-divider">|</span> <i id="isg-back-to-top" class="fa fa-arrow-up"></i></p>
      </div>
    </footer>

  </div>



  <!-- PANELS PAGES -->
  <div id="isg-panels">
    <?php include './parts/noi.php'?>
    <?php include './parts/resume.php'?>
    <?php include './parts/gallery.php'?>
    <?php include './parts/contacts.php'?>
    <?php include './parts/rsvp.php'?>
    <?php include './parts/wishlist.php'?>


  </div>







  <!-- PANEL -->
  <script type="text/javascript" src="js/isg-panel.min.js"></script>
  <!-- CAROUSEL -->
  <script type="text/javascript" src="js/slick.min.js"></script>
  <script type="text/javascript">
  // Testimonials carousel
  jQuery(document).ready(function () {
    "use strict";

    // Initialize the plugin
    jQuery('#testimonial-carousel').slick({
      adaptiveHeight: true, // Adaptive height
      autoplay: true, // Autoplay
      autoplaySpeed: 4000, // autoplay speed in milliseconds
      arrows: false, // Navigation arrows
      dots: true, // Navigation dots
      draggable: false, // Draggable
      infinite: true, // Infinite loop
      speed: 500, // Transition speed
      fade: true // Fade Animation
    });

    // Required to display the carousels in the panels correctly.
    jQuery("#isg-boxes").find(".isg-panel-open").on('click', function () {
      setTimeout(function () {
        jQuery("#isg-panels").find('.isg-slick-carousel').slick('setPosition');
      }, 100);
      return false;
    });
  });
  </script>




  <!-- GOOGLE MAP -->
  <script type="text/javascript" src="js/markerwithlabel.js"></script>
  <?php include './js/dvmap.php'?>

  <script type="text/javascript">

  var bottoneClose = $(".isg-panel-close");
  /*Controllo del back button */
  window.onload = function () {
    if (typeof history.pushState === "function") {
      history.pushState("jibberish", null, null);
      window.onpopstate = function () {
        history.pushState('newjibberish', null, null);
        // Handle the back (or forward) buttons here
        // Will NOT handle refresh, use onbeforeunload for this.
        //if(confirm('Vuoi uscire dalla app?') ) window.close();
        bottoneClose.click();
      };
    }
    else {
      var ignoreHashChange = true;
      window.onhashchange = function () {
        if (!ignoreHashChange) {
          ignoreHashChange = true;
          window.location.hash = Math.random();
          // Detect and redirect change here
          // Works in older FF and IE9
          // * it does mess with your hash symbol (anchor?) pound sign
          // delimiter on the end of the URL

        }
        else {
          ignoreHashChange = false;
        }
      };
    }
  }

  jQuery(document).ready(function () {
    "use strict";
    jQuery("#isg-google-map").dvmap({
      container: 'isg-google-map', // map container id
      latitute: ' 41.413331',
      longitude: '12.851881', // longtitude
      zoom: 12, // a numeric value between 0-19
      zoomcntrl: true, // zoom control - true or false
      maptypecntrl: false, // map type control - true or false
      dvcustom: false // custom map style - true or false
    });
  });
  </script>

  <script type="text/javascript" src="js/lightgallery.min.js"></script>
  <script type="text/javascript" src="js/portfolio-filter.js"></script>
  <!-- HOMEPAGE  -->
  <script type="text/javascript" src="js/home.js"></script>
  <!-- CUSTOM -->
  <script type="text/javascript" src="js/custom.js"></script>
  <!-- TABLE -->
  <script type="text/javascript" src="js/table.js"></script>
  <!-- HTML2Canvas -->
  <script type="text/javascript" src="js/html2canvas.min.js"></script>
  <link rel="stylesheet" href="../condivision/fl_set/jsc/countdown/countdown.css?vaffanculoGoogle">
  <script type="text/javascript" src="../condivision/fl_set/jsc/countdown/countdown.js"></script>
  <script>

  $(document).ready(function(){
    $("#countdown").countdown({
      date: "10 july 2018 00:00:00",
      format: "on"
    },

    function() {
      // callback function
    });
  });


  function takeScreen(){
    var canvas = document.getElementById('capture');
    var ctx = canvas.getContext('2d');
    width = $('#isg-header').width();
    height = $('#isg-header').height();
    var image;

    html2canvas($('#isg-header')[0], {
      canvas:canvas,
      height:height,
      width:width,
      backgroundColor: '#d7d4d4'  });
    sendImg();
  }

  function sendImg(){

    var canvas = document.getElementById('capture');
    image = canvas.toDataURL("image/png"); 

    $.post('code/create_img.php',{img : image}, function(data){ alert('dsf'); });
  }

  </script>

</body>

</html>
