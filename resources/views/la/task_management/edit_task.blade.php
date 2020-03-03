@extends("la.layouts.app")

<?php
use Dwij\Laraadmin\Models\Module;
?>

@section("contentheader_title", "Task Assign")
@section("contentheader_description", "Task Assign listing")
@section("htmlheader_title", "Task Assign listing")

@section("main-content")
<div class="box box-black">
    <div class="box-header with-border">
        Edit Task
    </div>    
    <div class="box-body">
            <div class="box-body">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="name">Task Name :</label>
                            <input class="form-control" placeholder="Task" name="task" value="Task A"/>
                        </div>

                        <div class="col-md-6 form-group">
                        <label>Due Date</label>
                            <div class='input-group date' id='datetimepicker1'>
                                <input type='text' class="form-control" placeholder="Choose Date" value="08/11/2019"/>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="name">Task Description :</label>
                            <textarea class="form-control" placeholder="Description" name="description" value="">Collaborate with your co-assignee</textarea>
                        </div>
                        <div class="col-md-6 form-group">
                        <label>Extension</label>
                            <div class='input-group date' id='datetimepicker1'>
                                <input type='text' class="form-control" placeholder="Choose Date"/>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                        
                    <div class="row">
                        <div class="col-md-6 form-group">
                        <label>Type :</label>
                            <select class="form-control" data-placeholder="Select Type" id="frequency">
                                <option value="1">Daily</option>
                                <option value="2">Weekly</option>
                                <option value="3">Monthly</option>
                                <option value="4">Yearly</option>
                                <option value="5" selected>Once</option>
                            </select>
                        </div>
                        <br>
                        <div class="col-md-6 form-group">
                            <div class="recur-toggle weeks no-show" id="weekly">
                                <div class="btn-group" data-toggle="buttons">
                                    <label class="btn btn-default btn-md">
                                        <input type="checkbox">
                                        Sun
                                    </label>
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
                                    <label class="btn btn-default btn-md">
                                        <input type="checkbox">
                                        Sat
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group" id="times">
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
                        <div class="col-md-6 form-group" id="until">
                        <label>Until :</label>
                            <div class='input-group date' id='datetimepicker1'>
                                <input type='text' class="form-control" placeholder="Choose Date"/>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                    </div>
                    </div>
            </div>
            <div class="row col-sm-12">
                <?php $users = App\User::all(); ?>
                <div class="col-sm-6" >
                    <div class="col-sm-6">
                        <label>Main PIC <sup style="color:red;">*</sup></label>
                    </div>
                    <input type="hidden" name="supporting_count" id="supporting_count" value="1">
                    <div class="form-group col-sm-12" id="supporting_grid_1">
                        <div class="col-md-6">
                            <select class="form-control input-sm" required data-placeholder="Select Supporting" rel="select2" id="supporting_1" name="supporting_1">
                                <option selected disabled>Choose Supporting</option>
                                @foreach($users as $user)
                                    @if(!$user->hasRole("SUPER_ADMIN"))
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endif
                                @endforeach
                            </select> 
                        </div>
                        <div class="col-md-6 next">
                            <a class="btn btn-primary btn-sm"  onclick="insertSupportingRow()"><i class="fa fa-plus"></i></a>
                            <button type="button" class="btn btn-danger btn-sm" onclick="deleteSupportingRow(1)"><i class="fa fa-minus"></i></button>           
                        </div>
                    </div>
                </div>
                <div class="col-sm-6" >
                    <div class="col-md-6">
                        <label>Supportings <sup style="color:red;">*</sup></label>
                    </div> 
                    <input type="hidden" name="reportTo_count" id="reportTo_count" value="1">
                    <div class="form-group col-sm-12" id="reportTo_grid_1">
                        <div class="col-md-6">
                            <select class="form-control input-sm" required data-placeholder="Select Report To" rel="select2" id="reportTo_1" name="reportTo_1">
                                <option selected disabled>Choose Report To</option>
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
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-6" align="right">
                    {!! Form::submit( 'Update', ['class'=>'btn btn-warning btn-sm']) !!}
                </div>
                <div class="col-sm-6" align="left">
                    <a href="{{ url(config('laraadmin.adminRoute') . '/task_management/task_assign') }}" class="btn btn-default btn-sm">Cancel</a>
                </div>
            </div>
            {!! Form::close() !!}
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
$(function () {
    $('#datetimepicker1').datetimepicker();

    $("#weekly").hide();
    $("#times").hide();
    $("#until").hide();

    $('#frequency').on('change',function(){
        if($(this).val() == "1")
        {
            $("#weekly").hide();
            $("#times").show();
            $("#until").show();
        }
        else if($(this).val() == "2")
        {
            $("#weekly").show();
            $("#times").show();
            $("#until").show();
        }
        else if($(this).val() == "3")
        {
            $("#weekly").hide();
            $("#times").show();
            $("#until").show();
        }
        else if($(this).val() == "4")
        {
            $("#weekly").hide();
            $("#times").show();
            $("#until").show();
        }
        else
        {
            $("#weekly").hide();
            $("#times").hide();
            $("#until").hide();
        }
    }

);
});
var supporting_grid;
var reportTo_grid;
function insertSupportingRow()
{
    supporting_gird = $("#supporting_count").val();
    supporting_gird++;
    $("#supporting_count").val(supporting_gird);
    var new_entry1 = `<div class="form-group col-sm-12" id="supporting_grid_${supporting_gird}"><div class="col-md-6">
                    <select class="form-control input-sm" required data-placeholder="Select Supporting" rel="select2" id="supporting_${supporting_gird}" name="supporting_${supporting_gird}">
                        <option selected disabled>Choose Supporting</option>
                        @foreach($users as $user)
                            @if(!$user->hasRole("SUPER_ADMIN"))
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endif
                        @endforeach
                    </select> 
                </div>
                <div class="col-md-6 next">
                    <a class="btn btn-primary btn-sm"  onclick="insertSupportingRow()"><i class="fa fa-plus"></i></a>
                    <button type="button" class="btn btn-danger btn-sm" onclick="deleteSupportingRow(${supporting_gird})"><i class="fa fa-minus"></i></button>           
                </div></div>`;
    supporting_gird--;                        
    $("#supporting_grid_"+supporting_gird).after(new_entry1);
    $("[rel=select2]").select2({
        
    });
}

function deleteSupportingRow(grid_no){
    var count = $("#supporting_count").val();
    if(count > 1)
    {
        $("#supporting_grid_"+grid_no).remove();   
    }
    else
    {
        alert('Rows cannot be removed');
    }
    count--;
    $("#supporting_count").val(count);
}

function insertReportToRow()
{
    reportTo_grid = $("#reportTo_count").val();
    reportTo_grid++;
    $("#reportTo_count").val(reportTo_grid);
    var new_entry1 = `<div class="form-group col-sm-12" id="reportTo_grid_${reportTo_grid}"><div class="col-md-6">
                    <select class="form-control input-sm" required data-placeholder="Select Report To" rel="select2" id="reportTo_${reportTo_grid}" name="reportTo_${reportTo_grid}">
                        <option selected disabled>Choose Report To</option>
                        @foreach($users as $user)
                            @if(!$user->hasRole("SUPER_ADMIN"))
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endif
                        @endforeach
                    </select> 
                </div>
                <div class="col-md-6 next">
                    <a class="btn btn-primary btn-sm"  onclick="insertReportToRow()"><i class="fa fa-plus"></i></a>
                    <button type="button" class="btn btn-danger btn-sm" onclick="deleteReportToRow(${reportTo_grid})"><i class="fa fa-minus"></i></button>           
                </div></div>`;
                reportTo_grid--;                        
        $("#reportTo_grid_"+reportTo_grid).after(new_entry1);
    
        $("[rel=select2]").select2({
        
    });
}

function deleteReportToRow(grid_no){
    var count = $("#reportTo_count").val();
    if(count > 1)
    {
        $("#reportTo_grid_"+grid_no).remove();   
    }
    else
    {
        alert('Rows cannot be removed');
    }
    count--;
    $("#reportTo_count").val(count);
}
</script>
@endpush