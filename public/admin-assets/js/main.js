//========   AJAX SETUP FOR  CSRF TOKEN FOR ALL POST REQUEST  ===
$.ajaxSetup({
  headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
  }
});







$(document).ready(function(){

  $('.modal').on("hidden.bs.modal", function (e) { 
      if ($('.modal:visible').length) { 
          $('body').addClass('modal-open');
      }
  });    
  
});







function forgot_pw_form(event){
  event.preventDefault();

  let email = $("#fp_email").val().trim();
    
  let sbutton = $("#fpassword_sbutton").html(); //grab the initial content
  $(".form_error").remove();
    $("#fpassword_sbutton").html('<span class="fa fa-spin fa-spinner fa-2x"></span> Please wait...');

    let fdata = {ch: 'forgot_password', email, lag_index:0};
  $.ajax({
  type: "POST",
  url:   "./connect/signing.php",
  data: fdata,
  success: function(data){	
        if(data.substr(0,4) == 'PASS'){
            $("#fpassword_form form").html('<div class="alert alert-success text-center py-5"><span class="fa fa-check-circle fa-4x"></span> <br> <p style="font-size:16px;">An password reset link has been sent to your email address; Check your email inbox and follow the link. </p></div>');
        }
        else{
            $("#fpassword_sbutton").html(sbutton);
            try{
                let rdata = JSON.parse(data);
                rdata.forEach(function(row){
                    $("#"+row[0]).after('<div class="form_error">'+row[1]+'</div>')
                });
            }catch(exception){
                toastr.error(data);
            }
            
        }
    }
    });

}



function reset_pw_form(event){
  event.preventDefault();

  let new_password = $("#new_password").val().trim();
    let rnew_password = $("#rnew_password").val().trim();
    let token = $("#token").val();
    
  let sbutton = $("#sbutton").html(); //grab the initial content
  $(".form_error").remove();
    $("#sbutton").html('<span class="fa fa-spin fa-spinner fa-2x"></span> Please wait...');

    let fdata = {ch: 'reset_password', new_password, rnew_password, token, lag_index:0};
  $.ajax({
  type: "POST",
  url:   "./connect/signing.php",
  data: fdata,
  success: function(data){	
        if(data.substr(0,4) == 'PASS'){
            $("#rp_form").html('<div class="alert alert-success text-center py-5"><span class="fa fa-check-circle fa-4x"></span> <br> <p style="font-size:16px;"> Password has been reset successfully <br><br><a href="./">Proceed to login</a> </p></div>');
        }
        else{
            $("#sbutton").html(sbutton);
            try{
                let rdata = JSON.parse(data);
                rdata.forEach(function(row){ 
                    $("#"+row[0]).after('<div class="form_error">'+row[1]+'</div>')
                });
            }catch(exception){
                toastr.error(data);
            }
            
        }
    }
    });

}



function update_password(event){
  event.preventDefault();

  let old_password = $("#old_password").val().trim();
    let new_password = $("#new_password").val().trim();
    let confirm_password = $("#confirm_password").val().trim();

    
  var loaderHtml =  successMsg =  url = updating_msg = "";
  successMsg = 'Password Updated Successfully';
  loaderHtml = '<span class="fa fa-spin fa-spinner fa-2x"></span> Please waite...';
  url = "/admin/update-password";
    
  let sbutton = $("#pwd_sbutton").html(); //grab the initial content
  $(".form_error").remove();
    $("#pwd_sbutton").html(loaderHtml);

    let fdata = {old_password, new_password, confirm_password};
  $.ajax({
  type: "POST",
  url,
  data: fdata,
  success: function(data){
        $("#pwd_sbutton").html(sbutton);	
        toastr.success(successMsg);
        $("#login_form")[0].reset();    
    },
  error: function (xhr, status, error, message) {
    $("#pwd_sbutton").html(sbutton);
    render_errors(JSON.parse(xhr.responseText), 'toast');
  }
    });	
}

function logoutAccount(event){

  event.preventDefault();
  $("#modal_alert  .modal-body").html('<h4 style="margin-top:30px">Do you want to logout?</h4>');
  $("#modal_alert  .action_btn").show().html('Yes! logout now').off().on('click', function(){
    window.location.href = "/admin/logout"		
  });
  $("#modal_alert").modal('show');

}







//======== FUNCTION TO RENDER SERVER SIDE ERRORS ============
function  render_errors(data, ch,  shouldAlertAll){

  let parent = $("body");

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
