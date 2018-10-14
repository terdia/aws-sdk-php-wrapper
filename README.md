# aws-sdk-php-wrapper
A simple wrapper over aws php sdk to perform basic file management task

## Installation
composer require devscreencast/aws-sdk-php-wrapper

## Basic Usage

### Environment Variables
#### Create a .env file with the following:
AWS_ACCESS_KEY_ID=your_key
AWS_SECRET_ACCESS_KEY=your_secret

```php
<?php
require_once 'vendor/autoload.php';
use Devscreencast\S3Wrapper\Storage;

#load environment variables
$env = new \Dotenv\Dotenv('path_to/.env');
$env->load();

try{
    /**
     * Create an instance of Devscreencast\S3Wrapper\Storage class
     * and pass in config array
     */
    $storage = new Storage([
       'region' => 'us-east-1',
       'version' => 'latest',
       'bucket' => ''
    ]);
    
    if(isset($_POST['submit'])){
        $name = $_FILES['file']['name'];
        if(!$name){
            $msg = 'Please select a file';
        }
        
      /**
       * @param $key, the name given to the file upload field in the html form
       * @param $folder (optional) if omitted file in placed inside bucket
       * @param $filename (optional) if omitted a unique filename is generated
       * @param $options (optional) https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#putobject
       */
       $path = $storage->store('file', 'avatars/', $name);
    }
    
    # delete a file from bucket
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
    
    # get all objects from a buckets as iterator instance
    $bucket_contents = $storage->getBucketContents();
    foreach ($bucket_contents as $bucket_content){
            echo $bucket_contents['Key'] . '<br />';
    }
   
}catch (Exception $ex){
    die($ex->getMessage());
}
```
For complete example / demo refer to example folder 