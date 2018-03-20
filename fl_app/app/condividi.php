<?php
require_once 'code/config.php';
?>

<html>
<head>
  <title>Matrimonio in Cloud - Digital Wedding App</title>
    <!-- You can use Open Graph tags to customize link previews.
    Learn more: https://developers.facebook.com/docs/sharing/webmasters -->
  <meta property="og:url"           content="http://www.matrimonioincloud.it/app/condividi.php?id=<?php echo $code; ?>" />
  <meta property="og:type"          content="website" />
  <meta property="og:title"         content="Matrimonio in Cloud - Digital Wedding App" />
  <meta property="og:description"   content="Matrimonio in Cloud - Digital Wedding App" />
  <meta property="og:image"         content="http://www.matrimonioincloud.it/app/images/toShare/<?php echo $code; ?>.png" />

  <!-- CSS -->
  <link type="text/css" rel="stylesheet" href="css/normalize.css">
  <link type="text/css" rel="stylesheet" href="css/font-awesome.min.css">
  <link type="text/css" rel="stylesheet" href="css/style.css">
  <link type="text/css" rel="stylesheet" href="css/slick.css">
  <link type="text/css" rel="stylesheet" href="css/lightgallery.css">
  <link type="text/css" rel="stylesheet" href="css/colors.css">
  <link type="text/css" rel="stylesheet" href="css/media.css?123">
  <link type="text/css" rel="stylesheet" href="css/table.css?123">
  <link href="https://fonts.googleapis.com/css?family=Gochi+Hand" rel="stylesheet">
  <style>
    body,h1,h2,.isg-intro p.isg-subtitle,.isg-loading-text,.isg-loading-sub-text,.isg-rotated-text-inner,#countdown li p  { font-family: 'Gochi Hand', cursive;  }
    body{ background:none;}
    #isg-header{
      color: #212121;
      
      background-color: rgba(0,0,0,0.4); 
      // Font Rendering
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
      text-rendering: optimizeLegibility;
      width:940px;
      height:788px;
    }
  </style>
</head>

<body >

  <!-- JAVASCRIPT FILES -->
  <script type="text/javascript" src="js/jquery-3.1.0.min.js"></script>
    <!-- HTML2Canvas -->
    <script type="text/javascript" src="js/html2canvas.min.js"></script>
  <link rel="stylesheet" href="../condivision/fl_set/jsc/countdown/countdown.css?vaffanculoGoogle">
  <script type="text/javascript" src="../condivision/fl_set/jsc/countdown/countdown.js"></script>

  <!-- Load Facebook SDK for JavaScript -->
  <div id="fb-root"></div>
  <script>(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "https://connect.facebook.net/it_IT/sdk.js#xfbml=1&version=v2.12";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));</script>

  <canvas id="capture" style="display:none"></canvas>


       <!-- HEADER -->
       <header id="isg-header">
       <img id="imgAdd" src="images/sfondo.jpg" style="width:100%;height:100%;z-index:0;position: absolute;top: 0;left: 0;opacity: 0.4;" >



        <div class="isg-intro">
          <h1><?php echo $appName; ?></h1>
          <p class="isg-subtitle" style="color: #fff9;"><?php echo $appSubTitle; ?></p>
          <!-- HEADER ICONS -->
          <div id="timer">
            <h2 style="font-size: 1.0em;padding: 0;margin: 5% 0 0 0;color: white;"><i class="fa fa-heart-o fa-4x" aria-hidden="true"></i> </h2>

            <ul id="countdown" style="font-size: 2.0em;">
              <li>
                <span class="days">00</span>
                <p class="timeRefDays" style="font-size: 30px;color: #fff9;">Giorni</p>
              </li>
              <li>
                <span class="hours">00</span>
                <p class="timeRefHours"  style="font-size: 30px;color: #fff9;">Ore</p>
              </li>
              <li>
                <span class="minutes">00</span>
                <p class="timeRefMinutes"  style="font-size: 30px;color: #fff9;">Minuti</p>
              </li>
              <li>
                <span class="seconds">00</span>
                <p class="timeRefSeconds"  style="font-size: 30px;color: #fff9;">Secondi</p>
              </li>
            </ul>
          </div>
        </div>
      </header>

  <br>
  <br>
  <!-- Your share button code -->
  <div class="fb-share-button" style="margin: 0 40%;" data-href="http://www.matrimonioincloud.it/app/images/toShare/<?php echo $code; ?>.png" data-layout="button_count" data-size="small" data-mobile-iframe="true"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fwww.matrimonioincloud.it%2Fapp%2Fimages%2FtoShare%2F<?php echo $code; ?>.png&amp;src=sdkpreparse" class="fb-xfbml-parse-ignore">Condividi</a></div>

  <script>

$(document).ready(function(){
  $("#countdown").countdown({
    date: "10 july 2019 00:00:00",
    format: "on"
  },

  function() {
    // callback function
  });
});

    setTimeout(function(){

          var canvas = document.getElementById('capture');
    var ctx = canvas.getContext('2d');
    width = 940;
    height = 788;



    html2canvas($('#isg-header')[0], {
      canvas:canvas,
      height:height,
      width:width,
      backgroundColor: 'transparent',
      onrendered: function(canvas) {

      }
     });

    }, 2000);



      setTimeout(sendImg, 5000);

    function sendImg(){

      var canvas = document.getElementById('capture');

      image = canvas.toDataURL("image/png");

      $.post('code/create_img.php',{img : image}, function(data){
        var parsed =  $.parseJSON(data);
        if(parsed){

          }else{
            alert('Errore nella crazione dell\'imaggine');
          }
      });

      }
</script>


</body>
</html>