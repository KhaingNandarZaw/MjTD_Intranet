@extends('la.layouts.app')

@section('htmlheader_title')
    SOP Setup Detail
@endsection

@section('main-content')
<div id="page-content" class="profile2">
    
    <ul data-toggle="ajax-tab" class="nav nav-tabs profile" role="tablist">
        <li class=""><a href="javascript:history.back()" data-toggle="tooltip" data-placement="right" title="Back to SOP Lists"><i class="fa fa-chevron-left"></i></a></li>
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
                        <?php $time_frame = DB::table('frames')->wherenull('deleted_at')->where('id', $sop_setup->timeframe)->first(); ?>
                        <div class="form-group">
                            <label class="col-md-4 col-sm-6 col-xs-6">Work Description :</label>
                            <div class="col-md-8 col-sm-6 col-xs-6">{{$sop_setup->work_description}}</div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 col-sm-6 col-xs-6">Job Type/Management Type :</label>
                            <div class="col-md-8 col-sm-6 col-xs-6">{{$sop_setup->job_type}}</div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 col-sm-6 col-xs-6">Time Frame :</label>
                            <div class="col-md-8 col-sm-6 col-xs-6">{{$time_frame->name}}</div>
                        </div>
                        @la_display($module, 'pic_userid')
                        @la_display($module, 'report_to_userid')
                        <div class="form-group" id="every_interval" style="display: none;">
                            <label class="col-md-4 col-sm-6 col-xs-6">Every Interval :</label>
                            <div class="col-md-8 col-sm-6 col-xs-6">{{$sop_setup->every_interval}}</div>
                        </div>
                        <div class="form-group" id="day_of_week" style="display: none;">
                            <label class="col-md-4 col-sm-6 col-xs-6">Week :</label>
                            <div class="col-md-8 col-sm-6 col-xs-6">{{$sop_setup->dayofweek}}</div>
                        </div>
                        <div class="form-group" id="monthly_type" style="display: none;">
                            <label class="col-md-4 col-sm-6 col-xs-6">Monthly Type :</label>
                            <div class="col-md-8 col-sm-6 col-xs-6">{{$sop_setup->monthly_type}}</div>
                        </div>
                        <div class="form-group" id="day" style="display: none;">
                            <label class="col-md-4 col-sm-6 col-xs-6">Every Day of Month :</label>
                            <div class="col-md-8 col-sm-6 col-xs-6">{{$sop_setup->day}}</div>
                        </div>
                        <div class="form-group" id="week" style="display: none;">
                            <label class="col-md-4 col-sm-6 col-xs-6">Week :</label>
                            <div class="col-md-8 col-sm-6 col-xs-6">{{$sop_setup->week}}</div>
                        </div>
                        <div class="form-group" id="monthly_day_of_week" style="display: none;">
                            <label class="col-md-4 col-sm-6 col-xs-6">At :</label>
                            <div class="col-md-8 col-sm-6 col-xs-6">{{$sop_setup->monthly_dayofweek}}</div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 col-sm-6 col-xs-6">Remark :</label>
                            <div class="col-md-8 col-sm-6 col-xs-6">{{$sop_setup->remark}}</div>
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
@push('scripts')
<script>
$(function(){
    var time_frame = "<?php echo $time_frame->name ?>"; 
    var radioValue = "<?php echo $sop_setup->monthly_type ?>";
    
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