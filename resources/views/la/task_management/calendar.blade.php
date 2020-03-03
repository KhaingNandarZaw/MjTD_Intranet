@extends("la.layouts.app")

@section("contentheader_title", "Events")
@section("contentheader_description", "Events listing")
@section("section", "Events")
@section("sub_section", "Listing")
@section("htmlheader_title", "Events Listing")

@section("headerElems")

@endsection

@section("main-content")
<div class="row">
	<div class="col-sm-8">
		<div class="box box-success">
			<div class="box-body">
				<div id="calendar"></div>
				
			</div>
		</div>
	</div>
	<div class="col-sm-4" id="task_detail" style="display:none;">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">Task Detail</h3>
				<div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
				</div>
			</div>
			<div class="box-body">
				<div class="form-group col-sm-12">
					<label class="col-sm-6">Task Title :</label><span id="task_name"></span>
				</div>
				<div class="form-group col-sm-12">
					<label class="col-sm-6">Description :</label><span id="main_description"></span>
				</div>
				<div class="form-group col-sm-12">
					<label class="col-sm-6">Report To :</label><span id="report_to"></span>
				</div>
				<div class="form-group col-sm-12">
					<label class="col-sm-6">Assigned By :</label><span id="assigned_by"></span>
				</div>
				<div class="form-group col-sm-12">
					<label class="col-sm-6">Time Frame :</label><span id="time_frame"></span>
				</div>
				<div class="form-group col-sm-12">
					<label class="col-sm-6">To Finish At :</label><span id="task_date"></span>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('la-assets/plugins/datatables/datatables.min.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('la-assets/css/calendar.css') }}"/>
<!-- <link rel="stylesheet" type="text/css" href="{{ asset('la-assets/css/fullcalendar.css') }}"/> -->
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.css" />
<style>
.event-overdue{
    background-color: rgb(240, 80, 80);
  }
  .event-finish{
    background-color: rgb(61, 153, 112);
  }
  .event-onprogress{
    background-color: rgb(57, 204, 204);
  }
  .event-approved-overdue{
    background-color: rgb(243, 156, 18);
  }
  .event-onprogress{
    background-color: rgb(57, 204, 204);
  }
</style>
@endpush

@push('scripts')
<script src="{{ asset('la-assets/plugins/datatables/datatables.min.js') }}"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.js"></script>
<script>
$(function () {
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

	$("#task_detail").hide();

	$('#calendar').fullCalendar({
	    // put your options and callbacks here
		aspectRatio: 2,
	    left:   'title',
	    header: {
			left: 'prev,next today',
			center: 'title',
			right: 'month,basicWeek,basicDay,listWeek'
		},
	    defaultView: 'month',
	    events: "{{ url('/fullcalendar') }}",
		eventClick: function (event) {
			$("#task_detail").hide();
			$.ajax({
				type: "POST",
				url: "{{ url('/fullcalendar/getData') }}",
				data: {'_token': '{{ csrf_token() }}', 'id' : event.id, 'title' : event.title},
				success: function (response) {
					// console.log(response);
					if(response != null){
						$("#task_detail").show();
						$("#task_name").html(response.name);
						$("#main_description").html(response.MainDescription);
						$("#time_frame").html(response.time_frame);
						$("#task_date").html(response.task_date);
						$("#report_to").html(response.reportTo);
						$("#assigned_by").html(response.assignedBy);
					}
				}
			});
		}
	});
});
</script>

@endpush
