<?php


 /**
  * @author    Nicolás Bistolfi <nbistolfi@gmail.com>
  * @license   MIT
  * @copyright 2015, Nicolás Bistolfi
  *
  * @link      http://github.com/dumpk/esetres
  */
 use Dumpk\Esetres\EsetresAWS;

 class ElastcoderTest extends PHPUnit_Framework_TestCase
 {
     public function tearDown()
     {
     }

     public function testConnection()
     {
         $destination_key = getenv('KEY');

         if (EsetresAWS::objectExists($destination_key, getenv('BUCKET'))) {
             EsetresAWS::deleteObject($destination_key, getenv('BUCKET'));
         }
         $uploadResult = EsetresAWS::uploadFile(getenv('LOCAL_FILE'), getenv('KEY'), getenv('BUCKET'));

         $this->assertTrue(isset($uploadResult['ObjectURL']), 'Upload file object URL result');
         $objectURL = $uploadResult['ObjectURL'];

         $object = EsetresAWS::getObject($destination_key, getenv('BUCKET'));

         EsetresAWS::setPublicObject($destination_key, getenv('BUCKET'));

         $this->assertTrue(is_string(file_get_contents($object['@metadata']['effectiveUri'])));
         echo $object['@metadata']['effectiveUri'];

         EsetresAWS::deleteObject($destination_key, getenv('BUCKET'));
     }
 }
