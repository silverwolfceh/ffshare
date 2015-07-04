<?php
	require_once("config.php");
	require_once("script.php");
	if(checkBanIp($_SERVER["REMOTE_ADDR"]))
		banIP();
	if(isset($_GET['file']) && isset($_GET['base']))
	{
		$file = $_GET['file'];
		$base = $_GET['base'];
		if(!is_numeric($base) || $base % 9 == 0)
		{
			echo "Invalid base!";
			return;
		}
		if($file == "")
		{
			echo "Invalid file or deleted file";
			return;
		}
		if(strpos($file,"../") !== FALSE)
		{
			echo "Attacker detected! Your IP ".$_SERVER["REMOTE_ADDR"]." has been banned";
			addBanIp($_SERVER["REMOTE_ADDR"]);
			return;
		}
		$dir = $GLOBALS['uploaddir']."/".$GLOBALS['datadir'][$base % 9];
		$path = $dir."/".$file;
		//echo $base % 8;
		if(!file_exists($path) )
		{
			echo "Invalid file or deleted file";
			return;
		}
		if(!isset($_POST['pass']))
		{
			if(getPassword($path) != "")
			{
				echo "<form method='post' action='/0.sec/".$base."/".$file."'>";
				echo "Type your download password: <input type='password' name='pass' id='pass' /><br />";
				echo "<input type='submit' value='Download file' /></form>";
				return;
			}
		}
		else
		{
			if(getPassword($path) != $_POST['pass'])
			{
				echo "Invalid password";
				return;
			}
		}
		
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename='.basename($path));
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . filesize($path));
		ob_clean();
        flush();
		readfile($path);
		exit;
			
	}
	die("No file specified");
?>