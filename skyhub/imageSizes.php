<?php
require 'vendor/autoload.php'; // include Composer's autoloader

//used to output data, formated as JSON
function responseJson($jsonData) {
    header("Content-type: application/json; charset=utf-8", true);
    echo json_encode($jsonData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ); //JSON_UNESCAPED_SLASHES é usado para manter o formato das URLs
    exit;
}

$protocolo = strtolower(preg_replace('/[^a-zA-Z]/','',$_SERVER['SERVER_PROTOCOL'])); //gets our protocol, chances are its http 
$rootPath = $protocolo.'://'.$_SERVER['HTTP_HOST']."/"; //gets server identificator, can be either. site alias, ip or 'localhost'
$path = $rootPath."skyhub/"; // remove 'skyhub/' if planning code directly on root 

$client = new MongoDB\Client("mongodb://localhost:27017");
$collection = $client->skyhub->files;

if($collection->count()==0){ //we have not yet consumed the webService data, lets do it now
    require 'consume.php';
}
$result = $collection->find();

$list["images"]=[];
foreach ($result as $entry) { //build an array structure for data to be output
    array_push($list["images"], ["original"=>$path.$entry['original'],
        "small"=>$path.$entry['small'],
        "medium"=>$path.$entry['medium'],
        "large"=>$path.$entry['large']]);
}

responseJson($list);