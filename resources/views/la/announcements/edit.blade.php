@extends("la.layouts.app")

@section("contentheader_title")
    <a href="{{ url(config('laraadmin.adminRoute') . '/announcements') }}">Announcement</a> :
@endsection
@section("contentheader_description", $announcement->$view_col)
@section("section", "Announcements")
@section("section_url", url(config('laraadmin.adminRoute') . '/announcements'))
@section("sub_section", "Edit")

@section("htmlheader_title", "Announcements Edit : ".$announcement->$view_col)

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
        
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                {!! Form::model($announcement, ['route' => [config('laraadmin.adminRoute') . '.announcements.update', $announcement->id ], 'method'=>'PUT', 'id' => 'announcement-edit-form']) !!}
                    
                    @la_input($module, 'title')
					@la_input($module, 'description')
					@la_input($module, 'icon')
					@la_input($module, 'startdate')
                    @la_input($module, 'enddate')
                    <?php
                        $field_name = 'hidden_file';
                        if(isset($module->row)) {
                            $row = $module->row;
                        }
                            
                        if(isset($row) && isset($row->$field_name)) {
                            $hidden_file = $row->$field_name;
                        }
                    ?>  
                    <div class="form-group">
                        <input class="form-control input-sm" placeholder="Enter File" data-rule-minlength="0" data-rule-maxlength="0" required="1" name="hidden_file" type="hidden" value="{{$hidden_file}}" aria-required="true">
                        <div class="file" id="fm_dropzone_main" name="file">
                            <div class="dz-message"><i class="fa fa-cloud-upload"></i><br>Drop files here to upload</div>
                        </div>
                        <div class="uploaded_files">
                            <ol>
                            <?php 
                                $value = '';
                                $uploads_html = "";
                                
                                if(is_array($hidden_file)) {
                                    $hidden_file = json_encode($hidden_file);
                                }
                                
                                $default_val_arr = json_decode($hidden_file);

                                foreach($default_val_arr as $uploadId) {
                                    $manual = \App\Models\Upload::find($uploadId);
                                    if(isset($manual->id)) {             
                                        $uploads_html .= '<li class="list-group-item"><a class="preview" target="_blank" href="' . url("manualfiles/" . $manual->hash . DIRECTORY_SEPARATOR . $manual->filename) . '" >
                                                ' . $manual->name . '</a><a href="#" class="btn btn-xs btn-danger pull-right"><i class="fa fa-trash"></i></a></li>';
                                    }
                                    $value = $uploads_html;
                                }
                                echo $value;
                            ?>
                            </ol>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-6" align="right">
                            {!! Form::submit( 'Update', ['class'=>'btn btn-success btn-sm']) !!}
                        </div>
                        <div class="col-sm-6" align="left">
                            <a href="{{ url(config('laraadmin.adminRoute') . '/announcements') }}" class="btn btn-default btn-sm">Cancel</a>
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style type="text/css">
tr.group, tr.group:hover {
    background-color: #ddd !important;
}
.list-group {
    list-style-position: inside;
}
.list-group-item {
    display: list-item;
    margin-left: 0px;
    border : 0px;
    background-color : #fff0;
}
</style>
@endpush

@push('scripts')
<script src="{{ asset('la-assets/plugins/iconpicker/fontawesome-iconpicker.js') }}"></script>
<script>
$(function () {
    $("#announcement-edit-form").validate({
        
    });
    $("input[name=icon]").iconpicker();
    new Dropzone("div.file", {        
        maxFilesize: 500,
        maxFiles : 10,
        url: "{{action('LA\UploadsController@upload_files')}}",
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
            getUploadedFile(response.upload);
        }
    });
});


function getUploadedFile(upload) {
    $hinput = $("input[name=hidden_file]");
    
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
    var fileImage = upload.name;
    $(".uploaded_files ol").append("<li class='list-group-item'><a upload_id='"+upload.id+"' target='_blank' href='"+bsurl+"/files/"+upload.hash+"/"+upload.name+"'>"+fileImage+"</a><a href='#' onclick='deleteManualFile("+upload.id+")' class='btn btn-xs btn-danger pull-right'><i class='fa fa-trash'></i></a></li>"); 
}

function deleteManualFile(id){
    alert(id);
    $.ajax({
        
        dataType: 'json',
        url : "{{ url(config('laraadmin.adminRoute') . '/uploads_delete_file') }}",
        type: 'POST',
        data : {'_token': '{{ csrf_token() }}', 'file_id' : id},
        success: function ( response ) {
            $hinput = $("input[name=hidden_file]");
            var hiddenFIDs = JSON.parse($hinput.val());
            for( var i = 0; i < hiddenFIDs.length; i++){ 
                if ( hiddenFIDs[i] == id) {
                    hiddenFIDs.splice(i, 1); 
                    i--;
                }
            }
            $hinput.val(JSON.stringify(hiddenFIDs));
            getUploadedFiles();
        }
    });
}
function getUploadedFiles(){
    $(".uploaded_files ol").empty();
    var hinput = $("input[name=hidden_file]").val();
    $.ajax({
        dataType: 'json',
        url : "{{ url(config('laraadmin.adminRoute') . '/uploaded_files_byid') }}",
        type: 'POST',
        data : {'_token': '{{ csrf_token() }}', 'hinput' : hinput},
        success: function ( json ) {
            var uploadedFiles = json.uploads;
            for (var index = 0; index < uploadedFiles.length; index++) {
                var upload = uploadedFiles[index];
                getUploadedFile(upload);
            }
        }
    });
}

</script>
@endpush
