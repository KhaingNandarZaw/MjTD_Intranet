@extends("la.layouts.app")

<?php
use Dwij\Laraadmin\Models\Module;
?>

@section("contentheader_title", "Task Assignment")
@section("contentheader_description", "Assign New Task")
@section("htmlheader_title", "Assign New Task")

@section("main-content")
<div class="box box-black">
    <div class="box-header with-border">
        Assign New Task
    </div>    
    <div class="box-body">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label for="name">Task Title <span style="color:red;">*</span>:</label>
                        <input class="form-control" placeholder="Task Title" name="task" value=""/>
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="name">Task Description <span style="color:red;">*</span>:</label>
                        <textarea class="form-control" placeholder="Task Description or Notes" name="description" value=""></textarea>
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="name">Priority <span style="color:red;">*</span>:</label>
                        <select class="form-control" data-placeholder="Select Type" id="select2_priority">
                            <option value="1"> Important</option>
                            <option value="2" selected><i class="fa fa-dot-circle-o"></i> Normal</option>
                            <option value="3"><i class="fa fa-arrow-down"></i> Low</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label>Time Frame <span style="color:red;">*</span>:</label>
                        <select class="form-control" data-placeholder="Select Type" id="frequency">
                            <option value="1">Daily</option>
                            <option value="2">Weekly</option>
                            <option value="3">Monthly</option>
                            <option value="4">Yearly</option>
                            <option value="5" selected>Once</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4" id="once">
                        <label>Due Date</label>
                        <div class='input-group date' id='datetimepicker1'>
                            <input type='text' class="form-control" placeholder="Choose Date"/>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4 form-group" id="times">
                        <label>Every :</label>
                        <select class="form-control" data-placeholder="Select Type">
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                            <option>5</option>
                            <option>6</option>
                            <option>7</option>
                            <option>8</option>
                            <option>9</option>
                            <option>10</option>
                        </select>
                    </div>
                    <div class="col-md-4 form-group" id="weekly">
                        <label></label>
                        <div class="recur-toggle weeks no-show" >
                            <div class="btn-group" data-toggle="buttons">                                    
                                <label class="btn btn-default btn-md">
                                    <input type="checkbox">
                                    Mon
                                </label>
                                <label class="btn btn-default btn-md">
                                    <input type="checkbox">
                                    Tue
                                </label>
                                <label class="btn btn-default btn-md">
                                    <input type="checkbox">
                                    Wed
                                </label>
                                <label class="btn btn-default btn-md">
                                    <input type="checkbox">
                                    Thu
                                </label>
                                <label class="btn btn-default btn-md">
                                    <input type="checkbox">
                                    Fri
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 form-group" id="monthly">
                        <div class="radio">
                            <label><input checked="checked" name="monthly" type="radio" value="dayofweek"> Day Of Week </label>
                            <label><input name="monthly" type="radio" value="dayofmonth"> Day Of Month </label>
                        </div>
                    </div>
                    <div class="col-md-4" id="dayofweek">
                        <div class="col-sm-6 form-group">
                            <select class="form-control" data-placeholder="Select">
                                <option>First Week</option>
                                <option>Second Week</option>
                                <option>Third Week</option>
                                <option>Last Week</option>
                            </select>
                        </div>
                        <div class="col-sm-6 form-group">
                            <select class="form-control" data-placeholder="Select">
                                <option>Monday</option>
                                <option>Tuesday</option>
                                <option>Wednesday</option>
                                <option>Thursday</option>
                                <option>Friday</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 form-group" id="dayofmonth">
                        <label>At Every Day :</label>
                        <select class="form-control" data-placeholder="Select">
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                            <option>5</option>
                            <option>6</option>
                            <option>7</option>
                            <option>8</option>
                            <option>9</option>
                            <option>10</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 form-group" id="start">
                    <label>Start Date :</label>
                        <div class='input-group date' id='datetimepicker1'>
                            <input type='text' class="form-control" placeholder="Choose Date"/>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4 form-group" id="until">
                    <label>Until :</label>
                        <div class='input-group date' id='datetimepicker1'>
                            <input type='text' class="form-control" placeholder="Choose Date"/>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <?php $users = App\User::all(); ?>
                    <div class="col-md-6 form-group">
                        <label>Main PIC <sup style="color:red;">*</sup></label>
                        <input type="hidden" name="pic_count" id="pic_count" value="1">
                        <div class="form-group col-sm-12" id="pic_grid_1">
                            <div class="col-md-6">
                                <select class="form-control input-sm" required data-placeholder="Select PIC" rel="select2" id="pic_1" name="pic_1">
                                    <option selected disabled>Choose PIC</option>
                                    @foreach($users as $user)
                                        @if(!$user->hasRole("SUPER_ADMIN"))
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endif
                                    @endforeach
                                </select> 
                            </div>
                            <div class="col-md-6 next">
                                <a class="btn btn-primary btn-sm"  onclick="insertPICRow()"><i class="fa fa-plus"></i></a>
                                <button type="button" class="btn btn-danger btn-sm" onclick="deletePICRow(1)"><i class="fa fa-minus"></i></button>           
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-sm-6" >
                        <div class="col-md-6">
                            <label>Supportings <sup style="color:red;">*</sup></label>
                        </div> 
                        <input type="hidden" name="reportTo_count" id="reportTo_count" value="1">
                        <div class="form-group col-sm-12" id="reportTo_grid_1">
                            <div class="col-md-6">
                                <select class="form-control input-sm" required data-placeholder="Select Supportings" rel="select2" id="reportTo_1" name="reportTo_1">
                                    <option selected disabled>Choose Supportings</option>
                                    @foreach($users as $user)
                                        @if(!$user->hasRole("SUPER_ADMIN"))
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endif
                                    @endforeach
                                </select> 
                            </div>
                            <div class="col-md-6 next">
                                <a class="btn btn-primary btn-sm"  onclick="insertReportToRow()"><i class="fa fa-plus"></i></a>
                                <button type="button" class="btn btn-danger btn-sm" onclick="deleteReportToRow(1)"><i class="fa fa-minus"></i></button>           
                            </div>
                        </div>
                    </div> -->                
                </div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-6" align="right">
                            {!! Form::submit( 'Save', ['class'=>'btn btn-primary btn-sm']) !!}
                        </div>
                        <div class="col-sm-6" align="left">
                            <a href="{{ url(config('laraadmin.adminRoute') . '/task_management/task_assign') }}" class="btn btn-default btn-sm">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
            
            {!! Form::close() !!}
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
$(function () {
    function format(state) {
        return '<i class="fa fa-exclamation"></i> Important';
    }

    $("#select2_priority").select2({
        formatResult: format,
        formatSelection: format,
        escapeMarkup: function(m) { return m; }
    });

    $('#datetimepicker1').datetimepicker();

    $("#weekly").hide();
    $("#times").hide();
    $("#until").hide();
    $("#start").hide();
    $("#monthly").hide();
    $("#dayofweek").hide();
    $("#dayofmonth").hide();

    $("#monthly").on('change', function(){
        var is_external = $("input[name='monthly']:checked").val();
        if(is_external == "dayofweek"){
            $("#dayofweek").show();
            $("#dayofmonth").hide();
        }else{
            $("#dayofweek").hide();
            $("#dayofmonth").show();
        }
    });

    $('#frequency').on('change',function(){
        if($(this).val() == "1")
        {
            $("#weekly").hide();
            $("#times").show();
            $("#until").show();
            $("#start").show();
            $("#once").hide();
            $("#monthly").hide();
            $("#dayofweek").hide();
            $("#dayofmonth").hide();
        }
        else if($(this).val() == "2")
        {
            $("#weekly").show();
            $("#times").show();
            $("#until").show();
            $("#start").show();
            $("#once").hide();
            $("#monthly").hide();
            $("#dayofweek").hide();
            $("#dayofmonth").hide();
        }
        else if($(this).val() == "3")
        {
            $("#weekly").hide();
            $("#times").show();
            $("#until").show();
            $("#start").show();
            $("#once").hide();
            $("#monthly").show();
            $("#dayofweek").hide();
            $("#dayofmonth").hide();
        }
        else if($(this).val() == "4")
        {
            $("#weekly").hide();
            $("#times").show();
            $("#until").show();
            $("#start").show();
            $("#once").hide();
            $("#monthly").hide();
            $("#dayofweek").hide();
            $("#dayofmonth").hide();
        }
        else
        {
            $("#weekly").hide();
            $("#times").hide();
            $("#until").hide();
            $("#start").hide();
            $("#once").show();
            $("#monthly").hide();
            $("#dayofweek").hide();
            $("#dayofmonth").hide();
        }
    }

);
});
var pic_grid;
var reportTo_grid;
function insertPICRow()
{
    pic_gird = $("#pic_count").val();
    pic_gird++;
    $("#pic_count").val(pic_gird);
    var new_entry1 = `<div class="form-group col-sm-12" id="pic_grid_${pic_gird}"><div class="col-md-6">
                    <select class="form-control input-sm" required data-placeholder="Select PIC" rel="select2" id="pic_${pic_gird}" name="pic_${pic_gird}">
                        <option selected disabled>Choose PIC</option>
                        @foreach($users as $user)
                            @if(!$user->hasRole("SUPER_ADMIN"))
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endif
                        @endforeach
                    </select> 
                </div>
                <div class="col-md-6 next">
                    <a class="btn btn-primary btn-sm"  onclick="insertPICRow()"><i class="fa fa-plus"></i></a>
                    <button type="button" class="btn btn-danger btn-sm" onclick="deletePICRow(${pic_gird})"><i class="fa fa-minus"></i></button>           
                </div></div>`;
    pic_gird--;                        
    $("#pic_grid_"+pic_gird).after(new_entry1);
    $("[rel=select2]").select2({
        
    });
}

function deletePICRow(grid_no){
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