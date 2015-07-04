<?php
	
	$server_name = "FFShare.Net";
	$friendly_url = true;
	$server_url = "http://ffshare.net";
	$datadir = array("1" => "1min", "2" => "5mins", "3" => "1hour", "4" => "5hours", "5" => "12hours", "6" => "1day", "7" => "5days", "8" => "30days");
	$uploaddir = '/var/www/data/uploads';
	$visistor = "log/visitor.txt";
	$fileserved = "log/fileserved.txt";
	$banip = "log/bannedip.txt";
	$max_file_size = 104857600; //100MB
	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
		$server_url = "http://fastshare.com";
		$uploaddir = 'D:\\Dropbox\\Dropbox\\PHP\\www\\FastShare\\uploads';
		$max_file_size = 1048576000; //1000MB
	}	
?>