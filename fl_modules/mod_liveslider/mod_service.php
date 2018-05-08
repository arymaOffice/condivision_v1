<?php
//mostra l'iframe con il servizio e gestisce le pubblicità in basso
$url = filter_var($_GET['url'], FILTER_SANITIZE_URL);

$output = array();

$output = parse_url($url);

$id = str_replace ('id=','',$output['query']);
?>

<html>
    <head>  <meta charset="UTF-8"> <meta name="viewport" content="width=device-width, initial-scale=1.0"> </head>
    <body style="margin:0;padding:0;">
    <iframe src="<?php echo $url; ?>" webkitAllowFullScreen mozAllowFullScreen allowFullScreen style="width: 100%;height: 100%;border: none;"></iframe>
    <div id="ads" style="width: 80%;height: 20%;display: none;position: absolute;top: 40%;border: none;	margin: 0 10%;"></div>

    <script>
        var number = 0;

        function httpGetAsync(theUrl, callback)
        {//funzione per richiesta ajax
            var xmlHttp = new XMLHttpRequest();
            xmlHttp.onreadystatechange = function() {
                if (xmlHttp.readyState == 4 && xmlHttp.status == 200)
                    callback(xmlHttp.responseText);
            }
            xmlHttp.open("GET", theUrl, true); // true for asynchronous
            xmlHttp.send(null);
        }



            var files = [];//array dei file publicità
            var src = '';//src delle immagini

            //chiamata ajax
            httpGetAsync('mod_opera.php?give_ads&link_id=<?php echo $id; ?>',function(data){
                parsed = JSON.parse(data);
                if(parsed.esito == 1){

                    files = parsed.files;
                    src = parsed.src;
                }
            });

            setInterval(function(){ //pubbblicità con ritardo

                if(typeof files[number] === undefined || !files[number]){
                    //console.log('undefined' + number);
                    number = 0;
                }


                document.getElementById("ads").innerHTML = "";
                document.getElementById("ads").style.display = "block";
                document.getElementById("ads").innerHTML = "<img style='width:100%;height: 100%;' src='"+src+files[number]['path']+"'>";
                //console.log('add');
                setTimeout(function(){ document.getElementById("ads").style.display = "none"; /*console.log('rm');*/ number++;  },30000);

                

            },600000);


    </script>


</body>
</html>