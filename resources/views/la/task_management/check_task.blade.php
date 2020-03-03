@extends("la.layouts.app")

<?php
use Dwij\Laraadmin\Models\Module;
?>

@section("contentheader_title", "Checking Task Assignment")
@section("contentheader_description", "Checking Tasks listing")
@section("section", "Checking Task Assignment")
@section("sub_section", "Checking Tasks listing")
@section("htmlheader_title", "Checking Tasks listing")

@section("main-content")

<div class="nav-tabs-custom">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#fa-icons" data-toggle="tab" aria-expanded="false">Lists</a></li>
		<li class=""><a href="#fa-charts" data-toggle="tab" aria-expanded="false">Charts</a></li>
		<li class=""><a href="#calendar" data-toggle="tab" aria-expanded="true">Calendar</a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="fa-icons">
			<div class="form-group col-md-2">
				<label for="icon">Time Frame :</label>
					<select class="form-control" rel="select2" data-placeholder="Select Type">
						<option value="daily" selected>Daily</option>
						<option value="weekly">Weekly</option>
						<option value="monthly">Monthly</option>
						<option value="yearly">Yearly</option>
						<option value="once">Once</option>
					</select>
			</div>
			<div class="form-group col-md-2">
				<label for="icon">Status :</label>
					<select class="form-control" rel="select2" data-placeholder="Select Type">
						<option>On Progress</option>
						<option>Done</option>
						<option>Approved</option>
						<option>Rejected</option>
					</select>
			</div>
			<div class="form-group col-md-2">
				<label for="icon">Assignee :</label>
					<select class="form-control" rel="select2" data-placeholder="Select Type">
						<option>Aye Min</option>
						<option>Phyo Thandar</option>
						<option>Su Sandar</option>
					</select>
			</div>
			<div class="form-group col-md-2">
				<label>From Date</label>
				<div class='input-group date' id='datetimepicker1'>
					<input type='text' class="form-control" placeholder="Choose Date"/>
					<span class="input-group-addon">
						<span class="glyphicon glyphicon-calendar"></span>
					</span>
				</div>
			</div>
			<div class="form-group col-md-2">
				<label>To Date</label>
				<div class='input-group date' id='datetimepicker1'>
					<input type='text' class="form-control" placeholder="Choose Date"/>
					<span class="input-group-addon">
						<span class="glyphicon glyphicon-calendar"></span>
					</span>
				</div>
			</div>
			<div class="box-body">
				<table id="dt_modules" class="table table-bordered table-striped">
					<thead>
					<tr>
						<th>ID</th>
						<th class="col-sm-3">Task Title</th>
						<th>PIC</th>
						<th>Time Frame</th>
						<th>Assigned Date</th>
						<th>Finished Date</th>
						<th class="col-sm-1">Status</th>
						<th>Actions</th>
					</tr>
					</thead>
					<tbody>	
						<tr>
							<td>1</td>
							<td><a href="#">Task 1</a></td>
							<td>Mg Mg</td>
							<td>Daily</td>
							<td></td>
							<td></td>
							<td><small class="label label-warning">On Progress</small></td>
							<td><a href="#" class="btn btn-xs btn-primary" style="display:inline;padding:2px 5px 3px 5px;"  data-toggle="modal" data-target="#ModifyModal">Extend Due Date</a></td>
						</tr>
						<tr>
							<td>2</td>
							<td><a href="#"><span style="color:red;">Task 2</span></a></td>
							<td>Aye Aye</td>
							<td>Weekly</td>
							<td>2019-05-12</td>
							<td>2019-05-28</td>
							<td><small class="label label-danger">Rejected</td>
							<td></td>
						</tr>
						<tr>
							<td>3</td>
							<td><a href="#">Task 3</a></td>
							<td>Khaing Zin</td>
							<td>Monthly</td>
							<td>2019-05-12</td>
							<td>2019-05-28</td>
							<td><small class="label label-success">Approved</td>
							<td></td>
						</tr>
						<tr>
							<td>4</td>
							<td><a href="#"><span style="color:red;">Task 4</span></a></td>
							<td>Mon Mon</td>
							<td>Monthly</td>
							<td>2019-07-12</td>
							<td>2019-07-28</td>
							<td><small class="label label-primary">Done</td>
							<td><a href="#" class="btn btn-xs btn-success" style="display:inline;padding:2px 5px 3px 5px;">Approve</a>   <a href="#" class="btn btn-xs btn-danger" style="display:inline;padding:2px 5px 3px 5px;" data-toggle="modal" data-target="#AddModal">Reject</a></td>
						</tr>
						<tr>
							<td>5</td>
							<td><a href="#">Task 5</a></td>
							<td>Khaing Thinzar</td>
							<td>Yearly</td>
							<td></td>
							<td></td>
							<td><small class="label label-warning">On Progress</td>
							<td><a href="#" class="btn btn-xs btn-primary" style="display:inline;padding:2px 5px 3px 5px;"  data-toggle="modal" data-target="#ModifyModal">Extend Due Date</a></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="tab-pane" id="calendar">
			<div id="calendar"></div>
		</div>
		<div class="tab-pane" id="fa-charts">
			<div>
				<div class="row">
					<div class="form-group col-md-2">
						<label>From Date</label>
						<div class='input-group date' id='datetimepicker1'>
							<input type='text' class="form-control" placeholder="Choose Date"/>
							<span class="input-group-addon">
								<span class="glyphicon glyphicon-calendar"></span>
							</span>
						</div>
					</div>
					<div class="form-group col-md-2">
						<label>To Date</label>
						<div class='input-group date' id='datetimepicker1'>
							<input type='text' class="form-control" placeholder="Choose Date"/>
							<span class="input-group-addon">
								<span class="glyphicon glyphicon-calendar"></span>
							</span>
						</div>
					</div>
				</div>
				<div class="row">
					<div id="barchart"></div>
				</div>	
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="ModifyModal" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Confirmation</h4>
			</div>
			<div class="modal-body">
				<div class="box-body">
					<div class="form-group">
						<label>Current Due Date</label>
						<div class="input-group date"><input class="form-control" readonly data-rule-minlength="0" name="date_birth" type="text" value="08/01/2020"><span class="input-group-addon input_dt"><span class="fa fa-calendar"></span></span></div>
					</div>
					<div class="form-group">
						<label>Extend Due Date</label>
						<div class="input-group date"><input class="form-control" placeholder="Enter Extend Due Date" data-rule-minlength="0" name="date_birth" type="text"><span class="input-group-addon input_dt"><span class="fa fa-calendar"></span></span></div>
					</div>
					<div class="form-group">
						<label for="name">Remark :</label>
						<textarea class="form-control module_label_edit" placeholder="Remark" name="Remark" value=""></textarea>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-sm btn-success" data-dismiss="modal">Modify</button>
				<button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cancel</button>
			</div>
		</div>
	</div>
</div>

<!-- Details -->
<div class="modal fade" id="AddModal" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Reject Confirmation</h4>
			</div>
			<div class="modal-body">
				<div class="box-body">
					<div class="form-group">
						<label for="name">Reason :</label>
						<textarea class="form-control module_label_edit" placeholder="Reason" name="Reason"></textarea>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Reject</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
			</div>
		</div>
	</div>
</div>  
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('la-assets/plugins/datatables/datatables.min.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('la-assets/css/apexcharts.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('la-assets/css/calendar.css') }}"/>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.css" />
@endpush

@push('scripts')
<script src="{{ asset('la-assets/plugins/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('la-assets/js/apexcharts.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.js"></script>
<script>
$(function () {
    $("#example1").DataTable({
        "bLengthChange": false,
    });
	$('#calendar').fullCalendar({
	    // put your options and callbacks here
	    left:   'title',
	    header: {
			left: 'prev,next today',
			center: 'title',
			right: 'month,basicWeek,basicDay,listWeek'
		},
	    weekNumbers: true,
	    defaultView: 'month',
	    events: [
	        {
	            start: '2020-01-19',
	            title: 'Make the theme responsive',
	            className: 'event-full'

	        },
	        {
	            start: '2020-01-20',
	            title: 'full',
	            className: 'event-full'

	        },
	        {
	            start: '2020-01-20',
	            title: 'Make the theme responsive',
	            className: 'event-full'

	        }
	    ]
	});
	var options = {
        series: [{
          name: 'Todo',
          data: [44, 55, 41, 37, 22, 43, 21]
        }, {
          name: 'Approved',
          data: [53, 32, 33, 52, 13, 43, 32]
        }, {
          name: 'Done',
          data: [9, 7, 5, 8, 6, 9, 4]
        }, {
          name: 'Overdue',
          data: [12, 17, 11, 9, 15, 11, 20]
        }],
        chart: {
          type: 'bar',
          height: 350,
          stacked: true,
        },
        plotOptions: {
          bar: {
            horizontal: true,
          },
        },
        stroke: {
          width: 1,
          colors: ['#fff']
        },
        title: {
          text: 'Members'
        },
        xaxis: {
          categories: ['Mg Mg', 'Khine Zin', 'Aung Khant', 'Thiha', 'Aung aung', 'Myo Myo', 'Hla Hla'],
          labels: {
            formatter: function (val) {
              return val
            }
          }
        },
        yaxis: {
          title: {
            text: undefined
          },
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return val
            }
          }
        },
        fill: {
          opacity: 0.8
        },
        legend: {
          position: 'top',
          horizontalAlign: 'left',
          offsetX: 40
        }
      };

      var chart = new ApexCharts(document.querySelector("#barchart"), options);
      chart.render();
//     $(document).ready(function() {
//     var groupColumn = 1;
//     var table = $('#example1').DataTable({
//         "bLengthChange": false,
//         "columnDefs": [
//             { "visible": false, "targets": groupColumn }
//         ],
//         "order": [[ groupColumn, 'asc' ]],
//         "displayLength": 25,
//         "drawCallback": function ( settings ) {
//             var api = this.api();
//             var rows = api.rows( {page:'current'} ).nodes();
//             var last=null;
 
//             api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {
//                 if ( last !== group ) {
//                     $(rows).eq( i ).before(
//                         '<tr class="group"><td colspan="5">'+group+'</td></tr>'
//                     );
 
//                     last = group;
//                 }
//             } );
//         }
//     } );
 
//     // Order by the grouping
//     $('#example tbody').on( 'click', 'tr.group', function () {
//         var currentOrder = table.order()[0];
//         if ( currentOrder[0] === groupColumn && currentOrder[1] === 'asc' ) {
//             table.order( [ groupColumn, 'desc' ] ).draw();
//         }
//         else {
//             table.order( [ groupColumn, 'asc' ] ).draw();
//         }
//     } );
// } );
    
});
</script>
@endpush
