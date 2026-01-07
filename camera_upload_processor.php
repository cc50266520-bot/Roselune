<?php
header("Content-Type: application/json; charset=UTF-8");

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["image"]) || empty($data["image"])) {
  echo json_encode(["success"=>false,"message"=>"No image received"]);
  exit;
}

$imageData = $data["image"];

if (!preg_match('/^data:image\/(png|jpe?g|webp);base64,/', $imageData, $m)) {
  echo json_encode(["success"=>false,"message"=>"Invalid image format"]);
  exit;
}

$ext = $m[1];
if ($ext === "jpeg") $ext = "jpg";

$imageData = preg_replace('/^data:image\/(png|jpe?g|webp);base64,/', '', $imageData);
$imageData = str_replace(' ', '+', $imageData);

$binary = base64_decode($imageData);
if ($binary === false) {
  echo json_encode(["success"=>false,"message"=>"Decode failed"]);
  exit;
}

if (!is_dir("uploads")) mkdir("uploads", 0755, true);
$filename = "uploads/" . uniqid("img_", true) . "." . $ext;
file_put_contents($filename, $binary);


if ($ext === "png") $img = @imagecreatefrompng($filename);
elseif ($ext === "webp") $img = @imagecreatefromwebp($filename);
else $img = @imagecreatefromjpeg($filename);

if (!$img) {
  echo json_encode(["success"=>false,"message"=>"Could not read image"]);
  exit;
}

$w = imagesx($img);
$h = imagesy($img);


$step = 10;
$values = [];

for ($x=0; $x<$w; $x+=$step) {
  for ($y=0; $y<$h; $y+=$step) {
    $rgb = imagecolorat($img, $x, $y);
    $r = ($rgb >> 16) & 0xFF;
    $g = ($rgb >> 8) & 0xFF;
    $b = $rgb & 0xFF;

  
    $Y  = 0.299*$r + 0.587*$g + 0.114*$b;
    $Cb = 128 - 0.168736*$r - 0.331264*$g + 0.5*$b;
    $Cr = 128 + 0.5*$r - 0.418688*$g - 0.081312*$b;


    if ($Cb >= 77 && $Cb <= 127 && $Cr >= 133 && $Cr <= 173) {
      $values[] = $Y; 
    }
  }
}

imagedestroy($img);

if (count($values) < 30) {
  
  $shade = "medium";
} else {
  sort($values);
  $mid = (int)(count($values)/2);
  $medianY = $values[$mid];


  if ($medianY < 90) $shade = "dark";
  elseif ($medianY < 155) $shade = "medium";
  else $shade = "light";
}

echo json_encode([
  "success" => true,
  "shade" => $shade,
  "file" => $filename
]);
