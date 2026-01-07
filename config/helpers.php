<?php
function e($s){
  return htmlspecialchars($s ?? "", ENT_QUOTES, "UTF-8");
}
define("BASE_URL", "/HasnaaChakik_51831003/");

function url($path=""){
  return BASE_URL . ltrim($path, "/");
}


function navbar($active=""){
  $logged = isset($_SESSION["user_id"]);
  $btn = $logged
    ? '<a class="btn primary" href="'.url('auth/logout.php').'">Logout</a>'
    : '<a class="btn primary" href="'.url('auth/login.php').'">Login</a>';

  echo '
  <header class="main-header">
    <div class="logo"><a href="'.url('index.php').'" style="color:inherit;text-decoration:none;">Roselune</a></div>
    <nav>
      <a class="'.($active=="home"?"active":"").'" href="'.url('index.php').'">Home</a>
      <a class="'.($active=="products"?"active":"").'" href="'.url('products.php').'">Products</a>
      <a class="'.($active=="about"?"active":"").'" href="'.url('about.php').'">About</a>
      <a class="'.($active=="contact"?"active":"").'" href="'.url('contact.php').'">Contact</a>
      '.$btn.'
    </nav>
  </header>';
}
