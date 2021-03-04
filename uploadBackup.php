<?php

require 'vendor/autoload.php';
use Google\Cloud\Storage\StorageClient;

$storage = new StorageClient();
// Authenticating with a keyfile path.
$storage = new StorageClient([
    'keyFilePath' => '/var/backups/google_cloud_storage/google.json'
]);

$bucket = $storage->bucket('my-backups'); //Google bucket name

$createdFolder = Date("m-d-y"); //"08-10-19";
$backupPath = "/var/backups/mysql/";

$createdFolderPath = $backupPath.$createdFolder;
    
###### MySQL back up
$mysqlBackupPath = $createdFolderPath."/db.sql";
    
// shell_exec("mysql -u $db_username -p'$db_password' $database < $mysqlBackupPath"); //import script
exec("/bin/sh ~/scripts/mysqlimport.sh ");
    
###### Zip the application and database path
shell_exec("cd $backupPath && zip -r $createdFolder  $createdFolder");
##### upload file to google drive
$fileName = $createdFolderPath.".zip"; // 08-10-19.zip
    
// Upload a file to the bucket.
$res = $bucket->upload(
    fopen($fileName, 'r')
);
