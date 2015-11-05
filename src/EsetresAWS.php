<?php

/**
 * @author    Nicolás Bistolfi <nbistolfi@gmail.com>
 * @license   MIT
 * @copyright 2015, Nicolás Bistolfi
 *
 * @link      http://github.com/dumpk/esetres
 */
namespace Dumpk\Esetres;

use Aws\S3\S3Client;

class EsetresAWS
{
    protected static $instance = null;
    protected static $s3c = null;

    protected static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    protected function __construct()
    {
        //
    }

    protected function __clone()
    {
        //
    }

    protected static function _getConfig()
    {
        $config = array(
            'version' => 'latest',
            'key' => env('AWS_ACCESS_KEY_ID', ''),
            'secret' => env('AWS_SECRET_ACCESS_KEY', ''),
            'region' => env('AWS_REGION', ''),
            'timeout' => 3,
        );

        return $config;
    }

    public static function getS3C()
    {
        if (!isset(self::$s3c)) {
            self::$s3c = new S3Client(self::_getConfig());
        }

        return self::$s3c;
    }

    public static function uploadFile($localPath, $key, $bucket, $acl = 'public-read', $metadata = array(), $cache = 'max-age=3600', $extraOptions = array())
    {
        $s3c = self::getS3C();
        $objectOptions = [
            'ACL' => $acl,
            'Bucket' => $bucket,
            'Key' => $key,
            'CacheControl' => $cache,
            'SourceFile' => $localPath,
        ];
        $result = $s3c->putObject(array_merge($objectOptions, $extraOptions));

        return $result;
    }

    public static function getObject($key, $bucket)
    {
        $s3c = self::getS3C();
        $object = null;
        if ($s3c->doesObjectExist($bucket, $key)) {
            $object = $s3c->getObject(array('Bucket' => $bucket, 'Key' => $key));
        }

        return $object;
    }

    public static function deleteObject($key, $bucket)
    {
        $s3c = self::getS3C();
        $params = [
            'Bucket' => $bucket,
            'Key' => $key,
        ];
        if ($s3c->deleteObject($params)) {
            return true;
        } else {
            return false;
        }
    }

    public static function objectExists($key, $bucket)
    {
        $s3c = self::getS3C();
        if ($s3c->doesObjectExist($bucket, $key)) {
            return true;
        } else {
            return false;
        }
    }

    public static function setPublicObject($key, $bucket)
    {
        return self::setObjectACL($key, $bucket);
    }

    /**
     * setObjectACL
     * Change the access level of an object on S3.
     *
     * @param $key
     * @param $bucket
     * @param $acl (private|public-read|public-read-write|authenticated-read|bucket-owner-read|bucket-owner-full-control)
     */
    public static function setObjectACL($key, $bucket, $acl = 'public-read')
    {
        $s3c = self::getS3C();
        if ($s3c->doesObjectExist($bucket, $key)) {
            $result = $s3c->putObjectAcl(array(
                'ACL' => $acl,
                'Bucket' => $bucket,
                'Key' => $key,
            ));
            if ($result) {
                return true;
            }
        }

        return false;
    }
}
