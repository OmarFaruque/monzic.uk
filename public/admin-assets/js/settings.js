let glob_parent = null;



function updateSettings(event, np){

	let fdata = [];
	let param = '';
	let value = '';

	
	if(np == 0){
		glob_parent = $(event.target);
		event.preventDefault();
	  	$("#modal_confirm .modal-body").html("Please confirm your action"); 
	 	$("#modal_confirm").modal('show');  
		return;
 	}
	if(np == 5){
		
		let methods = [];
		$(`input[name="method"]`).each(function(){
			if($(this).prop('checked')){
				methods.push($(this).val());
			}
		})
		if(methods.length == 0){
			toastr.error("You must choose atlease one method");
			return;
		}
		$("#square_pmethods").val(JSON.stringify(methods));
		
		glob_parent = $(event.target);
		event.preventDefault();
	  	
		$("#modal_confirm .modal-body").html("Please confirm your action"); 
	 	$("#modal_confirm").modal('show');  
		return;
 	}
	else if(np == "10"){

		glob_parent = $(event.target);
		event.preventDefault();

		param = glob_parent.find('textarea').prop('name');
		value = CKEDITOR.instances[glob_parent.find('textarea').prop('id')].getData();

		fdata = {param, value};
		glob_parent.css('opacity', '0.3').css('pointer-events', 'none');


	}
	else if(np == "11"){

		glob_parent = $(event.target);
		event.preventDefault();

		param = "pags[]";
		value = "fff";
		fdata = glob_parent.serializeArray();

		fdata.push({ name: "param", value: param });
		fdata.push({ name: "value", value: value });

		glob_parent.css('opacity', '0.3').css('pointer-events', 'none');


	}
   else{
		value = "";
		param = glob_parent.find('input[name="param"]').val().trim();
		if(glob_parent.find('input[name="value"]').length){
			value = glob_parent.find('input[name="value"]').val().trim();
		}
		else if(glob_parent.find('select[name="value"]').length){
			value = glob_parent.find('select[name="value"]').val().trim();
		}
		else if(glob_parent.hasClass("is_ckedit")){
			let ck_elm_id = glob_parent.find("textarea").prop("id");
			value = CKEDITOR.instances[ck_elm_id].getData();
		}
		else{
			value = glob_parent.find('textarea[name="value"]').val().trim();
		}
		
		fdata = {param, value};

		$("#modal_confirm").modal('hide');

		glob_parent.css('opacity', '0.3').css('pointer-events', 'none');
	}
		
	  $.ajax({
	  type: "POST",
	  data: fdata,
	  url:  "/admin/settings",
	  success: function(data){
			 glob_parent.css('opacity', '1').css('pointer-events', 'auto');
			 toastr.success("Updated successfully");
		 },
		 error: function (xhr, status, error) {
			glob_parent.css('opacity', '1').css('pointer-events', 'auto');
			 render_errors(JSON.parse(xhr.responseText), 'toast');
		 }
		 });	
	 }



function evaluateJsQuote(event){

	event.preventDefault();

	let parent = $(event.target);

	parent.find('input[name="cpw"]').val("");

	let age = parseInt(parent.find('input[name="age"]').val());
	let minuteAvailable = parseInt(parent.find('input[name="minute_aval"]').val());
	let hourAvailable = parseInt(parent.find('input[name="hour_aval"]').val());
	let dayAvailable = parseInt(parent.find('input[name="day_aval"]').val());


	let getQuoteFunctionString = $("#quote_js_func").val().trim();

	let final_price = 0.00;
	// $("#view").val(getQuoteFunctionString);

	try{

	  // Load and evaluate the function dynamically
	  eval(getQuoteFunctionString);

	  // console.log(minuteAvailable, hourAvailable, dayAvailable, age);

	  final_price = getQuote(minuteAvailable, hourAvailable, dayAvailable, age);

	}
	catch(ex){
	  toastr.error(`You function has an error: ${ex.message} Check console for more details`);
	  console.log(ex);
	  return;
	}

	// alert(final_price);
	// alert(getQuoteFunctionString);
	// console.log(getQuoteFunctionString);

	parent.find('input[name="cpw"]').val(final_price.toFixed(2));

	toastr.success('Successful!');


 }



  function evaluatePhpQuote(event){

	event.preventDefault();

	let parent = $(event.target);

	let age = parseInt(parent.find('input[name="age"]').val());
	let minuteAvailable = parseInt(parent.find('input[name="minute_aval"]').val());
	let hourAvailable = parseInt(parent.find('input[name="hour_aval"]').val());
	let dayAvailable = parseInt(parent.find('input[name="day_aval"]').val());

	let getQuoteFunctionString = $("#quote_php_func").val().trim();

	fdata = {age, minuteAvailable, hourAvailable, dayAvailable, getQuoteFunctionString};

	let final_price = 0.00;

	parent.css('opacity', '0.3').css('pointer-events', 'none');
		
	$.ajax({
	type: "POST",
	data: fdata,
	url:  "/admin/evaluate-php-quote",
	success: function(data){

		   parent.css('opacity', '1').css('pointer-events', 'auto');
		   toastr.success("Successfully");

		   final_price = data.final_price;
		   parent.find('input[name="cpw"]').val(final_price.toFixed(2));
	   },
	   error: function (xhr, status, error) {
		  parent.css('opacity', '1').css('pointer-events', 'auto');
		   render_errors(JSON.parse(xhr.responseText), 'toast');
	   }
	});

  }

