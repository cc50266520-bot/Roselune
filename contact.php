<?php
require_once("config/helpers.php");
require_once("config/auth.php");
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Contact Us – Rosélune</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <style>
    .contact-hero {
      background: linear-gradient(135deg, #f7d4e8, #ffe6f2);
      padding: 80px 20px;
      text-align: center;
    }
    .contact-hero h1 {
      font-size: 60px;
      font-family: "Georgia", serif;
      margin: 0;
      color: #b03a6b;
      letter-spacing: 2px;
    }
    .contact-hero p {
      font-size: 18px;
      margin-top: 10px;
      color: #555;
    }
    .contact-box {
      background: #fff;
      border-radius: 20px;
      padding: 40px;
      max-width: 900px;
      margin: -60px auto 40px auto;
      box-shadow: 0 15px 40px rgba(0,0,0,0.12);
    }
    .contact-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
      gap: 25px;
      margin-top: 20px;
    }
    .contact-card {
      background: #faf6f7;
      border-radius: 18px;
      padding: 25px;
      text-align: center;
    }
    .contact-card h3 {
      margin: 10px 0;
      color: #b03a6b;
    }
    .contact-card p {
      margin: 0;
      color: #555;
      font-size: 15px;
    }
    .contact-footer {
      text-align: center;
      margin-top: 40px;
      color: #777;
      font-size: 14px;
    }
  </style>
</head>
<body>

<?php navbar("contact"); ?>

<section class="contact-hero">
  <h1>Rosélune</h1>
  <p>Luxury Beauty • Smart Technology • Personal Touch</p>
</section>


<div class="contact-box">
  <h2 style="text-align:center;margin-top:0;">Get in Touch</h2>
  <p class="muted" style="text-align:center;max-width:600px;margin:10px auto;">
    We’d love to hear from you. Whether you have a question about our products,
    AI recommendation feature, or future expansions — Rosélune is always here for you.
  </p>

  <div class="contact-grid">
    <div class="contact-card">
      <h3>👩‍💻 Founder</h3>
      <p><b>Hasnaa Chakik</b></p>
      <p>Computer Science Student</p>
      <p>Lebanese International University</p>
    </div>

    <div class="contact-card">
      <h3>📧 Email</h3>
      <p>
        <a href="mailto:51831003@students.liu.edu.lb" style="color:#b03a6b;text-decoration:none;">
          51831003@students.liu.edu.lb
        </a>
      </p>
      <p>We reply within 24 hours</p>
    </div>

    <div class="contact-card">
      <h3>📍 Location</h3>
      <p>Lebanon</p>
      <p>Tripoli – Qalamoun</p>
      <p>Middle East</p>
    </div>
  </div>

  <div style="margin-top:40px;text-align:center;">
    <a href="<?= url('camera_upload.php') ?>" class="btn primary">
      Try Our AI Recommendation
    </a>
  </div>
</div>

<div class="contact-footer">
  © <?= date("Y") ?> Rosélune • Beauty powered by technology ✨
</div>

</body>
</html>
