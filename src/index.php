<?php
set_time_limit(0);
require("pdo.php");

$db = dbConnect();
// Connection successful
if(@$db){
    include("mysqldump.php");
} else {
    die("Connection failed, please check dbSettings.php");
}
