<?php 
$id = filter_var($_GET['id'],FILTER_SANITIZE_NUMBER_INT);
?>

<html>
<head>
  <title>Matrimonio in Cloud - Digital Wedding App</title>
    <!-- You can use Open Graph tags to customize link previews.
    Learn more: https://developers.facebook.com/docs/sharing/webmasters -->
  <meta property="og:url"           content="http://www.matrimonioincloud.it/app/condividi.php?id=<?php echo $id; ?>" />
  <meta property="og:type"          content="website" />
  <meta property="og:title"         content="Matrimonio in Cloud - Digital Wedding App" />
  <meta property="og:description"   content="Matrimonio in Cloud - Digital Wedding App" />
  <meta property="og:image"         content="http://www.matrimonioincloud.it/app/images/toShare/<?php echo $id; ?>.png" />
</head>
<body>

  <!-- Load Facebook SDK for JavaScript -->
  <div id="fb-root"></div>
  <script>(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.12";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));</script>

  <img src="http://www.matrimonioincloud.it/app/images/toShare/<?php echo $id; ?>.png" style="width:100%;height:80%">

  <!-- Your share button code -->
  <div class="fb-share-button" 
    data-href="http://www.matrimonioincloud.it/app/condividi.php?id=<?php echo $id; ?>" 
    data-layout="button_count">
  </div>

</body>
</html>