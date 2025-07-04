// JavaScript Document


// JavaScript Document

var table = null;

let global_ch_dir = '';

const ddt = {
	ticket_id: 0,
	subject: 1,
	first_name: 2,
	last_name: 3,
	phone: 4,
	policy_number: 5,
	email: 6,
	unread: 7,
	is_closed: 8,
	updated_at: 9,
}

if($('#myTable').length){

	table = $('#myTable').DataTable({
		processing: true,
		serverSide: true,
		ajax: "/admin/tickets/data",
		bSortCellsTop: true,
		rowId: ddt.ticket_id,
		// dom: '<B<"datatable_dom_pull_left"f><t>lp>',
		lengthMenu: [[50, 100, -1], [50, 100, 'All']],
		order: [[1, "desc"]],
		initComplete: function () {
			$('.dt-buttons').before('<h2 class="mb-4">Tickets</h2>');
		},
	
		columns: [
			// Actions
			{
				data: null,
				orderable: false,
				className: 'actions',
				render: function (data, type, row) {
					const ticketId = row[ddt.ticket_id];
					return `
						<a style="color:#000" target="_blank" href="/admin/ticket/${ticketId}" class="btn btn-sm btn-primary me-1">View</a>
						<button  onclick="deleteTicket(event, 0)" data-id="${ticketId}" class="btn btn-sm btn-danger btn-delete">Delete</button>
					`;
				}
			},
	
			// Ticket Meta: ID, Policy, Status, Unread
			{
				data: ddt.ticket_id,
				name: 'ticket_id',
				render: function (data, type, row) {
					const isClosed = row[ddt.is_closed];
					const unread = row[ddt.unread];
					const policy = row[ddt.policy_number];
	
					return `
						<div>
							<strong>#${data}</strong> ${policy ? `<span class="text-muted"> | ${policy}</span>` : ''}
							<br>
							<span class="badge ${isClosed ? 'bg-secondary' : 'bg-success'}">
								${isClosed ? 'Closed' : 'Open'}
							</span>
							${unread > 0 ? `<span class="badge bg-danger ms-1">${unread} new</span>` : ''}
						</div>
					`;
				}
			},
	
			// Customer Info
			{
				data: ddt.email,
				name: 'first_name',
				render: function (data, type, row) {
					const fullName = `${row[ddt.first_name] ?? ''} ${row[ddt.last_name] ?? ''}`.trim();
					const phone = row[ddt.phone] ?? '';
					return `
						<div>
							<strong>${fullName || '-'}</strong><br>
							<a href="mailto:${data}">${data}</a><br>
							<small class="text-muted">${phone}</small>
						</div>
					`;
				}
			},
	
			// Subject
			{
				data: ddt.subject,
				name: 'subject',
				render: function (data) {
					return `<div>${data}</div>`;
				}
			},
	
			// Last Updated
			{
				data: ddt.updated_at,
				render: function (data) {
					const date = new Date(data);
					return `<span class="text-muted">${date.toLocaleString()}</span>`;
				}
			}
			
		]
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

	$.ajax({
		type: "POST",
		url:   "/admin/ticket/reply",
		data: fdata,
		dataType: 'json',
		success: function(data){


                let html = `<div class="mb-3 p-3 rounded border bg-light border-primary">
                        <div class="mb-1 small fw-bold">
                            Admin
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
            
			parent.find(".sbutton").html(sbutton);
			
            toastr.success("Mesage sent!");
			
        },
        error: function (xhr, status, error) {
            parent.css("opacity", "1").css("pointer-events", "auto");
            parent.find(".sbutton").html(sbutton);
            render_errors(JSON.parse(xhr.responseText), 'toast', parent);
        }
	});	
}



 
function remove_entry(id, np){
	   if(np == 0){
		 $("#modal_delete .modal-body").html("Do you want to delete this Coupon?"); 
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
		 url:  "/admin/coupons/"+id,
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






function updateTicketState(event, is_closed, np){

	if(np == 0){
	  glob_tr = $(event.target).closest('tr');
	  $("#modal_delete .modal-body").html(`<div class="text-center" style="font-size:25px"><b>Do you want to ${is_closed? 'Close':'Reopen'} this Ticket? </div>`); 
	  $("#modal_delete .delete_action_btn")[0].setAttribute('onClick', `updateTicketState(event, ${is_closed}, 1)`);
	  $("#modal_delete .delete_action_btn").removeClass('btn-success');
	  $("#modal_delete .delete_action_btn").removeClass('btn-danger').addClass('btn-primary').html('<span class="fa fa-check"></span>  Confirm !');
	  $("#modal_delete").modal('show');  
 }
 else{
	 
	 $("#modal_delete").modal('hide');

	 
	 let td = null;
	 let rdata = null;
	 let ticketId = null;

	 if(glob_tr.length){
		
		td = glob_tr.find('.actions');
		td.html('<span class="fa fa-spinner fa-spin"></span>');
		rdata = table.row(glob_tr).data();
		ticketId = rdata[ddt.ticket_id];

	 }
	 else{
		td = $("#st_button");
		ticketId = TICKET_ID;
	 }
	 let sbutton = td.html();
	 td.html('<span class="fa fa-spinner fa-spin"></span>');

		
	  $.ajax({
	  type: "POST",
	  url:  "/admin/ticket/state",
	  data: {ticket_id: ticketId},
	  success: function(data){
			if(glob_tr.length){
				td.closest('tr').remove();

			}
			else{
				window.location.reload();
			}
			toastr.success("Ticket Updated !");
		 },
		 error: function (xhr, status, error) {
			 td.html(sbutton);
			 render_errors(JSON.parse(xhr.responseText), 'toast');
		 }
		 });	
	 }


}




function deleteTicket(event, np){

	if(np == 0){
	  glob_tr = $(event.target).closest('tr');
	  $("#modal_delete .modal-body").html(`<div class="text-center" style="font-size:25px"><b>Do you want to delete this Ticket? </div><div class="py-3 text-center"><span class="py-2" id="delete_conf_code" style="font-size:20px; font-weight:bold"></span><br> Please confirm code below to proceed:<br> <input id="delete_conf_code_val" class="form-control" style="max-width:200px; display:inline-block" onpaste="event.preventDefault();"></div>`); 
	  $("#modal_delete .delete_action_btn")[0].setAttribute('onClick', `deleteTicket(event,  1)`);
	  $("#modal_delete .delete_action_btn").removeClass('btn-success');
	  $("#modal_delete .delete_action_btn").removeClass('btn-danger').addClass('btn-danger').html('<span class="fa fa-check"></span>  Confirm Delete !');
	  $("#modal_delete").modal('show');  
	  $("#delete_conf_code").html(random_string(4));
		  $("#delete_conf_code_val").val('');

 }
 else{
	 
	if($("#delete_conf_code").html() != $("#delete_conf_code_val").val()){
		alert('Wrong code.'); return;
	}

	 $("#modal_delete").modal('hide');

	 
	 let td = null;
	 let rdata = null;
	 let ticketId = null;

	 if(glob_tr.length){
		
		td = glob_tr.find('.actions');
		td.html('<span class="fa fa-spinner fa-spin"></span>');
		rdata = table.row(glob_tr).data();
		ticketId = rdata[ddt.ticket_id];

	 }
	 else{
		td = $("#st_button");
		ticketId = TICKET_ID;
	 }
	 let sbutton = td.html();
	 td.html('<span class="fa fa-spinner fa-spin"></span>');

		
	  $.ajax({
	  type: "DELETE",
	  url:  "/admin/ticket/"+ticketId,
	  success: function(data){
			if(glob_tr.length){
				td.closest('tr').remove();
			}
			else{

				td.closest('.container-fluid').html('<div class="alert alert-info p-5"> Ticket has been deleted!</div>');
			
			}
			toastr.success("Ticket deleted !");
		 },
		 error: function (xhr, status, error) {
			 td.html(sbutton);
			 render_errors(JSON.parse(xhr.responseText), 'toast');
		 }
		 });	
	 }


}





$(document).ready(function() {

	if($('#email_users').length){
	// Toggle user select visibility
	$('#send_type').on('change', function () { 
		$('#email_users').val(null).trigger('change');
		$('#user_select_wrapper').toggleClass('d-none', $(this).val() !== 'selected');
	});

	// Initialize select2
	  $('#email_users').select2({
		placeholder: 'Search and select users',
		allowClear: true,
		multiple: true,
		data: USER_DATA.map(user => ({
		  id: user.user_id,
		  text: `${user.first_name} ${user.last_name}`, // fallback
		  first_name: user.first_name,
		  last_name: user.last_name,
		  email: user.email
		})),
		templateResult: formatUser,
		templateSelection: formatUserSelection,
		escapeMarkup: markup => markup
	  });
	  
	  function formatUser(user) {
		if (!user.id) return user.text;
		return `
		  <div class="d-flex flex-column">
			<div class="fw-bold">${user.first_name} ${user.last_name}</div>
			<div class="small text-muted">${user.email}</div>
		  </div>
		`;
	  }


	  
	  function formatUserSelection(user) {
		if (!user.id) return user.text;
		return `
		  <div class="d-flex flex-column">
			<div class="fw-semibold text-dark">${user.first_name} ${user.last_name}</div>
			<div class="small text-muted">${user.email}</div>
		  </div>
		`;
	  }


	  
	  CKEDITOR.config.height = 350;
	  CKEDITOR.config.width = 'auto';
	  CKEDITOR.replace('email_message');




	  $('#customTabs a').click(function(e) {
		e.preventDefault();
		
		// Remove active class from all tabs
		$('#customTabs a').removeClass('active');
		// Add active class to clicked tab
		$(this).addClass('active');
	
		// Hide all tab panes
		$('.tab-pane').removeClass('active');
		// Show the targeted tab pane
		var target = $(this).data('target');
		$(target).addClass('active');
	  });


	  
	}





	
});




function sendMessage(event){ 
	
	event.preventDefault();

    let parent = $(event.target).closest('form');

	let fdata = parent.serializeArray();

    
	let email_message = CKEDITOR.instances['email_message'].getData();

	// Update or inject email_message
	let found = false;
	fdata.forEach(function(field) {
		if (field.name === 'email_message') {
			field.value = email_message;
			found = true;
		}
	});

	// If email_message was not originally in the form, add it
	if (!found) {
		fdata.push({ name: 'email_message', value: email_message });
	}


    parent.css("opacity", "0.5").css("pointer-events", "none");
	var sbutton = parent.find(".sbutton").html(); 
	$(".form_error, .formError").remove();
	parent.find(".sbutton").html('<span class="fa fa-spin fa-spinner fa-2x"></span> Submitting...');
	

	$.ajax({
		type: "POST",
		url:   "/admin/ticket/email",
		data: fdata,
		dataType: 'json',
		success: function(data){

			$('#email_users').val(null).trigger('change');

			CKEDITOR.instances['email_message'].setData('');

            parent.css("opacity", "1").css("pointer-events", "auto");
            $("form").trigger('reset');

			$('#user_select_wrapper').toggleClass('d-none', $('#send_type').val() !== 'selected');
            
			parent.find(".sbutton").html(sbutton);
			
            toastr.success("Mesage sent!");
			
        },
        error: function (xhr, status, error) {
            parent.css("opacity", "1").css("pointer-events", "auto");
            parent.find(".sbutton").html(sbutton);
            render_errors(JSON.parse(xhr.responseText), 'toast');
        }
	});	
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
  


