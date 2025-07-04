$(document).ready(function () {

    $('.modal').on("hidden.bs.modal", function (e) { 
        if ($('.modal:visible').length) { 
            $('body').addClass('modal-open');
        }
    });

    




    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            // 'X-XSRF-TOKEN': getCookie('XSRF-TOKEN')
        }
    });


    // ------------------------------------------------------- //
    // Custom Scrollbar
    // ------------------------------------------------------ //

    if ($(window).outerWidth() > 992) {
        $("nav.side-navbar").mCustomScrollbar({
            scrollInertia: 200
        });
    }

    // Main Template Color
    var brandPrimary = '#33b35a';

    // ------------------------------------------------------- //
    // Side Navbar Functionality
    // ------------------------------------------------------ //
    $('#toggle-btn').on('click', function (e) {

        e.preventDefault();

        if ($(window).outerWidth() > 1194) {
            $('nav.side-navbar').toggleClass('shrink');
            $('.page').toggleClass('active');
        } else {
            $('nav.side-navbar').toggleClass('show-sm');
            $('.page').toggleClass('active-sm');
        }
    });

   
    // ------------------------------------------------------- //
    // Universal Form Validation
    // ------------------------------------------------------ //

    $('.form-validate').each(function() {  
        $(this).validate({
            errorElement: "div",
            errorClass: 'is-invalid',
            validClass: 'is-valid',
            ignore: ':hidden:not(.summernote),.note-editable.card-block',
            errorPlacement: function (error, element) {
                // Add the `invalid-feedback` class to the error element
                error.addClass("invalid-feedback");
                //console.log(element);
                if (element.prop("type") === "checkbox") {
                    error.insertAfter(element.siblings("label"));
                } 
                else {
                    error.insertAfter(element);
                }
            }
        });
    });
    // ------------------------------------------------------- //
    // Material Inputs
    // ------------------------------------------------------ //

    var materialInputs = $('input.input-material');

    // activate labels for prefilled values
    materialInputs.filter(function () {
        return $(this).val() !== "";
    }).siblings('.label-material').addClass('active');

    // move label on focus
    materialInputs.on('focus', function () {
        $(this).siblings('.label-material').addClass('active');
    });

    // remove/keep label on blur
    materialInputs.on('blur', function () {
        $(this).siblings('.label-material').removeClass('active');

        if ($(this).val() !== '') {
            $(this).siblings('.label-material').addClass('active');
        } else {
            $(this).siblings('.label-material').removeClass('active');
        }
    });

    
    // ------------------------------------------------------- //
    // External links to new window
    // ------------------------------------------------------ //

    $('.external').on('click', function (e) {

        e.preventDefault();
        window.open($(this).attr("href"));
    });

   
});






function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
}

function  render_errors(data, ch, shouldAlertAll){

    if (typeof shouldAlertAll === "undefined") {
        shouldAlertAll = false;
    }
    
    genric_error = [];
    if('errors' in data){
        for(key in data.errors){
            data.errors[key].forEach(error => {
                //Detamin the element from key
                // Should be ID or Class
                if($('#'+key).length && !shouldAlertAll){
                    $('#'+key).after('<div class="form_error">'+error+'</div>');
                } 
                else{
                    if($('.'+key).length && !shouldAlertAll){
                        $('.'+key).after('<div class="form_error">'+error+'</div>');
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


function genric_block(state, msg){
    $("#genric_block .message_n").html(msg);
    if(state){
        $("#genric_block").css('display', 'flex');
    }
    else{
        $("#genric_block").css('display', 'none');
    }
}



function show_user(event,uid, tab){
    event.preventDefault();
    // alert(tab)
    if(typeof tab == 'undefined'){
        tab = 'users';
    }
	$("#modal_info .modal-body").html('<div class="text-center"><span class="fa  fa-5x fa-spin fa-spinner"></span></div>');
	$.ajax({
		type: "GET",
		url:  "/admin/app-user/"+uid+"?tab="+tab,
		success: function(data){

			let table = '<table class="table table-striped">';
			for(key in data){
                if(key == 'addresses'){
                   data.addresses.forEach(function(add){
                       var address_line = add.state + ", " + add.city + ", " + add.address;
                       var add_key = "Address";
                       if(add.is_main == 1){
                         add_key = "Main Address";
                       }
                       table += '<tr><th>'+add_key+'</th><td>'+address_line+'</td></tr>';
                   })
                }
                else{ 
				    table += '<tr><th>'+key+'</th><td>'+data[key]+'</td></tr>';
                }
			}
			table += '</table>';
			$("#modal_info .modal-body").html(table);
			$("#modal_info").modal('show');
		}
	});

}

function setMoreOrLess(){
    var showChar = 20; // Number of characters before "more" link appears
  
    $('.more_or_less').each(function() {
      var content = $(this).text();
      if (content.length > showChar) {
        var c = content.substr(0, showChar);
        var h = content.substr(showChar, content.length - showChar);
        var html = c + '<span class="more-text">' + h + '</span> <span class="show-more"> ...plus</span><span class="show-less"> ...moins</span>';
        $(this).html(html);
  
  
        $(this).find('.show-more').click(function() {
          $(this).siblings('.more-text').css('display', 'inline');
          $(this).hide();
          $(this).siblings('.show-less').show();
        });
        $(this).find('.show-less').click(function() {
          $(this).siblings('.more-text').css('display', 'none');
          $(this).hide();
          $(this).siblings('.show-more').show();
        });
        $(this).find('.more-text').hide();
        $(this).find('.show-less').hide();
      }
      $(this).removeClass('more_or_less');
    });
  }
  