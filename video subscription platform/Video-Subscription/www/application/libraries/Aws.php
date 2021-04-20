<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Coffee Theme
 *
 * PHP version >= 7.0.0
 *
 * @category  PHP
 * @package   VideoTube - PHP Script
 * @author    Nicolas Grimonpont <support@coffeetheme.com>
 * @copyright 2010-2019 Nicolas Grimonpont
 * @license   Standard License
 * @link      http://coffeetheme.com/
 */

// Include the autoloader provided in the SDK
require_once 'aws/aws-autoloader.php';
use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use Aws\S3\Exception\S3Exception;

class Aws
{
    public $bucket_name = '';
    public $region = '';
    public $version = '';
    public $scheme = '';
    public $access_key = '';
    public $secret_key = '';
    public $s3_url = '';    
    public $s3Client = null;

    public function __construct()
    {
        // Grab API key from config instead
        $this->ci = &get_instance();

        $this->bucket_name = $this->ci->config->item('amazonBucket');
        $this->region = 'us-east-1';
        $this->version = 'latest';
        $this->scheme = 'http';
        $this->access_key = $this->ci->config->item('amazonApiKey');
        $this->secret_key = $this->ci->config->item('amazonSecretKey');

        if (!isset($this->aws)){
            $this->s3Client = new S3Client([
                'version'     => 'latest',
                'region'      => $this->region,
                'credentials' => [
                    'key'    => $this->access_key,
                    'secret' => $this->secret_key,
                ],
            ]);
        }
    }

    /**
     * List buckets
     *
     * @return void
    */
    public function listBuckets()
    {
        return $this->s3Client->listBuckets();
    }

    /**
     * Delete S3 Object
     *
     * @access public
     */    	
    function delete_s3_object($file_path)
    {
        $response = $this->s3Client->deleteObject(array(
            'Bucket'     => $this->bucket_name,
            'Key'        => $file_path
        ));
        return true;
    }

    /**
     * Copy S3 Object
     *
     * @access public
     */ 
    function copy_s3_file($source,$destination)
    {
        $response = $this->s3Client->copyObject(array(
            'Bucket'     => $this->bucket_name,
            'Key'        => $destination,
            'CopySource' => "{$this->bucket_name}/{$source}",
        ));
        if($response['ObjectURL'])
        {
            return true;
        }
        return false;
    }

    /**
     * Create a new bucket in already specified region
     *
     * @access public
     */ 
    function create_bucket($bucket_name="", $region="")
    {
        $promise = $this->s3Client->createBucketAsync(['Bucket' => $bucket_name]);
        try {
            $result = $promise->wait();
            return true;
        } catch (Exception $e) {
            //echo "exception";exit;
            //echo $e->getMessage();
            return false;
        }		
    }

    /**
     * Create a presigned URL.     
     * @access public
     * Object key: Objecy key of S3 file.
     * Duration: duration of presigned URL in seconds, After that URL will not be accessible.
     */ 
    function get_presigned_url($object_key="",$duration="10")
    {
        $cmd = $this->s3Client->getCommand('GetObject', [
            'Bucket' => $this->bucket_name,
            'Key'    => $object_key
        ]);

        //Create presigned request
        $request = $this->s3Client->createPresignedRequest($cmd, "+$duration seconds");

        // Get the presigned url
        $presignedUrl = (string) $request->getUri();
        return $presignedUrl;
    }

    function getS3Details($acl = "private") {
        $algorithm = "AWS4-HMAC-SHA256";
        $service = "s3";
        $date = gmdate("Ymd\THis\Z");
        $shortDate = gmdate("Ymd");
        $requestType = "aws4_request";
        $expires = "86400"; // 24 Hours
        $successStatus = "201";
        $url = "//{$this->bucket_name}.s3.amazonaws.com";

        // Step 1: Generate the Scope
        $scope = [
            $this->access_key,
            $shortDate,
            $this->region,
            $service,
            $requestType
        ];
        $credentials = implode('/', $scope);

        // Step 2: Making a Base64 Policy
        $policy = [
            'expiration' => gmdate('Y-m-d\TG:i:s\Z', strtotime('+6 hours')),
            'conditions' => [
                ['bucket' => $this->bucket_name],
                ['acl' => $acl],
                ['starts-with', '$key', ''],
                ['starts-with', '$Content-Type', ''],
                ['success_action_status' => $successStatus],
                ['x-amz-credential' => $credentials],
                ['x-amz-algorithm' => $algorithm],
                ['x-amz-date' => $date],
                ['x-amz-expires' => $expires],
            ]
        ];
        $base64Policy = base64_encode(json_encode($policy));

        // Step 3: Signing your Request (Making a Signature)
        $dateKey = hash_hmac('sha256', $shortDate, 'AWS4' . $this->secret_key, true);
        $dateRegionKey = hash_hmac('sha256', $this->region, $dateKey, true);
        $dateRegionServiceKey = hash_hmac('sha256', $service, $dateRegionKey, true);
        $signingKey = hash_hmac('sha256', $requestType, $dateRegionServiceKey, true);

        $signature = hash_hmac('sha256', $base64Policy, $signingKey);

        // Step 4: Build form inputs
        // This is the data that will get sent with the form to S3
        $inputs = [
            'Content-Type' => '',
            'acl' => $acl,
            'success_action_status' => $successStatus,
            'policy' => $base64Policy,
            'X-amz-credential' => $credentials,
            'X-amz-algorithm' => $algorithm,
            'X-amz-date' => $date,
            'X-amz-expires' => $expires,
            'X-amz-signature' => $signature
        ];

        return compact('url', 'inputs');
    }
}
