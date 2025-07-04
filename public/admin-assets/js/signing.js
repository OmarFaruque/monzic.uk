// JavaScript Document


//////////////////////////////////////////////////////////////////////////////////


function sign_in(event){ 
	
	event.preventDefault();

	var username = $("#username").val().trim();
	var password = $("#password").val().trim();
	var remember = $("#remember").prop('checked')?1:0;

	if(password.length < 6) return;	

	if(!(/^[^\s()<>@,;:\/]+@\w[\w\.-]+\.[a-z]{2,}$/.test(username))) return;

	var fdata = {email : username,  password : password, remember} ;
		
	var sbutton = $("#sbutton").html(); //grab the initial content
	$("#errmsg").html('');  

	$(".form_error").remove();

	$("#sbutton").html('<span class="fa fa-spin fa-spinner fa-2x"></span> please wait...');
	$('.card-footer').hide();
	
	$.ajax({
		type: "POST",
		url:   "/admin/login",
		data: fdata,
		dataType: 'json',
		success: function(data){ //console.log(data);

			$("#sbutton").html('<span class="fa fa-sign-in text-success"> Logged in  Redirecting...</span>');
			$("form").trigger('reset');
			window.location.replace("./");
			
			},
			error: function (xhr, status, error) {
				$("#sbutton").html(sbutton);
				$('.card-footer').show();
				render_errors(JSON.parse(xhr.responseText), '#errmsg');
			}
		});	
}


function forgot_pw(event){

	event.preventDefault();

	let email = $("#email").val();

	var fdata = {email} ;
		
	var sbutton = $("#sbutton").html(); //grab the initial content
	
	$("#errmsg").html('');
	$(".formError, .form_error").remove();

	$("#sbutton").html('<span class="fa fa-spin fa-spinner fa-2x"></span> Sending...');
	$('.card-footer').hide();
		
		$.ajax({
			type: "POST",
			url:   "/admin/forgot-password",
			data: fdata,
			success: function(data){ //console.log(data);
					$('#form_area').html(`<div class="alert alert-success py-5 text-center"><span class="fa fa-5x fa-check-circle"></span><br>We have send a password reset link to your email address. Please follow the link in order to reset your password.<br><br><p><a href="./login">Back to Login</a></p></div>`)
			},
			error: function (xhr, status, error) {
				$("#sbutton").html(sbutton);
				$('.card-footer').show(); 
				render_errors(JSON.parse(xhr.responseText), '#errmsg');
			}
		});	
}
	


function reset_password(event){
		
	event.preventDefault() //Prevent default form submission, we are using ajax to submit user details

	var token = $('#token').val();
	var password = $('#password').val().trim();
	var confirm_password = $('#confirm_password').val().trim();

	let fdata = {password, confirm_password, token};

	var sbutton = $("#sbutton").html(); //grab the initial content
	$("#sbutton").html('<span class="fa fa-spin fa-spinner fa-2x"></span> please wait...');

	$('.card-footer').hide();
	//Ajax request to post registration details
	$.ajax({
	type: "POST",
	url:   "/admin/reset-password",
	data: fdata,
	data: fdata,
			success: function(data){ //console.log(data);
					$('#form_area').html(`<div class="alert alert-success py-5 text-center"><span class="fa fa-5x fa-check-circle"></span><br>Password reseted successfully. You can now login with your new password <br><br><p><a class="btn btn-main px-3" href="/admin/login">Login</a></p></div>`)
			},
			error: function (xhr, status, error) {
				$("#sbutton").html(sbutton);
				$('.card-footer').show(); 
				render_errors(JSON.parse(xhr.responseText), '#errmsg');
			}	
		});
	
}


$(document).ready(function() {
$(".close_mag").click(function(){ $.magnificPopup.close(); });
});


function myalert(messagez){
	//alert("jjjiii");
	
$.magnificPopup.close();

setTimeout(function(){
$.magnificPopup.open({
  items: {
    src: '<div style="width:100%; text-align:center" class="white-popup"><div style="width:auto; max-width:500px; display:inline-block; background-color:#EEE; text-align:left; padding:20px; color: #000">' + messagez + '</div></div>', // can be a HTML string, jQuery object, or CSS selector
    type: 'inline'
  }
}); }, 500);
	}
	

function dommie(vasz, str){ 
	if(vasz == "1"){
$.magnificPopup.open({
  items: {
    src: '<div style="width:100%; text-align:center" class="white-popup"><div style="width:auto; max-width:500px; display:inline-block; font-size:40px; background-color:transparent; color: #CCC"><i class="fa fa-spinner fa-spin"></i>  ' + str + '</div></div>', // can be a HTML string, jQuery object, or CSS selector
    type: 'inline'
  }
}); 	}

    else $.magnificPopup.close();
	}

	
function mytooltip(kz, message){
		
       var valuez = '<div id="tooltip_' + kz + '" style=" position:relative; border: thin dotted #FFC; width:auto; padding: 5px; border-radius: 4px;" role="tooltip"><div style="position:absolute;width:0;height:0;border-color:transparent;border-style:solid; top:0;left:50%; margin-bottom: 10px; margin-left:-5px;border-width:5px 5px 5px 5px;border-top-color:#F50"></div><div style="background-color: transparent; color:#F00; font-size:12px; font-family:\'Times New Roman\', Times, serif; text-align:left">' + message + '</div></div>';
       $("#" + kz).after(valuez);
		//$("#" + kz).tooltip('show');
     		$("#" + kz).focus(); 
			
					setTimeout(function(){$("#tooltip_" + kz).remove(); },10000);
		
		}








function setCookie(cname, cvalue, exdays) {
  var d = new Date();
  d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
  var expires = "expires="+d.toUTCString();
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}