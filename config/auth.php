<?php
if(session_status()===PHP_SESSION_NONE) session_start();

function require_login(){
  if(empty($_SESSION["user_id"])){
    header("Location: /HasnaaChakik_51831003/auth/login.php");
    exit();
  }
}

function require_admin(){
  require_login();
  if(!isset($_SESSION["is_admin"]) || (int)$_SESSION["is_admin"] !== 1){
    die("❌ Access denied. You are not admin.");
  }
}
