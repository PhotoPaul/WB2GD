<?php
// *** Settings Start ***
$urltoself = "http://www.grbc.gr/wb2gd/mysqldump.php";
define("session","tmp/lastfilesize.txt");
define("output","tmp/tmp.sql.bz2");
// Change debugger into true in order to save debugging logs
define("debugger",false);
define("debug","tmp/debug.txt");
// ***  Settings End  ***

if(!file_exists(session)){
	dbg("mysqldump started".PHP_EOL,false);
	// initialize session (needs to be different than "0"
	file_put_contents(session,"-1");
	// initiate mysqldump
	exec("mysqldump --user=".DBUSERNAME." --password=".DBPASSWORD." --host=".DBHOST." ".DBNAME." | bzip2  > ".output." &");
    // add some delay
    sleep(1);
	// post to self
	curl_post_async($urltoself);
} else {
	$lastfilesize = file_get_contents(session);
	$currentfilesize = filesize(output);

	dbg("Contents of ".session.": ".file_get_contents(session));
	dbg("Filesize of ".output.": ".filesize(output));

	if($lastfilesize != (string)$currentfilesize){
		// update session
		file_put_contents(session,$currentfilesize);
		// add some delay
		sleep(1);
		// post to self
		curl_post_async($urltoself);
	} else {
		dbg(PHP_EOL."Deleting file: ".session);
		unlink(session);
	}
}

function curl_post_async($url){
	exec("curl -X POST '".$url."' > /dev/null 2>&1 &");
	return;

	// curl_post_async using fsockopen
    // works but it's buggy
    /*
    $parts=parse_url($url);
    $fp = fsockopen($parts['host'],isset($parts['port'])?$parts['port']:80,$errno,$errstr,120);
	$out = "POST ".$parts['path']." HTTP/1.1\r\n";
	$out.= "Host: ".$parts['host']."\r\n";
	$out.= "Content-Type: application/x-www-form-urlencoded\r\n";
	$out.= "Content-Length: 1\r\n";
	$out.= "Connection: Close\r\n\r\n";
	fwrite($fp, $out);
	fclose($fp);
    */
}

function dbg($dbg,$append = true){
	if(debugger){
		if($append){
			file_put_contents(debug,$dbg.PHP_EOL,FILE_APPEND);
		} else {
			file_put_contents(debug,$dbg.PHP_EOL);
		}
	}
}