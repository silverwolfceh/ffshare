var allowSubmitForm = false;
var startime = 0;
    


function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
    }
    return "";
}


function countTime()
{
	//alert(startime);
	var d = new Date(startime);
	startime = startime + 1000;
	var msg = d.toLocaleString();
	$("#srvtime").html(msg)
	$("#timestr").html(msg)
	//console.log(d)
}

function getTime()
{
	var sid = getCookie("PHPSESSID")
	$.post('0.sec',{'function': 'gettime', 'sid': sid },function(reponse) {
				startime = reponse;
				console.log(startime);
				countTime();
				setInterval(function(){ countTime() },1000);
                return;
            });
}

function toggleError(isDisplay)
{
	if(isDisplay)
	{
		$("#errormsg").css("display","block")
		$("#bar_blank").css("display","none")
	}
	else
	{
		$("#errormsg").css("display","none")
		$("#bar_blank").css("display","block")
	}
}

function getUploadedPercent()
{
	var sid = getCookie("PHPSESSID")
	var uuid = $("#UPLOAD_IDENTIFIER").val()
	var time = new Date().getTime();
	$.post('0.sec',{'function': 'getuploadedpercent', 'sid': sid, 'UPLOAD_IDENTIFIER': uuid, "time": time },function(reponse) {
			if (reponse <= 100) 
			{
				//toggleError(false)
				//document.getElementById("bar_color").style.width = reponse + "%";
				//document.getElementById("status").innerHTML = reponse + "%";
				$("#errormsg").html(reponse);
				setTimeout("getUploadedPercent()", 1000);
			}
			else
			{
				$("#errormsg").html("done");
				//toggleError(true)
			}
    });
}

function loadCaptcha()
{
	var sid = getCookie("PHPSESSID")
	$.post('0.sec',{'function': 'getcaptcha', 'sid': sid },function(reponse) {
				$("#captcha-code").html(reponse)
                return;
            });
	
}
function menu_focus( element, i ) {
    if ( $(element).hasClass('active') ) {
        if ( i == 6 ) {
            if ( $('.navbar').hasClass('inv') == false )
                return;
        } else {
            return;
        }
    }

    enable_arrows( i );

    if ( i == 1 || i == 6 )
        $('.navbar').removeClass('inv');
    else
        $('.navbar').addClass('inv');

    $('.nav > li').removeClass('active');
    $(element).addClass('active');

    var icon = $(element).find('.icon');

    var left_pos = icon.offset().left - $('.nav').offset().left;
    var el_width = icon.width() + $(element).find('.text').width() + 10;

    $('.active-menu').stop(false, false).animate(
        {
            left: left_pos,
            width: el_width
        },
        1500,
        'easeInOutQuart'
    );
}
function checkCaptcha()
{
	var sid = getCookie("PHPSESSID")
	var res = $("#captcha-res").val();
	$.post('0.sec',{'function': 'setcaptcha', 'sid': sid, 'res': res },function(reponse) {
				if(reponse == "PASSED")
				{
					allowSubmitForm = true;
					$("#captcha-res").css("border-color","blue")
				}
				else
				{
					allowSubmitForm = false;
					$("#captcha-res").css("border-color","red")
				}
                return;
            });
}

$(function()
{
    //var lis = $('.nav > li');
    //menu_focus( lis[0], 1 );

    //$(".fancybox").fancybox({
    //    padding: 10,
    //    helpers: {
    //        overlay: {
    //            locked: false
    //        }
    //    }
    //});
    // Variable to store your files
	var files;

	// Add events
	$('input[type=file]').on('change', prepareUpload);
	$('#uploadform').on('submit', uploadFiles);
	
	// Animate
	$("html, body").animate({ scrollTop: $(document).height()-$(window).height() },10, function(){
		$("html, body").animate({ scrollTop: 0 }, 1500);
	});
	
	//getTime();
	// Grab the files and set them to our variable
	function prepareUpload(event)
	{
		var input = document.getElementById("filebutton");
		var max_size = $("#MAX_FILE_SIZE").val();
		if(input.files && input.files.length == 1)
		{           
			if (input.files[0].size > max_size) 
			{
				$("#errormsg").html('ERRORS: exceed max upload size');
				return false;
			}
		}
		files = event.target.files;
	}

	// Catch the form submit and upload the files
	function uploadFiles(event)
	{
		var input = document.getElementById("filebutton");
		var max_size = $("#MAX_FILE_SIZE").val();
		if(input.files && input.files.length == 1)
		{           
			if (input.files[0].size > max_size) 
			{
				$("#errormsg").html('ERRORS: exceed max upload size');
				return false;
			}
		}
		event.stopPropagation(); // Stop stuff happening
        event.preventDefault(); // Totally stop stuff happening

        // START A LOADING SPINNER HERE
		$("#errormsg").html("<img src='images/loading.gif' />");
		//getUploadedPercent()
        
		// Create a formdata object and add the files
		var data = new FormData();
		$.each(files, function(key, value)
		{
			console.log(value);
			data.append(key, value);
		});
		
		captchares = $("#captcha-res").val()
		livetime = $("#livetime").val()
		protectedpass = $("#password").val()
		uuid = $("#UPLOAD_IDENTIFIER").val()
		var sid = getCookie("PHPSESSID")
		data.append("UPLOAD_IDENTIFIER",uuid)
		data.append("captcha-res",captchares)
		data.append("live-time",livetime)
        data.append("function","uploadfile")
		data.append("sid",sid)
		data.append("password",protectedpass)
        $.ajax({
            url: '0.sec',
            type: 'POST',
            data: data,
            cache: false,
            dataType: 'json',
            processData: false, // Don't process the files
            contentType: false, // Set content type to false as jQuery will tell the server its a query string request
            success: function(data, textStatus, jqXHR)
            {
            	if(typeof data.error === 'undefined')
            	{
            		// Success so call function to process the form
            		submitForm(event, data);
					loadCaptcha()
            	}
            	else
            	{
            		// Handle errors here
            		console.log('ERRORS: ' + data.error);
					$("#errormsg").html('ERRORS: ' + data.error)
					loadCaptcha()
            	}
            },
            error: function(jqXHR, textStatus, errorThrown)
            {
            	// Handle errors here
            	console.log('ERRORS: ' + textStatus);
				$("#errormsg").html('ERRORS: Upload file failed')
				loadCaptcha()
            	// STOP LOADING SPINNER
            }
        });
    }

    function submitForm(event, data)
	{
		// Create a jQuery object from the form
		$form = $(event.target);
		
		// Serialize the form data
		var formData = $form.serialize();
		
		// You should sterilise the file names
		$.each(data.files, function(key, value)
		{
			formData = formData + '&filenames=' + value;
		});
		formData = formData + "&function=verifyfile";
		var sid = getCookie("PHPSESSID")
		formData = formData + "&sid=" + sid;
		$.ajax({
			url: '0.sec',
            type: 'POST',
            data: formData,
            cache: false,
            dataType: 'json',
            success: function(data, textStatus, jqXHR)
            {
            	if(typeof data.error === 'undefined')
            	{
            		// Success so call function to process the form
            		$("#downloadlink").val(data.file);
					$("#download").css("display","block");
					$("#errormsg").html('')
            	}
            	else
            	{
            		// Handle errors here
            		console.log('ERRORS: ' + data.error);
					$("#errormsg").html('ERRORS: ' + data.error)
            	}
            },
            error: function(jqXHR, textStatus, errorThrown)
            {
            	// Handle errors here
            	console.log('ERRORS: ' + textStatus);
				$("#errormsg").html('ERRORS: ' + textStatus)
            },
            complete: function()
            {
            	// STOP LOADING SPINNER
				loadCaptcha()
				$("#errormsg").html('')
            }
		});
	}
});

