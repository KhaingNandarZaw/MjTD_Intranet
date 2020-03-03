@extends('la.layouts.app')

@section('htmlheader_title')
    Task View
@endsection


@section('main-content')
<div id="page-content" class="profile2">
    
    <ul data-toggle="ajax-tab" class="nav nav-tabs profile" role="tablist">
        <li class=""><a href="{{ url(config('laraadmin.adminRoute') . '/tasks') }}" data-toggle="tooltip" data-placement="right" title="Back to Tasks"><i class="fa fa-chevron-left"></i></a></li>
        <li class="active"><a role="tab" data-toggle="tab" class="active" href="#tab-general-info" data-target="#tab-info"><i class="fa fa-bars"></i> General Info</a></li>
        <!-- <li><a role="tab" data-toggle="tab" href="#tab-general-info" data-target="#tab-calendar"><i class="fa fa-calendar"></i> Task Lists By Calendar</a></li> -->
    </ul>

    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active fade in" id="tab-info">
            <div class="tab-content">
                <div class="panel infolist">
                    <div class="panel-default panel-heading">
                        <h4>General Info</h4>
                    </div>
                    <div class="panel-body">
                    <?php $time_frame = DB::table('frames')->wherenull('deleted_at')->where('id', $task->time_frame)->first(); ?>
                        @la_display($module, 'name', '', 'write')
                        @la_display($module, 'description', '', 'write')
                        @la_display($module, 'pic_userid', '', 'write')
                        @la_display($module, 'report_to_userid', '', 'write')
                        @la_display($module, 'priority', '', 'write')
                        @la_display($module, 'time_frame', '', 'write')
                        <div class="form-group" id="every_interval" style="display:none;">
                            <label class="col-md-4 col-sm-6 col-xs-6">Every Interval :</label>
                            <div class="col-md-8 col-sm-6 col-xs-6">{{$task->every_interval}}</div>
                        </div>
                        <div class="form-group" id="due_date" style="display:none;">
                            <label class="col-md-4 col-sm-6 col-xs-6">Due Date :</label>
                            <?php $dt = strtotime($task->due_date); $value = date("d M Y", $dt); ?>
                            <div class="col-md-8 col-sm-6 col-xs-6">{{ $value }}</div>
                        </div>
                        <div class="form-group" id="day_of_week" style="display:none;">
                            <label class="col-md-4 col-sm-6 col-xs-6">Week :</label>
                            <div class="col-md-8 col-sm-6 col-xs-6">{{$task->dayofweek}}</div>
                        </div>
                        <div class="form-group" id="monthly_type" style="display:none;">
                            <label class="col-md-4 col-sm-6 col-xs-6">Monthly Type :</label>
                            <div class="col-md-8 col-sm-6 col-xs-6">{{$task->monthly_type}}</div>
                        </div>
                        <div class="form-group" id="day" style="display:none;">
                            <label class="col-md-4 col-sm-6 col-xs-6">Every Day of Month :</label>
                            <div class="col-md-8 col-sm-6 col-xs-6">{{$task->day}}</div>
                        </div>
                        <div class="form-group" id="week" style="display:none;">
                            <label class="col-md-4 col-sm-6 col-xs-6">Week :</label>
                            <div class="col-md-8 col-sm-6 col-xs-6">{{$task->week}}</div>
                        </div>
                        <div class="form-group" id="monthly_day_of_week" style="display:none;">
                            <label class="col-md-4 col-sm-6 col-xs-6">At :</label>
                            <div class="col-md-8 col-sm-6 col-xs-6">{{$task->monthly_dayofweek}}</div>
                        </div>
                        <div class="form-group" id="startDate" style="display:none;">
                            <label class="col-md-4 col-sm-6 col-xs-6">Start Date :</label>
                            <?php $dt = strtotime($task->start_date); $value = date("d M Y", $dt); ?>
                            <div class="col-md-8 col-sm-6 col-xs-6">{{ $value }}</div>
                        </div>
                        <div class="form-group" id="terminationDate" style="display:none;">
                            <label class="col-md-4 col-sm-6 col-xs-6">End Date :</label>
                            <?php $dt = strtotime($task->termination_date); $value = date("d M Y", $dt); ?>
                            <div class="col-md-8 col-sm-6 col-xs-6">{{ $value }}</div>
                        </div>
                        @la_display($module, 'remark', '', 'write')
                        <div class="form-group">
                            <label class="col-md-4 col-sm-6 col-xs-6">Attachments :</label>
                            <div class="col-md-8 col-sm-6 col-xs-6 fvalue">
                                <?php
                                $value = $task->attachments;
                                if($value != "" && $value != "[]" && $value != "null" && starts_with($value, "[")) {
                                    $uploads = json_decode($value);
                                    $uploads_html = "";
                                    
                                    foreach($uploads as $uploadId) {
                                        $upload = DB::table('task_attachments')->whereNull('deleted_at')->where('id', $uploadId)->first();;
                                        if(isset($upload->id)) {
                                            $uploadIds[] = $upload->id;
                                            $fileImage = "";
                                            
                                                $fileImage = "<i class='fa fa-file-o'></i> " . $upload->filename;
                                            
                                            $uploads_html .= '<a class="preview" target="_blank" href="' . url("task_attachments/" . $upload->hash . DIRECTORY_SEPARATOR . $upload->filename) . '" data-toggle="tooltip" data-placement="top" data-container="body" style="display:inline-block;margin-right:5px;" title="' . $upload->filename . '">
                                                    ' . $fileImage . '</a><br/>';
                                        }
                                    }
                                    $value = $uploads_html;
                                } else {
                                    $value = 'No files found.';
                                }
                                echo $value;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane fade in" id="tab-calendar">
            <div class="tab-content">
                <div class="panel infolist">
                    <div class="panel-default panel-heading">
                        <h4>Task Lists By Calendar</h4>
                    </div>
                    <div class="panel-body">
                        <div id="fullCalendar"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('la-assets/css/calendar.css') }}"/>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.js"></script>
<script>
$(function(){
    var time_frame = "<?php echo $time_frame->name ?>"; 
    var radioValue = "<?php echo $task->monthly_type ?>";

    var task_id = "<?php echo $task->id ?>";
    
    $("#due_date").css('display', 'none');
    $("#day_of_week").css('display', 'none');
    $("#every_interval").css('display', 'none');
    $("#startDate").css('display', 'none');
    $("#terminationDate").css('display', 'none');
    $("#week").css('display', 'none');
    $("#day").css('display', 'none');
    $("#monthly_type").css('display', 'none');
    $("#monthly_day_of_week").css('display', 'none');
    
    if(time_frame == 'Once'){
        $("#due_date").css('display', 'block');
    } else if(time_frame == 'Daily'){
        $("#every_interval").css('display', 'block');
        $("#startDate").css('display', 'block');
        $("#terminationDate").css('display', 'block');
    } else if(time_frame == 'Weekly'){
        $("#every_interval").css('display', 'block');
        $("#startDate").css('display', 'block');
        $("#terminationDate").css('display', 'block');
        $("#day_of_week").css('display', 'block');
    } else if(time_frame == 'Monthly'){
        if(radioValue == 'Day Of Week'){
            $("#week").css('display', 'block');
            $("#monthly_day_of_week").css('display', 'block');
        } else if(radioValue == 'Day Of Month'){
            $("#day").css('display', 'block');
        }
        $("#every_interval").css('display', 'block');
        $("#startDate").css('display', 'block');
        $("#terminationDate").css('display', 'block');
    }

    $('#fullCalendar').fullCalendar({
	    // put your options and callbacks here
	    left:   'title',
	    header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month,basicYear,basicWeek,basicDay,listWeek'
      },
	    defaultView: 'month',
        // events: "{{ url('/fullcalendar') }}",
        events: function(start, end, timezone, callback) {
            $.ajax({
                url: "{{ url('/fullcalendar_bytask') }}",
                type: 'POST',
                dataType: 'json',
                data: {
                    '_token': '{{ csrf_token() }}',
                    start: start.format(),
                    end: end.format(),
                    task_id : task_id
                },
                success: function(doc) {
                    var events = [];
                    if(!!doc.result){
                        $.map( doc.result, function( r ) {
                            events.push({
                                id: r.id,
                                title: r.title,
                                start: r.date_start,
                                end: r.date_end
                            });
                        });
                    }
                    callback(events);
                }
            });
        }
	});
})
</script>
@endpush
