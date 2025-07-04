// JavaScript Document


// JavaScript Document

var table = null;

let global_ch_dir = '';

let glob_tr = null;




let buttons = [
];

  
table = $('#myTable').DataTable( {
	"processing": true,
	"serverSide": true,
   "ajax": "/admin/users/data",
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
        { data: null, searchable: false, sortable: false, className: 'actions', render: function(data, type, row, meta){ return `<button onclick="remove_entry(event, 0)" class="btn btn-xs btn-danger "><i class="fa fa-trash"></i> Delete</button>`;}},
		{ data: 1, name: 'email'},
		{ data: 2, name: 'first_name'},
		{ data: 3, name: 'last_name'},
		{ data: 4, name: 'created_at', render: function(data, b,c){ return formatDate(data);}}
	    // etc
    ], 
	"order" : [[4, "desc"]],
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
		table.columns([0]).visible(true);
	}, 500);

}
table.on('column-visibility.dt', function(e, settings, column, state){
	$('#myTable thead tr:eq(1) th input, #myTable thead tr:eq(1) th select').css('width', '0%');
	$('#myTable thead tr:eq(1) th input, #myTable thead tr:eq(1) th select').css('opacity', '1');
	$('#myTable thead tr:eq(1) th input, #myTable thead tr:eq(1) th select').css('width', '100%');
})






function remove_entry(event, np){
	   if(np == 0){
		 glob_tr = $(event.target).closest('tr');
		 $("#modal_delete .modal-body").html('<div class="text-center"><b>Do you want to delete this user? <br> This will also delete all policies relating to user.</b></div> <br><div class="py-3 text-center"><span class="py-2" id="delete_conf_code" style="font-size:20px; font-weight:bold"></span><br> Please confirm code below:<br> <input id="delete_conf_code_val" class="form-control" style="max-width:200px; display:inline-block" onpaste="event.preventDefault();"></div>'); 
		 $("#modal_delete .delete_action_btn")[0].setAttribute('onClick', `remove_entry(event, 1)`);
		 $("#modal_delete .delete_action_btn").removeClass('btn-success');
		 $("#modal_delete .delete_action_btn").addClass('btn-danger');
		 $("#modal_delete .delete_action_btn").html('<span class="fa fa-trash-o"></span>  Remove');
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
		 url:  "/admin/user/"+rdata[0],
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

