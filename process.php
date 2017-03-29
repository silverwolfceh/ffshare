<?php
	@session_start();
	if(!isset($_POST["function"]))
	{
		die("Access denied!");
	}
	//if(isset($_SERVER["REMOTE_ADDR"]) && checkBanIp($_SERVER["REMOTE_ADDR"]))
		//banIP();
	$func = $_POST["function"];
	switch($func)
	{
		case "gettime":
		{
			$ts = time() * 1000;
			echo $ts;
			break;
		}
		case "getcaptcha":
		{
			$sid = $_POST['sid'];
			session_id($sid);
			$st1 = rand(1,100);
			$st2 = rand(1,100);
			$ops = ($st2 % 4 == 0 ? "+" : ($st2 % 4 == 1 ? "-" : ($st2 % 4 == 2 ? "*" : "/" )));
			switch($ops)
			{
				case "+":
					$_SESSION["captcha-answer"] = $st1 + $st2;
					break;
				case "-":
					$_SESSION["captcha-answer"] = $st1 - $st2;
					break;
				case "*":
					$_SESSION["captcha-answer"] = $st1 * $st2;
					break;
				case "/":
					$_SESSION["captcha-answer"] = $st1 / $st2;
					break;
			}
			$_SESSION["captcha-code"] = "$st1 $ops $st2 = ";
			echo $_SESSION["captcha-code"];
			break;
		}
		case "setcaptcha":
		{
			$sid = $_POST['sid'];
			$res = $_POST['res'];
			session_id($sid);
			if($res == $_SESSION["captcha-answer"])
				echo "PASSED";
			else
				echo "FAILED";
			break;
		}
		case "uploadfile":
		{
			require_once("config.php");
			require_once("script.php");
			$livetime = $_POST['live-time'];
			$sid = $_POST['sid'];
			$password = $_POST['password'];
			session_id($sid);
			$error = false;
			$files = array();
			$_SESSION['dirid'] =$livetime;
			$uploaddir = $GLOBALS['uploaddir'].'/'.$GLOBALS['datadir'][$livetime]."/";
			$debugmsg = "";
			foreach($_FILES as $file)
			{
				if($file['size'] > $GLOBALS['max_file_size'])
				{
					$data = array('error'=> 'Maximum allowed is '.ts($GLOBALS['max_file_size']));
					echo json_encode($data);
					return;
				}
				$randomname = vn_str_filter(basename($file['name']));
				while(file_exists($uploaddir.$randomname))
				{
					$ext = pathinfo($randomname, PATHINFO_EXTENSION);
					$bname = pathinfo($randomname, PATHINFO_FILENAME);
					$randomname = $bname."_".rand(1,1000).".".$ext;
				}
				if(move_uploaded_file($file['tmp_name'], $uploaddir . $randomname))
				{
					$debugmsg = $file['name'];
					$files[] = $randomname;
					if($password != "")
					{
						$f = fopen($uploaddir.$randomname.".lock","w");
						fwrite($f,$password);
						fclose($f);
					}
				}
				else
				{
					$error = true;
				}
			}
			$_SESSION["captcha-answer"] = "";
			$data = ($error) ? array('error' => 'There was an error uploading your files') : array('files' => $files,'filename' => $debugmsg);
			echo json_encode($data);
			break;
		}
		case "verifyfile":
		{
			require_once("config.php");
			require_once("script.php");
			$sid = $_POST['sid'];
			session_id($sid);
			$randomnum = rand(0,1000000);
			while(($randomnum % 9)  != $_SESSION['dirid'])
				$randomnum = rand(0,1000000);
			$filepath = $GLOBALS['server_url']."/download.php?base=".$randomnum."&file=".$_POST["filenames"];
			if($GLOBALS['friendly_url'])
				$filepath = $GLOBALS['server_url']."/0.sec/".$randomnum."/".$_POST["filenames"];
			$data = array('success' => 'File upload success', 'file' => $filepath);
			echo json_encode($data);
			addOneFileServed();
			break;
		}
	}
	
?>