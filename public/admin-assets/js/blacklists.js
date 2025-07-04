// JavaScript Document


// JavaScript Document

var table = null;

let global_ch_dir = '';

const ddt = {
	id: 0,
	matches: 1,
	hits: 2,
	created_at: 3
}

if($('#myTable').length){
	let buttons = [
		{
			text: 'Create BlackList',
			action: function(){
				create_row();
			} 
		}
	];

	table = $('#myTable').DataTable( {
		"processing": true,
		"serverSide": true,
		"ajax": "/admin/blacklists/data",
		"bSortCellsTop" : true,
		"rowId" : "0",
			//    dom: '<B<t>l>',
		dom: '<B<"datatable_dom_pull_left"f><t>lp>',
		'initComplete' : function(setting, json){ //When table has been fully initialize
			// set_up_data_plugs();
			$('.dt-buttons').before('<h2>BlackLists</h2>');
		},
		//responsive: true,
		'lengthMenu': [[50, 100, -1], [50, 100,  'All']],
		columns: [
			{ data: null, searchable: false, sortable: false, className: 'actions', render: function(data, type, row, meta){ return (meta.row + meta.settings._iDisplayStart + 1);}},
			{ data: ddt.matches, name: 'matches'},
			{ data: ddt.hits, name: 'hits'},
			{ data: ddt.created_at, name: 'created_at', render: function(data,b,c){ return formatDate(data)}},
			{ data: null, searchable: false, sortable: false, className: 'actions', render: function(data, type, row, meta){ return `<button onclick="edit_row(event)" class="btn btn-xs btn-default bg-primary">Edit</button><button class="btn btn-xs btn-default bg-danger ml-2" onclick="remove_entry(${row[ddt.id]}, 0)">Delete</button>`;}},
			// etc
		], 
		"order" : [[3, "desc"]],
		//select: true,
		buttons: buttons
	} );


}

function create_row(){

	$("#modal_entry form").trigger('reset');
	$("#modal_entry").modal('show');
	$('#modal_entry .modal-header h4').html('Create BlackList');
	$('#modal_entry .modal-footer .btn-main').html('<i class="fa fa-plus"></i> Create BlackList');
	global_ch_dir = "POST";
	$("#_method").val(global_ch_dir);

}


function edit_row(event){

	event.preventDefault();

	$("#modal_entry form").trigger('reset');
	$("#modal_entry").modal('show');
	
	var parent = $(event.target).closest('tr');
	var rdata = table.row(parent).data();

	for(key in ddt){
		if($(`#${key}`).length){
			$(`#${key}`).val(rdata[ddt[key]]);
		}
	}

	let matches = JSON.parse(rdata[ddt.matches]);
	if('last_name' in matches){
		$("#last_name").val(matches.last_name);
	}
	if('first_name' in matches){
		$("#first_name").val(matches.first_name);
	}
	if('email' in matches){
		$("#email").val(matches.email);
	}
	if('birth_date' in matches){
		$("#birth_date").val(matches.birth_date);
	}

	if('registrations' in matches){
		$("#registrations").val(matches.registrations);
	}

	


	global_ch_dir = "PATCH";
	$("#_method").val(global_ch_dir);

	$("#modal_entry").modal('show');
	$('#modal_entry .modal-header h4').html('Edit BlackList');
	$('#modal_entry .modal-footer .btn-main').html('<i class="fa fa-save"></i> Update BlackList');


}

function update_entry(event){
	
	event.preventDefault();
	
	
	let fdata = $(event.target).serialize();
	
	$(".form_error").remove();

	let sbutton = $("#sbutton").html(); //grab the initial content
	$("#errmsg").html('');
	$("#sbutton").html('<span class="fa fa-spin fa-spinner fa-2x"></span> Submitting...');
   
   $.ajax({
	 type: "POST",
	 url:   "/admin/blacklists",
	 data: fdata,
	 dataType: 'json',
	 success: function(data){
		     
				$("#sbutton").html(sbutton);

				 e_id = data.data.id;
				 let ldata = data.data;
				 
				 if(global_ch_dir == 'PATCH'){
					 
					 table.ajax.reload();
					 
					 $("#errmsg").html('<div style="font-size:16px; color:#092; font-weight: bold" class="text-success">The entry has been updated successfully</div>');
					 		 
				 }
				 else{
					
					$("#modal_entry form").trigger('reset');
					
					table.ajax.reload();
				 }
				 $('#modal_entry').modal('hide');
				 
		    },
			error: function (xhr, status, error) {
				$("#sbutton").html(sbutton);
				render_errors(JSON.parse(xhr.responseText), '#errmsg');
			}
		  });
	
}


 
function remove_entry(id, np){
	   if(np == 0){
		 $("#modal_delete .modal-body").html("Do you want to delete this BlackList?"); 
		 $("#modal_delete .delete_action_btn")[0].setAttribute('onClick', `remove_entry(${id}, 1)`);
		 $("#modal_delete .delete_action_btn").removeClass('btn-success');
		 $("#modal_delete .delete_action_btn").addClass('btn-danger');
		 $("#modal_delete .delete_action_btn").html('<span class="fa fa-trash-o"></span>  Remove');
		 $("#modal_delete").modal('show');  
	}
	else{
	
		$("body").css('opacity', '0.3').css('pointer-events', 'none');
	   	
		 $.ajax({
		 type: "DELETE",
		 url:  "/admin/blacklists/"+id,
		 success: function(data){
				$("body").css('opacity', '1').css('pointer-events', 'auto');
				$("#modal_entry").modal('hide');
				$('#myTable  #'+ id).remove();
				
			},
			error: function (xhr, status, error) {
				$("body").css('opacity', '1').css('pointer-events', 'auto');
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
  



function get_position(role_n){
	if(role_n == '' || role_n == null) return '';
	if($(`#position option[value="${role_n}"]`).length){ 
		return  $(`#position option[value="${role_n}"]`).html();
	}
	return '';
}



//////////////////////////////////////////////////////////////////////////////////

var glob_edit_id = 0;


function formatDate(dateString) {
    if (!dateString) {
        return "";
    }

    // Declare the variable to hold the date object
    let date;

    // Check if the input string contains 'T' (i.e., it's in timestamp format)
    if (dateString.includes('T')) {
        // Create a new Date object in UTC
        let utcDate = new Date(dateString);

        // Get UTC time components and adjust to EST (UTC-5)
        let estOffset = utcDate.getTimezoneOffset() + 300; // EST is UTC-5 (300 minutes)
        utcDate.setMinutes(utcDate.getMinutes() - estOffset); // Adjust the time to EST

        // Assign the adjusted UTC date (now in EST) to the `date` variable
        date = utcDate;
    } else {
        // Create a new Date object from the input string assuming it's already in local time
        date = new Date(dateString);
    }

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
    return `${day}-${month}-${year} ${hours}:${minutes}:${seconds} ${ampm}`;
}


