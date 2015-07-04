<?php
	require_once("config.php");
	function ts($s_s)
	{
		if($s_s<=0) return 0;
		$s_w = array('B','KB','MB','GB','TB','PB','EB','ZB','YB');
		$s_e = floor(log($s_s)/log(1024));
		return sprintf('%.2f '.$s_w[$s_e], ($s_s/pow(1024, floor($s_e))));
	}
	function vn_str_filter ($str)
	{ 
		$unicode = array( 'a'=>'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ', 'd'=>'đ', 'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ', 'i'=>'í|ì|ỉ|ĩ|ị', 'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ', 'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự', 'y'=>'ý|ỳ|ỷ|ỹ|ỵ', 'A'=>'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ', 'D'=>'Đ', 'E'=>'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ', 'I'=>'Í|Ì|Ỉ|Ĩ|Ị', 'O'=>'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ', 'U'=>'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự', 'Y'=>'Ý|Ỳ|Ỷ|Ỹ|Ỵ', '-' => ' '); 
		foreach($unicode as $nonUnicode=>$uni)
		{ 
			$str = preg_replace("/($uni)/i", $nonUnicode, $str);
		} 
		return $str; 
	}

	function getHarddiskInfo()
	{
		$msg = ts(disk_free_space($GLOBALS['uploaddir']))."/".ts(@disk_total_space($GLOBALS['uploaddir']));
		return $msg;
	}
	function getSubDir($dir)
	{
		$cnt = 0;
		$files = scandir($dir);
		//echo $dir;
		foreach ($files as $entry)
		{
			if($entry == "." || $entry == "..")
				continue;
			if(is_file($dir."/".$entry))
				$cnt = $cnt + 1;
			if(is_dir($dir."/".$entry))
				$cnt = $cnt + getSubDir($dir."/".$entry);
		}
		return $cnt;
	}
	function getFileOnServer()
	{
		$cnt = getSubDir($GLOBALS['uploaddir']);
		return $cnt;
	}
	function getFileServed()
	{
		$handle = fopen($GLOBALS['fileserved'], "r");
		$cur = fread($handle,1024);
		fclose($handle);
		return trim ($cur);
	}
	function addOneFileServed()
	{
		$cur = getFileServed();
		$handle = fopen($GLOBALS['fileserved'], "w");
		fwrite($handle,$cur+1);
		fclose($handle);
	}
	function getVisistor()
	{
		$handle = fopen($GLOBALS['visistor'], "r");
		$cur = fread($handle,1024);
		fclose($handle);
		return trim ($cur);
	}
	function addOneVisistor()
	{
		$cur = getVisistor();
		$handle = fopen($GLOBALS['visistor'], "w");
		fwrite($handle,$cur+1);
		fclose($handle);
	}
	function readLastRun($idx)
	{
		$dir = $GLOBALS['uploaddir']."/".$GLOBALS['datadir'][$idx];
		$handle = fopen($dir."/runhistory.txt", "r");
		$cur = fread($handle,1024);
		fclose($handle);
		return trim ($cur);
	}
	function checkBanIp($ip)
	{
		$lines = file($GLOBALS['banip'], FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		if(in_array ($ip,$lines))
			return true;
		return false;
	}
	function addBanIp($ip)
	{
		$handle = fopen($GLOBALS['banip'], "a+");
		fwrite($handle,$ip."\n");
		fclose($handle);
	}
	function banIP()
	{
		die("Your IP has been BANNED! Contact http://facebook.com/soibac if you want to explain");
	}
	function getPassword($file)
	{
		$pass = "";
		$passfile = $file.".lock";
		if(file_exists($passfile))
		{
			$f = fopen($passfile,"r+");
			$pass = trim(fread($f,1024));
			fclose($f);
		}
		return $pass;
	}
?>