// JavaScript Document


// JavaScript Document

var table = null;

let global_ch_dir = '';



let buttons = [
];

$(document).ready(function(){
	populateMonthOptions(MIN_DATE, MAX_DATE);
  
table = $('#myTable').DataTable( {
	"processing": true,
	// "serverSide": true,
	"ajax": {
        "url": "/admin/stats/monthly/data",
        "type": "GET",
        "data": function (d) {
            d.selected_date = $('#stats_month').val();  // Add the selected date
        }
    },
   "bSortCellsTop" : true,
   "rowId" : "0",
//    dom: '<B<t>l>',
dom: '<B<t>lp>',
	'initComplete' : function(setting, json){ //When table has been fully initialize
	},
	//responsive: true,
	'lengthMenu': [[50, 100, 200, 500, 2000, -1], [50, 100, 200, 500, 2000, 'All']],
    columns: [
		{ data: 0,searchable: false, render: function(data, b,c){ return formatDate(data);}},
		{ data: 1,searchable: false, render: function(data, b,c){ return formatNumber(data);}},
		{ data: 2,searchable: false, render: function(data, b,c){ return formatNumber(data);}},
		{ data: 3,searchable: false, render: function(data, b,c){ return formatNumber(data);}},
		{ data: 4,searchable: false, render: function(data, b,c){ return formatNumber(data);}},
		{ data: 5,searchable: false, render: function(data, b,c){ return formatNumber(data);}},
        { data: 6,searchable: false, render: function(data, b,c){ return formatNumber(data);}},
        { data: 7,searchable: false, render: function(data, b,c){ return formatNumber(data);}}
        // etc
    ], 
	"order" : [[0, "desc"]],
	//select: true,
	buttons: buttons,
    footerCallback: function (row, data, start, end, display) {
        var api = this.api();

        // Clear the previous footer totals
        $(api.column(1).footer()).html('');
        $(api.column(2).footer()).html('');
        $(api.column(3).footer()).html('');
        $(api.column(4).footer()).html('');
        $(api.column(5).footer()).html('');
        $(api.column(6).footer()).html('');
        $(api.column(7).footer()).html('');

        // Calculate totals for each column
        var totalActiveUsers = 0;
        var totalNewUsers = 0;
        var totalReturningUsers = 0;
        var totalVotes = 0;
        var totalTodayStories = 0;
        var totalStories = 0;
        var totalPmClick = 0;

        api.rows({ search: 'applied' }).every(function() {
            var data = this.data();

            totalActiveUsers += parseFloat(data[1]) || 0;
            totalNewUsers += parseFloat(data[2]) || 0;
            totalReturningUsers += parseFloat(data[3]) || 0;
            totalVotes += parseFloat(data[4]) || 0;
            let xtotalStories = parseFloat(data[5]) || 0;
            if(xtotalStories > totalStories){
                totalStories = xtotalStories;
            }
            totalTodayStories += parseFloat(data[6]) || 0;

            totalPmClick += parseFloat(data[7]) || 0;

        });

        // Update footer with totals
        $(api.column(1).footer()).html(formatNumber(totalActiveUsers));
        $(api.column(2).footer()).html(formatNumber(totalNewUsers));
        $(api.column(3).footer()).html(formatNumber(totalReturningUsers));
        $(api.column(4).footer()).html(formatNumber(totalVotes));
        $(api.column(5).footer()).html(formatNumber(totalStories));
        $(api.column(6).footer()).html(formatNumber(totalTodayStories));
        $(api.column(7).footer()).html(formatNumber(totalPmClick));
    }
} );
});



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

    // Parse the date string
    const date = new Date(dateString);

    // Extract components
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    const year = String(date.getFullYear()).slice(-2);

    // Convert to 12-hour format
    let hours = date.getHours();
    const ampm = hours >= 12 ? "PM" : "AM";
    hours = hours % 12 || 12;

    const minutes = String(date.getMinutes()).padStart(2, '0');
    const seconds = String(date.getSeconds()).padStart(2, '0');

    // Format output as "MM/DD/YY hh:mm:ss AM/PM"
    // return `${month}/${day}/${year} ${String(hours).padStart(2, '0')}:${minutes}:${seconds} ${ampm}`;
    return `${month}/${day}/${year}`;
}



function formatNumber(numString) {
    // Check if numString is empty or null
    if (!numString) {
        return "";
    }

    // Convert the string to a number (parseFloat will handle decimals)
    const number = parseFloat(numString);

    // If it's not a valid number, return an empty string
    if (isNaN(number)) {
        return  numString;
    }

    // Format the number according to local conventions (e.g., adding commas for thousands)
    return number.toLocaleString();  // Default locale or you can specify one, e.g., 'en-US'
}










// Assume minDate and maxDate are provided as 'YYYY-MM-DD' format
function populateMonthOptions(minDateString, maxDateString) {

    // Check the format of minDateString and append EST timezone if missing
    if (!minDateString.includes("T") && !minDateString.includes("Z") && !minDateString.match(/[+-]\d{2}:\d{2}$/)) {
        // Case for "YYYY-MM-DD" (date only): add time and EST timezone
        if (minDateString.match(/^\d{4}-\d{2}-\d{2}$/)) {
            minDateString += "T00:00:00-05:00"; // Assume midnight EST
        }
        // Case for "YYYY-MM-DD HH:mm:ss" (date with time but no timezone)
        else if (minDateString.match(/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/)) {
            minDateString = minDateString.replace(" ", "T") + "-05:00"; // Convert to ISO format with EST
        }
    }

    // Check the format of maxDateString and append EST timezone if missing
    if (!maxDateString.includes("T") && !maxDateString.includes("Z") && !maxDateString.match(/[+-]\d{2}:\d{2}$/)) {
        // Case for "YYYY-MM-DD" (date only): add time and EST timezone
        if (maxDateString.match(/^\d{4}-\d{2}-\d{2}$/)) {
            maxDateString += "T00:00:00-05:00"; // Assume midnight EST
        }
        // Case for "YYYY-MM-DD HH:mm:ss" (date with time but no timezone)
        else if (maxDateString.match(/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/)) {
            maxDateString = maxDateString.replace(" ", "T") + "-05:00"; // Convert to ISO format with EST
        }
    }

    // Parse minDate and maxDate
    const min = new Date(minDateString);
    const max = new Date(maxDateString);
    
    const $select = $('#stats_month');
    $select.empty(); // Clear existing options if any

    // Loop from maxDate to minDate, one month at a time (descending)
    for (let d = new Date(max); d >= min; d.setMonth(d.getMonth() - 1)) {
        // Create label "Month Year" and value "Y-m-1"
        const month = d.toLocaleString('default', { month: 'long' });
        const year = d.getFullYear();
        const value = `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-01`;

        // Create an option element and append it to the select
        const option = $('<option></option>').val(value).text(`${month} ${year}`);
        $select.append(option);
    }

	if ($select.find('option').length > 10) {
		$select.removeClass('form-control');
        $select.selectize({
            create: false,
            sortField: 'text'
        });
    }

}
