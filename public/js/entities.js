// Initialize Datatable
let table = null;


//============= CREATE ENTITY  FUNCTION  ============================

function createEntity(event) { 
    event.preventDefault();

    let fdata = new FormData($(event.target)[0]);
    let sbutton = $("#sbutton").html(); // grab the initial content

    $(".form_error, .report-info").remove();
    $("#sbutton").html('<span class="fa fa-spin fa-spinner fa-2x"></span> creating entity...');

    $.ajax({
        type: "POST",
        url: "/entities",
        data: fdata,
        processData: false, // Necessary for FormData object
        contentType: false, // Necessary for FormData object
        dataType: 'json',
        success: function(data) {
            $("#sbutton").html(sbutton);
            $("form .card").append(`
                <div class="alert alert-success alert-dismissible fade show my-2 report-info" role="alert">
                    <i class="fa fa-check-circle"></i> Entity Created Successfully!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i class="fa fa-times"></i></button>
                </div>
            `);        
            $("form").trigger('reset');
            toastr.success('Entity Created Successfully!');
        },
        error: function(xhr, status, error) {
            $("#sbutton").html(sbutton);
            try {
                const response = JSON.parse(xhr.responseText);
                render_errors(response, 'toast');
            } catch (e) {
                console.error('Error parsing JSON response:', e);
                toastr.error('An error occurred. Please try again.');
            }
        }
    });
}


//============= EDIT ENTITY  FUNCTION  ============================

function editEntity(event) { 
    event.preventDefault();

    let fdata = new FormData($(event.target)[0]);
    let sbutton = $("#sbutton").html(); // grab the initial content

    let id = $("#id").val();
    fdata.append("_method", "PUT");  // This is how to signify the method

    $(".form_error, .report-info").remove();
    $("#sbutton").html('<span class="fa fa-spin fa-spinner fa-2x"></span> updating entity...');


    $.ajax({
        type: "POST",
        url: `/entities/${id}`,
        data: fdata,
        processData: false, // Necessary for FormData object
        contentType: false, // Necessary for FormData object
        dataType: 'json',
        success: function(data) {
            $("#sbutton").html(sbutton);
            $("form .card").append(`
                <div class="alert alert-success alert-dismissible fade show my-2 report-info" role="alert">
                    <i class="fa fa-check-circle"></i> Entity updated successfully!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i class="fa fa-times"></i></button>
                </div>
            `);        
            toastr.success('Entity updated successfully!');
        },
        error: function(xhr, status, error) {
            $("#sbutton").html(sbutton);
            try {
                const response = JSON.parse(xhr.responseText);
                render_errors(response, 'toast');
            } catch (e) {
                console.error('Error parsing JSON response:', e);
                toastr.error('An error occurred. Please try again.');
            }
        }
    });
}






//============= DATATABLE SETUP AND  FUNCTION  ============================

let ddt = {
    // id : 0,
    // name: 1,
    // version: 2,
    // main_contact: 3,
    // tel: 4,
    // type: 5,
    // school_type: 6,
    // barcode: 7,
    // barcode_type: 8,
    // students_enabled: 9,
    // barcode_prefix: 10,

    id : 0,
    name: 1,
    postcode: 2,
    tel: 3,
};


// This will only call if we are in the entities table page
if($('#myTable').length){

    table = $('#myTable').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": "/entities/data",
        "bSortCellsTop" : true,
        "rowId" : "0",
        // dom: '<B<"datatable_dom_pull_left"f><t>lp>',
        dom: '<t>',
        'initComplete' : function(setting, json){ //When table has been fully initialize
            // set_up_data_plugs();
        },
        'lengthMenu': [[50, 100, 200, 500, 2000, -1], [50, 100, 200, 500, 2000, 'All']],
        columns: [
            { data: ddt.id, name: 'entities.id', searchable:false},
            { data: ddt.name, name: 'entities.name'},
            { data: ddt.postcode, name: 'customer_info.cust_postcode'},
            { data: ddt.tel, name: 'entities.tel', searchable:false},
            { data: null, searchable: false, sortable: false, className: 'actions', render: function(data, type, row, meta){ return `<a class="action_btn" onclick="attachPopover(event)"  title="Options"><i class="fa-solid  fa-cog"></i></a>`;}},
            
            // etc
        ], 
        "order" : [[1, "desc"]],
        //select: true,
        buttons: [
            // {
            //     // Action to create new entity
            //     text: 'Create',
            //     action: function(){
            //         window.location.href ="/entities/create";
            //     }
            // },
            // 'colvis'
        ]
    } );
}

$("#name-search").on('change keyup', function(){
    let val = $("#name-search").val().trim();
    table.column(1).search(val).draw();
});
$("#postcode-search").on('change keyup', function(){
    let val = $("#postcode-search").val().trim();
    table.column(2).search(val).draw();
});


// NOT USED IN THIS CASE
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
// This try rearrange the search input widths accordingly
// table.on('column-visibility.dt', function(e, settings, column, state){
// 	$('#myTable thead tr:eq(1) th input, #myTable thead tr:eq(1) th select').css('width', '0%');
// 	$('#myTable thead tr:eq(1) th input, #myTable thead tr:eq(1) th select').css('opacity', '1');
// 	$('#myTable thead tr:eq(1) th input, #myTable thead tr:eq(1) th select').css('width', '100%');
// })


// setTimeout(function(){
//     $("#entityModal").modal('show');
// }, 1000);


// Fetch all the Entity Details  and show in modal
function viewEntity(event, id){

	event.preventDefault();

	$("#entityModal").modal('show');
    $("#entityModal .modal-body").css('opacity', '0.3').css('pointer-events', 'none');
	$('#entityModal .modal-header h5').html('<span class="fa fa-spin fa-spinner"></span>');

	$.ajax({
		type: "GET",
		url:   "/entities/"+id,
		dataType: 'json',
		success: function(data){

            $("#entityModal .modal-body").css('opacity', '1').css('pointer-events', 'auto');
	        $('#entityModal .modal-header h5').html('Entity Details');


			let entityData = data.data;

            // Function to format boolean values as 'Yes' or 'No'
            function formatBoolean(value) {
                return value == 1 ? 'Yes' : 'No';
            }
            
            // Set values in the modal
            $('#entity-id').text(entityData.id);
            $('#entity-name').text(entityData.name);
            $('#entity-main-contact').text(entityData.main_contact);
            $('#entity-tel').text(entityData.tel);
            $('#entity-type').text(entityData.type);
            $('#entity-heartbeat-on').text(formatBoolean(entityData.heartbeat_on));
            $('#entity-heartbeat').text(entityData.heartbeat);
            $('#entity-heartbeat-email').text(entityData.heartbeat_email);
            $('#entity-barcode').text(entityData.barcode);
            $('#entity-last-staff-update').text(entityData.last_staff_update);
            $('#entity-last-visitor-update').text(entityData.last_visitor_update);
            $('#entity-students-enabled').text(formatBoolean(entityData.students_enabled));
            $('#entity-pdf-background').text(entityData.pdf_background);
            $('#entity-last-access').text(entityData.last_access);
            $('#entity-barcode-prefix').text(entityData.barcode_prefix);
            $('#entity-ia-alert').text(entityData.ia_alert);
            $('#entity-badge-type').text(entityData.badge_type);
            $('#entity-onhold').text(entityData.onhold);
            $('#entity-school-type').text(entityData.school_type);
            $('#entity-version').text(entityData.version);
            $('#entity-full-mis').text(formatBoolean(entityData.full_mis));
            $('#entity-timezone').text(entityData.timezone);
            $('#entity-data-controller-name').text(entityData.data_controller_name);
            $('#entity-data-controller-address').text(entityData.data_controller_address);
            $('#entity-data-controller-email').text(entityData.data_controller_email);
            $('#entity-engineer-check-complete').text(formatBoolean(entityData.engineer_check_complete));
            $('#entity-last-modified').text(entityData.last_modified);
            $('#entity-test-account').text(formatBoolean(entityData.test_account));
            $('#entity-currency').text(entityData.currency);
            $('#entity-logo').text(entityData.logo);
            $('#entity-colour').text(entityData.colour);
            $('#entity-audit-mode').text(formatBoolean(entityData.audit_mode));
            $('#entity-verified').text(formatBoolean(entityData.verified));
            $('#entity-enabled').text(formatBoolean(entityData.enabled));



		}
	});
}


//============= DELETE ENTITY  FUNCTION  ============================
function deleteEntity(event, id){

    confirmAction("Confirm", "Do you want to delete this entity?", function(){
        $("#confirmModal").modal('hide');
        let elm = $('body'); 
        elm.css("pointer-events", "none").css("opacity", "0.8");

        let actions_td = $(`.table tr#${id} .actions`);
        let actions_html = actions_td.html();
        actions_td.html('<span class="fa fa-spinner fa-spin"></span>');
        
        $.ajax({
            type: "DELETE",
            url: `/entities/${id}`,
            success: function(data){	
                elm.css("pointer-events", "auto").css("opacity", "1");
                actions_td.closest('tr').remove(); //Remove the row
            },
            error: function (xhr, status, error) {
                actions_td.html(actions_html);
                elm.css("pointer-events", "auto").css("opacity", "1");
                render_errors(JSON.parse(xhr.responseText), 'toast');
            }
        });

    });


}



function attachPopover(event){

    // Delegate click event for dynamically created buttons with class .action_btn
    $('.action_btn').each(function(e) {

        event.preventDefault();
        elm = $(event.target).closest('a');

        let rdata = table.row(elm.closest('tr')).data();
        let id = rdata[ddt.id];


        // Hide all other popovers
        $('.action_btn[aria-describedby]').popover('hide');
    
        // Initialize popover only if it hasn't been initialized before
        if (! elm.hasClass('popover_nx')) {
            elm.popover({
            trigger: 'manual',  // We'll control when to show/hide
            // placement: 'bottom',
            html: true,
            content: `
              <div class="list-group" id="popupm_${id}">
                <a href="#" onclick="viewEntity(event, ${id})" class="d-none list-group-item list-group-item-action"><i class="fas fa-eye"></i> View</a>
                <a href="/entities/${id}/edit" class="list-group-item list-group-item-action"><i class="fas fa-edit"></i> Edit</a>
                <a href="#" onclick="alert();"  class="list-group-item list-group-item-action text-danger"><i class="fas fa-trash-alt"></i> Delete</a>
              </div>
            `
          });
          elm.addClass('popover_nx');
        //   elm.popover('show'); // Show popover for the clicked button
        } else {
          // Toggle popover for the clicked button if already initialized
          elm.popover('toggle');
        }
  
    });
  
}



$(document).ready(function() {
    
    // Close popover when clicking outside of it or the button
    $(document).on('click', function(e) {
      
        if (!$(e.target).closest('.action_btn, .popover').length) {
            $('.action_btn[aria-describedby]').popover('hide');
        }
    });

    // Ensure the viewEntity and deleteEntity functions work
    $(document).on('click', '.list-group-item-action', function(event) {
        var $link = $(this);
        
        if ($link.attr('href') === "#") {
        event.preventDefault();
        // Check the specific action based on the icon class
        if ($link.find('i').hasClass('fa-eye')) {
            // View action
            var id = $link.closest('.list-group').prop('id').replace('popupm_', '');
            
            viewEntity(event, id);
            
            // Hide all other popovers
            $('.action_btn[aria-describedby]').popover('hide');

        } else if ($link.find('i').hasClass('fa-trash-alt')) {
            // Delete action
            var id = $link.closest('.list-group').prop('id').replace('popupm_', '');
            deleteEntity(event, id);

            // Hide all other popovers
            $('.action_btn[aria-describedby]').popover('hide');
        }
        }
    });

});
  