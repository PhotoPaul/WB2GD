<?php

function dbConnect(){
    if(!file_exists("dbSettings.php")){
        // No Database settings found
        return false;
        // If dbSettings.php exists, then load it
    } else {
        try{
            include "dbSettings.php";
            // Check dbSettings
            return new PDO("mysql:host=".DBHOST.";dbname=".DBNAME,DBUSERNAME,DBPASSWORD);
        } catch(PDOException $ex){
            // Database settings are incorrect
            return false;
        }
    }
}