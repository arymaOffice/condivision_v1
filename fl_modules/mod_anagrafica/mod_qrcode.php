<?php 

require_once('../../fl_core/autentication.php');
include('../../fl_core/dataset/items_rel.php');
$id = check($_GET['id']);
include("../../fl_inc/headers.php");
 ?>
 
<body style=" background: rgb(241, 241, 241) none repeat scroll 0% 0%; text-align: center; padding: 20px;">

<div style="text-align: center; margin: 0 auto;">
<a href="http://lookit.condivision.biz/<?php echo $id; ?>/app.html"><img src="http://chart.apis.google.com/chart?cht=qr&chs=500x500&chl=http://lookit.condivision.biz/<?php echo $id; ?>/app.html" alt="Qr Code" />
<h2>http://lookit.condivision.biz/<?php echo $id; ?>/app.html</h2></a>

</div>

</body></html>