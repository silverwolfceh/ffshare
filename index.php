<?php
	@session_start();
	require_once("config.php");
	require_once("script.php");
	if(checkBanIp($_SERVER["REMOTE_ADDR"]))
		banIP();
	$uuid = md5(uniqid(mt_rand()));
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="images/favicon.ico">

    <title><?php echo $GLOBALS['server_name']; ?> - Share your file in a Fast and Free ways</title>
    <script src="js/jquery-1.10.2.min.js"></script>
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <script src="js/bootstrap.min.js"></script>
    <link href="css/bootstrap-dialog.min.css" rel="stylesheet" type="text/css" />
    <script src="js/bootstrap-dialog.min.js"></script>
	<link href="css/custom.css" rel="stylesheet" type="text/css" />
	<script src="js/custom.js"></script>
  </head>
  <body >

<div class="navbar navbar-fixed-top" >
          <div class="nav-collapse navbar-responsive-collapse in" style="height: auto;">
              <ul class="nav row">
                  <li data-slide="6" class="col-12 col-sm-2">
                      <a id="menu-link-6 floattime" href="/#" title="Current Time">
                          <span class="icon icon-contact"></span><span class="floattime" id="timestr"> </span>
                      </a>
                  </li>
                  <li data-slide="1" class="col-12 col-sm-2">
                      <a id="menu-link-1" href="/#home" title="Go To Home">
                          <span class="icon icon-home"></span> <span class="text"> UPLOAD </span>
                      </a>
                  </li>
                  <li data-slide="2" class="col-12 col-sm-2">
                      <a id="menu-link-2" href="/#stat" title="Go To Statistic">
                          <span class="icon icon-about"></span> <span class="text"> INFORMATION </span>
                      </a>
                  </li>
                 <!--  <li data-slide="4" class="col-12 col-sm-2">
                      <a id="menu-link-3" href="/#terms" title="Go To Terms and Agreements">
                          <span class="icon icon-about"></span> <span class="text"> TERMS</span>
                      </a>
                  </li> -->
<!--                  <li data-slide="5" class="col-12 col-sm-2">-->
<!--                      <a id="menu-link-4" href="/#slide-5" title="Next Section">-->
<!--                          <span class="icon icon-about"></span> <span class="text"> PROJECT</span>-->
<!--                      </a>-->
<!--                  </li>-->
                  <li data-slide="6" class="col-12 col-sm-2">
                      <a id="menu-link-6" href="/#contact" title="Send me some messages">
                          <span class="icon icon-contact"></span><span class="text"> CONTACT ME </span>
                      </a>
                  </li>

              </ul>
              <div class="row">
                  <div style="left: 41.015625px; width: 68px;" class="col-sm-2 active-menu"></div>
              </div>
          </div><!-- /.nav-collapse -->
      </div><!-- /.navibar -->
<div class="container-full" id="home">




      <div class="row">
        <div class="col-lg-12 text-center v-center slide story" >
			<h1>Upload a File</h1>
			<p class="lead">And share it in fast and free way</p>
			<p class="error" id="errormsg"></p>
			<div id="bar_blank" style="display:none">
			
				<div id="bar_color"></div>
			</div>
			<!--<iframe id="upload-frame" name="upload-frame" width=0 height=0></iframe>-->
			<form class="form-horizontal" method="post" enctype="multipart/form-data" action="" id="uploadform" >
				<fieldset>

					<!-- File Button --> 
					<div class="form-group">
						<label class="col-md-4 control-label" for="filebutton">Choose a file</label>
						<div class="col-md-4">
							<input type="hidden" id="UPLOAD_IDENTIFIER"  name="UPLOAD_IDENTIFIER"  value="<?php echo $uuid; ?>" />
							<input type="hidden" value="uploadform" name="PHP_SESSION_UPLOAD_PROGRESS" id="PHP_SESSION_UPLOAD_PROGRESS">
							<input id="filebutton" name="filebutton" class="input-file" type="file" required="">
							<input type="hidden" name="MAX_FILE_SIZE" id="MAX_FILE_SIZE" value="<?php echo $GLOBALS['max_file_size']; ?>" /> 
						
						</div>
					</div>

					<!-- Select Basic -->
					<div class="form-group">
						<label class="col-md-4 control-label" for="livetime">Live time</label>
						<div class="col-md-3">
							<select id="livetime" name="livetime" class="form-control">
								<!-- <option value="1">1 minutes</option>
								<option value="2">5 minutes</option> -->
								<option value="3">1 hour</option>
								<option value="4">5 hours</option>
								<option value="5">12 hours</option>
								<option value="6">1 day</option>
								<option value="7">5 days</option>
								<option value="8">30 days</option>
							</select>
						</div>
					</div>
		
					<!-- Prepended text-->
					<div class="form-group" style="display:none">
						<label class="col-md-4 control-label" for="captcha-res">Enter captcha</label>
						<div class="col-md-3">
							<div class="input-group">
								<span class="input-group-addon" id="captcha-code" onclick="loadCaptcha()">CLICK HERE</span>
								<input id="captcha-res" name="captcha-res" class="form-control" placeholder="6" type="text"  onblur="checkCaptcha()">
							</div>
							<p class="help-block">Click the grey cell to get or change captcha.</p>
						</div>
					</div>
					
					<!-- Text input-->
					<div class="form-group" id="passzone">
						<label class="col-md-4 control-label" for="password">Download password:</label>  
						<div class="col-md-3">
							<input id="password" name="password" type="password" class="form-control input-md" >
						</div>
						
					</div>

					<!-- Multiple Radios -->
					<div class="form-group" style="display:none">
						<label class="col-md-4 control-label" for="radios">Do you agree with our terms? </label>
						<div class="terms col-md-4">
							<div class="radio">
								<label for="radios-0">
									<input type="radio" name="radios" id="radios-0" value="1" checked="checked" required="">
									I agreed
								</label>
							</div>
							<div class="radio">
								<label for="radios-1">
									<input type="radio" name="radios" id="radios-1" value="2">
									No, thanks.
								</label>
							</div>
						</div>
					</div>

					<!-- Button (Double) -->
					<div class="buttons form-group">
						<label class="col-md-4 control-label" for="button1id"></label>
						<div class="col-md-8">
                            <span style="color: rgb(255, 242, 0);">Pressing Upload button mean that you agreed with our <a href="#terms">Terms</a></span>
                            <br /><br />
                            <button type="submit" id="button1id" name="button1id" class="btn btn-success" >Upload</button>
							<button type="reset" id="button2id" name="button2id" class="btn btn-warning" >Clear</button>
							<span style="color: rgb(255, 242, 0);">Max upload file size is <?php echo ts($GLOBALS['max_file_size']); ?></span>
						</div>
					</div>
					
					<!-- Text input-->
					<div class="form-group" style="display:none" id="download">
						<label class="col-md-4 control-label" for="downloadlink">Download link</label>  
						<div class="col-md-5">
							<input id="downloadlink" name="downloadlink" type="text" placeholder="" class="form-control input-md" onclick="this.select();">
							<span class="help-block" style="color:white">This is your download link for your file</span>  
						</div>
						
					</div>
					
					
					
				</fieldset>
			</form>
			
        </div>
        
	</div> <!-- /row -->
  <div class="row">
        <div class="col-lg-12">
        <br><br>
          <p class="pull-right" style="color:white">©Copyright 2015 <?php echo $GLOBALS['server_name']; ?><sup>TM</sup>.</p>
        <br><br>
        </div>
    </div>
	<div class="row">   
		<div class="col-lg-12 text-center v-center" style="font-size:39pt;">
			<a href="#"><i class="icon-google-plus"></i></a> <a href="#"><i class="icon-facebook"></i></a>  <a href="#"><i class="icon-twitter"></i></a> <a href="#"><i class="icon-github"></i></a> <a href="#"><i class="icon-pinterest"></i></a>
        </div>
    
    </div>
  	<br><br><br><br><br>
</div> <!-- /container full -->
<div class="container-full statistic" id="stat">
<!--  	<hr>-->
    <br />
    <br />
  	<div class="row" style="margin-top: 5%; margin-left: 5%; margin-right: 5%">
        <div class="col-md-4">
			<div class="panel panel-default">
				<div class="panel-heading"><h3>TERMS</h3></div>
				<div class="panel-body" style="color:blue">
					<b><i>
                    If you upload something onto our Fast & Free file sharing server. You were agreed with our term and conditions: <br />
                    1. The uploaded file are your responsible. We won't know and responsible for any DCMA or something related to rights on that file <br />
                    2. We are not responsible for any loss of your data. This is only temporary server, any files will be deleted in a certain time. You can buy PRO version to have your files backed up forever <br />
                    3. We reversed the right to LOCK your ip or provide your ip to the government if needed without prior notification <br />
                    4. We reversed the right to DELETE your files if your file were detected to violate our TERMS and AGREEMENTS <br />
                    5. We reversed the right to update this part without notification.
                    </i></b>
				</div>
          </div>
        </div>
		
      	<div class="col-md-4">
        	<div class="panel panel-default">
				<div class="panel-heading"><h3>Statistic</h3></div>
				<div class="panel-body">
					<p> Server time: <span id="srvtime"> 14:09 PM (16 May, 2015) </span> </p>
					<p> Files on server: <?php echo getFileOnServer(); ?> </p>
					<p> Hard disk (free/total): <?php echo getHarddiskInfo(); ?></p>
					<p> Total file served: <?php echo getFileServed(); ?> </p>
					<p> Total visistors: <?php echo getVisistor(); ?> </p>
                    <p>
                    <a href="http://www.histats.com" alt="page hit counter" target="_blank" >
                        <embed src="http://s10.histats.com/10.swf"  flashvars="jver=1&acsid=3070864&domi=4"  quality="high"  width="200" height="40" name="10.swf"  align="middle" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" wmode="transparent" /></a>
                    <img  src="http://sstatic1.histats.com/0.gif?3070864&101" alt="free log" border="0">
                    </p>
				</div>
          </div>
        </div>
      	<div class="col-md-4">
        	<div class="panel panel-default">
				<div class="panel-heading"><h3>About</h3></div>
				<div class="panel-body">
					<p>About site: <?php echo $GLOBALS['server_name']; ?> is a FAST and FREE file-sharing server. Users can upload their files and share in just one step. We won't store user files forever, we will respect user idea that their file won't appear public and will be deleted in a determined time. This is the best and private file-sharing server ever. </p>
					<p> About author: I am a programmer and sometime I want share my code for my teammate in a fast way without registering account or something like that. That is the reason why I build this system. If you meet any errors or have something to share, please contact me at: <a href="mailto:tongvuu@gmaiil.com"> tongvuu@gmail.com </a></p>
				</div>
          </div>
        </div>
    </div>
  
	<div class="row">
        <div class="col-lg-12">
        <br><br>
          <p class="pull-right" style="color:white">©Copyright 2015 <?php echo $GLOBALS['server_name']; ?><sup>TM</sup>.</p>
        <br><br>
        </div>
    </div>
</div>
<!-- <div class="container-full terms" id="terms">

    <br />
    <br />
    <div class="row" style="margin-left: 20%; margin-top: 5%">
        <div class="col-md-8">
            <div class="panel panel-default" style="margin: auto; background-color: transparent; border: 0px">
                <div class="panel-heading" style="background-color: transparent; color: rgb(139, 255, 15);"><h3>TERMS AND AGREEMENTS</h3></div>
                <div class="panel-body"  style="height:400px; color: rgb(240, 253, 36); ">
                <b><i>
                    If you upload something onto our Fast & Free file sharing server. You were agreed with our term and conditions: <br />
                    1. The uploaded file are your responsible. We won't know and responsible for any DCMA or something related to rights on that file <br />
                    2. We are not responsible for any loss of your data. This is only temporary server, any files will be deleted in a certain time. You can buy PRO version to have your files backed up forever <br />
                    3. We reversed the right to LOCK your ip or provide your ip to the government if needed without prior notification <br />
                    4. We reversed the right to DELETE your files if your file were detected to violate our TERMS and AGREEMENTS <br />
                    5. We reversed the right to update this part without notification.
                    </i></b>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <br><br>
            <p class="pull-right" style="color:white">©Copyright 2015 <?php echo $GLOBALS['server_name']; ?><sup>TM</sup>.</p>
            <br><br>
        </div>
    </div>
</div> -->
<div class="container-full contact" id="contact">
<!--    <hr>-->
    <br />
    <br />

    <div class="row" style="margin-left: 20%; margin-top: 5%">
        <div class="col-md-8">

            <div class="panel panel-default" style="margin: auto; text-align: center">
                <div class="panel-heading" style="background-color: #f5f5f5"><h3 >SEND ME A MESSAGE</h3></div>
                <div class="panel-body"  style="height:400px;color:black;">
                <form method="post" action="mail.php">
                    <div class="form-group" id=>
                        <label class="col-md-3 contact_control-label" for="fname">Full name:</label>
                        <div class="col-md-6">
                            <input id="fname" name="fname" type="text" class="form-control input-md letter-style" >
                        </div>

                    </div>
                    <div class="form-group" id=>
                        <label class="col-md-3 contact_control-label" for="email">Email:</label>
                        <div class="col-md-6">
                            <input id="email" name="email" type="text" class="form-control input-md letter-style" placeholder="abc@example.com" >
                        </div>

                    </div>
                    <div class="form-group" id=>
                        <label class="col-md-3 contact_control-label" for="title">Subject:</label>
                        <div class="col-md-6">
                            <input id="title" name="title" type="text" class="form-control input-md letter-style" placeholder="A bug was found" >
                        </div>

                    </div>

                    <div class="form-group" style="margin-top: 10px">
                        <label class="col-md-3 contact_control-label" for="message">Message:</label>
                        <div class="col-md-6 " >
                            <textarea id="message" name="message" cols=50 rows="10" style="border: dashed #000000 1px; color: blue"></textarea>
                        </div>

                    </div>
                    <div class="buttons form-group">
                        <label class="col-md-4 control-label" for="button1id"></label>
                        <div class="col-md-8">
                            <button type="submit" id="sendmsg" name="sendmsg" class="btn btn-success" >Send</button>
                            <button type="reset" id="resetmsg" name="resetmsg" class="btn btn-warning" >Clear</button>

                        </div>
                    </div>
                </form>

                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-lg-12">
            <br><br>
            <p class="pull-right" style="color:white">©Copyright 2015 <?php echo $GLOBALS['server_name']; ?><sup>TM</sup>.</p>
            <br><br>
        </div>
    </div>
</div>

<script>
<?php
	$current = time() * 1000;
	echo "startime = ".$current.";";
	echo "countTime();"; 
	echo "setInterval(function(){ countTime() },1000);";
?>
</script>
<?php
	addOneVisistor();
?>
</body>
</html>
