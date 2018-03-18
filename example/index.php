<?php
require_once __DIR__ .'/../vendor/autoload.php';
use Devscreencast\S3Wrapper\Storage;

$env = new \Dotenv\Dotenv(basePath());
$env->load();

try{
    /**
     * Create an instance of Devscreencast\S3Wrapper\Storage class
     * and pass in config array
     */
    $storage = new Storage([
       'region' => 'us-east-1',
       'version' => 'latest',
       'bucket' => 'learning-s3-bucket.com'
    ]);
    
    if(isset($_POST['submit'])){
        $name = $_FILES['file']['name'];
        if(!$name){
            $msg = 'Please select a file';
        }
        /**
         * @param folder (optional) if omitted file in placed inside bucket
         * @param filename (optional) if omitted a unique filename is generated
         */
       $path = $storage->store('avatars/', $name);
    }
    
    if(isset($_GET['delete'])){
        $item = $_GET['delete'];
        if(!$item){
            $msg = 'Item is required';
        }else{
            if($storage->delete($item)){
                $msg = $item . ' deleted successfully';
            }else{
                $msg = 'Item not found';
            }
        }
    }
    
    $bucket_contents = $storage->getBucketContents();
    
}catch (Exception $ex){
    die($ex->getMessage());
}

require_once __DIR__ .'/app.php';