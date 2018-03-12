var wrapper = document.getElementById("signature-pad"),
    clearButton = wrapper.querySelector("[data-action=clear]"),
    saveButton = wrapper.querySelector("[data-action=save]"),
    canvas = wrapper.querySelector("canvas"),
    signaturePad;

// Adjust canvas coordinate space taking into account pixel ratio,
// to make it look crisp on mobile devices.
// This also causes canvas to be cleared.
function resizeCanvas() {
    var ratio =  window.devicePixelRatio || 1;
    canvas.width = canvas.offsetWidth * ratio;
    canvas.height = canvas.offsetHeight * ratio;
    canvas.getContext("2d").scale(ratio, ratio);
}

window.onresize = resizeCanvas;
resizeCanvas();

signaturePad = new SignaturePad(canvas);

clearButton.addEventListener("click", function (event) {
    signaturePad.clear();
});

saveButton.addEventListener("click", function (event) {
    if (signaturePad.isEmpty()) {
        alert("Firma nel pannello prima.");
    } else {
        //document.getElementById("sign").src = signaturePad.toDataURL();
		
		var imgData = getBase64Data(signaturePad.toDataURL("image/png"));
		localStorage.setItem("imgData", imgData);	
		showSign("sign");
		window.location.assign('index.php');
		
    }
});

function getBase64Data(dataURL) {
    return dataURL.replace(/^data:image\/(png|jpg);base64,/, "");
}


function getBase64Image(img) {
    var canvas = document.createElement("canvas");
    canvas.width = img.width;
    canvas.height = img.height;

    var ctx = canvas.getContext("2d");
    ctx.drawImage(img, 0, 0);

    var dataURL = canvas.toDataURL("image/png");
    return dataURL.replace(/^data:image\/(png|jpg);base64,/, "");
}

function showSign(idImg){
var dataImage = localStorage.getItem('imgData');
var bannerImg = document.getElementById(idImg);
bannerImg.src = "data:image/png;base64," + dataImage;
}