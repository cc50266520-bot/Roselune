<?php
require_once("config/helpers.php");
require_once("config/auth.php");
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Rosélune – Smart Beauty Experience</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <style>
    .home-hero{
      min-height: 85vh;
      background:
        linear-gradient(rgba(0,0,0,.45), rgba(0,0,0,.55)),
        url("<?= url('assets/images/hero.jpg') ?>") center/cover no-repeat;
      display:flex;
      align-items:center;
      justify-content:center;
      text-align:center;
      color:#fff;
      padding: 40px 20px;
    }
    .home-hero h1{
      font-family: Georgia, serif;
      font-size: 72px;
      margin: 0;
      letter-spacing: 3px;
    }
    .home-hero h1 span{
      color:#ffb6d5;
    }
    .home-hero p{
      font-size: 20px;
      max-width: 820px;
      margin: 18px auto;
      line-height:1.7;
      opacity:.95;
    }
    .home-actions{
      margin-top: 26px;
      display:flex;
      gap:14px;
      justify-content:center;
      flex-wrap:wrap;
    }
    .home-actions a{
      padding:14px 26px;
      border-radius:999px;
      font-weight:900;
      font-size:15px;
      text-decoration:none;
    }
    .btn-main{
      background:#ff5da2;
      color:#fff;
      box-shadow:0 15px 30px rgba(255,93,162,.4);
    }
    .btn-outline{
      background:transparent;
      border:2px solid #fff;
      color:#fff;
    }

    .home-section{
      padding: 70px 16px;
      max-width: 1200px;
      margin: auto;
    }
    .features{
      display:grid;
      grid-template-columns: repeat(auto-fit, minmax(260px,1fr));
      gap:22px;
      margin-top:40px;
    }
    .feature-card{
      background:#fff;
      border-radius:22px;
      padding:30px;
      box-shadow:0 18px 40px rgba(0,0,0,.08);
      text-align:center;
    }
    .feature-card h3{
      margin:12px 0;
      color:#b03a6b;
    }
    .feature-card p{
      color:#555;
      line-height:1.6;
    }
  </style>
</head>
<body>

<?php navbar("home"); ?>

<section class="home-hero">
  <div>
    <h1>Ros<span>é</span>lune</h1>
    <p>
      A new generation beauty store where elegance meets technology.
      Discover makeup designed for <b>you</b> — powered by intelligent photo-based recommendations.
    </p>

    <div class="home-actions">
      <a href="<?= url('camera_upload.php') ?>" class="btn-main">
        Try AI Makeup Recommendation
      </a>
      <a href="<?= url('products.php') ?>" class="btn-outline">
        Explore Products
      </a>
    </div>
  </div>
</section>

<section class="home-section">
  <h2 style="text-align:center;margin:0;">Why Rosélune?</h2>
  <p class="muted" style="text-align:center;max-width:850px;margin:14px auto 0;">
    Rosélune is more than a beauty store. It’s a smart shopping experience that helps
    customers choose the right makeup style using modern technology.
  </p>

  <div class="features">
    <div class="feature-card">
      <div style="font-size:42px;">📸</div>
      <h3>Photo-Based AI</h3>
      <p>
        Upload or take a photo and instantly receive makeup recommendations
        tailored to your appearance.
      </p>
    </div>

    <div class="feature-card">
      <div style="font-size:42px;">💄</div>
      <h3>Premium Products</h3>
      <p>
        Carefully selected foundations, blushes, and cosmetics for every style and tone.
      </p>
    </div>

    <div class="feature-card">
      <div style="font-size:42px;">🌍</div>
      <h3>Growing Brand</h3>
      <p>
        Founded in Tripoli, Lebanon and expanding to Qatar — Rosélune is a trending
        beauty concept in the region.
      </p>
    </div>
  </div>
</section>

<section class="home-section" style="text-align:center;padding-top:0;">
  <h2>Find Your Perfect Match Today</h2>
  <p class="muted" style="max-width:700px;margin:12px auto;">
    Experience smart beauty shopping powered by technology.
    Start your personalized makeup journey now.
  </p>
  <a href="<?= url('camera_upload.php') ?>" class="btn-main" style="display:inline-block;margin-top:14px;">
    Start AI Recommendation
  </a>
</section>

</body>
</html>
