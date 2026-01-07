<?php
require_once("config/helpers.php");
require_once("config/auth.php");
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>About - Rosélune</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<?php navbar("about"); ?>

<div class="container">

  <div class="section-card" style="background:#fff;">
    <h1 style="margin:0;font-family:Georgia,serif;font-size:52px;color:var(--brand);">
      About Rosélune
    </h1>
    <p class="muted" style="margin:10px 0 0 0;font-size:16px;max-width:900px;">
      Rosélune is a modern beauty store that blends elegance, quality makeup, and smart technology — built to help
      every customer find the perfect makeup match with confidence.
    </p>
  </div>

  <div class="section-card" style="background:#fff;margin-top:18px;">
    <div style="display:grid;grid-template-columns:1.2fr 1fr;gap:18px;align-items:start;">
      <div>
        <h2 style="margin:0 0 8px 0;">Our Story</h2>
        <p class="muted" style="line-height:1.8;margin:0;">
          Founded in <b>Tripoli, Lebanon</b>, Rosélune began as a passion project created by
          <b>Hasnaa Chakik</b>, a <b>Computer Science</b> student at the <b>Lebanese International University</b>.
          Inspired by the beauty industry and the power of technology, Roselune was built to offer a smooth, modern
          shopping experience — with a unique AI-driven feature that helps customers choose products based on their look.
        </p>

        <p class="muted" style="line-height:1.8;margin:12px 0 0 0;">
          Today, Rosélune continues to grow, with a new expansion in <b>Qatar</b> — becoming a trending brand thanks to
          its smart recommendation system and clean, premium product selection.
        </p>

        <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:16px;">
          <a class="btn primary" href="<?= url('camera_upload.php') ?>">Try AI Recommendation</a>
          <a class="small-btn" href="<?= url('products.php') ?>">Explore Products</a>
        </div>
      </div>

      <div class="card" style="padding:18px;">
        <h3 style="margin:0 0 10px 0;">Quick Facts</h3>
        <div class="muted" style="display:grid;gap:10px;">
          <div><b>Origin:</b> Tripoli, Lebanon 🇱🇧</div>
          <div><b>Expansion:</b> Qatar 🇶🇦</div>
          <div><b>Specialty:</b> Makeup + Smart Recommendation</div>
          <div><b>Mission:</b> Personalized beauty for everyone</div>
        </div>
      </div>
    </div>
  </div>

  <div class="section-card" style="background:#fff;margin-top:18px;">
    <h2 style="margin:0 0 8px 0;">Why Roselune is Trending</h2>
    <p class="muted" style="line-height:1.8;margin:0;max-width:980px;">
      What makes Roselune different is the built-in <b>AI feature</b> that helps customers discover a suitable
      makeup style by using a photo upload or a camera capture. The system suggests products that match the customer’s
      look — making shopping faster, easier, and more personalized.
    </p>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:16px;margin-top:14px;">
      <div class="card">
        <h3 style="margin-top:0;">📸 Photo-Based Matching</h3>
        <p class="muted">Upload or take a photo and get makeup recommendations in seconds.</p>
      </div>
      <div class="card">
        <h3 style="margin-top:0;">🛍️ Smooth Shopping</h3>
        <p class="muted">Browse products, add to cart, checkout, and place orders easily.</p>
      </div>
      <div class="card">
        <h3 style="margin-top:0;">🧑‍💼 Admin Management</h3>
        <p class="muted">Admins can add/edit/delete products and track orders from the dashboard.</p>
      </div>
    </div>
  </div>

  <div class="section-card" style="background:#fff;margin-top:18px;">
    <h2 style="margin:0 0 8px 0;">Our Vision</h2>
    <p class="muted" style="line-height:1.8;margin:0;max-width:980px;">
      Our goal is to become the #1 beauty destination in the region by combining premium products with smart technology.
      Roselune is built to scale — and to keep improving the AI recommendation feature with better accuracy and more styles.
    </p>
  </div>

  <div class="section-card" style="background:#fff;margin-top:18px;text-align:center;">
    <h2 style="margin:0 0 8px 0;">Ready to Find Your Match?</h2>
    <p class="muted" style="margin:0 0 14px 0;">Try the AI feature and get your recommended makeup style now.</p>
    <a class="btn primary" href="<?= url('camera_upload.php') ?>">Start Recommendation</a>
  </div>

</div>

</body>
</html>
