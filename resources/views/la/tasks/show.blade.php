@extends('la.layouts.app')

@section('htmlheader_title')
    Task View
@endsection


@section('main-content')
<div id="page-content" class="profile2">
    
    <ul data-toggle="ajax-tab" class="nav nav-tabs profile" role="tablist">
        <li class=""><a href="{{ url(config('laraadmin.adminRoute') . '/tasks') }}" data-toggle="tooltip" data-placement="right" title="Back to Tasks"><i class="fa fa-chevron-left"></i></a></li>
        <li class="active"><a role="tab" data-toggle="tab" class="active" href="#tab-general-info" data-target="#tab-info"><i class="fa fa-bars"></i> General Info</a></li>
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
                        <div class="form-group">
                            <label class="col-md-4 col-sm-6 col-xs-6">Task Name :</label>
                            <div class="col-md-8 col-sm-6 col-xs-6">{{$task->name}}</div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 col-sm-6 col-xs-6">Task Description :</label>
                            <div class="col-md-8 col-sm-6 col-xs-6">{{$task->description}}</div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 col-sm-6 col-xs-6">Prority :</label>
                            <div class="col-md-8 col-sm-6 col-xs-6">{{$task->priority}}</div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 col-sm-6 col-xs-6">Time Frame :</label>
                            <div class="col-md-8 col-sm-6 col-xs-6">{{$time_frame->name}}</div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 col-sm-6 col-xs-6">Every Interval :</label>
                            <div class="col-md-8 col-sm-6 col-xs-6">{{$task->every_interval}}</div>
                        </div>
                        <div class="form-group" id="due_date">
                            <label class="col-md-4 col-sm-6 col-xs-6">Due Date :</label>
                            <?php $dt = strtotime($task->due_date); $value = date("d M Y", $dt); ?>
                            <div class="col-md-8 col-sm-6 col-xs-6">{{ $value }}</div>
                        </div>
                        <div class="form-group" id="day_of_week">
                            <label class="col-md-4 col-sm-6 col-xs-6">Week :</label>
                            <div class="col-md-8 col-sm-6 col-xs-6">{{$task->dayofweek}}</div>
                        </div>
                        <div class="form-group" id="monthly_type">
                            <label class="col-md-4 col-sm-6 col-xs-6">Monthly Type :</label>
                            <div class="col-md-8 col-sm-6 col-xs-6">{{$task->monthly_type}}</div>
                        </div>
                        <div class="form-group" id="day">
                            <label class="col-md-4 col-sm-6 col-xs-6">Every Day of Month :</label>
                            <div class="col-md-8 col-sm-6 col-xs-6">{{$task->day}}</div>
                        </div>
                        <div class="form-group" id="week">
                            <label class="col-md-4 col-sm-6 col-xs-6">Week :</label>
                            <div class="col-md-8 col-sm-6 col-xs-6">{{$task->week}}</div>
                        </div>
                        <div class="form-group" id="monthly_day_of_week">
                            <label class="col-md-4 col-sm-6 col-xs-6">At :</label>
                            <div class="col-md-8 col-sm-6 col-xs-6">{{$task->dayofweek}}</div>
                        </div>
                        <div class="form-group" id="startDate">
                            <label class="col-md-4 col-sm-6 col-xs-6">Start Date :</label>
                            <?php $dt = strtotime($task->start_date); $value = date("d M Y", $dt); ?>
                            <div class="col-md-8 col-sm-6 col-xs-6">{{ $value }}</div>
                        </div>
                        <div class="form-group" id="terminationDate">
                            <label class="col-md-4 col-sm-6 col-xs-6">End Date :</label>
                            <?php $dt = strtotime($task->termination_date); $value = date("d M Y", $dt); ?>
                            <div class="col-md-8 col-sm-6 col-xs-6">{{ $value }}</div>
                        </div>
                    </div>
                </div>
                @if(count($task_pics) > 0)
                <div class="tab-content">
                    <div class="panel infolist">
                        <div class="panel-default panel-heading">
                            <h4>Main PIC Lists</h4>
                        </div>
                        <div class="panel-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Main PIC</th>
                                        <th>Task Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($task_pics as $key => $task_pic)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $task_pic->name }}</td>
                                        <td>{{ $task_pic->description }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function(){
    var time_frame = "<?php echo $time_frame->name ?>"; 
    var radioValue = "<?php echo $task->monthly_type ?>";
    
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
})
</script>
@endpush
