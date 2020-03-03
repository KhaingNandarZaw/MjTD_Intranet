@extends("la.layouts.app")

@section("contentheader_title", "Events")
@section("contentheader_description", "Events listing")
@section("section", "Events")
@section("sub_section", "Listing")
@section("htmlheader_title", "Events Listing")

@section("headerElems")

@endsection

@section("main-content")
<div class="box box-success">
	<!--<div class="box-header"></div>-->
	<div class="box-body">
		<div id="calendar"></div>
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
    background-color: rgb(255, 0, 0);
    opacity: 0.8;
  }
  .event-finish{
    background-color: rgb(0, 128, 0);
    opacity: 0.8;
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
	            start: '2020-01-10',
	            title: 'Task Two',
	            className: 'event-full event-finish'
	        },
          {
	            start: '2020-01-13',
	            title: 'Task Two',
	            className: 'event-full event-finish'
	        },
          {
	            start: '2020-01-16',
	            title: 'Task Two',
	            className: 'event-full event-finish'
	        },
	        {
	            start: '2020-01-15',
	            title: 'Make the theme responsive',
	            className: 'event-full event-overdue'
	        },
	        {
	            start: '2020-01-20',
	            title: 'full',
	            className: 'event-full event-finish'
	        },
	        {
	            start: '2020-01-22',
	            title: 'full',
	            className: 'event-full event-overdue'
	        },
	        {
	            start: '2020-01-20',
	            title: 'Make the theme responsive',
	            className: 'event-full event-overdue'
	        },
	        {
	            start: '2020-01-25',
	            title: 'Make the theme responsive',
	            className: 'event-full event-onprogress'
	        },
	        {
	            start: '2020-01-28',
	            title: 'Make the theme responsive',
	            className: 'event-full event-onprogress'
	        },
	        {
	            start: '2020-01-29',
	            title: 'Make the theme responsive',
	            className: 'event-full event-onprogress'
	        } ,
	        {
	            start: '2020-01-29',
	            title: 'Task One',
	            className: 'event-full event-onprogress'
	        }
	    ]
	});
});
</script>

@endpush
