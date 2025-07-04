// JavaScript Document


// JavaScript Document

var table = null;

let global_ch_dir = '';

let glob_tr = null;


let ddt = {
	id: 0,
	policy_number: 1,
	cpw: 2,
	update_price: 3,
	start_date: 4,
	start_time: 5,
	end_date: 6,
	end_time: 7,
	reg_number: 8,
	first_name: 9,
	middle_name: 10,
	last_name: 11,
	email: 12
};


let buttons = [
	'csv'
];


if(jQuery('#myTable').length){
  
	table = $('#myTable').DataTable( {
		"processing": true,
		"serverSide": true,
	"ajax": "/admin/orders/data-un",
	"bSortCellsTop" : true,
	"rowId" : "0",
	//    dom: '<B<t>l>',
	dom: '<B<"datatable_dom_pull_left"f><t>lp>',
		'initComplete' : function(setting, json){ //When table has been fully initialize
			set_up_data_plugs();
		},
		//responsive: true,
		'lengthMenu': [[50, 100, 200, 500, 2000, -1], [50, 100, 200, 500, 2000, 'All']],
		columns: [
			{ data: ddt.id, name: "id", searchable: false,  className: 'actions', render: function(data, type, row, meta){ return  getActions(row[ddt.policy_number]);}},
			{ data: ddt.policy_number, name: 'policy_number'},
			{ data: ddt.cpw, name: 'cpw', render: function(a,b,c){ return getFinalAmount(c[ddt.cpw], c[ddt.update_price]); }},
			{ data: ddt.start_date, name: 'start_date'},
			{ data: ddt.start_time, name: 'start_time'},
			{ data: ddt.reg_number, name: 'reg_number'},
			{ data: ddt.email, name: 'users.email'},
			{ data: ddt.first_name, name: 'quotes.first_name'},
			{ data: ddt.middle_name, name: 'quotes.middle_name'},
			{ data: ddt.last_name, name: 'quotes.last_name'}
		], 
		// "order" : [[1, "asc"]],
		"order" : [[0, "desc"]],
		//select: true,
		buttons: buttons
	} );
}

function set_up_data_plugs(){

	$('#myTable thead tr:eq(1) th').each( function (i) {
		if($(this).hasClass('sch')){
			$(this).html('<input type="text" placeholder="search"/>')
			$('input', this).on('keyup change', function(){
			if ( table.column((i)).search() !== this.value ) {
				table
					.column((i))
					.search( this.value)
					.draw();
			}
		} );
		
		} 
		else if($(this).hasClass('sch_sel')){
				$('select', this).on('change', function(){
				let n_value = (this.value == "" || this.value == null)? this.value : this.value;
				if ( table.column(i).search() !== n_value ) {
					table
						.column(i)
						.search( n_value )
						.draw();
				}
			} );
		}
	
	});
	setTimeout(function(){
		table.columns([0]).visible(false);
		table.columns([0]).visible(true);
	}, 500);

}

if(jQuery('#myTable').length){
	table.on('column-visibility.dt', function(e, settings, column, state){
		$('#myTable thead tr:eq(1) th input, #myTable thead tr:eq(1) th select').css('width', '0%');
		$('#myTable thead tr:eq(1) th input, #myTable thead tr:eq(1) th select').css('opacity', '1');
		$('#myTable thead tr:eq(1) th input, #myTable thead tr:eq(1) th select').css('width', '100%');
	})
}






function remove_entry(event, np){
	   if(np == 0){
		 glob_tr = $(event.target).closest('tr');
		 $("#modal_delete .modal-body").html('<div class="text-center"><b>Do you want to delete this Order? <br> This will permanently delete it from database.</b></div> <br><div class="py-3 text-center"><span class="py-2" id="delete_conf_code" style="font-size:20px; font-weight:bold"></span><br> Please confirm code below:<br> <input id="delete_conf_code_val" class="form-control" style="max-width:200px; display:inline-block" onpaste="event.preventDefault();"></div>'); 
		 $("#modal_delete .delete_action_btn")[0].setAttribute('onClick', `remove_entry(event, 1)`);
		 $("#modal_delete .delete_action_btn").removeClass('btn-success');
		 $("#modal_delete .delete_action_btn").addClass('btn-danger');
		 $("#modal_delete .delete_action_btn").addClass('btn-danger').removeClass('btn-primary').html('<span class="fa fa-trash-o"></span>  Remove');
		 $("#modal_delete").modal('show');  
		 $("#delete_conf_code").html(random_string(5));
		 $("#delete_conf_code_val").val('');

	}
	else{
		if($("#delete_conf_code").html() != $("#delete_conf_code_val").val()){
			alert('Wrong code.'); return;
		}

		$("#modal_delete").modal('hide');

		let td = glob_tr.find('.actions');
		let sbutton = td.html();
		td.html('<span class="fa fa-spinner fa-spin"></span>');

		let rdata = table.row(glob_tr).data();
	   	
		 $.ajax({
		 type: "DELETE",
		 url:  "/admin/orders/"+rdata[0],
		 success: function(data){
				td.html(sbutton);
				glob_tr.remove();
			},
			error: function (xhr, status, error) {
				td.html(sbutton);
				render_errors(JSON.parse(xhr.responseText), 'toast');
			}
			});	
		}
}


function confirm_entry(event, np){
	if(np == 0){
	  glob_tr = $(event.target).closest('tr');
	  $("#modal_delete .modal-body").html('<div class="text-center" style="font-size:25px"><b>Do you want to confirm/approve this order? </div> <br><div class="py-3 text-center"><span class="py-2" id="delete_conf_code" style="font-size:20px; font-weight:bold"></span><br> Please confirm code below to proceed:<br> <input id="delete_conf_code_val" class="form-control" style="max-width:200px; display:inline-block" onpaste="event.preventDefault();"></div>'); 
	  $("#modal_delete .delete_action_btn")[0].setAttribute('onClick', `confirm_entry(event, 1)`);
	  $("#modal_delete .delete_action_btn").removeClass('btn-success');
	  $("#modal_delete .delete_action_btn").addClass('btn-success');
	  $("#modal_delete .delete_action_btn").removeClass('btn-danger').addClass('btn-success').html('<span class="fa fa-check"></span>  Confirm !');
	  $("#modal_delete").modal('show');  
	  $("#delete_conf_code").html(random_string(5));
	  $("#delete_conf_code_val").val('');

 }
 else{
	 if($("#delete_conf_code").html() != $("#delete_conf_code_val").val()){
		 alert('Wrong code.'); return;
	 }

	 $("#modal_delete").modal('hide');

	 let td = glob_tr.find('.actions');
	 let sbutton = td.html();
	 td.html('<span class="fa fa-spinner fa-spin"></span>');

	 let rdata = table.row(glob_tr).data();
		
	  $.ajax({
	  type: "POST",
	  url:  "/admin/orders/confirm",
	  data: {id: rdata[0]},
	  success: function(data){
			 td.closest('tr').remove();
			 toastr.success("Order Confirmed !");
		 },
		 error: function (xhr, status, error) {
			 td.html(sbutton);
			 render_errors(JSON.parse(xhr.responseText), 'toast');
		 }
		 });	
	 }
}





function random_string(length) {
	var result           = '';
	var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	var charactersLength = characters.length;
	for ( var i = 0; i < length; i++ ) {
	  result += characters.charAt(Math.floor(Math.random() * 
  charactersLength));
   }
   return result;
}
  



function formatDate(dateString) {
    if (!dateString) {
        return "";
    }

	// Check the format of dateString and append EST timezone if missing
    if (!dateString.includes("T") && !dateString.includes("Z") && !dateString.match(/[+-]\d{2}:\d{2}$/)) {
        // Case for "YYYY-MM-DD" (date only): add time and EST timezone
        if (dateString.match(/^\d{4}-\d{2}-\d{2}$/)) {
            dateString += "T00:00:00-05:00"; // Assume midnight EST
        }
        // Case for "YYYY-MM-DD HH:mm:ss" (date with time but no timezone)
        else if (dateString.match(/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/)) {
            dateString = dateString.replace(" ", "T") + "-05:00"; // Convert to ISO format with EST
        }
    }

    // Declare the variable to hold the date object
    let date;

    // Check if the input string contains 'T' (i.e., it's in timestamp format)
    // if (dateString.includes('T')) {
    //     // Create a new Date object in UTC
    //     let utcDate = new Date(dateString);

    //     // Get UTC time components and adjust to EST (UTC-5)
    //     let estOffset = utcDate.getTimezoneOffset() + 300; // EST is UTC-5 (300 minutes)
    //     utcDate.setMinutes(utcDate.getMinutes() - estOffset); // Adjust the time to EST

    //     // Assign the adjusted UTC date (now in EST) to the `date` variable
    //     date = utcDate;
    // } else {
    //     // Create a new Date object from the input string assuming it's already in local time
    //     date = new Date(dateString);
    // }


	date = new Date(dateString);

    // Extract components
    let month = String(date.getMonth() + 1).padStart(2, '0');
    let day = String(date.getDate()).padStart(2, '0');
    let year = String(date.getFullYear()).slice(-2);
    let hours = date.getHours();
    let minutes = String(date.getMinutes()).padStart(2, '0');
    let seconds = String(date.getSeconds()).padStart(2, '0');

    // Determine AM/PM and adjust hour format
    let ampm = hours >= 12 ? 'PM' : 'AM';
    hours = hours % 12;
    hours = hours ? hours : 12; // The hour '0' should be '12'
    hours = String(hours).padStart(2, '0');

    // Format the date string
    return `${month}/${day}/${year} ${hours}:${minutes}:${seconds} ${ampm}`;
}



function getActions(policy_number){

	let html = `<a class="btn btn-xs btn-primary bg-primary" target="_blank" href="/admin/order/edit/${policy_number}"><i class="fa fa-edit"></i> Edit</a>`;
	 

	html += '<button onclick="confirm_entry(event, 0)" class="btn btn-xs btn-success "><i class="fa fa-check"></i> Confirm/Approve Order</button> | <button onclick="remove_entry(event, 0)" class="btn btn-xs btn-danger "><i class="fa fa-trash"></i> Delete</button>';

	return html;

}


function getFinalAmount(cpw, update_price){

	
	if(update_price && (update_price != cpw) && parseInt(update_price) != 0){
		return `<strike>${numberFormat(cpw)}</strike> ${numberFormat(update_price)}`
	}
	else{
		return numberFormat(cpw);
	}

}


// Helper function to format numbers
function numberFormat(value) {
    return new Intl.NumberFormat('en-US', { 
        minimumFractionDigits: 2, 
        maximumFractionDigits: 2 
    }).format(value);
}


function shouldModifyDateChange(event){
	if($("#modify_date").prop("checked")){
		$(".period_grp").removeClass("d-none2");
		$(".period_grp input, .period_grp select").prop("required", true);
		$("#hour_period_type")[0].click();
	}
	else{
		$(".period_grp").addClass("d-none2");
		$(".period_grp input, .period_grp select").prop("required", false);
		resetDateTime();
	}
}


function updateQuoteForm(event){ 
	
	event.preventDefault();

    let parent = $(event.target).closest('form');

    let fdata = parent.serialize();
    
    parent.css("opacity", "0.5").css("pointer-events", "none");
	var sbutton = parent.find(".sbutton").html(); 
	$(".form_error, .formError").remove();
	parent.find(".sbutton").html('<span class="fa fa-spin fa-spinner fa-2x"></span> Submitting...');
	

	$.ajax({
		type: "POST",
		url:   "/admin/order",
		data: fdata,
		dataType: 'json',
		success: function(data){
            
			if($("#_method").val() == "POST"){
				parent.trigger('reset');
				parent.html(`<div class="alert text-center alert-success py-5 px-3 px-md-5"><i class="fa fa-check-circle fa-5x"></i><br> <p style="font-size:24px;"> Order created successfully and message has been sent! <br><br> <a href="/admin/order/new">Create another policy</a> </p></div>`);

				parent.css("opacity", "1").css("pointer-events", "auto");

		
			}
			else{
				parent.css("opacity", "1").css("pointer-events", "auto");
				parent.find(".sbutton").html(sbutton);
				toastr.success("Order updated successfully");
			}



        },
        error: function (xhr, status, error) {
            parent.css("opacity", "1").css("pointer-events", "auto");
            parent.find(".sbutton").html(sbutton);
            render_errors(JSON.parse(xhr.responseText), 'toast', parent);
        }
	});	
}

jQuery(document).ready(function(){
	setTimeout(function(){
		resetDateTime();
		
		if(typeof boccupation != "undefined"){
			try{
				$("#occupation-selectized").val(boccupation).trigger("input");
				setTimeout(function(){
					occupation_control.setValue(boccupation);
				}, 1000);
			}
			catch(exception){
			}
		} 
	}, 4000);


	if($('#user_id').length){
	
		let $user_select = $('#user_id').selectize({
			valueField: 'user_id',
			labelField: 'label',  // Use a custom field for label display
			searchField: ['name', 'email'],
			create: false,
			placeholder: 'Search user',  
			load: function (query, callback) {
				if (!query.length) return callback();
				$.ajax({
					url: '/admin/order/users', 
					type: 'GET',
					dataType: 'json',
					data: { q: query },  
					success: function (data) {
						var formattedData = data.map(item => ({
							user_id: item.user_id,
							name: item.name,
							email: item.email,
							label: `${item.name} (${item.email})` // Custom label format
						}));
						callback(formattedData);
					},
					error: function () {
						callback();
					}
				});
			},
			score: function (search) {
				var words = search.toLowerCase().split(' ');
				return function (item) {
					var label = item.label.toLowerCase();
					return words.some(word => label.includes(word)) ? 1 : 0;
				};
			},
			render: {
				item: function (item, escape) {
					return `<div class="">
						<strong>${escape(item.name)} (${escape(item.email)})</strong><br>
					</div>`;
				},
				option: function (item, escape) {
					return `<div class="py-2 px-3">
						<strong>${escape(item.name)} (${escape(item.email)})</strong><br>
					</div>`;
				}
			}
		});
		// Get Selectize instance
		var selectizeControl = $user_select[0].selectize;
		
	}

})

function resetDateTime(){

	if(typeof bstartDate != "undefined"){
		$("#start_date").val(bstartDate).trigger('change');
		$("#end_date").val(bendDate).trigger('change');
		$("#start_time").val(bstartTime).trigger('change');
		$("#end_time").val(bendTime).trigger('change');

		setTimeout(() => {
			get_quote();
		}, 100);
	}
}

function setUpdatePrice(){


}