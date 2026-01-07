<?php
header('Content-Type: application/json; charset=utf-8');

$body = file_get_contents('php://input');
if(!$body){
    echo json_encode(['success'=>false,'message'=>'No input']);
    exit;
}
$data = json_decode($body, true);
if(empty($data['imageBase64'])){
    echo json_encode(['success'=>false,'message'=>'No image data']);
    exit;
}
$img = $data['imageBase64'];
if(preg_match('/^data:image\/(png|jpeg|jpg);base64,/', $img, $m)){
    $ext = ($m[1] === 'png') ? 'png' : 'jpg';
    $img = preg_replace('/^data:image\/\w+;base64,/', '', $img);
    $img = str_replace(' ', '+', $img);
    $decoded = base64_decode($img);
    if($decoded === false){
        echo json_encode(['success'=>false,'message'=>'Invalid base64']);
        exit;
    }
    $uploadsDir = __DIR__ . '/assets/uploads/';
    if(!is_dir($uploadsDir)) mkdir($uploadsDir, 0777, true);
    $filename = 'photo_' . time() . '.' . $ext;
    $fullpath = $uploadsDir . $filename;
    file_put_contents($fullpath, $decoded);
    $webpath = 'assets/uploads/' . $filename;
    echo json_encode(['success'=>true,'path'=>$webpath]);
    exit;
} else {
    echo json_encode(['success'=>false,'message'=>'Invalid image format']);
    exit;
}
