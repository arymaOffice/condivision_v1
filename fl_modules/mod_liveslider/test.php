<?php
//mostra l'iframe con il servizio e gestisce le pubblicità in basso 
$url = filter_var($_GET['url'], FILTER_SANITIZE_URL);
?>

<html>
    <head>  <meta charset="UTF-8"> <meta name="viewport" content="width=device-width, initial-scale=1.0"> </head>
    <body style="margin:0;padding:0;">
    <iframe src="<?php echo $url; ?>"  style="width: 100%;height: 80%;border: none;"></iframe>
    <div id="ads" style="width: 100%;height: 20%;"></div>

    <script>
        var number = 0;

        function httpGetAsync(theUrl, callback)
{
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function() { 
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200)
            callback(xmlHttp.responseText);
    }
    xmlHttp.open("GET", theUrl, true); // true for asynchronous 
    xmlHttp.send(null);
}

        setInterval(function(){
            //code goes here that will be run every 5 seconds.
            document.getElementById("ads").innerHTML = "";

            httpGetAsync('mod_opera.php?give_ads='+number,function(data){
                parsed = JSON.parse(data);
                if(parsed.esito == 1){

                    if(parsed.type == 'img'){ //append image
                        document.getElementById("ads").innerHTML = "<img style='width:100%;height: 100%;' src='"+parsed.src+"'>";
                    }else{
                        document.getElementById("ads").innerHTML = "<video src='"+parsed.src+"'></video>";                        
                    }

                }else{
                    alert('errror');
                }
            })


            number ++;
        }, 30000);

    </script>


</body>
</html>