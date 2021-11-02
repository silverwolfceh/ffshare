<?php
	function executeCURL($url,$data = '',$isPost = true)
	{
		$s = curl_init(); 
		curl_setopt($s,CURLOPT_URL,$url); 
        curl_setopt($s,CURLOPT_HTTPHEADER,array('Expect:')); 
        curl_setopt($s,CURLOPT_TIMEOUT,500);
        curl_setopt($s,CURLOPT_MAXREDIRS,4);  
        curl_setopt($s,CURLOPT_RETURNTRANSFER,true); 
		curl_setopt($s,CURLOPT_BINARYTRANSFER, true);
		curl_setopt($s,CURLOPT_POST,true); 
        curl_setopt($s,CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2049.0 Safari/537.36");  
        curl_setopt($s,CURLOPT_REFERER,"http://anbuteam.info");
        curl_setopt($s, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($s, CURLOPT_SSL_VERIFYPEER, 0);
        if($isPost)
       	{
       		curl_setopt($s,CURLOPT_POSTFIELDS,http_build_query($data));
       	}
       	$output = curl_exec($s);
       	curl_close($s);
       	return $output;
	}
	function notify($subject,$content,$name,$email)
    {
        $apiurl = "https://api.sendgrid.com/api/mail.send.json";
        $apidata = array("api_user" => "silverwolfceh",
                          "api_key"  => 'AAAAAAAAAAAAAAAAAAAAAA',
                          "to" => "soibac@outlook.com",
						  "cc" => "tongvuu@gmail.com",
                          "toname" => "Tong Vuu",
                          "subject" => "$subject",
                          "html" => "$content",
                          "from" => "$email",
						  "fromname" => "$name"
						  );
		return executeCURL($apiurl,$apidata,true);
    }
	if(isset($_POST['fname']))
	{
		notify($_POST['title'],$_POST['message'],$_POST['fname'],$_POST['email']);
                echo "<script>alert('Email sent! Thanks for contact me');</script>";
                echo "<script>document.location.href='index.php';</script>";
	}
	die("Access Denied");
?>