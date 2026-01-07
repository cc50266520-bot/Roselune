<?php
session_start();
require_once("config/helpers.php");
require_once("config/auth.php");
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>AI Recommendation – Rosélune</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <style>
    body{background:linear-gradient(135deg,#f7d4e8,#ffe6f2);}
    .wrap{max-width:1100px;margin:26px auto 60px auto;padding:0 16px;}
    .top{
      background:#fff;border-radius:22px;padding:22px;
      box-shadow:0 18px 40px rgba(0,0,0,.08);
      display:flex;justify-content:space-between;gap:14px;align-items:center;flex-wrap:wrap;
    }
    .top h1{margin:0;font-family:Georgia,serif;color:#b03a6b;letter-spacing:2px;font-size:34px;}
    .top p{margin:8px 0 0;color:#666;line-height:1.7;max-width:740px;}
    .pill{
      display:inline-block;padding:10px 14px;border-radius:999px;
      border:1px solid #eadfe3;background:#fff;color:#7a2a4a;font-weight:900;
    }

    .grid{margin-top:18px;display:grid;grid-template-columns:1.1fr .9fr;gap:18px;}
    @media(max-width:900px){.grid{grid-template-columns:1fr;}}
    .card{
      background:#fff;border-radius:22px;padding:18px;
      box-shadow:0 18px 40px rgba(0,0,0,.08);
    }

    .btnRow{display:flex;gap:10px;flex-wrap:wrap;margin-top:12px;}
    .btn{
      padding:12px 14px;border-radius:14px;border:1px solid #eadfe3;
      font-weight:900;cursor:pointer;background:#fff;color:#7a2a4a;
    }
    .btn.primary{
      border:none;background:#ff5da2;color:#fff;
      box-shadow:0 14px 26px rgba(255,93,162,.30);
    }
    .btn:disabled{opacity:.55;cursor:not-allowed;}
    .uploadLabel{display:inline-flex;align-items:center;gap:10px;}

    .preview{
      width:100%;max-height:420px;object-fit:cover;border-radius:18px;
      border:1px solid #eadfe3;background:#faf6f7;margin-top:12px;
    }
    video{width:100%;border-radius:18px;border:1px solid #eadfe3;margin-top:12px;display:none;}
    .muted{color:#666;line-height:1.7;}
    .status{margin-top:12px;font-weight:900;}
    .status.ok{color:#0b7a3e;}
    .status.bad{color:#b00020;}
  </style>
</head>
<body>

<?php navbar("home"); ?>

<div class="wrap">
  <div class="top">
    <div>
      <h1>Rosélune AI Recommendation</h1>
      <p>Upload a face photo or take one using your camera — we detect your skin tone and redirect you to the best matching products automatically.</p>
    </div>
    <span class="pill">AI Match ✨</span>
  </div>

  <div class="grid">
  
    <div class="card">
      <h2 style="margin:0 0 8px 0;">Capture or Upload</h2>
      <p class="muted" style="margin:0;">Choose an option, then click <b>Continue</b>.</p>

      <div class="btnRow">
        <button class="btn primary" id="openCamBtn" type="button">Open Camera</button>
        <button class="btn" id="captureBtn" type="button" disabled>Capture</button>

        <label class="btn uploadLabel">
          Upload Photo
          <input type="file" id="fileInput" accept="image/*" style="display:none;">
        </label>

        <button class="btn primary" id="continueBtn" type="button" disabled>Continue</button>
      </div>

      <video id="video" autoplay playsinline></video>
      <canvas id="canvas" width="720" height="540" style="display:none;"></canvas>
      <img id="preview" class="preview" alt="Preview">

      <div id="status" class="status muted">Waiting for photo…</div>
    </div>

   
    <div class="card">
      <h2 style="margin:0 0 8px 0;">How it works</h2>
      <ol class="muted" style="margin:0;padding-left:18px;">
        <li>Upload or capture a clear face photo.</li>
        <li>System detects skin tone (light / medium / dark).</li>
        <li>You are redirected to matching makeup products.</li>
      </ol>

      <div style="margin-top:14px;padding:14px;border-radius:18px;border:1px solid #eadfe3;background:#fff;">
        <div style="font-weight:900;color:#2b0f1d;">Tips</div>
        <ul class="muted" style="margin:10px 0 0 18px;">
          <li>Good light, face centered.</li>
          <li>Avoid strong flash or heavy filters.</li>
        </ul>
      </div>
    </div>
  </div>
</div>

<script>
  const DETECT_URL = "camera_upload_processor.php";

  const openCamBtn  = document.getElementById("openCamBtn");
  const captureBtn  = document.getElementById("captureBtn");
  const continueBtn = document.getElementById("continueBtn");
  const fileInput   = document.getElementById("fileInput");

  const video   = document.getElementById("video");
  const canvas  = document.getElementById("canvas");
  const preview = document.getElementById("preview");
  const status  = document.getElementById("status");

  let stream = null;
  let imageBase64 = "";

  function setStatus(msg, ok=true){
    status.textContent = msg;
    status.className = "status " + (ok ? "ok" : "bad");
  }

  openCamBtn.addEventListener("click", async () => {
    try{
      stream = await navigator.mediaDevices.getUserMedia({ video:true });
      video.srcObject = stream;
      video.style.display = "block";
      captureBtn.disabled = false;
      setStatus("Camera opened. Click Capture.");
    } catch(e){
      setStatus("Camera permission denied or not available.", false);
    }
  });

  
  captureBtn.addEventListener("click", () => {
    if(!stream){
      setStatus("Camera not opened.", false);
      return;
    }
    const ctx = canvas.getContext("2d");
    canvas.width  = video.videoWidth || 720;
    canvas.height = video.videoHeight || 540;
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

   
    imageBase64 = canvas.toDataURL("image/jpeg", 0.9);
    preview.src = imageBase64;

    continueBtn.disabled = false;
    setStatus("Captured ✅ Click Continue.");
  });


  fileInput.addEventListener("change", () => {
    const file = fileInput.files[0];
    if(!file) return;

    const reader = new FileReader();
    reader.onload = () => {
      imageBase64 = reader.result;
      preview.src = imageBase64;
      continueBtn.disabled = false;
      setStatus("Uploaded ✅ Click Continue.");
    };
    reader.readAsDataURL(file);
  });

  
  continueBtn.addEventListener("click", async () => {
    if(!imageBase64){
      setStatus("Please upload or capture a photo.", false);
      return;
    }

    setStatus("Detecting skin tone…");

    try{
      const res = await fetch(DETECT_URL, {
        method:"POST",
        headers:{"Content-Type":"application/json"},
        body: JSON.stringify({ image: imageBase64 })
      });

      const data = await res.json();

      if(!data.success){
        setStatus(data.message || "Detection failed.", false);
        return;
      }

      const shade = (data.shade || "medium").toLowerCase();
      setStatus("Detected: " + shade.toUpperCase() + " ✅ Redirecting…");

      window.location.href = `recommendations_bundle.php?skin_tone=${encodeURIComponent(shade)}`;


    } catch(err){
      setStatus("Fetch error. Check DETECT_URL and server.", false);
    }
  });
</script>

</body>
</html>
