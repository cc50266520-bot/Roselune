
async function loadModels(){
  await faceapi.nets.tinyFaceDetector.loadFromUri('/hasnaa_chakik_51831003/models');
}

function classifyTone(r,g,b){
  const brightness = (r*0.299 + g*0.587 + b*0.114);
  if (brightness > 170) return "light";
  if (brightness > 120) return "medium";
  return "universal"; 
}

async function autoDetectAndRedirect(imgEl){
  const statusBox = document.getElementById("statusBox");
  statusBox.style.display = "block";
  statusBox.innerText = "Analyzing image...";

  await loadModels();

  const detection = await faceapi.detectSingleFace(
    imgEl,
    new faceapi.TinyFaceDetectorOptions()
  );

  if(!detection){
    statusBox.innerText = "No face detected. Redirecting to universal products...";
    window.location.href = "recommendations.php?tone=universal";
    return;
  }
  const box = detection.box;
  const c = document.createElement("canvas");
  c.width = Math.floor(box.width);
  c.height = Math.floor(box.height);
  const ctx = c.getContext("2d");
  ctx.drawImage(imgEl, box.x, box.y, box.width, box.height, 0, 0, c.width, c.height);

  const data = ctx.getImageData(0,0,c.width,c.height).data;
  let r=0,g=0,b=0,count=0;
  for(let i=0;i<data.length;i+=4){
    r+=data[i]; g+=data[i+1]; b+=data[i+2]; count++;
  }
  r=Math.round(r/count); g=Math.round(g/count); b=Math.round(b/count);

  const tone = classifyTone(r,g,b);
  statusBox.innerText = "Detected tone: " + tone + " Redirecting...";
  setTimeout(()=> window.location.href = "recommendations.php?tone=" + tone, 700);
}
