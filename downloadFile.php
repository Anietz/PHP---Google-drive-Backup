<?php

$createdFolder = Date("m-d-y"); //"08-10-19";

$basePath = "/var/mysql/downloader/";

shell_exec("unzip ".$basePath."recent_backup.zip && 
mysql -u user -p'password' database  < ".$basePath.$createdFolder."/db.sql");


