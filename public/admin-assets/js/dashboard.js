let glob_charts = {};

let allChartData = [
	{
		key: 'daily_active_users',
		label: 'DAILY',
		title: 'Active Users',
		unit: 'day',
		display_format: '',
		data: [],
		time: [],
		color: "blue"
	},
	{
		key: 'daily_new_users',
		label: 'DAILY',
		title: 'New Users',
		unit: 'day',
		display_format: '',
		data: [],
		time: [],
		color: "green"
	},
	{
		key: 'daily_returning_users',
		label: 'DAILY',
		title: 'Returning Users',
		unit: 'day',
		display_format: '',
		data: [],
		time: [],
		color: "orange"
	},
	{
		key: 'monthly_active_users',
		label: 'MONTHLY',
		title: 'Active Users',
		unit: 'month',
		display_format: '',
		data: [],
		time: [],
		color: "black"
	},
	{
		key: 'monthly_new_users',
		label: 'MONTHLY',
		title: 'New Users',
		unit: 'month',
		display_format: '',
		data: [],
		time: [],
		color: "red"
	},
	{
		key: 'monthly_returning_users',
		label: 'MONTHLY',
		title: 'Returning Users',
		unit: 'month',
		display_format: '',
		data: [],
		time: [],
		color: "brown"
	}
];


$(document).ready(function(){

	updateMetrics();

	$(".chart-card").on('click', 'button.zoom_plus', function(){
		let type = $(this).closest('.chart-card').data('type');
		let chart = glob_charts[type];
		chart.zoom(1.1);
	});
	$(".chart-card").on('click', 'button.zoom_minus', function(){
		let type = $(this).closest('.chart-card').data('type');
		let chart = glob_charts[type];
		chart.zoom(0.9);
	});
	$(".chart-card").on('click', 'button.zoom_home', function(){
		let type = $(this).closest('.chart-card').data('type');
		let chart = glob_charts[type];
		chart.resetZoom();
	});

	$('a[data-toggle="toggle-content"]').click(function (e) {
        e.preventDefault();
        
        const target = $(this).attr('href');
        
        // If "Both" is selected, show both `chartDiv` and `tableDiv`
        if (target === "#bothDiv") {
          $('#chartDiv, #tableDiv').addClass('active-content'); // Show both divs
        } else {
          // Hide all content
          $('.toggle-content').removeClass('active-content');
          // Show only the selected content
          $(target).addClass('active-content');
        }

        // Toggle active state on nav links
        $('a[data-toggle="toggle-content"]').removeClass('active');
        $(this).addClass('active');
    });

	if (window.innerWidth > 800) {
		$("ul.navbar-nav li.nav-item a.nav-link i.fas.fa-bars").parent()[0].click();
	}


});

function updateMetrics(){
	
	let date = $("#daterange_n").val().trim();
	
	$(".form_error").remove();

	$(".metric-card table td").html('<small><span class="fa fa-spin fa-spinner"></span></small>');;

	$("body").css('opacity', '0.3').css('pointer-events', 'none');
   
   $.ajax({
	 type: "GET",
	 url:   "/admin/dashboard?date="+date,
	 dataType: 'json',
	 success: function(data){		     
		$("body").css('opacity', '1').css('pointer-events', 'auto');
		$(".metric-card table td").html('');

		let val = 0;
		for(key in data.data){
			val = data.data[key];
			$(`#${key}`).html(processNumber(val));
		}

		let light_mode_users = data.data.light_mode_users;
		let dark_mode_users = data.data.dark_mode_users;

		totalModeUser = 0;
		if(light_mode_users && dark_mode_users){
			totalModeUser = light_mode_users + dark_mode_users;
		}
		else if(light_mode_users){
			totalModeUser = light_mode_users;
		}
		else if(dark_mode_users){
			totalModeUser = dark_mode_users;
		}

		if(light_mode_users){
			let light_mode_users_per =  (light_mode_users / totalModeUser) * 100 ;
			light_mode_users_per = parseFloat(light_mode_users_per.toFixed(1));
			$("#light_mode_users").html(`${light_mode_users} (${light_mode_users_per}%)`);
		}
		else{
			$("#light_mode_users").html('');
		}

		if(dark_mode_users){
			let dark_mode_users_per =  (dark_mode_users / totalModeUser) * 100 ;
			dark_mode_users_per = parseFloat(dark_mode_users_per.toFixed(1));
			$("#dark_mode_users").html(`${dark_mode_users} (${dark_mode_users_per}%)`);
		}
		else{
			$("#dark_mode_users").html('');
		}


				 
	},
	error: function (xhr, status, error) {
		$("body").css('opacity', '1').css('pointer-events', 'auto');
		$(".metric-card table td").html('');
		render_errors(JSON.parse(xhr.responseText), '#errmsg');
	}
	});
	
}


function processNumber(num){
	if(!num){
		return num;
	}

	try{
		 num =  num.toLocaleString('en-US');
		 return num;
	}
	catch(exception){
		return num;
	}


}



loadCharts();

function loadCharts(){
	
	$(".chart-card card-body").html('<small><span class="fa fa-spin fa-spinner"></span></small>');;
   
   $.ajax({
	 type: "GET",
	 url:   "/admin/charts",
	 dataType: 'json',
	 success: function(data){		     
		$(".chart-card card-body").html('');
		
		let nChartData = allChartData;

		data.data.forEach(thisData => {
		
			for(let ind = 0; ind < 3; ind++){
			
				//  var time = moment(data_ra[0]).format('HH:mm');
				if(thisData[(ind + 1)]){
					nChartData[ind]['data'].unshift(parseInt(thisData[(ind + 1)]));
					// nChartData[ind]['time'].unshift(thisData[0]);
					nChartData[ind]['time'].unshift(thisData[0]);
				}

			}
		});

		data.data2.forEach(thisData => {
		
			for(let ind = 0; ind < 3; ind++){
			
				//  var time = moment(data_ra[0]).format('HH:mm');
				if(thisData[(ind + 1)]){
					nChartData[(ind + 3)]['data'].unshift(parseInt(thisData[(ind + 1)]));
					// nChartData[ind]['time'].unshift(thisData[0]);
					let date_n =   thisData[0] + "-" + getLastDateOfMonth(thisData[0] + "-01");
					nChartData[(ind + 3)]['time'].unshift(date_n);
				}

			}
		});


		// alert(JSON.stringify(nChartData[0]));
		plot_chartJS_now(nChartData[0].key, nChartData[0]);
		plot_chartJS_now(nChartData[1].key, nChartData[1]);
		plot_chartJS_now(nChartData[2].key, nChartData[2]);
		plot_chartJS_now(nChartData[3].key, nChartData[3]);
		plot_chartJS_now(nChartData[4].key, nChartData[4]);
		plot_chartJS_now(nChartData[5].key, nChartData[5]);

		
	},
	error: function (xhr, status, error) {
		$(".chart-card card-body").html('');
		render_errors(JSON.parse(xhr.responseText), '#errmsg');
	}
	});
	
}




function plot_chartJS_now(type, chartData){
	
	$(`.chart_${type}`).html(`<canvas id="chart_${type}" width="100%"></canvas>`);

	$(`.chart_${type}`).prepend(`<div class="text-right chart-btn"><button class="btn zoom_plus btn-primary btn-sm mx-1"><i class="fa fa-search-plus"></i></button><button class="btn zoom_home btn-secondary btn-sm mx-1"><i class="fa fa-square-o"></i></button><button class="btn zoom_minus btn-primary btn-sm mx-1"><i class="fa fa-search-minus"></i></button></div>`);


	
	var canvas = document.getElementById(`chart_${type}`);
	glob_charts[type] =  new Chart(canvas, {
	  type: 'line',
	  data: {
		labels: chartData.time,
		datasets: [{
		//   label: chartData.label,
		  yAxisID: 'A',
		  data: chartData.data,
		  borderColor: chartData.color,
		  backgroundColor: chartData.color,
		  fill: false,
		  borderWidth: 1.4,    // It determine the thickness of the plot line
		  lineTension: 0.1,  //0, 2, 3  It determine the line curve
		  pointRadius : 1.3,   // It determine the radious of the point marker
		//   pointHoverRadius : 3,
		  pointHoverRadius: 7

		}]
	  },
	  options: {
		scales: {
		
			A: {
					title: {
						display: false,
						text: chartData.label
					},
					beginAtZero: true,
					min: 0,
					ticks: {
						beginAtZero: true,
						min: 0
					}
			},
		  x: 
			{
				type: 'time',
				time: {
							unit: chartData.unit, //day, week,  month, quarter, year
							displayFormats:{
								// 'day' : 'DD-MMM-YY', //week,  month, quarter, year
							}
						},
				distribution: 'series',
				// distribution: 'linear',
                display: false,
                scaleLabel: {
                    display: true,
                    labelString: "Time",
                },
				title: {
					display: false,
					text: 'Time'
				}
            }
		
		},
		plugins: {
			legend: {
				display: false
			},
			title: {
				display: false,
				text: chartData.title
			},
			zoom: {
			  zoom: {
				wheel: {
				  enabled: true,
				},
				drag:{
					enabled: true
				},
				pinch: {
				  enabled: true
				},
				mode: 'xy',
			  },
			  pan: {
				// pan options and/or events
				enabled: false,
				mode: 'xy'
			  },
			  limits: {
				// axis limits
			  }
			}
		}	
	}
	  
	});

}


function getLastDateOfMonth(dateString) {
    const date = new Date(dateString);
    const year = date.getFullYear();
    const month = date.getMonth() + 1; // Months are zero-based, so add 1

    // Create a new date for the next month and set day to 0
    // This rolls the date back to the last day of the previous month
    const lastDate = new Date(year, month, 0);

    return lastDate.getDate();
}


function getLastDateOfMonth(dateString) {
    const date = new Date(dateString);
    const year = date.getFullYear();
    const month = date.getMonth() + 1; // Months are zero-based, so add 1

    // Create a new date for the next month and set day to 0
    // This rolls the date back to the last day of the previous month
    const lastDate = new Date(year, month, 0);

    return lastDate.getDate();
}

