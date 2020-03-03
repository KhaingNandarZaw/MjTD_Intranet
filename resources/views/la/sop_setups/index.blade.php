@extends("la.layouts.app")

@section("contentheader_title")
    <a href="{{ url(config('laraadmin.adminRoute') . '/sop_setups') }}">SOP Setup</a> 
@endsection

@section("section", "SOP Setup")
@section("section_url", url(config('laraadmin.adminRoute') . '/sop_setups'))
@section("sub_section", "Create") 

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

<div class="box box-info">
    <div class="box-body">
        <div class="row">
        <?php $users = App\User::all(); ?>
            <div class="form-group col-sm-12">
                <label for="pic">PIC<span style="color: red;"> * </span>:</label>
                <select class="form-control input-sm" required data-placeholder="Select User" onchange="getSOPbyPic(this.value); getUploadedFiles(this.value);" rel="select2" id="user_id" name="user_id">
                    <option selected disabled>Choose PIC User</option>
                    @foreach($users as $user)
                        @if(!$user->hasRole("SUPER_ADMIN"))
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="form-group col-sm-6">
                <button id="AddNewManual" class="btn btn-success btn-sm pull-right">Add New Manual Files</button>
            </div>
            <div class="form-group col-sm-6">
                <button id="AddNewWorkflow" class="btn btn-success btn-sm pull-right">Add New Workflow Files</button>
            </div>
            <div class="form-group col-sm-6">
                <input class="form-control input-sm" placeholder="Enter File" data-rule-minlength="0" data-rule-maxlength="0" required="1" name="manual_file" type="hidden" value="[]" aria-required="true">
                <div class="manualfile" id="fm_dropzone_main" name="file">
                    <a id="closeManual"><i class="fa fa-times"></i></a>
                    <div class="dz-message"><i class="fa fa-cloud-upload"></i><br>Drop Manual files here to upload</div>
                </div>
                <div class="uploaded_manualfiles">
                    <ol></ol>
                </div>
            </div>
            <div class="form-group col-sm-6">
                <input class="form-control input-sm" placeholder="Enter File" data-rule-minlength="0" data-rule-maxlength="0" required="1" name="workflow_file" type="hidden" value="[]" aria-required="true">
                <div class="workflowfile" id="fm_dropzone_main" name="file">
                    <a id="closeWorkflow"><i class="fa fa-times"></i></a>
                    <div class="dz-message"><i class="fa fa-cloud-upload"></i><br>Drop Workflow files here to upload</div>
                </div>
                <div class="uploaded_workflowfiles">
                    <ol class="list-group"></ol>
                </div>
            </div>
            @if(Module::hasAccess('SOP_Setups','delete'))
            <div class="form-group col-sm-12">
                <button type="button" class="btn btn-success btn-sm pull-right sop_modal">Add New SOP Data</button>
            </div>
            @endif
            <div class="form-group col-sm-12">
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title ">SOP Lists</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <table id="example1" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Work Description</th>
                                    <th>Project Type</th>
                                    <th>Time Frame</th>
                                    <th>Supportings</th>
                                    <th>Report To</th>
                                    <th>Acknowledge To</th>
                                    <th>Remark</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="AddModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Add SOP Setup</h4>
            </div>
            {!! Form::open(['action' => 'LA\SOP_SetupsController@store', 'id' => 'sop_setup-add-form']) !!}
            <div class="modal-body">
                <div class="box-body">
                    <input type="hidden" id="pic_userid" name="pic_userid" class="form-control">
                    <div class="row col-sm-12">
                        <div class="form-group col-sm-6">
                            <label>Work Description<span style="color:red;"> * </span></label>
                            <input type="text" class="form-control" id="work_description" name="work_description" required placeholder="Work Description">
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="pic">Job Type/Management Type :</label>
                            <input type="text" class="form-control" id="job_type" name="job_type" placeholder="Job Type/Management Type">
                        </div>
                    </div>
                    <div class="row col-sm-12">
                        <div class="form-group col-sm-6">
                            <label for="pic">Time Frame<span style="color: red;"> * </span>:</label>
                            <?php $time_frames = App\Models\Frame::all(); ?>
                            <select class="form-control" required rel="select2" id="timeframe" name="timeframe" onchange="timeFrameChanged()">
                                <option selected disabled>Choose Time Frame</option>
                                @foreach($time_frames as $time_frame)
                                    <option value="{{ $time_frame->id }}">{{ $time_frame->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="pic">Report To<span style="color: red;"> * </span>:</label>
                            <select class="form-control" required rel="select2" id="report_to" name="report_to">
                                <option selected disabled>Choose Report To</option>
                                @foreach($users as $user)
                                    @if(!$user->hasRole("SUPER_ADMIN"))
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row col-sm-12">
                        <div class="col-sm-6" id="every_interval" style="display: none;">
                            @la_input($module, 'every_interval')
                        </div>
                        <div class="col-sm-6" id="day_of_week" style="display: none;">
                            @la_input($module, 'dayofweek')
                        </div>
                    </div>
                    <div class="row col-sm-12">
                        <div class="col-sm-4" id="monthly_type" style="display: none;">
                            @la_input($module, 'monthly_type')
                        </div>
                        <div class="col-sm-6" id="day" style="display: none;">
                            @la_input($module, 'day')
                        </div>
                        <div class="col-sm-4" id="week" style="display: none;">
                            @la_input($module, 'week')
                        </div>
                        <div class="col-sm-4" id="monthly_day_of_week" style="display: none;">
                            @la_input($module, 'monthly_dayofweek')
                        </div>
                    </div>
                    <div class="row col-sm-12">
                        <div class="form-group col-sm-6" id="start_date">
                            @la_input($module, 'start_date')
                        </div>
                        <div class="form-group col-sm-6">
                            <label>Remark :</label>
                            <textarea class="form-control input-sm" id="remark" name="remark" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="col-sm-6" >
                            <div class="col-sm-6">
                                <label>Supportings </label>
                            </div>
                            <input type="hidden" name="supporting_count" id="supporting_count" value="1">
                            <div class="form-group col-sm-12" id="supporting_grid_1">
                                <div class="col-md-8">
                                    <select class="form-control input-sm" data-placeholder="Select Supporting" rel="select2" id="supporting_1" name="supporting_1">
                                        <option selected disabled>Choose Supporting</option>
                                        @foreach($users as $user)
                                            @if(!$user->hasRole("SUPER_ADMIN") && $user->hasRole("EMPLOYEE"))
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endif
                                        @endforeach
                                    </select> 
                                </div>
                                <div class="col-md-4 next">
                                    <a class="btn btn-primary btn-sm"  onclick="insertSupportingRow()"><i class="fa fa-plus"></i></a>
                                    <button type="button" class="btn btn-danger btn-sm" onclick="deleteSupportingRow(1)"><i class="fa fa-minus"></i></button>           
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6" >
                            <div class="col-md-6">
                                <label>Acknowledge To </label>
                            </div> 
                            <input type="hidden" name="reportTo_count" id="reportTo_count" value="1">
                            <div class="form-group col-sm-12" id="reportTo_grid_1">
                                <div class="col-md-8">
                                    <select class="form-control input-sm" data-placeholder="Select Acknowledge To" rel="select2" id="reportTo_1" name="reportTo_1">
                                        <option selected disabled>Choose Acknowledge To</option>
                                        @foreach($users as $user)
                                            @if(!$user->hasRole("SUPER_ADMIN"))
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endif
                                        @endforeach
                                    </select> 
                                </div>
                                <div class="col-md-4 next">
                                    <a class="btn btn-primary btn-sm"  onclick="insertReportToRow()"><i class="fa fa-plus"></i></a>
                                    <button type="button" class="btn btn-danger btn-sm" onclick="deleteReportToRow(1)"><i class="fa fa-minus"></i></button>           
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row col-sm-12">
                        <div class="form-group col-sm-6">
                            <button type="submit" class="btn btn-sm btn-primary pull-right" >Save</button>
                        </div>
                        <div class="form-group col-sm-6">
                            <!-- <a href="{{ url(config('laraadmin.adminRoute') . '/sop_setups') }}" class="btn btn-default btn-sm">Cancel</a> -->
                            <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('la-assets/plugins/datatables/datatables.min.css') }}"/>
<style>
.list-group {
    list-style-position: inside;
}

.list-group-item {
    display: list-item;
    margin-left: 0px;
    border : 0px;
}
#fm_dropzone_main #closeManual, #closeWorkflow {
    display: block;
    position: relative;
    width: 10px;
    float: right;
    margin-top: -2px;
    margin-right: 2px;
}
</style>
@endpush

@push('scripts')
<script src="{{ asset('la-assets/plugins/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('la-assets/plugins/dropzone/dropzone.js') }}"></script>
<script src="{{ asset('la-assets/plugins/datatables/datatables.min.js') }}"></script>
<script>
$(function () {
    
    $("div.manualfile").slideUp();
    $("#AddNewManual").on("click", function() {
        $("div.manualfile").slideDown();
    });
    $("#closeManual").on("click", function() {
        $("div.manualfile").slideUp();
    });

    $("div.workflowfile").slideUp();
    $("#AddNewWorkflow").on("click", function() {
        $("div.workflowfile").slideDown();
    });
    $("#closeWorkflow").on("click", function() {
        $("div.workflowfile").slideUp();
    });

    $("#sop_setup-add-form").validate({
        
    });
    new Dropzone("div.manualfile", {
        accept : function(file , done) {
            var pic_userid = $("#user_id").val();
            if(pic_userid != 0 && pic_userid != null) {
                done();
            } else {
                alert("Please select PIC.");
                done('Error');
            }
        },
        maxFiles : 10,
        url: "{{action('LA\UploadsController@upload_ManualFiles')}}",
        type : 'POST',
        params: {
            _token: "{{csrf_token()}}"
        },
        init: function() {
            this.on("sending", function(file, xhr, data) {
                data.append("userid", $("#user_id").val());
            });
            this.on("complete", function(file) {
                this.removeFile(file);
                this.processQueue();
            });
            this.on("error", function(file, response) {
                console.log(response);
            });
        },
        success: function(file, response){
            getManualUploadedFiles(response.upload);
        }
    });
    new Dropzone("div.workflowfile", {
        accept : function(file , done) {
            var pic_userid = $("#user_id").val();
            if(pic_userid != 0 && pic_userid != null) {
                done();
            } else {
                alert("Please select PIC.");
                done('Error');
            }
        },
        maxFiles : 10,
        url: "{{action('LA\UploadsController@upload_WorkflowFiles')}}",
        type : 'POST',
        params: {
            _token: "{{csrf_token()}}"
        },
        init: function() {
            this.on("sending", function(file, xhr, data) {
                data.append("userid", $("#user_id").val());
            });
            this.on("complete", function(file) {
                this.removeFile(file);
                this.processQueue();
            });
            this.on("error", function(file, response) {
                console.log(response);
            });
        },
        success: function(file, response){
            console.log(response);
            getWorkflowUploadedFiles(response.upload);
        }
    });
    $('.sop_modal').click(function(e) {
        var user_id = $("#user_id").val();
        if(user_id != null && user_id != 0){
            $("#pic_userid").val(user_id);
            $("#AddModal").modal();
        }
        else
            alert('Please select PIC.');
    });
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
    $("#start_date").css('display', 'none');
    var selectVal = $("#timeframe option:selected").text();
    if(selectVal == 'Once'){
        $("#due_date").css('display', 'block');
        $("#start_date").css('display', 'block');
    } else if(selectVal == 'Daily'){
        $("#every_interval").css('display', 'block');
        $("#startDate").css('display', 'block');
        $("#terminationDate").css('display', 'block');
        $("#start_date").css('display', 'block');
    } else if(selectVal == 'Weekly'){
        $("#every_interval").css('display', 'block');
        $("#startDate").css('display', 'block');
        $("#terminationDate").css('display', 'block');
        $("#day_of_week").css('display', 'block');
        $("#start_date").css('display', 'block');
    } else if(selectVal == 'Monthly'){
        var radioValue = $("input[name='monthly_type']:checked").val();
        if(radioValue == 'Day Of Week'){
            $("#week").css('display', 'block');
            $("#monthly_day_of_week").css('display', 'block');
        } else if(radioValue == 'Day Of Month'){
            $("#day").css('display', 'block');
        }
        $("#every_interval").css('display', 'block');
        $("#monthly_type").css('display', 'block');
        $("#startDate").css('display', 'block');
        $("#terminationDate").css('display', 'block');
        $("#start_date").css('display', 'block');
    }
}

function getManualUploadedFiles(upload) {
    $hinput = $("input[name=manual_file]");
    
    var hiddenFIDs = JSON.parse($hinput.val());
    // check if upload_id exists in array
    var upload_id_exists = false;
    for (var key in hiddenFIDs) {
        if (hiddenFIDs.hasOwnProperty(key)) {
            var element = hiddenFIDs[key];
            if(element == upload.id) {
                upload_id_exists = true;
            }
        }
    }
    if(!upload_id_exists) {
        hiddenFIDs.push(upload.id);
    }
    $hinput.val(JSON.stringify(hiddenFIDs));
    var fileImage = upload.filename;
    $(".uploaded_manualfiles ol").append("<li class='list-group-item'><a upload_id='"+upload.id+"' target='_blank' href='"+bsurl+"/manualfiles/"+upload.hash+"/"+upload.filename+"'>"+fileImage+"</a></li>"); 
}

function getWorkflowUploadedFiles(upload) {
    $hinput = $("input[name=workflow_file]");
    
    var hiddenFIDs = JSON.parse($hinput.val());
    // check if upload_id exists in array
    var upload_id_exists = false;
    for (var key in hiddenFIDs) {
        if (hiddenFIDs.hasOwnProperty(key)) {
            var element = hiddenFIDs[key];
            if(element == upload.id) {
                upload_id_exists = true;
            }
        }
    }
    if(!upload_id_exists) {
        hiddenFIDs.push(upload.id);
    }
    $hinput.val(JSON.stringify(hiddenFIDs));
    var fileImage = upload.filename;
    $(".uploaded_workflowfiles ol").append("<li class='list-group-item'><a upload_id='"+upload.id+"' target='_blank' href='"+bsurl+"/workflowfiles/"+upload.hash+"/"+upload.filename+"'>"+fileImage+"</a></li>"); 
}

function getUploadedFiles(pic_userid){
    $(".uploaded_manualfiles ol").empty();
    $(".uploaded_workflowfiles ol").empty();
    $.ajax({
        dataType: 'json',
        url : "{{ url(config('laraadmin.adminRoute') . '/uploaded_flowchartFiles') }}",
        type: 'POST',
        data : {'_token': '{{ csrf_token() }}', 'pic_id' : pic_userid},
        success: function ( json ) {
            var uploadedFiles = json.uploads;
            for (var index = 0; index < uploadedFiles.length; index++) {
                var upload = uploadedFiles[index];
                getWorkflowUploadedFiles(upload);
            }
        }
    });
    $.ajax({
        dataType: 'json',
        url : "{{ url(config('laraadmin.adminRoute') . '/uploaded_manualFiles') }}",
        type: 'POST',
        data : {'_token': '{{ csrf_token() }}', 'pic_id' : pic_userid},
        success: function ( json ) {
            var uploadedFiles = json.uploads;
            for (var index = 0; index < uploadedFiles.length; index++) {
                var upload = uploadedFiles[index];
                getManualUploadedFiles(upload);
            }
        }
    });
}

function getSOPbyPic(pic_id){
    $.ajax({
        "url" : "{{ url(config('laraadmin.adminRoute') . '/sop_data_by_pic') }}",
        type: 'POST',
        data : {'_token': '{{ csrf_token() }}', 'pic_id' : pic_id},
        success: function(data)
        {
            if ($.fn.DataTable.isDataTable("#example1")) {
                $("#example1").DataTable().destroy();
            }
            var my_columns = [];
            if(data.length != 0) {
                $.each( data[0], function( key, value ) {
                        var my_item = {};
                        my_item.data = key;
                        my_item.title = key;
                        my_columns.push(my_item);
                });
                $("#example1").DataTable({  
                    "destroy": true,
                    data: data,
                    "columns": my_columns
                });
            }else{
                $("#example1").DataTable({  
                    "destroy": true,
                    data: data
                });
            }
        }
    });
}

var supporting_grid;
var reportTo_grid;
function insertSupportingRow()
{
    supporting_gird = $("#supporting_count").val();
    supporting_gird++;
    $("#supporting_count").val(supporting_gird);
    var new_entry1 = `<div class="form-group col-sm-12" id="supporting_grid_${supporting_gird}"><div class="col-md-8">
                    <select class="form-control input-sm" required data-placeholder="Select Supporting" rel="select2" id="supporting_${supporting_gird}" name="supporting_${supporting_gird}">
                        <option selected disabled>Choose Supporting</option>
                        @foreach($users as $user)
                            @if(!$user->hasRole("SUPER_ADMIN"))
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endif
                        @endforeach
                    </select> 
                </div>
                <div class="col-md-4 next">
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
    var new_entry1 = `<div class="form-group col-sm-12" id="reportTo_grid_${reportTo_grid}"><div class="col-md-8">
                    <select class="form-control input-sm" required data-placeholder="Select Acknowledge To" rel="select2" id="reportTo_${reportTo_grid}" name="reportTo_${reportTo_grid}">
                        <option selected disabled>Choose Acknowledge To</option>
                        @foreach($users as $user)
                            @if(!$user->hasRole("SUPER_ADMIN"))
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endif
                        @endforeach
                    </select> 
                </div>
                <div class="col-md-4 next">
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
