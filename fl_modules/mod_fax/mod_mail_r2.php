<?php

if($_SESSION['usertype'] != 0)  { echo "Accesso Non Autorizzato"; exit; }
if (isset($_GET['id']))
{
  $var = $_GET['id'];
  $conn_id = check($_GET['conn_id']);
  $mail_conn = $mail_conns[$conn_id];
}

$fetchstructure = imap_fetchstructure($mail_conn, $var);
$intestazioni=imap_header($mail_conn, $var);

echo "<div class=\"esito\" style=\"text-align: left; width: 95%;\">";
echo"Da: <b>".htmlentities($intestazioni->fromaddress)."</b><br>";
echo"Per: <b>".htmlentities($intestazioni->toaddress)."</b><br>";
//echo"CC: <b>".htmlentities($intestazioni->ccaddress)."</b><br>";
echo"Oggetto:".quoted_printable_decode($intestazioni->subject)."</b><br>";
$data=gmstrftime("%b %d %Y", strtotime($intestazioni->date))
;echo "Data: $data<br></div>";
//echo"Dimensione:".ceil($intestazioni->bytes/1024)."<br>";

$v1 = "0";
$i = "";
$ctrl = 0;
$ed = 0;
$mail_or_link = 0;
$allegati_qu = "";
$filenames = "";
foreach($fetchstructure as $k => $val)
{
//echo $k." = ".$val."<br>";
  if (($ctrl=="") && ($k=="subtype"))
  {
    if ($val=="RELATED")
    {
      $i = ".2";
      $ctrl=1;
    }
    if ($val=="MIXED")
    {
      $i = ".2";
      $ctrl=1;
      $ed=1;
      echo "<b>Allegati:</b> ";
    }
  }
  if ($k=="parts")
  {
    $items = $fetchstructure -> parts;
	
	
    foreach($items as $k0 => $val0)
    {
      $v1 = $v1+1;
	  
      foreach($val0 as $k1 => $val1)
      {
        if (($k1=="subtype") && ($val1=="TIFF"))
        {
          $atch = $fetchstructure -> parts[$v1-1] -> parameters[0] -> value;
          echo "<p><a href=\"attach.php?conn_id=$conn_id&id=$var&item=".$v1."&amp;file=".$atch."\" class=\"button\" onclick=\"window.open(this.href); return: false;\">".$atch."</a></p>";
		 (!isset($allegati_qu)) ? $allegati_qu = "allegati[]=$v1" : $allegati_qu .= "&allegati[]=$v1";
		 (!isset($filenames)) ? $filenames = "filenames[]=$atch" : $filenames .= "&filenames[]=$atch";
        } else if (($k1=="disposition") && ($val1=="ATTACHMENT"))
        {
          $atch = $fetchstructure -> parts[$v1-1] -> dparameters[0] -> value;
          echo "<p><a href=\"attach.php?conn_id=$conn_id&id=$var&item=".$v1."&amp;file=".$atch."\" class=\"button\" onclick=\"window.open(this.href); return: false;\">".$atch."</a></p>";
		 (!isset($allegati_qu)) ? $allegati_qu = "allegati[]=$v1" : $allegati_qu .= "&allegati[]=$v1";
		 (!isset($filenames)) ? $filenames = "filenames[]=$atch" : $filenames .= "&filenames[]=$atch";
        } else if (($k1=="subtype") && ($val1=="PDF"))
        {
           $atch = $fetchstructure -> parts[$v1-1] -> parameters[0] -> value;
          echo "<p><a href=\"attach.php?conn_id=$conn_id&id=$var&item=".$v1."&amp;file=".$atch."\" class=\"button\" onclick=\"window.open(this.href); return: false;\">".$atch."</a></p>";
		 (!isset($allegati_qu)) ? $allegati_qu = "allegati[]=$v1" : $allegati_qu .= "&allegati[]=$v1";
		 (!isset($filenames)) ? $filenames = "filenames[]=$atch" : $filenames .= "&filenames[]=$atch";
        }
      } 
    }
  }
}
if ($v1=="0")
{
  $v1 = 1;
  $mail_or_link=1;
}
if ($ed)
{
  $v1=1;
  echo "<br /><br />";
}
$string=@imap_fetchbody($mail_conn, $var, $v1.$i);
$string=@quoted_printable_decode(@str_replace("=\r\n","",$string));
if ($mail_or_link=="")
{
  echo $string;
}else{
  $word="";
  $row = @explode("\n", $string);
  for ($inc=0;$inc<@sizeof($row);$inc++)
  {
    $row[$inc] = $row[$inc]."<br />";
    $word=@array_merge($word,@explode(" ", $row[$inc]));
  }
  for ($k=0;$k<@sizeof($word);$k++)
  {
    $address = @strpos($word[$k], "www");
    $url = @strpos($word[$k], "http");
    $link = @strpos($word[$k], "href");
    $ftp = @strpos($word[$k], "ftp");
    if ($link == 0)
    {
      if (($url == 0) && ($address == 0))
      {
        echo $word[$k]." ";
      }else{
        echo "<a href=\"".@str_replace('<br />','',$word[$k])."\">";
        echo $word[$k]."</a>";
      }
    }else{
      echo $word[$k]." ";
    }
  }
}

  echo "<p><a href=\"attach.php?conn_id=$conn_id&archivia=true&id=$var&$allegati_qu&$filenames\" class=\"button\">Archivia Fax</a></p>";
@imap_close($mail_conn);
?>