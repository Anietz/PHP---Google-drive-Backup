<?php



require 'vendor/autoload.php';
use Google\Cloud\Storage\StorageClient;
// Authenticating with a keyfile path.
$storage = new StorageClient([
    'keyFilePath' => './link.json'
]);


$createdFolder = Date("m-d-y").'.zip'; //"08-10-19";


$bucket = $storage->bucket('complete-health-backups');
$object = $bucket->object("03-03-21.zip");
$object->downloadToFile('./recent_backup.zip');
printf('Downloaded gs://%s/%s to %s' . PHP_EOL,
        $bucketName, $objectName, basename($destination));




//Import new script
$basePath = "/var/mysql/downloader/";

shell_exec("unzip ".$basePath."recent_backup.zip && 
mysql -u user -p'password' database  < ".$basePath.$createdFolder."/db.sql");


