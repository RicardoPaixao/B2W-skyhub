<?php
require 'vendor/autoload.php'; // include Composer's autoloader
require_once 'common.php';

//used the GD library to resize an image.
//TODO check possible missbehavior if the source and target reolutions have different width-to-height ratios
function resize_image($file, $w, $h) {
    list($width, $height) = getimagesize($file);
    $src = imagecreatefromjpeg($file);
    $dst = imagecreatetruecolor($w, $h);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $w, $h, $width, $height);
    return $dst;
}

//constains all resolutions and resolution names, as per the challenge description.
//storing this data this way uses less code further down, and we can easly add or remove resolutions
$sizes=array(
    "small" =>array("width"=>320, "height"=>240),
    "medium"=>array("width"=>384, "height"=>288),
    "large" =>array("width"=>640, "height"=>480));

$client = new MongoDB\Client("mongodb://localhost:27017");
$collection = $client->skyhub->files;

$url="http://54.152.221.29/images.json";
$response = doCurlRequest($url, "GET");
$response = json_decode($response, true);//here we already have the 10 URLs

foreach ($response["images"] as $key => $value) {
    $url = $value["url"];
    $localUrl = "images/".substr($url, strrpos($url, '/') + 1);
    if(file_exists ($localUrl))continue; // this file if already present, we shoud skip any further handling
    $ext = substr($localUrl, strrpos($localUrl, '.')); //gets the file extension
    $localUrl = substr($localUrl, 0, strrpos($localUrl, '.')); //removes file extension from string, it simplifies the process of creating the resized files
    file_put_contents($localUrl.$ext, fopen($url, 'r'));//save file to HD. we will keep a copy fo the originals.

    $mongoData=['original'=> $localUrl.$ext];//setup for mongoDB isertion query
    foreach ($sizes as $sizeName => $size) {//here the $sizes array comes in handy
        $image_p = resize_image($localUrl.$ext, $size["width"], $size["height"]);//resizes the image, but doesnt save it yet
        $resizedFilename = $localUrl."_".$size["width"]."_".$size["height"].$ext;//our rezized file will have the oldName_width_height.ext format
        imagejpeg($image_p, $resizedFilename, 100);//saves the resized file with new filename
        $mongoData[$sizeName] = $resizedFilename;//partially the mongoDB query
    }
     $dbResponse = $collection->insertOne($mongoData);//inserts all urls to database.
}

echo "dados consumidos, aquivos criados, e banco atualizado com sucesso";