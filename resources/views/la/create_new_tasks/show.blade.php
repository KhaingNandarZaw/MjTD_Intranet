@extends('la.layouts.app')

@section('htmlheader_title')
    Create New Task View
@endsection


@section('main-content')
<div id="page-content" class="profile2">
    
    <ul data-toggle="ajax-tab" class="nav nav-tabs profile" role="tablist">
        <li class=""><a href="javascript:history.back()" data-toggle="tooltip" data-placement="right" title="Back to Create New Tasks"><i class="fa fa-chevron-left"></i></a></li>
        <li class="active"><a role="tab" data-toggle="tab" class="active" href="#tab-general-info" data-target="#tab-info"><i class="fa fa-bars"></i> General Info</a></li>
    </ul>

    <div class="tab-content">
        <?php $time_frame = DB::table('frames')->wherenull('deleted_at')->where('id', $create_new_task->time_frame)->first(); ?>
        <div role="tabpanel" class="tab-pane active fade in" id="tab-info">
            <div class="tab-content">
                <div class="panel infolist">
                    <div class="panel-default panel-heading">
                        <h4>General Info</h4>
                    </div>
                    <div class="panel-body">
                    <div class="form-group">
                            <label class="col-md-4 col-sm-6 col-xs-6">Task Title :</label>
                            <div class="col-md-8 col-sm-6 col-xs-6">{{$create_new_task->name}}</div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 col-sm-6 col-xs-6">Task Description :</label>
                            <div class="col-md-8 col-sm-6 col-xs-6">{{$create_new_task->description}}</div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 col-sm-6 col-xs-6">Prority :</label>
                            <div class="col-md-8 col-sm-6 col-xs-6">{{$create_new_task->priority}}</div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 col-sm-6 col-xs-6">Requested By :</label>
                            <?php $user = DB::table('users')->wherenull('deleted_at')->where('id', $create_new_task->created_by)->first(); ?>
                            <div class="col-md-8 col-sm-6 col-xs-6">{{ $user->name }}</div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 col-sm-6 col-xs-6">PIC :</label>
                            <?php $user = DB::table('users')->wherenull('deleted_at')->where('id', $create_new_task->pic_user_id)->first(); ?>
                            <div class="col-md-8 col-sm-6 col-xs-6">{{ $user->name }}</div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 col-sm-6 col-xs-6">Report To :</label>
                            <?php $user = DB::table('users')->wherenull('deleted_at')->where('id', $create_new_task->report_to_userid)->first(); ?>
                            <div class="col-md-8 col-sm-6 col-xs-6">{{ $user->name }}</div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 col-sm-6 col-xs-6">Time Frame :</label>
                            <div class="col-md-8 col-sm-6 col-xs-6">{{$time_frame->name}}</div>
                        </div>
                        <div class="form-group" id="every_interval" style="display:none;">
                            <label class="col-md-4 col-sm-6 col-xs-6">Every Interval :</label>
                            <div class="col-md-8 col-sm-6 col-xs-6">{{$create_new_task->every_interval}}</div>
                        </div>
                        <div class="form-group" id="due_date" style="display:none;">
                            <label class="col-md-4 col-sm-6 col-xs-6">Due Date :</label>
                            <?php $dt = strtotime($create_new_task->due_date); $value = date("d M Y", $dt); ?>
                            <div class="col-md-8 col-sm-6 col-xs-6">{{ $value }}</div>
                        </div>
                        <div class="form-group" id="day_of_week" style="display:none;">
                            <label class="col-md-4 col-sm-6 col-xs-6">Week :</label>
                            <div class="col-md-8 col-sm-6 col-xs-6">{{$create_new_task->dayofweek}}</div>
                        </div>
                        <div class="form-group" id="monthly_type" style="display:none;">
                            <label class="col-md-4 col-sm-6 col-xs-6">Monthly Type :</label>
                            <div class="col-md-8 col-sm-6 col-xs-6">{{$create_new_task->monthly_type}}</div>
                        </div>
                        <div class="form-group" id="day" style="display:none;">
                            <label class="col-md-4 col-sm-6 col-xs-6">Every Day of Month :</label>
                            <div class="col-md-8 col-sm-6 col-xs-6">{{$create_new_task->day}}</div>
                        </div>
                        <div class="form-group" id="week" style="display:none;">
                            <label class="col-md-4 col-sm-6 col-xs-6">Week :</label>
                            <div class="col-md-8 col-sm-6 col-xs-6">{{$create_new_task->week}}</div>
                        </div>
                        <div class="form-group" id="monthly_day_of_week" style="display:none;">
                            <label class="col-md-4 col-sm-6 col-xs-6">At :</label>
                            <div class="col-md-8 col-sm-6 col-xs-6">{{$create_new_task->monthly_dayofweek}}</div>
                        </div>
                        <div class="form-group" id="startDate" style="display:none;">
                            <label class="col-md-4 col-sm-6 col-xs-6">Start Date :</label>
                            <?php $dt = strtotime($create_new_task->start_date); $value = date("d M Y", $dt); ?>
                            <div class="col-md-8 col-sm-6 col-xs-6">{{ $value }}</div>
                        </div>
                        <div class="form-group" id="terminationDate" style="display:none;">
                            <label class="col-md-4 col-sm-6 col-xs-6">End Date :</label>
                            <?php $dt = strtotime($create_new_task->termination_date); $value = date("d M Y", $dt); ?>
                            <div class="col-md-8 col-sm-6 col-xs-6">{{ $value }}</div>
                        </div>
                        <div class="form-group" style="display:none;">
                            <label class="col-md-4 col-sm-6 col-xs-6">Status :</label>
                            <div class="col-md-8 col-sm-6 col-xs-6">{{ $create_new_task->status }}</div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 col-sm-6 col-xs-6">Remark :</label>
                            <div class="col-md-8 col-sm-6 col-xs-6">{{ $create_new_task->remark }}</div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 col-sm-6 col-xs-6">Attachments :</label>
                            <div class="col-md-8 col-sm-6 col-xs-6">
                                <?php
                                $value = $create_new_task->attachments;
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
                        @if(isset($create_new_task->confirmed_by))
                            <div class="form-group">
                                <label class="col-md-4 col-sm-6 col-xs-6">Confirmed By :</label>
                                <?php $user = DB::table('users')->wherenull('deleted_at')->where('id', $create_new_task->confirmed_by)->first(); ?>
                                <div class="col-md-8 col-sm-6 col-xs-6">{{ $user->name }}</div>
                            </div>
                        @endif
                        @if(isset($create_new_task->confirmed_date))
                        <div class="form-group">
                            <label class="col-md-4 col-sm-6 col-xs-6">Confirmed Date :</label>
                            <?php $dt = strtotime($create_new_task->confirmed_date); $value = date("d M Y", $dt); ?>
                            <div class="col-md-8 col-sm-6 col-xs-6">{{ $value }}</div>
                        </div>
                        @endif
                        @if(isset($create_new_task->rejected_by))
                            <div class="form-group">
                                <label class="col-md-4 col-sm-6 col-xs-6">Rejected By :</label>
                                <?php $user = DB::table('users')->wherenull('deleted_at')->where('id', $create_new_task->rejected_by)->first(); ?>
                                <div class="col-md-8 col-sm-6 col-xs-6">{{ $user->name }}</div>
                            </div>
                        @endif
                        @if(isset($create_new_task->rejected_date))
                        <div class="form-group">
                            <label class="col-md-4 col-sm-6 col-xs-6">Rejected Date :</label>
                            <?php $dt = strtotime($create_new_task->rejected_date); $value = date("d M Y", $dt); ?>
                            <div class="col-md-8 col-sm-6 col-xs-6">{{ $value }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function(){
    var time_frame = "<?php echo $time_frame->name ?>"; 
    var radioValue = "<?php echo $create_new_task->monthly_type ?>";
    
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
});
</script>
@endpush