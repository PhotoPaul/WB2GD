<?php
set_time_limit(0);

loadGoogleDriveClient();

function loadGoogleDriveClient(){
    require_once "lib/google-api-php-client-0.6.7/src/Google_Client.php";
    require_once "lib/google-api-php-client-0.6.7/src/contrib/Google_DriveService.php";
    echo "loaded";
}

function mysqldump(){
    require("pdo.php");
    $db = dbConnect();
    // Connection successful
    if(@$db){
        include("mysqldump.php");
    } else {
        die("Connection failed, please check dbSettings.php");
    }
}