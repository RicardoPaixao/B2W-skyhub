<?php
require 'vendor/autoload.php'; // include Composer's autoloader
require_once 'common.php';

class tester {
   public function testWebServerJSONPayload(){
      $imageSizes="http://localhost/skyhub/imageSizes.php";
      $response = doCurlRequest($imageSizes, "GET");
      $response = json_decode($response, true);
      foreach ($response["images"] as $foto) {
            foreach ($foto as $key => $url) {
               $filename = "images/".substr($url, strrpos($url, '/') + 1); //extrai o nome do arquivo da url e verifica se ele consta no servidor.
               assert(file_exists ($filename));
               list($width, $height) = getimagesize($url);
               switch ($key) {
                  case 'small':
                     assert($width==320);
                     assert($height==240);
                     break;               
                  case 'medium':
                     assert($width==384);
                  assert($height==288);
                     break;            
                  case 'large':
                     assert($width==640);
                     assert($height==480);
                     break;
                  default:
                     break;
               }
            }
      }
   }

   public function testDBEntrySize() {
      $client = new MongoDB\Client("mongodb://localhost:27017");
      $collection = $client->skyhub->files;
      assert($collection->count()==10);
   }
}

$tester = new tester();
$tester->testWebServerJSONPayload();
$tester->testDBEntrySize();
echo "Fim da bateria de testes";