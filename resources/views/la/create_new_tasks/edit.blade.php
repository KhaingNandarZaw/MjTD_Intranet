@extends("la.layouts.app")

@section("contentheader_title")
    <a href="{{ url(config('laraadmin.adminRoute') . '/create_new_tasks') }}">Create New Task</a> :
@endsection
@section("contentheader_description", $new_task->$view_col)
@section("section", "Create New Tasks")
@section("section_url", url(config('laraadmin.adminRoute') . '/create_new_tasks'))
@section("sub_section", "Edit")

@section("htmlheader_title", "Create New Tasks Edit : ".$new_task->$view_col)

@section("main-content")

@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="box">
    <div class="box-header">
    {!! Form::model($new_task, ['route' => [config('laraadmin.adminRoute') . '.create_new_tasks.update', $new_task->id ], 'method'=>'PUT', 'id' => 'create_new_task-edit-form']) !!}
        <div class="row">
            <div class="col-sm-4">
                @la_input($module, 'name')
            </div>
            <div class="col-sm-4">
                @la_input($module, 'description')
            </div>
            <div class="col-sm-4">
                @la_input($module, 'priority')
            </div>
        </div>
        <div class="row">
        <?php $users = App\User::all(); ?>
            @if(Entrust::hasRole("SUPER_ADMIN"))
            <div class="form-group col-sm-4" >
                <label>PIC <sup style="color:red;">*</sup></label>
                <select class="form-control input-sm" required rel="select2" id="pic_user_id" name="pic_user_id">
                    <option selected disabled>Choose PIC</option>
                    @foreach($users as $user)
                        @if(!$user->hasRole("SUPER_ADMIN") && $user->hasRole("EMPLOYEE"))
                            @if($user->id == $new_task->pic_user_id)
                            <option value="{{ $user->id }}" selected>{{ $user->name }}</option>
                            @else
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endif
                        @endif
                    @endforeach
                </select> 
            </div>
            @endif
            <div class="form-group col-sm-4" >
                <label>Report To <sup style="color:red;">*</sup></label>
                <select class="form-control input-sm" required data-placeholder="Select Report To" rel="select2" id="report_to_userid" name="report_to_userid">
                    <option selected disabled>Choose Report To</option>
                    @foreach($users as $user)
                        @if(!$user->hasRole("SUPER_ADMIN"))
                            @if($user->id == $new_task->report_to_userid)
                            <option value="{{ $user->id }}" selected>{{ $user->name }}</option>
                            @else
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endif
                        @endif
                    @endforeach
                </select> 
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="role">Time Frame<span style="color: red;"> * </span>:</label>
                    <select class="form-control" required="1" onchange="timeFrameChanged()" name="time_frame" id="time_frame" rel="select2" > 
                        @foreach($time_frames as $time_frame)
                            @if($time_frame->id == $new_task->time_frame)
                                <option value="{{ $time_frame->id }}" selected>{{ $time_frame->name }}</option>
                            @else
                                <option value="{{ $time_frame->id }}">{{ $time_frame->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-sm-4" id="due_date">
                @la_input($module, 'due_date')
            </div>
            <div class="col-sm-4" id="every_interval" style="display: none;">
                @la_input($module, 'every_interval')
            </div>
            <div class="col-sm-4" id="day_of_week" style="display: none;">
                @la_input($module, 'dayofweek')
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4" id="monthly_type" style="display: none;">
                @la_input($module, 'monthly_type')
            </div>
            <div class="col-sm-4" id="day" style="display: none;">
                @la_input($module, 'day')
            </div>
            <div class="col-sm-4" id="week" style="display: none;">
                @la_input($module, 'week')
            </div>
            <div class="col-sm-4" id="monthly_day_of_week" style="display: none;">
                @la_input($module, 'monthly_dayofweek')
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4" id="startDate" style="display: none;">
                @la_input($module, 'start_date')
            </div>
            <div class="col-sm-4" id="terminationDate" style="display: none;">
                @la_input($module, 'termination_date')
            </div>
        </div>
        <div class="row">
            <div class="form-group col-sm-6">
                {!! Form::submit( 'Update', ['class'=>'btn btn-sm btn-success pull-right']) !!} 
            </div>
            <div class="form-group col-sm-6">
                <a href="{{ url(config('laraadmin.adminRoute') . '/create_new_tasks') }}" class="btn btn-sm btn-default">Cancel</a>
            </div>
        </div>
    {!! Form::close() !!}
    </div>
</div>

@endsection

@push('scripts')
<script>
$(function () {
    $("#create_new_task-edit-form").validate({
        
    });
    timeFrameChanged();
    $("input[name=monthly_type]").on('change', function(){
        timeFrameChanged();
    });
});
function timeFrameChanged(){
    $("#due_date").css('display', 'none');
    $("#day_of_week").css('display', 'none');
    $("#every_interval").css('display', 'none');
    $("#startDate").css('display', 'none');
    $("#terminationDate").css('display', 'none');
    $("#week").css('display', 'none');
    $("#day").css('display', 'none');
    $("#monthly_type").css('display', 'none');
    $("#monthly_day_of_week").css('display', 'none');
    var selectVal = $("#time_frame option:selected").text();
    if(selectVal == 'Once'){
        $("#due_date").css('display', 'block');
    }
    else if(selectVal == 'Daily'){
        $("#every_interval").css('display', 'block');
        $("#startDate").css('display', 'block');
        $("#terminationDate").css('display', 'block');
    }else if(selectVal == 'Weekly'){
        $("#every_interval").css('display', 'block');
        $("#startDate").css('display', 'block');
        $("#terminationDate").css('display', 'block');
        $("#day_of_week").css('display', 'block');
    }else if(selectVal == 'Monthly'){
        var radioValue = $("input[name='monthly_type']:checked").val();
        if(radioValue == 'Day Of Week'){
            $("#week").css('display', 'block');
            $("#monthly_day_of_week").css('display', 'block');
        }else if(radioValue == 'Day Of Month'){
            $("#day").css('display', 'block');
        }
        $("#every_interval").css('display', 'block');
        $("#monthly_type").css('display', 'block');
        $("#startDate").css('display', 'block');
        $("#terminationDate").css('display', 'block');
    }
}

var pic_grid;
function insertPicRow()
{
    pic_grid = $("#pic_count").val();
    pic_grid++;
    $("#pic_count").val(pic_grid);
    var new_entry1 = `<div class="form-group col-sm-12" id="pic_grid_${pic_grid}"><div class="col-md-3">
                    <select class="form-control input-sm" required data-placeholder="Select PIC" rel="select2" id="pic_${pic_grid}" name="pic_${pic_grid}">
                        <option selected disabled>Choose PIC</option>
                        @foreach($users as $user)
                            @if(!$user->hasRole("SUPER_ADMIN"))
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endif
                        @endforeach
                    </select> 
                </div>
                <div class="col-md-3">
                        <textarea class="form-control" placeholder="Description/Comment" id="desc_${pic_grid}" name="desc_${pic_grid}"></textarea>
                    </div>
                <div class="col-md-6 next">
                    <a class="btn btn-primary btn-sm"  onclick="insertPicRow()"><i class="fa fa-plus"></i></a>
                    <button type="button" class="btn btn-danger btn-sm" onclick="deletePicRow(${pic_grid})"><i class="fa fa-minus"></i></button>           
                </div></div>`;
    pic_grid--;                        
    $("#pic_grid_"+pic_grid).after(new_entry1);
    $("[rel=select2]").select2({
        
    });
}

function deletePicRow(grid_no){
    var count = $("#pic_count").val();
    if(count > 1)
    {
        $("#pic_grid_"+grid_no).remove();   
    }
    else
    {
        alert('Rows cannot be removed');
    }
    count--;
    $("#pic_count").val(count);
}

</script>
@endpush
