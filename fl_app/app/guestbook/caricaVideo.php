<?php
include_once("header.php");
?>
       <style type="text/css">
            html{
                min-width:320px;
            }
            body{
             
               min-width:320px;
               padding:0px;
               margin:0px;
            }

            #video{
              display: none; 
            }

        

            .alpha {
                filter: alpha(opacity=0);
                -moz-opacity: 0.0;
                -khtml-opacity: 0.0;
                opacity: 0.0;
                z-index:-9999999;
                position:fixed;
                overflow:hidden;
            }
            
            #button{
            text-align:center;
            }
            #button .fa { font-size: 13em; margin: 20px; }
            .load{
            position:relative;width:100%;height:100%;background-color:white;padding:0px;margin:0px;text-align: center;

            }
            h2 { text-transform: uppercase; }
        </style>
            
                    
        <script type="text/javascript">
          

            window.onload=function(){
	            document.getElementById("button").addEventListener("click", function(){
	              document.getElementById("video").value="";
	              document.getElementById("video").click();  // trigger the click of actual file upload button
	            });
            }

      
            function isIPhone(){
                return (
                    (navigator.platform.indexOf("iPhone") != -1) ||
                    (navigator.platform.indexOf("iPod") != -1)
                );
            }
 
                
                
            function getFileExtension(filename) {
                return filename.split('.').pop();
            }


            function sendVideo(){
                //alert('Immagine inviata');
                
		        document.getElementById("preview2").src = "loader.gif";
		        document.getElementById("manda").style.display = "none";
		        document.getElementById("commento").readOnly = true;
		        formData= new FormData();
     			$.ajax({
        				        url: "uploadVideo.php",
        				        type: "POST",
        				        data: formData,
        				        processData: false,
        				        contentType: false,
        				        success: function(data){
        				        K=false;
        				        //document.getElementById("preview2").src = "noImage.png";
            			        window.location = "gallery.php";
        				        }
      			});
                                
                                
            }


    
            
            
      

</script>

<link rel="stylesheet" href="https://www.matrimonioincloud.it/condivision/fl_set/css/font-awesome.min.css">



<div class="alpha">
    <img src="loader.gif" alt="Image preview" id="preview" >
</div>
<div class="container">
	<h2>Carica un Video </h2>
	<div class="row" >
		<div style="text-align: center;max-width: 500px;margin: auto;">
    		
   			 <div id="button"><i class="fa fa-video-camera" aria-hidden="true"></i></div>

  			 <form name="upload" id="upload" method='post' action='uploadVideo.php' enctype="multipart/form-data">
                <input type="file" name="video"  accept="video/*" id="video" capture="camcorder">  
           		<textarea class="form-control" rows="2" id="commento" name="commento" placeholder="Lascia un messaggio agli sposi... " style="margin: 20px auto 20px auto;" required></textarea>
           		<input type="submit" id="manda" class="btn btn2 btn-info" name="manda" value="Carica Video"   />
   			</form>
   			<div style="clear:both"></div>
		</div>
	</div>
</div>

 <button type="button" class="btn btn-dark btn-circle btn-lg myBTNhover" style="background-color:#b77c9c;" onclick="window.location='gallery.php'" ><i class="fa fa-picture-o"></i></button>

<?php
include_once("footer.php");
