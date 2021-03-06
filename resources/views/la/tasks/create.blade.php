@extends("la.layouts.app")

@section("contentheader_title", "Tasks")
@section("contentheader_description", "Assign Task")
@section("section", "Tasks")
@section("sub_section", "Create")
@section("htmlheader_title", "Assign New Task")

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
    <!--<div class="box-header"></div>-->
    {!! Form::open(['action' => 'LA\TasksController@store', 'id' => 'task-add-form']) !!}
    <div class="box-body">
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
            <div class="form-group col-md-4">
                <label>PIC <span style="color:red;">*</span>:</label>
                <select class="form-control input-sm" required data-placeholder="Select PIC" rel="select2" id="pic_userid" name="pic_userid">
                    <option selected disabled>Choose PIC</option>
                    @foreach($users as $user)
                        @if($user->hasRole("EMPLOYEE"))
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endif
                    @endforeach
                </select> 
            </div>
            <div class="form-group col-sm-4">
                <label>Report To <span style="color:red;">*</span>:</label>
                <select class="form-control input-sm" required data-placeholder="Select Report To" rel="select2" id="report_to_userid" name="report_to_userid">
                    <option selected disabled>Choose Report To</option>
                    @foreach($users as $user)
                        @if(!$user->hasRole("ADMIN"))
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endif
                    @endforeach
                </select> 
            </div>
            <?php $users = App\User::all(); ?>
            <div class="form-group col-sm-4">
                <label for="cc">Cc :</label>
                <select class="form-control input-sm" multiple data-placeholder="Select Task" rel="select2" name="cc_users[]">
                    @foreach($users as $user)
                        @if(!$user->hasRole("SUPER_ADMIN"))
                        <option value="{{ $user->email }}">{{ $user->name }}</option>
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
                            @if($time_frame->name == 'Once'))
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
            <div class="col-sm-4">
                @la_input($module, 'remark')
            </div>
            <div class="form-group col-sm-6">
                <input class="form-control input-sm" placeholder="Enter File" data-rule-minlength="0" data-rule-maxlength="0" required="1" name="attachments" type="hidden" value="[]" aria-required="true">
                <div id="fm_dropzone_main" name="file">
                    <a id="closeDZ1"><i class="fa fa-times"></i></a>
                    <div class="dz-message"><i class="fa fa-cloud-upload"></i><br>Drop files here to attach</div>
                </div>
                <div class="uploaded_files">
                    <ol class="list-group"></ol>
                </div>
            </div>
        </div>
        <!-- <div class="row">
            <div class="form-group" >
                <div class="col-md-6">
                    <label>PIC <sup style="color:red;">*</sup></label>
                </div> 
                <input type="hidden" name="pic_count" id="pic_count" value="1">
                <div class="form-group col-sm-12" id="pic_grid_1">
                    <div class="col-md-3">
                        <select class="form-control input-sm" required data-placeholder="Select PIC" rel="select2" id="pic_1" name="pic_1">
                            <option selected disabled>Choose PIC</option>
                            @foreach($users as $user)
                                @if(!$user->hasRole("SUPER_ADMIN") && !$user->hasRole("OFFICER"))
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endif
                            @endforeach
                        </select> 
                    </div>
                    <div class="col-md-3">
                        <textarea class="form-control" placeholder="Description/Comment" id="desc_1" name="desc_1"></textarea>
                    </div>
                    <div class="col-md-6 next">
                        <a class="btn btn-primary btn-sm"  onclick="insertPicRow()"><i class="fa fa-plus"></i></a>
                        <button type="button" class="btn btn-danger btn-sm" onclick="deletePicRow(1)"><i class="fa fa-minus"></i></button>           
                    </div>
                </div>
            </div>
        </div> -->
        <div class="col-md-8 col-md-offset-5">
            <div class="form-group">
                {!! Form::submit( 'Assign', ['class'=>'btn btn-sm btn-primary']) !!} 
                <a href="{{ url(config('laraadmin.adminRoute') . '/tasks') }}" class="btn btn-sm btn-default">Cancel</a>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
</div>

@endsection

@push('styles')

@endpush

@push('scripts')
<script>
$(function () {
    
    new Dropzone("#fm_dropzone_main", {
        maxFiles : 10,
        maxFilesize: 500,
        url: "{{action('LA\UploadsController@upload_task_files')}}",
        type : 'POST',
        params: {
            _token: "{{csrf_token()}}"
        },
        init: function() {
            this.on("complete", function(file) {
                this.removeFile(file);
                this.processQueue();
            });
            this.on("error", function(file, response) {
                console.log(response);
            });
        },
        success: function(file, response){
            loadUploadedFile(response.upload);
        }
    });

    $("#task-add-form").validate({
        
    });

    $("#closeDZ1").on("click", function() {
        $("#fm_dropzone_main").slideUp();
    });

    $("input[name=monthly_type]").on('change', function(){
        timeFrameChanged();
    });
});

function loadUploadedFile(upload) {
    $hinput = $("input[name=attachments]");
    
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
    $(".uploaded_files ol").append("<li class='list-group-item'><a upload_id='"+upload.id+"' target='_blank' href='"+bsurl+"/task_attachments/"+upload.hash+"/"+upload.filename+"'>"+fileImage+"</a><a href='#' onclick='delete_task_attachment(" + upload.id + ")' class='btn btn-xs btn-danger pull-right'><i class='fa fa-trash'></i></a></li>"); 
}
function getUploadedFiles(){
    $(".uploaded_files ol").empty();
    var hinput = $("input[name=attachments]").val();
    $.ajax({
        dataType: 'json',
        url : "{{ url(config('laraadmin.adminRoute') . '/uploaded_task_attachments') }}",
        type: 'POST',
        data : {'_token': '{{ csrf_token() }}', 'hinput' : hinput},
        success: function ( json ) {
            var uploadedFiles = json.uploads;
            for (var index = 0; index < uploadedFiles.length; index++) {
                var upload = uploadedFiles[index];
                loadUploadedFile(upload);
            }
        }
    });
}
function delete_task_attachment(upload_id){
    $hinput = $("input[name=attachments]");
    
    var hiddenFIDs = JSON.parse($hinput.val());
    for( var i = 0; i < hiddenFIDs.length; i++){ 
        if ( hiddenFIDs[i] == upload_id) {
            hiddenFIDs.splice(i, 1); 
            i--;
        }
    }
    $hinput.val(JSON.stringify(hiddenFIDs));
    
    $.ajax({
        dataType: 'json',
        url : "{{ url(config('laraadmin.adminRoute') . '/delete_task_attachment') }}",
        type: 'POST',
        data : {'_token': '{{ csrf_token() }}', 'file_id' : upload_id},
        success: function ( json ) {
            getUploadedFiles();
        }
    });
}
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
                            @if(!$user->hasRole("SUPER_ADMIN") && !$user->hasRole("OFFICER"))
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