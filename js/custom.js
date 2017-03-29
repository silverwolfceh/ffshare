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
			if (reponse != '100') 
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

function progressHandlingFunction(e){
    if(e.lengthComputable){
        $('progress').attr({value:e.loaded,max:e.total,});
        var percent = Math.round( (e.loaded /  e.total) * 100);

        if(percent == 100)
        {
        	$('#upload_percent').html("<strong> Processing file...Please wait...</strong>");	
        }
        else
        {
        	$('#upload_percent').html("<strong>" + percent + " %</strong>");	
        }
        
    }
}
$(function()
{
    // Variable to store your files
	var files;

	// Add events
	$('input[type=file]').on('change', prepareUpload);
	$('#uploadform').on('submit', uploadFiles);
	$("div.lazy").lazyload({
      effect : "fadeIn"
 	 });
	// Animate
	// $("html, body").animate({ scrollTop: $(document).height()-$(window).height() },10, function(){
	// 	$("html, body").animate({ scrollTop: 0 }, 1500);
	// });
	
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
        $('#uploadprogress').css('display','block');
        $('#download').css('display','none')
        $('#upload_percent').html('')
        
		// Create a formdata object and add the files
		var data = new FormData();
		$.each(files, function(key, value)
		{
			data.append(key, value);
		});
		
		var livetime = $("#livetime").val()
		var protectedpass = $("#password").val()
		var sid = getCookie("PHPSESSID")		
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
            xhr: function() {  // Custom XMLHttpRequest
            	var myXhr = $.ajaxSettings.xhr();
            	if(myXhr.upload){ // Check if upload property exists
                	myXhr.upload.addEventListener('progress',progressHandlingFunction, false); // For handling the progress of the upload
            	}
            	return myXhr;
       		},
            success: function(data, textStatus, jqXHR)
            {
            	if(typeof data.error === 'undefined')
            	{
            		// Success so call function to process the form
            		submitForm(event, data);
            	}
            	else
            	{
            		// Handle errors here
            		console.log('ERRORS: ' + data.error);
					$("#errormsg").html('ERRORS: ' + data.error)
					$('#uploadprogress').css('display','none')
            	}
            },
            error: function(jqXHR, textStatus, errorThrown)
            {
            	// Handle errors here
            	console.log('ERRORS: ' + textStatus);
				$("#errormsg").html('ERRORS: Upload file failed')
            	$('#uploadprogress').css('display','none')
            }
        });
    }

    function submitForm(event, data)
	{
		// Create a jQuery object from the form
		$form = $(event.target);
		
		// Done, off uploadprogress
		$('#uploadprogress').css('display','none')

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
				$("#errormsg").html('')
            }
		});
	}
});

