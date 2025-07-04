// JavaScript Document


// JavaScript Document

var table = null;

let global_ch_dir = '';




let buttons = [
	{
			text: 'New Admin',
			action: function () {
			   create_row();
			}
	},
	'csv',
	// 'colvis'
	
];

  
table = $('#myTable').DataTable( {
	"processing": true,
	"serverSide": true,
   "ajax": "/admin/admins/data",
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
        { data: null, searchable: false, sortable: false, render: function(data, type, row, meta){ return (meta.row + meta.settings._iDisplayStart + 1);}},
		{ data: 1, name: 'fname'},
		{ data: 2, name: 'lname'},
		{ data: 3, name: 'email'},
		{ data: 4, name: 'phone'},
		{ data: 5, name: 'role', render: function(a,b,c){ return  get_role(c[5]); }},
		{ data: null, searchable: false, className: "editor_btn",  sortable: false,  render: function(data, type, row, meta){  return '<button class="editor_update" onclick="edit_row(event)"><span class="fa fa-edit"></span> Edit </button> | <button onclick="remove_entry(event, 0)" class="editor_remove"><span class="fa fa-trash-o"> Delete</button>'}},
		
        // etc
    ], 
	"order" : [[1, "asc"]],
	//select: true,
	buttons: buttons
} );


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
	}, 500);

}
table.on('column-visibility.dt', function(e, settings, column, state){
	$('#myTable thead tr:eq(1) th input, #myTable thead tr:eq(1) th select').css('width', '0%');
	$('#myTable thead tr:eq(1) th input, #myTable thead tr:eq(1) th select').css('opacity', '1');
	$('#myTable thead tr:eq(1) th input, #myTable thead tr:eq(1) th select').css('width', '100%');
})




function create_row(){
	
	$("#modal_entry form").trigger('reset');
	
	global_ch_dir = 'POST';
	
	$('#modal_entry .modal-header h4').html("New Admin");
	$('#modal_entry .modal-footer button:eq(1)').html('<span class="fa fa-plus"></span> Create');
	
	$('#modal_entry').modal('show');

	$("#password").val('').attr('required', true).attr('placeholder', '');
	
	$("#errmsg").html('');

}


function edit_row(event){

	event.preventDefault();

	$("#modal_entry form").trigger('reset');
	
	var parent = $(event.target).closest('tr');
	var rdata = table.row(parent).data();
	
	$("#entry_id").val(rdata[0]); 
	
	$("#fname").val(rdata[1]);
	$("#lname").val(rdata[2]);
	$("#email").val(rdata[3]);
	$("#phone").val(rdata[4]);
	$("#role").val(rdata[5]);
	
	$("#password").val('').attr('required', false).attr('placeholder', 'Please leave the field blank if you are not changing your password');
	
	global_ch_dir = 'PATCH';
	
	$('#modal_entry .modal-header h4').html("Update Admin");
	$('#modal_entry .modal-footer button:eq(1)').html('<span class="fa fa-save"></span> Update');
		
	$('#modal_entry').modal('show');
	$("#errmsg").html('');
}




function update_entry(event){
	
	event.preventDefault();
	
	let fname = $("#fname").val().trim();
	let lname = $("#lname").val().trim();
	let email = $("#email").val().trim();
	let password = $("#password").val().trim();
	let phone = $("#phone").val().trim();
	let role = $("#role").val();
	let e_id =  $("#entry_id").val();
	
	$(".form_error").remove();

	let fdata = {_method: global_ch_dir, admin_id: e_id, phone, fname, lname, email, password, role};

	let sbutton = $("#sbutton").html(); //grab the initial content
	$("#errmsg").html('');
	$("#sbutton").html('<span class="fa fa-spin fa-spinner fa-2x"></span> Submitting...');
   
   $.ajax({
	 type: "POST",
	 url:   "/admin/admins",
	 data: fdata,
	 dataType: 'json',
	 success: function(data){
		     
				$("#sbutton").html(sbutton);

				 var e_id = data.data.admin_id;
				
				 if(global_ch_dir == 'PATCH'){
					 var table_row = "#myTable  #" + e_id;
					 let bdata = table.row(table_row).data();
					 table.row(table_row).data([e_id, fname, lname, email, phone, role]).invalidate();
					 $("#modal_entry form").trigger('reset');
					 $("#errmsg").html('<div style="font-size:16px; color:#092; font-weight: bold" class="text-success">The entry has been updated successfully</div>');
					 		 
				 }
				 else{

					 var rowNode = table.row.add([e_id, fname, lname, email, phone, role]).draw().node();
					 $(rowNode).css('color', 'green').animate({color: 'black'});
				 }
				 $('#modal_entry').modal('hide');
				 
		    },
			error: function (xhr, status, error) {
				$("#sbutton").html(sbutton);
				render_errors(JSON.parse(xhr.responseText), '#errmsg');
			}
		  });
	
}



 
 function remove_entry(event, np){
	    
	   if(np == 0){
		 event.preventDefault();
		 glob_entry_id = $(event.target).closest('tr').prop('id');
		 $("#modal_delete .modal-body").html("Do you want to delete this Admin?"); 
		 $("#modal_delete .delete_action_btn")[0].setAttribute('onClick', 'remove_entry(event, 1)');
		 $("#modal_delete .delete_action_btn").removeClass('btn-success');
		 $("#modal_delete .delete_action_btn").addClass('btn-danger');
		 $("#modal_delete .delete_action_btn").html('<span class="fa fa-trash-o"></span>  Remove');
		 $("#modal_delete").modal('show');  
		 
		 $("#delete_conf_code").html(random_string(8));
		 $("#delete_conf_code_val").val('');

		   
		   }

	  else{

		if($("#delete_conf_code").html() != $("#delete_conf_code_val").val()){
			alert('Wrong code.'); return;
		}

		   
	   var sbutton = $('#myTable  #'+ glob_entry_id + ' .editor_btn').html();
	   
	   $('#myTable  #'+ glob_entry_id +' .editor_btn').html('<span class="fa fa-spin fa-spinner"></span>')
			
		 $.ajax({
		 type: "DELETE",
		 url:  "/admin/admin/"+glob_entry_id,
		 success: function(data){  console.log(data);
				 	
					$('#myTable  #'+ glob_entry_id).remove();
					 
				},
				error: function (xhr, status, error) {
					$('#myTable  #'+ glob_entry_id + ' .editor_btn').html(sbutton);
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
  

function get_role(role_n){
	if(role_n == '' || role_n == null) return '';
	if(role_n in glob_roles){ 
			return  glob_roles[role_n];
	}
	return '';
}

