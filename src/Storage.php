<?php

namespace Devscreencast\S3Wrapper;

use Aws\Exception\AwsException;
use Aws\Sdk;
use Exception;

class Storage
{
    /** @var array $requiredS3ParamsKeys */
    protected $requiredS3ParamsKeys = ['region', 'version', 'bucket'];

    /** @var array $s3Params */
    private $s3Params;

    /** @var \Aws\S3\S3Client $client */
    private $client;

    /**
     * Storage constructor.
     * @param array $s3Params
     * @throws Exception
     */
    public function __construct(array $s3Params)
    {
        if (!($this->requiredS3ParamsKeys == array_keys($s3Params))) {
            throw new Exception('config [] should contain the following keys ' . implode(',', $this->requiredS3ParamsKeys));
        }

        $this->s3Params = $s3Params;

        $this->client = $this->createS3Client([
                'region' => $this->s3Params['region'],
                'version' => $this->s3Params['version']
            ]
        );
    }

    public function createS3Client(array $params)
    {
        $sdk = new Sdk($params);
        return $sdk->createS3();
    }

    /**
     * @param $bucketName
     * @return \Aws\Result
     * @throws \Exception
     */
    public function createBucket($bucketName)
    {

        if (!$bucketName) {
            throw new Exception('Bucket name is required');
        }
        try {
            return $this->client->createBucket([
                'Bucket' => $bucketName
            ]);
        } catch (AwsException $ex) {
            throw new Exception($ex->getMessage());
        }
    }

    /**
     * Move file to s3 storage
     *
     * @param $key
     * @param null $destinationFolder
     * @param null $filename
     * @param array $options refer to https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#putobject
     * @return null|string
     * @throws Exception
     */
    public function store($key, $destinationFolder = null, $filename = null, $options = [])
    {
        if ($filename === null) {
            $filename = md5(microtime());
            $fileExt = FileUploadHelper::guessUploadFileExtension();
            $filename = $filename . '.' . $fileExt;
        }
        $destinationFolder === null ? $path = $filename : $path = $destinationFolder . $filename;
        $params = [
            'Bucket' => $this->s3Params['bucket'],
            'Key' => $path,
            'SourceFile' => FileUploadHelper::getFileSource($key),
            'StorageClass' => 'STANDARD',
            'CacheControl' => 'max-age=86400',
            'ACL' => 'public-read'
        ];

        try {
            $this->client->putObject(array_merge($params, $options));
        } catch (AwsException $ex) {
            throw new Exception($ex->getMessage());
        }

        return $path;
    }

    /**
     * Get items in a bucket
     *
     * @param null $bucketName
     * @param string $folder
     * @return \Iterator
     * @throws \Exception
     */
    public function getBucketContents($bucketName = null, $folder = '')
    {
        if ($bucketName === null) {
            $bucket = $this->s3Params['bucket'];
            if (!$bucket) {
                throw new Exception('Bucket name is required');
            }
        } else {
            $bucket = $bucketName;
        }
        try {
            return $this->client->getIterator('ListObjects', [
                'Bucket' => $bucket,
                'Prefix' => $folder
            ]);
        } catch (AwsException $ex) {
            throw new Exception($ex->getMessage());
        }
    }

    /**
     * Get a list of buckets
     * @return mixed
     */
    public function getBuckets()
    {
        return $this->client->listBuckets()['Buckets'];
    }

    /**
     * Get the url for the specified object
     *
     * @param $key
     * @param null $bucket_name
     * @return string
     * @throws \Exception
     */
    public function getUrl($key, $bucket_name = null)
    {
        if ($bucket_name === null) {
            $bucket = $this->s3Params['bucket'];
            if (!$bucket) {
                throw new Exception('Bucket name is required');
            }
        } else {
            $bucket = $bucket_name;
        }

        try {
            return $this->client->getObjectUrl($bucket, $key);
        } catch (AwsException $ex) {
            throw new Exception($ex->getMessage());
        }

    }

    /**
     * Get the specified object
     *
     * @param $key
     * @param null $bucket_name
     * @return \Aws\Result
     * @throws \Exception
     */
    public function getOneObject($key, $bucket_name = null)
    {
        if ($bucket_name === null) {
            $bucket = $this->s3Params['bucket'];
            if (!$bucket) {
                throw new Exception('Bucket name is required');
            }
        } else {
            $bucket = $bucket_name;
        }
        try {
            return $this->client->getObject([
                'Bucket' => $bucket,
                'Key' => $key
            ]);
        } catch (AwsException $ex) {
            throw new Exception($ex->getMessage());
        }
    }

    /**
     * @param $object_key
     * @return string
     */
    public function getObjectName($object_key)
    {
        $keys = explode('/', $object_key);
        return $keys[1] !== "" ? basename($object_key) : "";
    }

    /**
     * Delete the specified resource or object
     *
     * @param $key
     * @param null $bucket_name
     * @return bool
     * @throws \Exception
     */
    public function delete($key, $bucket_name = null)
    {
        if ($bucket_name === null) {
            $bucket = $this->s3Params['bucket'];
            if (!$bucket) {
                throw new Exception('Bucket name is required');
            }
        } else {
            $bucket = $bucket_name;
        }

        if (!$key) {
            throw new Exception('Missing required Key');
        }

        try {
            $this->client->deleteObject([
                'Bucket' => $bucket,
                'Key' => $key
            ]);
        } catch (AwsException $ex) {
            throw new Exception($ex->getMessage());
        }

        return true;
    }
}