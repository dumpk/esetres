<?php

 /**
  * @author    Nicolás Bistolfi <nbistolfi@gmail.com>
  * @license   MIT
  * @copyright 2015, Nicolás Bistolfi
  *
  * @link      http://github.com/dumpk/esetres
  */
 use Dumpk\Esetres\EsetresAWS;
 use PHPUnit\Framework\TestCase;

 class ElastcoderTest extends TestCase
 {
     public function tearDown()
     {
     }

     public function testConnection()
     {
         $destination_key = getenv('KEY');

         if (EsetresAWS::fileExists($destination_key, getenv('BUCKET'))) {
             EsetresAWS::deleteFile($destination_key, getenv('BUCKET'));
         }
         $uploadResult = EsetresAWS::uploadFile(getenv('LOCAL_FILE'), getenv('KEY'), getenv('BUCKET'));

         $this->assertTrue(isset($uploadResult['ObjectURL']), 'Upload file object URL result');
         $objectURL = $uploadResult['ObjectURL'];

         $object = EsetresAWS::getObject($destination_key, getenv('BUCKET'));

         EsetresAWS::makeFilePublic($destination_key, getenv('BUCKET'));

         $this->assertTrue(is_string(file_get_contents($object['@metadata']['effectiveUri'])));
         echo $object['@metadata']['effectiveUri'];

         $this->assertTrue(EsetresAWS::fileExists($destination_key, getenv('BUCKET')));

         EsetresAWS::deleteObject($destination_key, getenv('BUCKET'));
     }

     public function testEncription()
     {
         $destination_key = 'enc'.getenv('KEY');

         if (EsetresAWS::fileExists($destination_key, getenv('BUCKET'))) {
             EsetresAWS::deleteFile($destination_key, getenv('BUCKET'));
         }
         $encription_key = '1231affd2950a1ab06a21137f54bd648';
         $aditional_options =  [
            'SSECustomerAlgorithm'  => 'AES256',
            'SSECustomerKey'        => $encription_key,
            'SSECustomerKeyMD5'     => md5($encription_key, true),
         ];
         $uploadResult = EsetresAWS::uploadFile(
                            getenv('LOCAL_FILE'),
                            $destination_key,
                            getenv('BUCKET'),
                            'public-read',
                            array(),
                            'max-age=3600',
                            $aditional_options
        );

         $this->assertTrue(isset($uploadResult['ObjectURL']), 'Upload file object URL result');
         $objectURL = $uploadResult['ObjectURL'];

         $this->assertTrue(EsetresAWS::fileExists($destination_key, getenv('BUCKET'), $aditional_options));

         $object = EsetresAWS::getObject($destination_key, getenv('BUCKET'), null, $aditional_options);

         EsetresAWS::deleteObject($destination_key, getenv('BUCKET'));
     }
 }
