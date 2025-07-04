const $ = jQuery;

//========   AJAX SETUP FOR  CSRF TOKEN FOR ALL POST REQUEST  ===
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
    }
});

let address_control = null;
let occupation_control = null;


//============= SIGN UP  FUNCTION  ============================


function registerForm(event){ 
	

	event.preventDefault();

	var first_name = $("#reg_first_name").val().trim();
    var last_name = $("#reg_last_name").val().trim();
    var email = $("#reg_email").val().trim();
	var password = $("#reg_password").val().trim();

    var quote_id = (typeof QUOTATION_ID != 'undefined') ? QUOTATION_ID : '';

	var fdata = {first_name, last_name,  password, email, quote_id} ;
	
    let parent = $(event.target).closest('form');
    parent.css("opacity", "0.5").css("pointer-events", "none");
	var sbutton = parent.find(".sbutton").html(); 
	$(".form_error, .formError").remove();
	parent.find(".sbutton").html('<span class="fa fa-spin fa-spinner fa-2x"></span> please wait...');
	

	$.ajax({
		type: "POST",
		url:   "/register",
		data: fdata,
		dataType: 'json',
		success: function(data){
            parent.css("opacity", "1").css("pointer-events", "auto");
			
            if(! quote_id){
                parent.html('<div class="alert alert-success py-5 text-center"> <i class="fa fa-4x fa-check-circle"></i><br><br>  Account created successfully! You can now login </div>');
                $("form").trigger('reset');

                $("input.verify_email_address").val(email)
                $(".verify_email_address:not(input)").html(email);
                $("#verifyModal").modal("show");
                $(".need-verify-msg").addClass('d-none')
                $(".resend-verify-email").removeClass('d-none');

            }
            else{
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': data.token
                    }
                });
                
                THIS_CFR_TOKEN = data.token;

                $("#user_email").val(email);
                $("#user_name").val(data.name);
                $(".non_auth").removeClass("non_auth");
                $("#authModal").modal("hide");
                $(".auth_region").remove();
                $("#auth_stripe").html(`<div class="alert alert-success py-3 text-center"><i class="fa fa-check-circle"></i> You are now logged in</div>`);

                $("#login_region").html(`<div style="font-size: 16px;">Your account have been created, and you are currently logged in as  <b>${data.name}</b> (${data.email})</div>`);

                
            }
            
            toastr.success("Account created successfully!");
        },
        error: function (xhr, status, error) {
            parent.css("opacity", "1").css("pointer-events", "auto");
            parent.find(".sbutton").html(sbutton);
            render_errors(JSON.parse(xhr.responseText), 'toast', parent);
        }
	});	
}




//============= LOGIN  FUNCTION  ============================

function loginForm(event){ 
	
	event.preventDefault();

	var username = $("#username").val().trim();
	var password = $("#password").val().trim();
	var remember = $("#rememberme").prop('checked')?1:0;

	if(password.length < 2) return;	

    var quote_id = (typeof QUOTATION_ID != 'undefined') ? QUOTATION_ID : '';

	var fdata = {username,  password, remember, quote_id} ;
	
    let parent = $(event.target).closest('form');
    parent.css("opacity", "0.5").css("pointer-events", "none");
	var sbutton = parent.find(".sbutton").html(); 
	$(".form_error, .formError").remove();
	parent.find(".sbutton").html('<span class="fa fa-spin fa-spinner fa-2x"></span> Logging in...');
	


	$.ajax({
		type: "POST",
		url:   "/login",
		data: fdata,
		dataType: 'json',
		success: function(data){
			// parent.css("opacity", "1").css("pointer-events", "auto");

            if(! quote_id){
                parent.html('<div class="alert alert-success py-3"> <i class="fa fa-sign-in"></i> Logged in!  Redirecting...</div>');
                $("form").trigger('reset');
                window.location.replace("/my-account");    
            }
            else{

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': data.token
                    }
                });

                THIS_CFR_TOKEN = data.token;

                if(typeof squarePMethods  != 'undefined' || typeof paypayPMethods  != 'undefined'){
                    // setUpPayment();
                }
                else{
                //    try{ elements.fetchUpdates(); } catch(exception){}
                }

                $("#user_email").val(data.email);
                $("#user_name").val(data.name);
                $(".auth_region").remove();
                $(".non_auth").removeClass("non_auth");
                $("#authModal").modal("hide");
                toastr.success("Logged in successfully!");
                $("#auth_stripe").html(`<div class="alert alert-success py-3 text-center"><i class="fa fa-check-circle"></i> You are now logged in</div>`);
                
                $("#login_region").html(`<div style="font-size: 16px;">You are currently logged in as  <b>${data.name}</b>  <span class="verify_email_address">(${data.email})</span></div>`);

                if( data.email_verified_at ){
                    EMAIL_VERIFICATION_STATE = true;
                }
                else{
                    $("input.verify_email_address").val(data.email)
                    $(".verify_email_address:not(input)").html(data.email);
                    $("#verifyModal").modal("show");
                    $(".need-verify-msg").addClass('d-none')
                    $(".resend-verify-email").removeClass('d-none');
                }

            }
            			
        },
        error: function (xhr, status, error) {
            parent.css("opacity", "1").css("pointer-events", "auto");
            parent.find(".sbutton").html(sbutton);
            render_errors(JSON.parse(xhr.responseText), 'toast', parent);
        }
	});	
}





//============= Forgot Pasword  ============================

function forgotForm(event){ 
	
	event.preventDefault();

	var email = $("#fgt_email").val();

	var fdata = {email} ;
	
    let parent = $(event.target).closest('form');
    parent.css("opacity", "0.5").css("pointer-events", "none");
	var sbutton = parent.find(".sbutton").html(); 
	$(".form_error, .formError").remove();
	parent.find(".sbutton").html('<span class="fa fa-spin fa-spinner fa-2x"></span> Submitting...');
	


	$.ajax({
		type: "POST",
		url:   "/forgot-password",
		data: fdata,
		dataType: 'json',
		success: function(data){

            parent.html('<div class="alert alert-success py-5 text-center"> <i class="fa fa-4x fa-check-circle"></i><br> A password reset link has been sent to your email address. follow the link in order to reset your password</div>');
            			
            parent.css("opacity", "1").css("pointer-events", "auto");
            
        },
        error: function (xhr, status, error) {
            parent.css("opacity", "1").css("pointer-events", "auto");
            parent.find(".sbutton").html(sbutton);
            render_errors(JSON.parse(xhr.responseText), 'toast', parent);
        }
	});	
}


//============= Reset Pasword  ============================

function resetForm(event){ 
	
	event.preventDefault();

	var password = $("#password").val();
    var token = $("#token").val();
    var confirm_password = $("#confirm_password").val();

	var fdata = {password, confirm_password, token} ;
	
    let parent = $(event.target).closest('form');
    parent.css("opacity", "0.5").css("pointer-events", "none");
	var sbutton = parent.find(".sbutton").html(); 
	$(".form_error, .formError").remove();
	parent.find(".sbutton").html('<span class="fa fa-spin fa-spinner fa-2x"></span> Submitting...');
	


	$.ajax({
		type: "POST",
		url:   "/reset-password",
		data: fdata,
		dataType: 'json',
		success: function(data){

            parent.css("opacity", "1").css("pointer-events", "auto");

            parent.html('<div class="alert alert-success py-5 text-center"> <i class="fa fa-4x fa-check-circle"></i><br> Password Reset Successfully</div>');
            			
        },
        error: function (xhr, status, error) {
            parent.css("opacity", "1").css("pointer-events", "auto");
            parent.find(".sbutton").html(sbutton);
            render_errors(JSON.parse(xhr.responseText), 'toast', parent);
        }
	});	
}



//============= QUOTE SUMMITION  FUNCTION  ============================
$("form input, form select, form textarea").on('input', function(){
    let id = $(this).prop('id')
    let val = $(this).val();
    if(id && val && val.length >= 2){
        $(`#error_n_${id}`).remove();
    }
})

function quoteForm(event){ 
	
	event.preventDefault();

    let parent = $(event.target).closest('form');

    let fdata = parent.serialize();
    
    parent.css("opacity", "0.5").css("pointer-events", "none");
	var sbutton = parent.find(".sbutton").html(); 
	$(".form_error, .formError").remove();
	parent.find(".sbutton").html('<span class="fa fa-spin fa-spinner fa-2x"></span> Submitting...');
	

	$.ajax({
		type: "POST",
		url:   "/order/get-quote",
		data: fdata,
		dataType: 'json',
		success: function(data){
            
            console.log('after success: ', data)
            // parent.css("opacity", "1").css("pointer-events", "auto");
            // $("form").trigger('reset');
			// window.location.replace("/checkout");

        },
        error: function (xhr, status, error) {
            console.log('after error: ', error)
            parent.css("opacity", "1").css("pointer-events", "auto");
            parent.find(".sbutton").html(sbutton);
            render_errors(JSON.parse(xhr.responseText), 'toast', parent);
        }
	});	
}


//============= APPLY  PROMO FUNCTION  ============================

function  applyPromoCode(event){ 
	
	event.preventDefault();

	var promo_code = $("#promo_code").val().trim();

    let fdata = {promo_code, id: QUOTATION_ID}

    let parent = $(event.target).closest('form');
    parent.css("opacity", "0.5").css("pointer-events", "none");
    var sbutton = parent.find(".sbutton").html(); 
	$(".form_error, .formError").remove();
	parent.find(".sbutton").html('<span class="fa fa-spin fa-spinner"></span>');
	
	$.ajax({
		type: "POST",
		url:   "/promo-code",
		data: fdata,
		dataType: 'json',
		success: function(data){
			parent.css("opacity", "1").css("pointer-events", "auto");
            parent.find(".sbutton").html(sbutton);

            let amount = data.amount;

            if(amount == CPW_AMOUNT_DEFAULT){
                CPW_AMOUNT = CPW_AMOUNT_DEFAULT;
                $('.cpw_amount').html(numberFormat(CPW_AMOUNT_DEFAULT));

                $('.cpw_total').html(numberFormat(CPW_AMOUNT_DEFAULT));
                $('.cpw_discount').closest('tr').addClass('d-none');


            }
            else{
                CPW_AMOUNT = amount;
                $('.cpw_amount').html(`<strike>${numberFormat(CPW_AMOUNT_DEFAULT)}</strike> ${numberFormat(amount)}`);

                $('.cpw_total').html(numberFormat(amount));
                $('.cpw_discount').closest('tr').removeClass('d-none');

                $('.cpw_discount').html(numberFormat( parseFloat(CPW_AMOUNT_DEFAULT) - parseFloat(amount) ));

            }

            // Update the payment element price
            // elements.update({
            //     amount: parseInt(100 * amount)
            // })
            
            if('change' in data && data.change == 1){
                if(typeof squarePMethods  != 'undefined' || typeof paypayPMethods  != 'undefined'){
                    setUpPayment();
                }
                else{
                    // try{ elements.fetchUpdates(); } catch(exception){}
                }
            }

            toastr.success("Promo Code applied");

        },
        error: function (xhr, status, error) {
            parent.css("opacity", "1").css("pointer-events", "auto");
            parent.find(".sbutton").html(sbutton);
            
            CPW_AMOUNT = CPW_AMOUNT_DEFAULT;
            $('.cpw_amount').html(numberFormat(CPW_AMOUNT_DEFAULT));

            $('.cpw_total').html(numberFormat(CPW_AMOUNT_DEFAULT));
            $('.cpw_discount').closest('tr').addClass('d-none');

            // Update the payment element price
            // elements.update({
            //     amount: parseInt(100 * CPW_AMOUNT_DEFAULT)
            // });
            
            let errorData = JSON.parse(xhr.responseText);

            if('change' in errorData && errorData.change == 1){
                if(typeof squarePMethods  != 'undefined' || typeof paypayPMethods  != 'undefined'){
                    setUpPayment();
                }
                else{
                    // try{ elements.fetchUpdates(); } catch(exception){}
                }
            }

            render_errors(errorData, 'toast', parent);
        }
	});	
}




//============= UPDATE PROFILE  FUNCTION  ============================

function updateProfile(event){ 
	
	event.preventDefault();

    let parent = $(event.target).closest('form');

    let fdata = parent.serialize();

    parent.css("opacity", "0.5").css("pointer-events", "none");
	var sbutton = parent.find(".sbutton").html(); 
	$(".form_error, .formError").remove();
	parent.find(".sbutton").html('<span class="fa fa-spin fa-spinner fa-2x"></span> Submitting...');
	
	$.ajax({
		type: "POST",
		url:   "/my-account/edit-account",
		data: fdata,
		dataType: 'json',
		success: function(data){
			parent.css("opacity", "1").css("pointer-events", "auto");
            parent.find(".sbutton").html(sbutton);
            toastr.success("Profile updated  successfully!");
            parent.find(`input[type="password"]`).val('');
        },
        error: function (xhr, status, error) {
            parent.css("opacity", "1").css("pointer-events", "auto");
            parent.find(".sbutton").html(sbutton);
            render_errors(JSON.parse(xhr.responseText), 'toast', parent);
        }
	});	
}



//============= CONTACT SUMMITION  FUNCTION  ============================

function contactForm(event){ 
	
	event.preventDefault();

    let parent = $(event.target).closest('form');

    let fdata = parent.serialize();
    
    parent.css("opacity", "0.5").css("pointer-events", "none");
	var sbutton = parent.find(".sbutton").html(); 
	$(".form_error, .formError").remove();
	parent.find(".sbutton").html('<span class="fa fa-spin fa-spinner fa-2x"></span> Submitting...');
	

	$.ajax({
		type: "POST",
		url:   "/contact",
		data: fdata,
		dataType: 'json',
		success: function(data){
            parent.css("opacity", "1").css("pointer-events", "auto");
            $("form").trigger('reset');

            parent.find(".sbutton").html('<div class="alert alert-success"><span class="fa fa-spin fa-check-circle"></span> Thanks for reaching out to us. We will respond to you very soon. </div>');
            toastr.success("Mesage sent!");
			
            
        },
        error: function (xhr, status, error) {
            parent.css("opacity", "1").css("pointer-events", "auto");
            parent.find(".sbutton").html(sbutton);
            render_errors(JSON.parse(xhr.responseText), 'toast', parent);
        }
	});	
}



//============= CONTACT SUMMITION  FUNCTION  ============================

function contactReply(event){ 
	
	event.preventDefault();

    let parent = $(event.target).closest('form');

    let fdata = parent.serialize();
    
    parent.css("opacity", "0.5").css("pointer-events", "none");
	var sbutton = parent.find(".sbutton").html(); 
	$(".form_error, .formError").remove();
	parent.find(".sbutton").html('<span class="fa fa-spin fa-spinner fa-2x"></span> Submitting...');
	
    let message = $("#message").val();
    let user_name = $("#user_name").val();

	$.ajax({
		type: "POST",
		url:   "/ticket/reply",
		data: fdata,
		dataType: 'json',
		success: function(data){


                let html = `<div class="mb-3 p-3 rounded border bg-white border-secondary">
                        <div class="mb-1 small fw-bold">
                            ${user_name}
                            <span class="text-muted fw-normal ms-2">
                                just now
                            </span>
                        </div>
                        <div class="text-body">
                         ${message.replace(/\n/g, '<br>')}
                        </div>
                    </div>`;

                    $(".message_cont").append(html);



            parent.css("opacity", "1").css("pointer-events", "auto");
            $("form").trigger('reset');
            parent.find(".sbutton").html('<div class="alert alert-success"><span class="fa fa-spin fa-check-circle"></span> Message received. We will respond to your message soon </div>');
            toastr.success("Mesage sent!");
			
        },
        error: function (xhr, status, error) {
            parent.css("opacity", "1").css("pointer-events", "auto");
            parent.find(".sbutton").html(sbutton);
            render_errors(JSON.parse(xhr.responseText), 'toast', parent);
        }
	});	
}

//============= SEND CLAIM  FUNCTION  ============================

function sendClaim(event){ 
	
	event.preventDefault();

    let parent = $(event.target).closest('form');

    let fdata = parent.serialize();
    
    parent.css("opacity", "0.5").css("pointer-events", "none");
	var sbutton = parent.find(".sbutton").html(); 
	$(".form_error, .formError").remove();
	parent.find(".sbutton").html('<span class="fa fa-spin fa-spinner fa-2x"></span> Submitting...');
	

	$.ajax({
		type: "POST",
		url:   "/claims",
		data: fdata,
		dataType: 'json',
		success: function(data){
            parent.css("opacity", "1").css("pointer-events", "auto");
            $("form").trigger('reset');

            parent.find(".sbutton").html('<div class="alert alert-success"><span class="fa fa-spin fa-check-circle"></span> Thanks for reaching out to us. We will respond to you very soon. </div>');
            toastr.success("Mesage sent!");
			
            
        },
        error: function (xhr, status, error) {
            parent.css("opacity", "1").css("pointer-events", "auto");
            parent.find(".sbutton").html(sbutton);
            render_errors(JSON.parse(xhr.responseText), 'toast', parent);
        }
	});	
}








//======== FUNCTION TO RENDER SERVER SIDE ERRORS ============
function  render_errors(data, ch,  parent, shouldAlertAll){

    if (typeof shouldAlertAll === "undefined") {
        shouldAlertAll = false;
    }
    
    genric_error = [];
    if('errors' in data){
        for(key in data.errors){
            data.errors[key].forEach(error => {
                //Detamin the element from key
                // Should be ID or Class
                
                if( typeof parent != 'undefined' &&  parent.find(`input[name="${key}"], select[name="${key}"], textarea[name="${key}"]`).length && !shouldAlertAll){
                    if(parent.find(`input[name="${key}"], select[name="${key}"], textarea[name="${key}"]`).closest('.input-group').length){
                        parent.find(`input[name="${key}"], select[name="${key}"], textarea[name="${key}"]`).closest('.input-group').after('<div id="error_n_'+key+'" class="form_error">'+error+'</div>');
                    }else{
                        parent.find(`input[name="${key}"], select[name="${key}"], textarea[name="${key}"]`).after('<div id="error_n_'+key+'" class="form_error">'+error+'</div>');
                    }
                }

                else if($('#'+key).length && !shouldAlertAll){
                    if($('#'+key).closest('.input-group')){
                        $('#'+key).closest('.input-group').after('<div id="error_n_'+key+'" class="form_error">'+error+'</div>');
                    }else{
                        $('#'+key).after('<div id="error_n_'+key+'" class="form_error">'+error+'</div>');
                    }
                } 
                else{
                    if($('.'+key).length && !shouldAlertAll){
                        if($('#'+key).closest('.input-group')){
                            $('.'+key).closest('.input-group').after('<div id="error_n_'+key+'" class="form_error">'+error+'</div>');;
                        }else{
                            $('.'+key).after('<div id="error_n_'+key+'" class="form_error">'+error+'</div>');
                        }
                    }
                    //Determin the structure.
                    // addresses.0.city
                    else if(key.includes('.') && !shouldAlertAll){  //Like addresses.0.state
                        var ctree = key.split('.');
                        var element = $('.' +ctree[0] + ' .'+ctree[2] + ':eq('+ctree[1]+')');
                    
                        if(element.length){
                            element.after('<div class="form_error">'+error+'</div>');
                        }else{
                            // Log a generic error
                            genric_error.push(key+": "+error);
                        }
                    }
                    else{
                        genric_error.push(key+": "+error);
                    }
                } 
            });
        }
    }

    if('message' in data){
        let msgg = data.message; 
        if(msgg.includes('CSRF token')){
          msgg = "This page has expired. Please refresh";
        }
        genric_error.push(msgg);
    }

    if(genric_error.length > 0){
        genric_msg = genric_error.join('<br>');
        if(ch == 'toast'){
            toastr.error(genric_msg);
        }
        else if(ch == 'alert'){
            myalert('<span class="text-danger">'+genric_error+'</span>');
        }
        else if(ch == ""){
            $("#form_error").html(genric_msg);
        }
        else{
            $(ch).html('<div class="text-danger">'+genric_msg+'</div>');
        }
    }    
}


// OVERLAY BLOCKER WHEN MAKING AJAX REQUEST
function genric_block(state, msg){
    $("#genric_block .message_n").html(msg);
    if(state){
        $("#genric_block").css('display', 'flex');
    }
    else{
        $("#genric_block").css('display', 'none');
    }
}

$(document).ready(function(){

    // If there is email verification requirement
    if(verifyModalAction == "show_verify_window"){
        $("#verifyModal").modal("show");
        $(".verify_email_address:not(input)").html(curVerifyEmail);
        $("input.verify_email_address").val(curVerifyEmail);

        $(".need-verify-msg").addClass('d-none')
        $(".resend-verify-email").removeClass('d-none');
    }
    else if(verifyModalAction == "show_resend_window"){
        $("#resendCodeModal").modal("show");
        $(".verify_email_address:not(input)").html(curVerifyEmail);
        $("input.verify_email_address").val(curVerifyEmail);

        $(".need-verify-msg").removeClass('d-none')
        $(".resend-verify-email").addClass('d-none');
    } 


    $('.digit-input').on('input', function() {
        const $this = $(this);
        const value = $this.val();
        if (value) {
            $this.addClass('filled');
            $this.next('.digit-input').focus();
        } else {
            $this.removeClass('filled');
        }
    });

    $('.digit-input').on('paste', function(e) {
        const clipboardData = e.originalEvent.clipboardData || window.clipboardData;
        const pastedData = clipboardData.getData('Text').trim();

        if (pastedData.length === 6) {
            const inputs = $('.digit-input');
            inputs.each((index, input) => {
                $(input).val(pastedData[index]).addClass('filled');
            });
        }
    });

    $('.digit-input').on('keydown', function(e) {
        if (e.key === "Backspace" && !$(this).val()) {
            $(this).prev('.digit-input').focus();
        }
    });


    //====  TOGGLE INPUT TO SHOW/HIDE PASSWORD FIELD   =======
    $(".action.pwd").on('click', function(){
        let parent = $(this).closest('.input-group');
        if(parent.find('.action .fa-eye').length){
            parent.find('.form-control').attr('type', 'input');
        }
        else{
            parent.find('.form-control').attr('type', 'password');
        }
        parent.find('.action i').toggleClass('fa-eye fa-eye-slash');
    })

    //  Set Active nav item
    let active_nav = $(".main-sidebar").data("active_nav"); 
    $(".main-sidebar").find(`.nav-item.${active_nav}`).addClass('active');


    // CONTENT PAGE NAV
    $(".content-nav li > a").on('click', function(){
        let this_tab = $(this);
        let target = $(this).data('target');
        if($(`#${target}`).hasClass('active_content')){
            return;
        }
        $('.active_content').fadeOut(500, function(){
            $('.active_content').removeClass('active_content');
            $(".content-nav li > a").removeClass('active');
            this_tab.addClass('active');
            $(`#${target}`).fadeIn(500);
            $(`#${target}`).addClass('active_content');
        });

    });

    $(".view_summ_action").on('click', function(){
        let al = $(this).closest('a');
        if($(".quotation_summ").hasClass('d-none')){
            al.html(`Hide Summary  Details <i class="fa fa-caret-up"></i></a>`);
        }
        else{
            al.html(`View Summary  Details <i class="fa fa-caret-down"></i></a>`);
        }
        $(".quotation_summ").toggleClass('d-none');
    })

    if($("#promo_code").val()){
        $("#promo_code").closest("form").trigger('submit');
    }

});


function logoutAccount(event){
    event.preventDefault();
    confirmAction("Confirm", "Do you want to logout?", function(){
        $("#confirmModal").modal('hide');
        window.location.href = "/user/logout";
    });
}

// A function to confirm action like deletes
function confirmAction(title, message, callback, type){

    if( typeof type == "undefined"){
        type = "bg-warning";
    }

    $("#confirmModal .modal-content").removeClass().addClass('modal-content').addClass(type);

    $("#confirmModal .modal-header h4").html(title);
    $("#confirmModal .modal-body").html(message);
    $("#confirmModal .btn-action").off().on('click', callback);
    $("#confirmModal").modal('show');
  }



  function setStartEndDateTime(){
    $("#start_date").val("");
    $("#start_time").val("");
    $("#end_date").val("");
    $("#end_time").val("");

    jQuery('button.single_add_to_cart_button.button').css('pointer-events', 'none');
    setTimeout(() => {
        get_quote();
    }, 100);

    if(! $(`#start_minute`).val()){
        return;
    }

    
    $("#start_time").val(`${$("#start_hour").val()}:${$(`#start_minute`).val()}`);
    $("#start_date").val($("#start_daten").val().replace('xx', ''));

    let current_date_time = `${$("#start_daten").val().replace('xx', '')} ${$("#start_hour").val()}:${$(`#start_minute`).val()}`;

    let type = $('#duration_type').val();
    let period = $('#duration_period').val();
    if(type && period){

        period = parseInt(period);


        end_date_time = getEndDateTime(current_date_time, period, type);
        end_date_time_arr = end_date_time.split(" ");

        $("#end_date").val(end_date_time_arr[0]);
        $("#end_time").val(end_date_time_arr[1]);


    }

    setTimeout(() => {
        get_quote();
    }, 100);

  }


  function getEndDateTime(currentDateTime, period, periodType) {
    // Parse the input date in Y-m-d H:i format
    const [datePart, timePart] = currentDateTime.split(' ');
    const [year, month, day] = datePart.split('-').map(Number);
    const [hour, minute] = timePart.split(':').map(Number);
  
    let endDateTime = new Date(year, month - 1, day, hour, minute);
  
    switch (periodType.toLowerCase()) {
      case 'hours':
        endDateTime.setHours(endDateTime.getHours() + period);
        break;
      case 'days':
        endDateTime.setDate(endDateTime.getDate() + period);
        break;
      case 'weeks':
        endDateTime.setDate(endDateTime.getDate() + period * 7);
        break;
      default:
        console.error("Invalid period type. Use 'hours', 'days', or 'weeks'.");
        return null;
    }
  
    return formatDateTime(endDateTime);
  }
  
  function formatDateTime(date) {
    const y = date.getFullYear();
    const m = String(date.getMonth() + 1).padStart(2, '0');
    const d = String(date.getDate()).padStart(2, '0');
    const H = String(date.getHours()).padStart(2, '0');
    const i = String(date.getMinutes()).padStart(2, '0');
    return `${y}-${m}-${d} ${H}:${i}`;
  }
  

  jQuery(document).ready(function() {

    if($('#occupation').length && $('#start_daten').length){

        let immVal = $(`#start_daten option[data-class="immediate"]`).val();
        
        $('.selectizer').selectize({});

        
        // let $address_ctl = $('#address').selectize({
        //     valueField: 'address_selector',
        //     labelField: 'address_selector',
        //     searchField: 'address_selector',
        //     options: [], // Initialize as empty
        //     create: false,
        //     maxItems: 1,
        //     placeholder: 'Select Address'
        // });
        // address_control = $address_ctl[0].selectize;
          
        
        // address_control.on('change', function(value) {
        //     if (value) {
        //       const item = address_control.options[value];
        //       $('#postcode').val(item.postcode || ''); // Set the postcode value
        //     }
        // });

        $('#address').on('change', function(){
            if ($('#address').val()) {
                let postcode = $('#address option:selected').data('postcode');
                if(postcode){
                    $('#postcode').data('postcode', postcode); // Set the postcode value
                    $('#postcode').val(postcode);
                }
            }
        });

        $('#postcode').on('keyup', function(){
            let postcode = $('#postcode').val().trim();
            if (postcode && postcode == $('#postcode').data('postcode')) {
                $("#address").closest('div').removeClass('d-none');
            }
            else{
                $("#address").closest('div').addClass('d-none');
            }
        });



        // let $occupation_ctl = $('#occupation').selectize({
        //     options: occupation_list,
        //     valueField: 'desc', // Use desc as the value
        //     labelField: 'desc', // Show desc as the display label
        //     searchField: 'desc',
        //     create: false,
        //     maxItems: 1,
        //     placeholder: 'Search Occupation'
        // });
        // occupation_control = $occupation_ctl[0].selectize;
        
        let $occupation_ctl = $('#occupation').selectize({
            valueField: 'desc',
            labelField: 'desc',
            searchField: 'desc',
            create: false,
            maxItems: 1,
            placeholder: 'Search Occupation',
            preload: false, // Don't preload options
            load: function(query, callback) {
              // Clear options if query is empty
            //   if (!query.length && typeof bstartDate == "undefined") {
            //     this.clearOptions(); // Clears all options when search is empty
            //     callback([]);
            //     return;
            //   }
          
              // Simulate filtering or AJAX call here
              setTimeout(() => {
                const filtered = occupation_list.filter(item => item.desc.toLowerCase().includes(query.toLowerCase()));
                
                // Add the filtered options
                this.clearOptions(); // Clear existing options
                filtered.forEach(item => {
                  this.addOption(item); // Add the new filtered option
                });
                
                callback(filtered); // Return the filtered options
              }, 300); // Simulate delay for async-like behavior
            }
          });
          
          occupation_control = $occupation_ctl[0].selectize;
          
          // Listen for input events on the search field
        $('#occupation-selectized').on('input', function() {
        if (!this.value.trim()) {
            // If search input is empty, clear the options
            setTimeout(function(){
                occupation_control.clearOptions();
                occupation_control.refreshOptions();
            }, 200);
        }
        });

          
          
          
          

        
        $('#start_daten'). on('change', function () {
            let selectedDate = $(this).val();

            let isImmediate = false;

            if(selectedDate.includes('xx')){
                selectedDate = selectedDate.replace('xx', '');
                isImmediate = true;
                $("#hh_mm_line").addClass('d-none');
            }
            else{
                $("#hh_mm_line").removeClass('d-none');
            }
        
            // Set loading message in start_hour select
            $("#start_hour").html('<option value="">Loading hours...</option>');            
            $("#start_minute").html('<option value="">Select hour first</option>');

            if( ! selectedDate){
                setStartEndDateTime();
                return;
            }

        
            if (selectedDate) {
                $.ajax({
                    url: '/get-available-hours',
                    method: 'GET',
                    data: { date: selectedDate },
                    dataType: 'json',
                    success: function (response) {
                        // Clear loading option and populate new options
                        $("#start_hour").html('<option value="">Hour</option>');

                        let firstVal = "";
                        let kx = 0;
        
                        if (response && response.hours) {
                            response.hours.forEach(function (hour) {
                                $("#start_hour").append(`<option value="${hour}">${hour}</option>`);
                                if(kx == 0){
                                    firstVal = hour;
                                    kx++;
                                }
                            });
                        }

                        if(isImmediate){
                            if(! firstVal){
                                $("#hh_mm_line").removeClass('d-none');
                            }
                            $("#start_hour").val(firstVal).trigger('change');
                        }

                        setStartEndDateTime();
                    },
                    error: function () {
                        $("#hh_mm_line").removeClass('d-none');
                        $("#start_hour").html('<option value="">Error loading hours</option>');
                        setStartEndDateTime();
                    }
                });
            }
        });

        $('#start_hour').on('change', function () {
            let selectedDate = $('#start_daten').val();
            let selectedHour = $(this).val();
        
            let isImmediate = false;

            if(selectedDate.includes('xx')){
                selectedDate = selectedDate.replace('xx', '');
                isImmediate = true;
                $("#hh_mm_line").addClass('d-none');
            }
            else{
                $("#hh_mm_line").removeClass('d-none');
            }

            // Set loading message in minute select
            $(`#start_minute`).html(`<option value="">Select hour first</option>`);

            if(! selectedHour){
                setStartEndDateTime();
                return;
            }
            // Set loading message in minute select
            $(`#start_minute`).html(`<option value="">Loading minutes...</option>`);

        
            if (selectedDate && selectedHour !== "") {
                $.ajax({
                    url: '/get-available-minutes',
                    method: 'GET',
                    data: { date: selectedDate, hour: selectedHour },
                    dataType: 'json',
                    success: function (response) {
                        // Clear loading option and populate new options
                        $(`#start_minute`).html(`<option value="">Minute</option>`);
        
                        let firstVal = "";
                        let kx = 0;

                        if (response && response.minutes) {
                            response.minutes.forEach(function (minute) {
                                $(`#start_minute`).append(`<option value="${minute}">${minute}</option>`);
                                if(kx == 0){
                                    firstVal = minute;
                                    kx++;
                                }
                            });
                        }

                        if(response.minutes.length == 0){
                            $("#start_hour").val("");
                            $(`#start_hour option[value="${selectedHour}"]`).remove();
                            let firstOptionValue = $(`#start_hour option:eq(1)`).val();
                            $(`#start_hour`).val(firstOptionValue).trigger('change');
                        }

                        if(isImmediate && response.minutes.length  > 0){
                            if(! firstVal){
                                $("#hh_mm_line").removeClass('d-none');
                            }
                            $(`#start_minute`).val(firstVal).trigger('change');
                        }
                        setStartEndDateTime();

                    },
                    error: function () {
                        $("#hh_mm_line").removeClass('d-none');
                        $(`#start_minute`).html(`<option value="">Error loading minutes</option>`);
                        setStartEndDateTime();
                    }
                });
            }
        });
        $('#start_minute').on('change', function () {
            setStartEndDateTime();
        });
        

        $("#start_daten").val(immVal).trigger('change');




        $('.duration_type a').on('click', function(e){
            
            let types = {
                hours : {"1": "1 hour", "3": "3 hours", "5": "5 hours", "o": "Other"},
                days : {"1": "1 day", "3": "3 days", "5": "5 days", "o": "Other"},
                weeks : {"1": "1 week", "2": "2 weeks", "3": "3 weeks", "4": "4 weeks"}
            };

            e.preventDefault();
            $('.duration_type a').addClass('disabled ').removeClass('active');
            $(this).addClass('active').removeClass('disabled');
            let type = $(this).data('type');

            $('#duration_type').val(type);

            $('.duration_period').html('').removeClass('d-none');
            $('#duration_period').html('').addClass('d-none');
            let val = "";
            for(key in  types[type]){
                val = types[type][key];
                $('.duration_period').append(`<div class="col-6 col-sm-3"><a class="quick-btn  ${ ((type != "weeks" && key == "1") || (type == "weeks" && key == "1"))? 'active':'disabled' }  quick" data-period="${key}">${val}</a></div>`);
            }
            if(type == "hours"){
                for(let i = 1; i < 24; i++){
                    $('#duration_period').append(`<option ${(i == 1)?'selected':''} value="${i}"> ${i} ${(i > 1)?'hours':'hour'} </option>`);
                }
            }
            else if(type == "days"){
                for(let i = 1; i < 29; i++){
                    $('#duration_period').append(`<option ${(i == 1)?'selected':''} value="${i}"> ${i} ${(i > 1)?'days':'day'} </option>`);
                }
            }
            else if(type == "weeks"){
                for(let i = 1; i < 5; i++){
                    $('#duration_period').append(`<option ${(i == 1)?'selected':''} value="${i}"> ${i} ${(i > 1)?'days':'day'} </option>`);
                }
            }

            setStartEndDateTime();

        });

        $('.duration_period').on('click', 'a', function(e){
            e.preventDefault();
            $('.duration_period a').addClass('disabled ').removeClass('active');
            $(this).addClass('active').removeClass('disabled');
            
            let period = $(this).data('period');
            
            if(period == "o"){
                $('#duration_period').removeClass('d-none');
                $('.duration_period').addClass('d-none');
            }
            else{
                $('.duration_period').removeClass('d-none');
                $('#duration_period').addClass('d-none');

                $('#duration_period').val(period);
            }
            setStartEndDateTime();

        });
        $('#duration_period').on('change', function(){
            setStartEndDateTime();
        });
        $("#hour_period_type")[0].click();

    }// END IF FOR QOTE PAGE



    
    
    // for submit event for home form
    jQuery('#homeformbtn').click(function(e) {
        e.preventDefault();
        jQuery('#homeform').submit();
    })



  jQuery('button.single_add_to_cart_button.button').css('pointer-events', 'none');
    


  jQuery('#get_cars').click(function() {

    let xbn = $('#get_cars').html();
    $('#get_cars').html('<i class="fa fa-spin fa-spinner"></i> Searching..');
    $('#get_cars').prop('disabled', true);

    var reg_no = jQuery('input[name="reg_numberx"]').val();

    jQuery('input[name="vehicle_make"]').val('');
    jQuery('input[name="vehicle_model"]').val('');
    jQuery('input[name="engine_cc"]').val('');
    jQuery('input[name="reg_number"]').val('');
    jQuery('#mm_cc_line').addClass('d-none');

    jQuery.ajax({
        // url: 'https://api.checkcardetails.co.uk/vehicledata/vehicleregistration?apikey=4e0ee9aa7d54be20f04268e70dfee472&vrm=' +
        //     reg_no,
        url: "/checkcardetails/" + reg_no,
        type: "POST",
        success: function(result) {

            $('#get_cars').html(xbn);
            $('#get_cars').prop('disabled', false);

            let eng_make = result.make;
            let eng_model = result.model;
            var eng_cc = result.engineCapacity;

            jQuery('#mm_cc_line').removeClass('d-none');
            
            jQuery('input[name="vehicle_make"]').val(eng_make);
            jQuery('input[name="vehicle_model"]').val(eng_model);
            jQuery('input[name="engine_cc"]').val(eng_cc);
            jQuery('input[name="reg_number"]').val(reg_no)

            get_quote();
        },
        error: function (xhr, status, error) {
            $('#get_cars').html(xbn);
            $('#get_cars').prop('disabled', false);
            
            toastr.error("No data for this registration number");

            setTimeout(function(){
                render_errors(JSON.parse(xhr.responseText), 'toast', parent);
            }, 1000);
            

        }
        
    });
});



    // Get Price
    jQuery('input[name="date_of_birth"]')
    .change(function() {
        jQuery('button.single_add_to_cart_button.button').css('pointer-events', 'none');
        setTimeout(() => {
            get_quote();
        }, 100);
    });

    // camel case names
    jQuery(document).ready(function() {
        jQuery('input[name="first_name"],  input[name="middle_name"], input[name="last_name"]').on('input',
            function() {
                var inputValue = jQuery(this).val();
                var lettersOnly = inputValue.replace(/[^a-zA-Z]/g, '');
                var capitalizedValue = lettersOnly.charAt(0).toUpperCase() + lettersOnly.slice(1);
                jQuery(this).val(capitalizedValue);
            });

        jQuery('input[name="reg_numberx"]').on('input', function() {
            var reg_number = jQuery(this).val();
            var upperCaseValue = reg_number.toUpperCase();
            jQuery(this).val(upperCaseValue);
        });
    });


    



    /// FOr SIngle Pagte Redirect

    if (jQuery('.xcontainer').hasClass('single-product')) {

        var urlParams = new URLSearchParams(window.location.search);

        // Function to format date as DD/MM/YYYY
        function formatDate(date) {
            var day = date.getDate().toString().padStart(2, '0');
            var month = (date.getMonth() + 1).toString().padStart(2, '0'); // Months are 0-based
            var year = date.getFullYear();
            return day + '-' + month + '-' + year;
        }

        // for reg number
        if (urlParams.has('reg_no')) {

            var reg_no = urlParams.get('reg_no');
            jQuery('input[name="reg_numberx"]').val(reg_no.toUpperCase());
            if (reg_no != '') {
                jQuery.ajax({
                    // url: 'https://api.checkcardetails.co.uk/vehicledata/vehicleregistration?apikey=4e0ee9aa7d54be20f04268e70dfee472&vrm=' +
                    //     reg_no,
                    url: "/checkcardetails/" + reg_no,
                    type: "POST",
                    success: function(result) {

                        let eng_make = result.make;
                        let eng_model = result.model;
                        var eng_cc = result.engineCapacity;
                        jQuery('input[name="vehicle_make"]').val(eng_make);
                        jQuery('input[name="vehicle_model"]').val(eng_model);
                        jQuery('input[name="engine_cc"]').val(eng_cc);

                        jQuery('input[name="reg_number"]').val(reg_no);
                        jQuery('#mm_cc_line').removeClass('d-none');

                    },
                    error: function (xhr, status, error) {
                        // $('#get_cars').prop('disabled', false);
                        // toastr.error("No data for this registration number");
                    }
                });
            }
        }


 



    jQuery('.single-product input[name="date_of_birth"]').on('change', function() {
        console.log('test'); 

        const dateOfBirthInput = jQuery('input[name="date_of_birth"]').val();

        // Split the input into day, month, and year
        const parts = dateOfBirthInput.split('-');
        if (parts.length !== 3) {
            if(dateOfBirthInput){
                window.alert('Invalid date format. Please use DD-MM-YYYY.');
            }
            jQuery('input[name="date_of_birth"]').val('');
            return;
        }

        const day = parseInt(parts[0], 10);
        const month = parseInt(parts[1], 10) - 1; // Subtract 1 because months are zero-based
        const year = parseInt(parts[2], 10);

        const userDateInput = new Date(year, month, day);
        const currentDate = new Date();
        const eighteenYearsAgo = new Date();
        eighteenYearsAgo.setFullYear(currentDate.getFullYear() - 17);

        if (userDateInput > eighteenYearsAgo) {
            // Show a popup window if the user is under 18
            window.alert('You are not old enough to use our services.');
            // Clear the input field
            jQuery('.single-product input[name="date_of_birth"]').val('');
        }
    });

}



});



var get_quote = function () {
    // Clear the CPW input field
        jQuery('#cpw_val').html('');

        $(".cwp-input-wrapper").addClass('d-none');

        var dob = jQuery('input[name="date_of_birth"]').val();
        var registration_no = jQuery('input[name="reg_number"]').val();

        if (dob  && registration_no) {
            // Parse the date of birth in DD-MM-YYYY format
            var dobParts = dob.split('-');
            var dobDate = new Date(dobParts[2], dobParts[1] - 1, dobParts[0]); // Month is 0-based

            // Calculate age
            var currentDate = new Date();
            var ageInMilliseconds = currentDate - dobDate;
            var ageInYears = ageInMilliseconds / (365.25 * 24 * 60 * 60 * 1000);
            var age = Math.floor(ageInYears);

            // Parse start and end dates and times
            var user_start_date = jQuery('input[name="start_date"]').val();
            var user_start_time = jQuery('input[name="start_time"]').val();
            var user_end_date = jQuery('input[name="end_date"]').val();
            var user_end_time = jQuery('input[name="end_time"]').val();

            // console.log(user_start_date , user_start_time, user_end_date, user_end_time);

            if (
                user_start_date !== '' &&
                user_start_time !== '' &&
                user_end_date !== '' &&
                user_end_time !== ''
            ) {
                var startDateParts = user_start_date.split('-');
                var endDateParts = user_end_date.split('-');
                var startTimeParts = user_start_time.split(':');
                var endTimeParts = user_end_time.split(':');

                var startDateTime = new Date(
                    startDateParts[0],
                    startDateParts[1] - 1,
                    startDateParts[2],
                    startTimeParts[0],
                    startTimeParts[1],
                    startTimeParts[2] || 0
                );
                var endDateTime = new Date(
                    endDateParts[0],
                    endDateParts[1] - 1,
                    endDateParts[2],
                    endTimeParts[0],
                    endTimeParts[1],
                    endTimeParts[2] || 0
                );


                 // Calculate time difference
                 var timeDifferenceMs = endDateTime - startDateTime;
                 if (timeDifferenceMs > 0) {
                     var minutesDifference = timeDifferenceMs / (1000 * 60);
                    var hoursDifference = minutesDifference / 60;

                    var dayAvailable = Math.floor(hoursDifference / 24);
                    var hourAvailable = Math.ceil(hoursDifference - dayAvailable * 24);
                    var minuteAvailable = Math.ceil(minutesDifference - (dayAvailable * 24 * 60) - (hourAvailable * 60));


                     if(dayAvailable > 28){

                        jQuery('input[name="end_date"]').val("");
                        jQuery('input[name="end_time"]').val("");
                        
                        toastr.error("Maximum order period can't be more than 4 weeks (28 days)");
                        return;
                    }


                    console.log(minuteAvailable, hourAvailable, dayAvailable, age);

                    // Example usage
                    let final_price = getQuote(minuteAvailable, hourAvailable, dayAvailable, age);

                    
                    // Update CPW field and enable button if valid
                    if (jQuery.isNumeric(final_price)) {
                        
                        $(".cwp-input-wrapper").removeClass('d-none');

                        jQuery('#cpw_val').html(final_price.toFixed(2));
                        jQuery('#cpw').val(final_price.toFixed(2)).trigger('change');
                        jQuery('button.single_add_to_cart_button.button').css('pointer-events', 'fill');
                    }

                     

                 }

            }
        }
    };
    //func ends



function showPolicyDetails(id){

    $("#policyModal").modal('show');
    $("#policyModal td, #update_price").html('<i class="fa fa-spin fa-spinner"></i>');

    $.ajax({
		type: "GET",
		url:   "/my-account/order/"+id,
		dataType: 'json',
		success: function(data){
            for(key in data.data){
                $(`#${key}`).html(data.data[key]);
            }


            $("#vehicle_make").html(` ${data.data.vehicle_make} ${data.data.vehicle_model}`);

            $("#start_time").html( reverseDate(data.data.start_date).replace("-", "/").replace("-", "/") + " " +  convertTo12HourFormat(data.data.start_time) );
            $("#end_time").html( reverseDate(data.data.end_date).replace("-", "/").replace("-", "/") + " " +  convertTo12HourFormat(data.data.end_time) );


            $("#address").html(`${data.data.address}, ${data.data.postcode.toUpperCase()}`);

            if(data.data.middle_name){
                $("#tr_middle_name").removeClass('d-none');
            }
            else{
                $("#tr_middle_name").addClass('d-none');
            }

            if(data.data.cpw != data.data.update_price){

                $(".cpw_subtotal").html("£" + numberFormat(data.data.cpw));
                $(".cpw_subtotal").closest('tr').removeClass('d-none');

                $('.cpw_discount').html("£" + numberFormat( parseFloat(data.data.cpw) - parseFloat(data.data.update_price) ));
                $(".cpw_discount").closest('tr').removeClass('d-none');

            }
            else{
                $(".cpw_subtotal").closest('tr').addClass('d-none');
                $(".cpw_discount").closest('tr').addClass('d-none');
            }
            $(".cpw_total").html("£" + numberFormat(data.data.update_price));

        },
        error: function (xhr, status, error) {
            $("#policyModal td, #update_price").html('');
            render_errors(JSON.parse(xhr.responseText), 'toast', parent);
        }
    });



}



// Helper function to format numbers
function numberFormat(value) {
    return new Intl.NumberFormat('en-US', { 
        minimumFractionDigits: 2, 
        maximumFractionDigits: 2 
    }).format(value);
}


function reverseDate(value) {

    try{
        let datas = value.split("-");
        return datas[2]+"-"+datas[1]+'-'+datas[0];
    }
    catch(exception){
        return value;
    }

}

function validateRegNumber(event){

    event.preventDefault();

    var reg_no = jQuery('input[name="reg_no"]').val();
    
    let parent = $(event.target).closest('form');
    parent.css("opacity", "0.5").css("pointer-events", "none");
	$(".form_error").remove();



	$.ajax({
		// type: "GET",
		// url: 'https://api.checkcardetails.co.uk/vehicledata/vehicleregistration?apikey=4e0ee9aa7d54be20f04268e70dfee472&vrm=' + reg_no,
		url: "/checkcardetails/" + reg_no,
        type: "POST",
        dataType: 'json',
		success: function(data){
            parent[0].submit();
            parent.css("opacity", "1").css("pointer-events", "auto");

        },
        error: function (xhr, status, error) {
            parent.after('<div class="form_error">Vehicle with the provided parameters was not found.</div>')
            parent.css("opacity", "1").css("pointer-events", "auto");
            render_errors(JSON.parse(xhr.responseText), 'toast', parent);
        }
	});	
}


function setAddressTown(){

    let town = $('#address option:selected').data('town');
    $("#town").val(town);

}

function searchAddress(event){

    event.preventDefault();
    
    let postcode =  $('#postcode').val().trim();

    // Define the regex for a valid UK postcode
    const postcodeRegex = /^([A-Z]{1,2}[0-9][0-9A-Z]?|[A-Z][A-Z][0-9][A-Z0-9]|[A-Z]{1,2}[0-9][A-Z]|GIR 0AA) ?[0-9][A-Z]{2}$/i;

    // Trim any spaces and validate against the regex
    postcode = postcode.trim().toUpperCase();

    if (! postcodeRegex.test(postcode)) {
        
        toastr.error('Not a valid UK postcode');

        return;
    }

    $('#postcode').closest('.row').css('pointer-events', 'none').css('opacity', '0.4');
    $('#postcode').closest('.row').find('button i').prop('disabled', true);//  attr('class', 'fa fa-spin fa-spinner');

    $("#address").closest('div').addClass('d-none');

    $.ajax({
        url: '/search-address',
        method: 'GET',
        data: { postcode },
        dataType: 'json',
        success: function (response) {

            $('#postcode').closest('.row').css('pointer-events', 'auto').css('opacity', '1');
            $('#postcode').closest('.row').find('button i').prop('disabled', false); //.attr('class', 'fa fa-search');

            // Clear loading option and populate new options
            // address_control.clearOptions();
            $("#address").html('<option value=""></option>');
            $("#postcode").data("postcode", "");

            if (response && response.addresses) {
                response.addresses.forEach(function (address) {
                    // address_control.addOption({
                    //     address_selector: address.address_selector,
                    //     postcode: address.postcode
                    // });
                    $("#address").append(`<option data-town="${address.postTown}" data-postcode="${address.postcode}" value="${address.address_selector}">${address.address_selector}</option>`);
                });
                // address_control.refreshOptions();

            }
            if(response.addresses.length > 0){
                $("#postcode").data("postcode", postcode);
                $("#address").closest('div').removeClass('d-none');
            }
            else{
                toastr.error("No address found for this postcode");
            }

        },
        error: function () {

            $('#postcode').closest('.row').css('pointer-events', 'auto').css('opacity', '1');
            $('#postcode').closest('.row').find('button i').prop('disabled', false); 
            
            toastr.error("Error getting address. If this issue continue. Kindly report to us. Thanks");
        }
    });
}


  

function clearAddress(){
 
    $("#postcode").parent().removeClass('d-none');
    $("#address").parent().addClass('d-none');
    $("#town").val("");
}



function viewPolicy(event){

    event.preventDefault();

    let policy_number = $('input[name="policy_number"]').val().trim();
    let date_of_birth = validateDate();
    let last_name = $('input[name="last_name"]').val().trim();
    let postcode = $('input[name="postcode"]').val().trim();
    if(!date_of_birth){
        $("#yyyy")[0].focus();
        return;
    }

    let fdata = {policy_number, date_of_birth, last_name, postcode};

    let sbutton = $("#sbutton").html();
    $("#sbutton").html('<i class="fa fa-spin fa-spinner"></i> Retrieving details');

    $.ajax({
		type: "POST",
        data: fdata,
		url:   "/view-order",
		dataType: 'json',
		success: function(data){

            $("#sbutton").html(sbutton);

            if(! data.data){
                toastr.error("No order matching these details was found");
                return;
            }
            for(key in data.data){
                $(`#${key}`).html(data.data[key]);
            }


            $("#start_date").html( reverseDate(data.data.start_date).replace("-", "/").replace("-", "/") );
            $("#end_date").html(reverseDate(data.data.end_date).replace("-", "/").replace("-", "/"));
            $("#date_of_birth").html(reverseDate(data.data.date_of_birth).replace("-", "/").replace("-", "/"));


            let stime = convertTo12HourFormat(data.data.start_time);
            let etime = convertTo12HourFormat(data.data.end_time);

            $("#start_time").html(stime);
            $("#end_time").html(etime);


            $("#postcode").html(data.data.postcode.toUpperCase());


            if(data.data.middle_name){
                $("#tr_middle_name").removeClass('d-none');
            }
            else{
                $("#tr_middle_name").addClass('d-none');
            }

            if(data.data.cpw != data.data.update_price){

                $(".cpw_subtotal").html("£" + numberFormat(data.data.cpw));
                $(".cpw_subtotal").closest('.line').removeClass('d-none');

                $('.cpw_discount').html("£" + numberFormat( parseFloat(data.data.cpw) - parseFloat(data.data.update_price) ));
                $(".cpw_discount").closest('.line').removeClass('d-none');

            }
            else{
                $(".cpw_subtotal").closest('.line').addClass('d-none');
                $(".cpw_discount").closest('.line').addClass('d-none');
            }
            $(".cpw_total").html("£" + numberFormat(data.data.update_price));

            $(".vform").addClass('d-none');
            $("#policy_details").removeClass('d-none');


            
            let tamount_st = numberFormat(data.data.update_price).split(".");

            $("#cpw_total").html(`£${tamount_st[0]}.${tamount_st[1]}`);

            $(".vform").addClass('d-none');
            $("#policy_details").removeClass('d-none');

            if(! data.data.contact_number){
            
                $("#contact_number").html("N/A");
            
            }
            if(! data.data.vehicle_model == "N/A"){
                $("#vehicle_model").html("");
            }

            if("user" in data.data){
                $("#email").html(data.data.user.email);
            }

            let pper = calculatePPercentage(data);
            $(".progress_main > div").css('width', `${pper[0]}%`);
            if(pper[0] >= 100){
                $("#policy_status").html('Expired Order');
                $("#time_diff").html("");
            }
            else if(pper[0] == 0){
                $("#policy_status").html('Upcoming Order');
                $("#time_diff").html(pper[1]);
            }
            else{
                $("#policy_status").html('Active Order');
                $("#time_diff").html(pper[1]);
            }


        },
        error: function (xhr, status, error) {
            $("#sbutton").html(sbutton);
            render_errors(JSON.parse(xhr.responseText), 'toast', parent);
        }
    });

}



// $(document).ready(function(){
//     viewPolicyNB();
// })

function viewPolicyNB(){
    let policy_number = $('input[name="policy_number"]').val().trim();
    let date_of_birth = "";
    let last_name = $('input[name="last_name"]').val().trim();
    let postcode = $('input[name="postcode"]').val().trim();
    
    let fdata = {policy_number, date_of_birth, last_name, postcode};

    let sbutton = $("#sbutton").html();
    $("#sbutton").html('<i class="fa fa-spin fa-spinner"></i> Retrieving details');

    $.ajax({
		type: "POST",
        data: fdata,
		url:   "/view-order",
		dataType: 'json',
		success: function(data){

            $("#sbutton").html(sbutton);

            if(! data.data){
                toastr.error("No order matching these details was found");
                return;
            }
            for(key in data.data){
                $(`#${key}`).html(data.data[key]);
            }


            $("#start_date").html( reverseDate(data.data.start_date).replace("-", "/").replace("-", "/") );
            $("#end_date").html(reverseDate(data.data.end_date).replace("-", "/").replace("-", "/"));
            $("#date_of_birth").html(reverseDate(data.data.date_of_birth).replace("-", "/").replace("-", "/"));


            let stime = convertTo12HourFormat(data.data.start_time);
            let etime = convertTo12HourFormat(data.data.end_time);

            $("#start_time").html(stime);
            $("#end_time").html(etime);


            $("#postcode").html(data.data.postcode.toUpperCase());


            if(data.data.middle_name){
                $("#tr_middle_name").removeClass('d-none');
            }
            else{
                $("#tr_middle_name").addClass('d-none');
            }

            if(data.data.cpw != data.data.update_price){

                $(".cpw_subtotal").html("£" + numberFormat(data.data.cpw));
                $(".cpw_subtotal").closest('.line').removeClass('d-none');

                $('.cpw_discount').html("£" + numberFormat( parseFloat(data.data.cpw) - parseFloat(data.data.update_price) ));
                $(".cpw_discount").closest('.line').removeClass('d-none');

            }
            else{
                $(".cpw_subtotal").closest('.line').addClass('d-none');
                $(".cpw_discount").closest('.line').addClass('d-none');
            }

            let tamount_st = numberFormat(data.data.update_price).split(".");

            $("#cpw_total").html(`£${tamount_st[0]}.${tamount_st[1]}`);

            $(".vform").addClass('d-none');
            $("#policy_details").removeClass('d-none');

            $("#email").html(data.data.user.email);

            let pper = calculatePPercentage(data);
            $(".progress_main > div").css('width', `${pper[0]}%`);
            if(pper[0] >= 100){
                $("#policy_status").html('Expired Order');
                $("#time_diff").html("");
            }
            else if(pper[0] == 0){
                $("#policy_status").html('Upcoming Order');
                $("#time_diff").html(pper[1]);
            }
            else{
                $("#policy_status").html('Active Order');
                $("#time_diff").html(pper[1]);
            }


        },
        error: function (xhr, status, error) {
            $("#sbutton").html(sbutton);
            render_errors(JSON.parse(xhr.responseText), 'toast', parent);
        }
    });
}


function calculatePPercentage(data) {

    const now = new Date();


    const ukOffset = now.toLocaleString('en-GB', { timeZone: 'Europe/London', timeZoneName: 'short' }).includes('BST') ? '+01:00' : '+00:00';

    // Parse date and time strings from the server (assumed to be in UK time)
    const startDateTime = new Date(`${data.data.start_date}T${data.data.start_time}${ukOffset}`);
    const endDateTime = new Date(`${data.data.end_date}T${data.data.end_time}${ukOffset}`);
    
    if (isNaN(startDateTime.getTime()) || isNaN(endDateTime.getTime())) {
        console.error('Invalid date or time format');
        return [0, 'Invalid date/time'];
    }

    if (now >= endDateTime) {
        return [100, 'date expired'];
    }

    if (now <= startDateTime) {
        const timeDiff = formatTimeDiff(startDateTime - now);
        return [0, `Starts in ${timeDiff}`];
    }

    const totalDuration = endDateTime - startDateTime;
    const elapsed = now - startDateTime;
    const percentage = (elapsed / totalDuration) * 100;

    const timeRemaining = formatTimeDiff(endDateTime - now);
    return [Math.min(100, Math.max(0, percentage)), `Ends in ${timeRemaining}`];
}

function formatTimeDiff(ms) {
    const totalSeconds = Math.floor(ms / 1000);
    const days = Math.floor(totalSeconds / 86400);
    const hours = Math.floor((totalSeconds % 86400) / 3600);
    const minutes = Math.floor((totalSeconds % 3600) / 60);

    const pad = (num) => String(num).padStart(2, '0');

    if (days > 0) {
        return `${days}d ${pad(hours)}h ${pad(minutes)}m`;
    } else if (hours > 0) {
        return `${pad(hours)}h ${pad(minutes)}m`;
    } else {
        return `${pad(minutes)}m`;
    }
}





function viewDocument(event, url){

    event.preventDefault();
    
    $("#baseForm").prop('action', url);
    $("#baseForm").prop('method', 'POST');
    $("#baseForm").prop('target', '_blank');
    $("#baseForm").removeAttr('onsubmit');
    
    $("#baseForm #sbutton button")[0].click();

}



function verifyEmail(event){
    
    event.preventDefault();

    let code = "";
    $(".digit6 input").each(function(){
        code += $(this).val();
    });
    let elm =  $("#verifyModal");
    areaOverlay(elm, true, ' Verifying...');

    let fdata = {code};
    
    $(".form_error, .formError").remove();
    $.ajax({
        type: "POST",
        url: '/auth/verify-email',
        data: fdata,
         success: function(data){
            areaOverlay(elm, false, '');	
            $("#form").trigger('reset');
            
            elm.find('.modal-body').html(`
            <div class="alert alert-success text-center py-3 px-2">
                <span class="far fa-check-circle fa-3x"></span><br><br>
                <h3>Email verified</h3>
                <br>
                <button type="button" class="btn btn-primary mt-3" data-bs-dismiss="modal">Close</button>
            </div>
            `);


            if(typeof squarePMethods  != 'undefined' || typeof paypayPMethods  != 'undefined'){
                EMAIL_VERIFICATION_STATE = true;
                setUpPayment();
            }
            else if(typeof squarePMethods){
                EMAIL_VERIFICATION_STATE = true;
                // try{ elements.fetchUpdates(); } catch(exception){}
            }


        },
        error: function (xhr, status, error, message) {
            areaOverlay(elm, false, '');
            render_errors(JSON.parse(xhr.responseText), 'toast');
        }
    });
}



function resendVerificationCode(event, state){
    event.preventDefault();
    let email = $("input.verify_email_address").val();
    let elm =  $("body");
    areaOverlay(elm, true, ' Sending verification code..');
    let fdata = {email};

    $(".digit6 input").val('');

    $(".form_error, .formError").remove();
    $.ajax({
        type: "POST",
        url: '/auth/resend-verification-code',
        data: fdata,
         success: function(data){
            areaOverlay(elm, false, '');
            $(".verify_email_address:not(input)").html(email);
            $("#verifyModal .digits6 input").val("");

            $(".modal").modal("hide");
            setTimeout(function(){
                $("#verifyModal").modal('show');
            }, 1000);
            if(state == 1){
                setTimeout(function(){
                    $("#resendRespModal").modal('show');
                }, 1000);
            }
        },
        error: function (xhr, status, error, message) {
            areaOverlay(elm, false, '');
            render_errors(JSON.parse(xhr.responseText), 'toast');
        }
    });
}


function changeVerificationEmail(event){

    $(".modal").modal("hide");
    setTimeout(function(){
        $("#resendCodeModal").modal("show");
        $(".need-verify-msg").addClass('d-none')
        $(".resend-verify-email").removeClass('d-none');
    }, 1000);
}


function areaOverlay(elm, state, message){

    if(typeof message == 'undefined'){
      if(state){
        elm.prepend(`<div class="area-overlay"><span class="fa fa-spin fa-spinner"></span></div>`);
        // elm.css('opacity', "0.8");
      }
      else{
        elm.find(".area-overlay").remove();
        elm.css('opacity', "1");
      }
    }
    else{
      if(state){
        $("body").prepend(`<div class="area-overlay" style="position:fixed"><span class="fa fa-spin fa-spinner"></span> <div class="message">${message}</div></div>`);
        // $("body").css("opacity", "0.8");
      }
      else{
        $(".area-overlay").remove();
        $("body").css("opacity", "1");
      }
    }
  }
  



function convertTo12HourFormat(time) {
    const [hours, minutes] = time.split(':');
    let period = 'AM';
  
    let hour = parseInt(hours, 10);
    if (hour >= 12) {
      period = 'PM';
      hour = hour > 12 ? hour - 12 : hour;
    }
    if (hour === 0) {
      hour = 12;
    }
  
    return `${hour}:${minutes} ${period}`;
  }
  