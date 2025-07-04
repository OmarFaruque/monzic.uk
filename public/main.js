const $ = jQuery;

//========   AJAX SETUP FOR  CSRF TOKEN FOR ALL POST REQUEST  ===
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
    }
});




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

                $("#user_email").val(data.email);
                $("#user_name").val(data.name);
                $(".auth_region").remove();
                $(".non_auth").removeClass("non_auth");
                $("#authModal").modal("hide");
                toastr.success("Logged in successfully!");
                $("#auth_stripe").html(`<div class="alert alert-success py-3 text-center"><i class="fa fa-check-circle"></i> You are now logged in</div>`);
                
                $("#login_region").html(`<div style="font-size: 16px;">You are currently logged in as  <b>${data.name}</b> (${data.email})</div>`);
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
            console.log('success')
            //parent.css("opacity", "1").css("pointer-events", "auto");
            $("form").trigger('reset');
			// window.location.replace("/checkout");

        },
        error: function (xhr, status, error) {
            console.log('errors');
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
            elements.update({
                amount: parseInt(100 * amount)
            })
            CLIENT_SECRET = "";

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
            elements.update({
                amount: parseInt(100 * CPW_AMOUNT_DEFAULT)
            })
            CLIENT_SECRET = "";

            render_errors(JSON.parse(xhr.responseText), 'toast', parent);
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
                        parent.find(`input[name="${key}"], select[name="${key}"], textarea[name="${key}"]`).closest('.input-group').after('<div class="form_error">'+error+'</div>');
                    }else{
                        parent.find(`input[name="${key}"], select[name="${key}"], textarea[name="${key}"]`).after('<div class="form_error">'+error+'</div>');
                    }
                }

                else if($('#'+key).length && !shouldAlertAll){
                    if($('#'+key).closest('.input-group')){
                        $('#'+key).closest('.input-group').after('<div class="form_error">'+error+'</div>');
                    }else{
                        $('#'+key).after('<div class="form_error">'+error+'</div>');
                    }
                } 
                else{
                    if($('.'+key).length && !shouldAlertAll){
                        if($('#'+key).closest('.input-group')){
                            $('.'+key).closest('.input-group').after('<div class="form_error">'+error+'</div>');;
                        }else{
                            $('.'+key).after('<div class="form_error">'+error+'</div>');
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





  jQuery(document).ready(function() {



    
    // for submit event for home form
    jQuery('#homeformbtn').click(function(e) {
        e.preventDefault();
        jQuery('#homeform').submit();
    })



    jQuery('button.single_add_to_cart_button.button').css('pointer-events', 'none');
    var get_quote = function () {
    // Clear the CPW input field
        jQuery('input[name="cpw"]').val('');

        var dob = jQuery('input[name="date_of_birth"]').val();
        var registration_no = jQuery('input[name="reg_number"]').val();

        if (dob !== '' && registration_no !== '') {
            // Parse the date of birth in DD-MM-YYYY format
            var dobParts = dob.split('-');
            var dobDate = new Date(dobParts[2], dobParts[1] - 1, dobParts[0]); // Month is 0-based

            // Calculate age
            var currentDate = new Date();
            var ageInMilliseconds = currentDate - dobDate;
            var ageInYears = ageInMilliseconds / (365.25 * 24 * 60 * 60 * 1000);
            var age = Math.floor(ageInYears);

            // Base prices
            let basePrice = 22.58;
            let basePriceHour = 13.72;
            let basePricePerHour = 0.38; //1.89;


            // Parse start and end dates and times
            var user_start_date = jQuery('input[name="start_date"]').val();
            var user_start_time = jQuery('input[name="start_time"]').val();
            var user_end_date = jQuery('input[name="end_date"]').val();
            var user_end_time = jQuery('input[name="end_time"]').val();

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
                    startDateParts[2],
                    startDateParts[1] - 1,
                    startDateParts[0],
                    startTimeParts[0],
                    startTimeParts[1],
                    startTimeParts[2] || 0
                );
                var endDateTime = new Date(
                    endDateParts[2],
                    endDateParts[1] - 1,
                    endDateParts[0],
                    endTimeParts[0],
                    endTimeParts[1],
                    endTimeParts[2] || 0
                );

                // Calculate time difference
                var timeDifferenceMs = endDateTime - startDateTime;
                if (timeDifferenceMs > 0) {
                    var minutesDifference = timeDifferenceMs / (1000 * 60);
                    var hoursDifference = minutesDifference / 60;
                    var daysDifference = Math.floor(hoursDifference / 24);
                    var remainingHours = Math.ceil(hoursDifference - daysDifference * 24);

                    if(daysDifference > 5 && daysDifference <= 7){ // 1 week
                        daysDifference = 6;
                        remainingHours = 0;
                    }
                    else if(daysDifference > 7 && daysDifference <= 14){ // 2 weeks
                        daysDifference =  (daysDifference > 9)? 9 : daysDifference ; // Can be 8
                        remainingHours = 0;
                    }
                    else if(daysDifference > 14 && daysDifference <= 21){ // 3 weeks
                        daysDifference = 11;
                        remainingHours = 0;
                    }
                    else if(daysDifference > 21 && daysDifference <= 28){ // 4 weeks
                        daysDifference = 13;
                        remainingHours = 0;
                    }
                    else if(daysDifference > 28){

                        jQuery('input[name="end_date"]').val("");
                        jQuery('input[name="end_time"]').val("");
                        
                        toastr.error("Maximum policy period can't be more than 4 weeks (28 days)");
                        return;
                    }

                    // alert(`${daysDifference} ---  ${remainingHours}`);

                    // Calculate final price
                    let final_price = 0;
                    if(daysDifference == 0){
                        final_price = basePriceHour + basePricePerHour * (remainingHours - 1);
                    }
                    else if (daysDifference > 0) {
                        final_price =  (daysDifference * basePrice) + (basePricePerHour * remainingHours);
                    }

                    
                    // Discount for age
                    if (age >= 18 && age <= 20) {
                        final_price -= (age - 17) * 0.2;
                    }
                    else if (age >= 21 && age <= 30) {
                        final_price -= (age - 17) * 0.4;
                    } 
                    else if (age > 30) {
                        let sage = (age > 50)? 50 : age;  
                        final_price -= (sage - 17) * 0.2;
                    }


                    // Update CPW field and enable button if valid
                    if (jQuery.isNumeric(final_price)) {
                        jQuery('input[name="cpw"]').val(final_price.toFixed(2));
                        jQuery('button.single_add_to_cart_button.button').css('pointer-events', 'fill');
                    }
                }
            }
        }
    };
    //func ends



  jQuery('#get_cars').click(function() {
    var reg_no = jQuery('input[name="reg_number"]').val();
    jQuery.ajax({
        url: 'https://api.checkcardetails.co.uk/vehicledata/vehicleregistration?apikey=4e0ee9aa7d54be20f04268e70dfee472&vrm=' +
            reg_no,
        success: function(result) {

            let eng_make = result.make;
            let eng_model = result.model;
            var eng_cc = result.engineCapacity;

            jQuery('input[name="vehicle_make"]').val(eng_make);
            jQuery('input[name="vehicle_model"]').val(eng_model);
            jQuery('input[name="engine_cc"]').val(eng_cc);
            get_quote();
        }
    });
});



    // Get Price
    jQuery(
        'input[name="engine_cc"],input[name="start_date"],input[name="start_time"],input[name="end_date"],input[name="end_time"],input[name="date_of_birth"]')
    .change(function() {
        jQuery('button.single_add_to_cart_button.button').css('pointer-events', 'none');
        setTimeout(() => {
            get_quote();
        }, 100);
    })
    jQuery('a.quick-btn').click(function() {
    jQuery('button.single_add_to_cart_button.button').css('pointer-events', 'none');
    setTimeout(() => {
        get_quote();
    }, 100);
    })


    jQuery('input[name="start_time"]').timepicker({
        'timeFormat': 'H:i:s', // 24-hour format with seconds
        'step': 5 // Interval of 5 minutes
    });
    
    jQuery('input[name="end_time"]').timepicker({
        'timeFormat': 'H:i:s', // 24-hour format with seconds
        'step': 5 // Interval of 5 minutes
    });

    // For Date Selector
    jQuery('a.quick-btn').click(function() {
        jQuery(this).addClass('active').removeClass('disabled');
        jQuery(this).siblings('.quick-btn').addClass('disabled ').removeClass('active');
        let dayGaps = parseInt(jQuery(this).attr('data-gap'));

        if (dayGaps > 0) {
            var startDate = new Date();
            startDate.setDate(startDate.getDate());
            var updatedstartDate = (startDate.getDate()).toString().padStart(2, '0') + '-' + (
                    startDate.getMonth() + 1).toString().padStart(2, '0') + '-' + startDate
                .getFullYear();
            jQuery('input[name="start_date"]').val(updatedstartDate);

            var userStartTime = jQuery('input[name="start_time"]').val();
            // if (userStartTime === '') {
                startDate.setMinutes(startDate.getMinutes() +
                5); // Set start time 5 minutes in the future
                jQuery('input[name="start_time"]').val(startDate.getHours().toString().padStart(2,
                        '0') + ':' + startDate.getMinutes().toString().padStart(2, '0') + ':' +
                    startDate.getSeconds().toString().padStart(2, '0'));
            // }

            var endDate = new Date(startDate);
            endDate.setDate(endDate.getDate() + dayGaps);

            var maxEndDate = new Date(startDate);
            maxEndDate.setDate(maxEndDate.getDate() + 28);

            if (endDate > maxEndDate) {
                endDate = maxEndDate;
            }

            var updatedEndDate = (endDate.getDate()).toString().padStart(2, '0') + '-' + (endDate
                .getMonth() + 1).toString().padStart(2, '0') + '-' + endDate.getFullYear();
            jQuery('input[name="end_date"]').val(updatedEndDate);
            jQuery('input[name="end_time"]').val(startDate.getHours().toString().padStart(2, '0') +
                ':' + startDate.getMinutes().toString().padStart(2, '0') + ':' + startDate
                .getSeconds().toString().padStart(2, '0'));
        } else {
            jQuery('input[name="start_date"]').val('');
            jQuery('input[name="end_date"]').val('');
            jQuery('input[name="start_time"]').val('');
            jQuery('input[name="end_time"]').val('');
        }
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

        jQuery('input[name="reg_number"]').on('input', function() {
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
            jQuery('input[name="reg_number"]').val(reg_no);
            if (reg_no != '') {
                jQuery.ajax({
                    url: 'https://api.checkcardetails.co.uk/vehicledata/vehicleregistration?apikey=4e0ee9aa7d54be20f04268e70dfee472&vrm=' +
                        reg_no,
                    success: function(result) {

                        let eng_make = result.make;
                        let eng_model = result.model;
                        var eng_cc = result.engineCapacity;
                        jQuery('input[name="vehicle_make"]').val(eng_make);
                        jQuery('input[name="vehicle_model"]').val(eng_model);
                        jQuery('input[name="engine_cc"]').val(eng_cc);
                    }
                });
            }
        }


        

        // for days and weeks
        if (urlParams.has('cstm_day')) {
            var dayGaps = parseInt(urlParams.get('cstm_day'));
            if (dayGaps > 0) {
                var startDate = new Date();
                startDate.setDate(startDate.getDate());
                var updatedStartDate = formatDate(startDate);
                jQuery('input[name="start_date"]').val(updatedStartDate);
                jQuery('input[name="start_time"]').val(startDate.getHours() + ':' + startDate.getMinutes() +
                    ':' + startDate.getSeconds());
                var endDate = new Date(startDate);
                endDate.setDate(endDate.getDate() + dayGaps);

                var maxEndDate = new Date(startDate);
                maxEndDate.setDate(maxEndDate.getDate() + 28);

                if (endDate > maxEndDate) {
                    endDate = maxEndDate;
                }

                var updatedEndDate = formatDate(endDate);
                jQuery('input[name="end_date"]').val(updatedEndDate);
                jQuery('input[name="end_time"]').val(startDate.getHours() + ':' + startDate.getMinutes() +
                    ':' + startDate.getSeconds());
            }
        }

        // for hours
        if (urlParams.has('cstm_hour')) {
            var hourGaps = parseInt(urlParams.get('cstm_hour')); // Parse as integer
            if (hourGaps > 0) {
                // Current date and time
                var currentDate = new Date();
                var updatedstartDate = formatDate(currentDate);

                var hours = currentDate.getHours().toString().padStart(2, '0');
                var minutes = currentDate.getMinutes().toString().padStart(2, '0');
                var seconds = currentDate.getSeconds().toString().padStart(2, '0');
                var updatedstartTime = hours + ':' + minutes + ':' + seconds;
                var userStartTime = jQuery('input[name="start_time"]').val();

                if (userStartTime === '') {
                    currentDate.setMinutes(currentDate.getMinutes() +
                    5); // Set start time 5 minutes in the future
                    jQuery('input[name="start_time"]').val(currentDate.getHours().toString().padStart(2,
                            '0') + ':' + currentDate.getMinutes().toString().padStart(2, '0') + ':' +
                        currentDate.getSeconds().toString().padStart(2, '0'));
                }

                jQuery('input[name="start_date"]').val(updatedstartDate);
                jQuery('input[name="start_time"]').val(updatedstartTime);

                // Calculate end date and time    
                var dateTime = new Date(currentDate.getTime() + hourGaps * 60 * 60 * 1000);

                var newDate = formatDate(dateTime);
                var hoursEnd = dateTime.getHours().toString().padStart(2, '0');
                var minutesEnd = dateTime.getMinutes().toString().padStart(2, '0');
                var secondsEnd = dateTime.getSeconds().toString().padStart(2, '0');
                var newTime = hoursEnd + ':' + minutesEnd + ':' + secondsEnd;

                // Check if adding hours crosses to the next day
                if (dateTime.getDate() !== currentDate.getDate()) {
                    var nextDay = new Date(currentDate);
                    nextDay.setDate(nextDay.getDate() + 1);
                    newDate = formatDate(nextDay);
                }

                jQuery('input[name="end_date"]').val(newDate);
                jQuery('input[name="end_time"]').val(newTime);
            }
        }
    }







    jQuery('.single-product input[name="date_of_birth"]').on('change', function() {
        console.log('test'); 

        const dateOfBirthInput = jQuery('input[name="date_of_birth"]').val();

        // Split the input into day, month, and year
        const parts = dateOfBirthInput.split('-');
        if (parts.length !== 3) {
            window.alert('Invalid date format. Please use DD-MM-YYYY.');
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

    jQuery('input[name="start_date"]').on('change', function() {
        checkStartDate();
    });

    jQuery('input[name="start_time"]').on('change', function() {
        checkStartDate();
    });

    function checkStartDate() {
        const userStartDate = new Date(jQuery('input[name="start_date"]').val() + ' ' + jQuery(
            'input[name="start_time"]').val());
        const currentDate = new Date();

        if (userStartDate < currentDate) {
            window.alert('Start date/time cannot be in the past.');
            jQuery('input[name="start_date"]').val('');
            jQuery('input[name="start_time"]').val('');

            // Set start time to 5 minutes in the future
            currentDate.setMinutes(currentDate.getMinutes() + 5);
            jQuery('input[name="start_date"]').val(currentDate.toLocaleDateString('en-US', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit'
            }));
            jQuery('input[name="start_time"]').val(currentDate.toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            }));
        }
    }



});


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


            $("#start_date").html(reverseDate(data.data.start_date));
            $("#end_date").html(reverseDate(data.data.end_date));
            $("#date_of_birth").html(reverseDate(data.data.date_of_birth));

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
		type: "GET",
		url: 'https://api.checkcardetails.co.uk/vehicledata/vehicleregistration?apikey=4e0ee9aa7d54be20f04268e70dfee472&vrm=' + reg_no,
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