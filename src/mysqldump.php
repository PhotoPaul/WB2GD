<?php
// *** Settings Start ***
$urltoself = "http://www.grbc.gr/wb2gd/mysqldump.php";
$session = "tmp/lastfilesize.txt";
$output = "tmp/tmp.sql.bz2";
// ***  Settings End  ***

if(!file_exists($session)){
	// initialize session (needs to be different than "0"
	file_put_contents($session,"-1");
	// initiate mysqldump
	exec("mysqldump --user=".DBUSERNAME." --password=".DBPASSWORD." --host=".DBHOST." ".DBNAME." | bzip2  > ".$output." &");
    // add some delay
    sleep(1);
	// post to self
	curl_post_async($urltoself);
}

$lastfilesize = file_get_contents($session);
$currentfilesize = filesize($output);
if($lastfilesize != $currentfilesize){
    // update session
    file_put_contents($session,$currentfilesize);
    // add some delay
    sleep(1);
    // post to self
    curl_post_async($urltoself);
} else {
    unlink($session);
}

function curl_post_async($url){
	$parts=parse_url($url);
    $fp = fsockopen($parts['host'],isset($parts['port'])?$parts['port']:80,$errno,$errstr,120);
	$out = "POST ".$parts['path']." HTTP/1.1\r\n";
	$out.= "Host: ".$parts['host']."\r\n";
	$out.= "Content-Type: application/x-www-form-urlencoded\r\n";
	$out.= "Content-Length: 1\r\n";
	$out.= "Connection: Close\r\n\r\n";
	fwrite($fp, $out);
	fclose($fp);
}