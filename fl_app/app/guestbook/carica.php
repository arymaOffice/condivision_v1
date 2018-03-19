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

            #photo{
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

            .load{
            position:relative;width:100%;height:100%;background-color:white;padding:0px;margin:0px;text-align: center;

            }
        </style>
            
                    
        <script type="text/javascript">
            window.onload=function(){
	            document.getElementById("button").addEventListener("click", function(){
	            document.getElementById("photo").value="";
	              document.getElementById("photo").click();  // trigger the click of actual file upload button
	            });
            }

            // usage:
            /**var input = document.getElementById('input');
            input.onchange = function(e) {
              getOrientation(input.files[0], function(orientation) {
                alert('orientation: ' + orientation);
              });
            }*/

            //Copyright by Marco 01-12-2017
            function isIPhone(){
                return (
                    (navigator.platform.indexOf("iPhone") != -1) ||
                    (navigator.platform.indexOf("iPod") != -1)
                );
            }
            var isChanged = false;
            var degree = 0;
            function TrasformazioneOrientamentoIdxAngolo(orientation) {
              //  if (isIPhone()) return;

                degree = 0;
                switch (orientation) {
                    case 1:
                        degree = 0;
                        break;
                    case 2:
                        degree = 0;
                        break;
                    case 3:
                        degree = 180;
                        break;
                    case 4:
                        degree = 180;
                        break;
                    case 5:
                        degree = 90;
                        break;
                    case 6:
                        degree = 90;
                        break;
                    case 7:
                        degree = 270;
                        break;
                    case 8:
                        degree = 270;
                        break;
                }
            }

            var fnCaricaImmagine = (function() { 
                    var photo = document.getElementById("photo");
                    
                    if(photo.files != undefined) { 
                        //var loader = document.getElementById("loader");
                        //loader.style.display = "inline";
                        var file  = photo.files[0];
                        //var rotation = 2;
  
                        getOrientation(file, function(orientation) {  // this.files[0]
                             //-2: not jpeg
                             //-1: not defined
                             //https://i.stack.imgur.com/VGsAj.gif
                            TrasformazioneOrientamentoIdxAngolo(orientation);
                            //alert('orientation: ' + orientation + ' degree: ' + degree);
                        });

                        document.getElementById("orig").value = file.fileSize;
                        var preview = document.getElementById("preview");
                        var r = new FileReader();
                        var base64 = '';                               
                        r.onload = (function(event) { 
                            return function(e) {                                 
                                //var rotation=90;
                                var rotation = degree;
                                var maxx = 800;
                                var maxy = 800;
                                
                                var previewImage = new Image();
                                previewImage.onload = function(){

                                    //var filename = photo.files[0].name;
                                    //var ext = getFileExtension(filename);
                                    //alert(ext); 
                                   // setTimeout(function(){ _resize(previewImage, maxx, maxy);}, 5000);
                                   
                                    base64 = fnResizeRotation(previewImage, maxx, maxy,rotation);
                                    if(base64 !== '') { 
                                        document.getElementById('base64').value = base64;
                                    } else {
                                        alert('problem - please attempt to upload again');
                                    }
                                }
                                previewImage.src = e.target.result;   // r.result;
      
                                
                            }; 
                            
                        })(preview);
                        
                        r.readAsDataURL(file);
                    } else {
                        alert("Seems your browser doesn't support resizing");
                    }
                    return false;
            });



            //function _resize(img, maxWidth, maxHeight,rotation) 
            var fnResizeRotation = (function (pImg, pMaxWidth, pMaxHeight, pRotation) {
                    //alert("imgObj:" + pImg + " Maxwidth:" + pMaxWidth + " MaxHeight:" + pMaxHeight + "Rotation:" + pRotation);
                    try {
                        var ratio = 1;
                        var canvas = document.createElement("canvas");
                        canvas.style.display="none";
                        document.body.appendChild(canvas);

                        
                        //if(canvas) document.body.removeChild(canvas);
                        //canvas = document.createElement("canvas");
                        
                        var canvasCopy = document.createElement("canvas");
                        canvasCopy.style.display="none";
                        document.body.appendChild(canvasCopy);
                        //canvasCopy.style.width="20%";
       
                        
                        var ctx = canvas.getContext("2d");
                        var copyContext = canvasCopy.getContext("2d");
                        
                        
                        if(pImg.width > pMaxWidth)
                                ratio = pMaxWidth / pImg.width;
                        else if(pImg.height > pMaxHeight)
                                ratio = pMaxHeight / pImg.height;

                        /*                      
                        var wrh = pImg.width / pImg.height;
                        var newWidth = canvas.width;
                        var newHeight = newWidth / wrh;
                        if (newHeight > canvas.height) {
                            newHeight = canvas.height;
                            newWidth = newHeight * wrh;
                        }
                       */
                       
                        // metodo dynamico resize qualit�    disattivato risolto nel ricorsivo 
                        // new thumbnailer(canvasCopy, pImg, ratio, 8); //this produces lanczos3
                        // but feel free to raise it up to 8. Your client will appreciate
                        // that the program makes full use of his machine.

                        
                        if(pRotation == 90 || pRotation == 270) {
                            canvasCopy.width = pImg.height;
                            canvasCopy.height = pImg.width;
                        } else {
                            canvasCopy.width = pImg.width;
                            canvasCopy.height = pImg.height;
                        }
                        
   
                        
                        // metodo dynamico
                        canvas.width = canvasCopy.width * ratio;
                        canvas.height = canvasCopy.height * ratio;
                                  
                        
                        copyContext.clearRect(0,0,canvasCopy.width,canvasCopy.height);
                        if(pRotation == 90 || pRotation == 270) {
                            copyContext.translate(pImg.height/2,pImg.width/2);
                        } else {
                            copyContext.translate(pImg.width/2,pImg.height/2);
                        }
                       
                       
                        copyContext.rotate(pRotation*Math.PI/180);
                        copyContext.drawImage(pImg,-pImg.width/2,-pImg.height/2);
                        
                        ctx.drawImage(canvasCopy, 0, 0, canvas.width, canvas.height);
                        //uguale perchè assegno le stesse dimensioni in "ratio" --,
                        //ctx.drawImage(canvasCopy, 0, 0, canvasCopy.width, canvasCopy.height, 0, 0, canvas.width, canvas.height);

                    } catch (e) { 
                        //document.getElementById('loader').style.display="none";
                        alert("Problema nel Resize Rotate Immagine");
                        return '';
                    }
                    
                    
                    

                    
                                        
                    // Stream Base64 
                    var dataURL = canvas.toDataURL("image/jpg",0.1);
                    document.body.removeChild(canvas);
                    document.body.removeChild(canvasCopy);

                    //var fullQuality = canvas.toDataURL('image/jpeg', 0.8);
                    preview2.src=dataURL;
                    return dataURL.replace(/^data:image\/(png|jpeg|jpg);base64,/, "");

            });

                
                
            function getFileExtension(filename) {
                return filename.split('.').pop();
            }


            function send(){
                //alert('Immagine inviata');
                
		        var base64 = document.getElementById('base64').value;
		        var commento = document.getElementById('commento').value;
		        document.getElementById("preview2").src = "loader.gif";
		        document.getElementById("manda").style.display = "none";
		        document.getElementById("commento").readOnly = true;
		        formData= new FormData();
                formData.append("base64", base64);
                formData.append("commento", commento);
      				        $.ajax({
        				        url: "upload.php",
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


            function getOrientation(file, callback) {
              var reader = new FileReader();
              reader.onload = function(e) {

                var view = new DataView(e.target.result);
                if (view.getUint16(0, false) != 0xFFD8) return callback(-2);
                var length = view.byteLength, offset = 2;
                while (offset < length) {
                  var marker = view.getUint16(offset, false);
                  offset += 2;
                  if (marker == 0xFFE1) {
                    if (view.getUint32(offset += 2, false) != 0x45786966) return callback(-1);
                    var little = view.getUint16(offset += 6, false) == 0x4949;
                    offset += view.getUint32(offset + 4, little);
                    var tags = view.getUint16(offset, little);
                    offset += 2;
                    for (var i = 0; i < tags; i++)
                      if (view.getUint16(offset + (i * 12), little) == 0x0112)
                        return callback(view.getUint16(offset + (i * 12) + 8, little));
                  }
                  else if ((marker & 0xFF00) != 0xFF00) break;
                  else offset += view.getUint16(offset, false);
                }
                return callback(-1);
              };
              reader.readAsArrayBuffer(file);
            }

            
            
            
            
            
            // returns a function that calculates lanczos weight
            function lanczosCreate(lobes) {
                return function(x) {
                    if (x > lobes)
                        return 0;
                    x *= Math.PI;
                    if (Math.abs(x) < 1e-16)
                        return 1;
                    var xx = x / lobes;
                    return Math.sin(x) * Math.sin(xx) / x / xx;
                };
            }

            // elem: canvas element, img: image element, sx: scaled width, lobes: kernel radius
            function thumbnailer(elem, img, sx, lobes) {
                this.canvas = elem;
                elem.width = img.width;
                elem.height = img.height;
                elem.style.display = "none";
                this.ctx = elem.getContext("2d");
                this.ctx.drawImage(img, 0, 0);
                this.img = img;
                this.src = this.ctx.getImageData(0, 0, img.width, img.height);
                this.dest = {
                    width : sx,
                    height : Math.round(img.height * sx / img.width),
                };
                this.dest.data = new Array(this.dest.width * this.dest.height * 3);
                this.lanczos = lanczosCreate(lobes);
                this.ratio = img.width / sx;
                this.rcp_ratio = 2 / this.ratio;
                this.range2 = Math.ceil(this.ratio * lobes / 2);
                this.cacheLanc = {};
                this.center = {};
                this.icenter = {};
                setTimeout(this.process1, 0, this, 0);
            }

            thumbnailer.prototype.process1 = function(self, u) {
                self.center.x = (u + 0.5) * self.ratio;
                self.icenter.x = Math.floor(self.center.x);
                for (var v = 0; v < self.dest.height; v++) {
                    self.center.y = (v + 0.5) * self.ratio;
                    self.icenter.y = Math.floor(self.center.y);
                    var a, r, g, b;
                    a = r = g = b = 0;
                    for (var i = self.icenter.x - self.range2; i <= self.icenter.x + self.range2; i++) {
                        if (i < 0 || i >= self.src.width)
                            continue;
                        var f_x = Math.floor(1000 * Math.abs(i - self.center.x));
                        if (!self.cacheLanc[f_x])
                            self.cacheLanc[f_x] = {};
                        for (var j = self.icenter.y - self.range2; j <= self.icenter.y + self.range2; j++) {
                            if (j < 0 || j >= self.src.height)
                                continue;
                            var f_y = Math.floor(1000 * Math.abs(j - self.center.y));
                            if (self.cacheLanc[f_x][f_y] == undefined)
                                self.cacheLanc[f_x][f_y] = self.lanczos(Math.sqrt(Math.pow(f_x * self.rcp_ratio, 2)
                                        + Math.pow(f_y * self.rcp_ratio, 2)) / 1000);
                            weight = self.cacheLanc[f_x][f_y];
                            if (weight > 0) {
                                var idx = (j * self.src.width + i) * 4;
                                a += weight;
                                r += weight * self.src.data[idx];
                                g += weight * self.src.data[idx + 1];
                                b += weight * self.src.data[idx + 2];
                            }
                        }
                    }
                    var idx = (v * self.dest.width + u) * 3;
                    self.dest.data[idx] = r / a;
                    self.dest.data[idx + 1] = g / a;
                    self.dest.data[idx + 2] = b / a;
                }

                if (++u < self.dest.width)
                    setTimeout(self.process1, 0, self, u);
                else
                    setTimeout(self.process2, 0, self);
            };
            thumbnailer.prototype.process2 = function(self) {
                self.canvas.width = self.dest.width;
                self.canvas.height = self.dest.height;
                self.ctx.drawImage(self.img, 0, 0, self.dest.width, self.dest.height);
                self.src = self.ctx.getImageData(0, 0, self.dest.width, self.dest.height);
                var idx, idx2;
                for (var i = 0; i < self.dest.width; i++) {
                    for (var j = 0; j < self.dest.height; j++) {
                        idx = (j * self.dest.width + i) * 3;
                        idx2 = (j * self.dest.width + i) * 4;
                        self.src.data[idx2] = self.dest.data[idx];
                        self.src.data[idx2 + 1] = self.dest.data[idx + 1];
                        self.src.data[idx2 + 2] = self.dest.data[idx + 2];
                    }
                }
                self.ctx.putImageData(self.src, 0, 0);
                self.canvas.style.display = "block";
            };

    </script>
<div class="alpha">
    <img src="loader.gif" alt="Image preview" id="preview" >
</div>
<div class="container">
	<h2>Carica una foto </h2>
	<div class="row" >
		<div style="text-align: center;max-width: 500px;margin: auto;">
    		<input type="file" name="photo" id="photo" onchange="fnCaricaImmagine();" accept="image/jpg, image/jpeg, image/png">  
   			<div id="button"> <img src="noImage.png" alt="Image preview" id="preview2" style="max-height:400px;width: 90%;" ></div>
    		<!--<img src="loader.gif" id="loader" />style="display:none;"-->
  			 <form name="upload" id="upload" method='post' action='show.php'>
    			<input type="hidden" id="base64" name="base64" value=""/>
        		<input type="hidden" id="orig" name="orig" value=""/>
				<input type="hidden" id="conferma" name="conferma" value='1'/>
         		<textarea class="form-control" rows="2" id="commento" name="commento" placeholder="stai pensando a.. " style="margin: 20px auto 20px auto;" required></textarea>
           		<input type="button" id="manda" class="btn btn2 btn-info" name="manda" value="Invia agli sposi" onclick="send();"  />
   			</form>
   			<div style="clear:both"></div>
		</div>
	</div>
</div>

 <button type="button" class="btn btn-dark btn-circle btn-lg myBTNhover" style="background-color:#b77c9c;" onclick="window.location='gallery.php'" ><i class="fa fa-picture-o"></i></button>

<?php
include_once("footer.php");
