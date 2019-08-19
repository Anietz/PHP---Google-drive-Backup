<?php

// include your composer dependencies
require_once 'vendor/autoload.php';


putenv('GOOGLE_APPLICATION_CREDENTIALS=/var/scripts/account_services.json'); //Include account_services.json from Google drive panel


$client = new Google_Client();
$client->setApplicationName("Client_Library_Examples");
$client->useApplicationDefaultCredentials();
$client->addScope(Google_Service_Drive::DRIVE);


$service = new Google_Service_Drive($client);



$createdFolder = .Date("d-m-y"); //"08-10-19";


$backupPath = "/var/scripts/";

$createdFolderPath = $backupPath.$createdFolder;

shell_exec("cd $backupPath &&  mkdir $createdFolder");

###### MySQL back up
$mysqlBackupPath = $createdFolderPath."/db_dump.sql";

$database = "test";
$db_username = "root";
$db_password = "1234";

shell_exec("mysqldump -u $db_username -p'$db_password' $database > $mysqlBackupPath");    

$applicationFolder = "/var/www/html";   //root folder for application in VPS


###### Zip the application and database path
shell_exec("cd $backupPath && zip -r $createdFolder $applicationFolder $mysqlBackupPath");

##### upload file to google drive
$fileName = $createdFolderPath.".zip"; // 08-10-19.zip
    
$fileMetadata = new Google_Service_Drive_DriveFile(array(
    'name' => $fileName,
    'mimeType' => 'application/zip'));


$content = file_get_contents($fileName);

$file = $service->files->create($fileMetadata, array(
    'data' => $content,
    'mimeType' => 'application/zip',
    'uploadType' => 'multipart',
    'fields' => 'id'));



###### remove created folder and zip file after Google drive backup
shell_exec("rm -r $fileName && rm -r $createdFolderPath");
