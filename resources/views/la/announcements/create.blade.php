@extends("la.layouts.app")

@section("contentheader_title", "Announcements")
@section("contentheader_description", "New Announcement")
@section("section", "Announcements")
@section("sub_section", "Create")
@section("htmlheader_title", "New Announcement")

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
    {!! Form::open(['action' => 'LA\AnnouncementsController@store', 'id' => 'announcement-add-form']) !!}
    <div class="box-body">
        <div class="col-sm-8 col-sm-offset-2">
            @la_input($module, 'title')
            @la_input($module, 'description')
            @la_input($module, 'icon')
            @la_input($module, 'startdate')
            @la_input($module, 'enddate')
            <div class="is_sent">
            @la_input($module, 'is_sent')
            </div>
            <div class="email">
                @la_input($module, 'email')
            </div>
            <div class="form-group">
                <input class="form-control input-sm" placeholder="Enter File" data-rule-minlength="0" data-rule-maxlength="0" required="1" name="hidden_file" type="hidden" value="[]" aria-required="true">
                <div class="file" id="fm_dropzone_main" name="file">
                    <div class="dz-message"><i class="fa fa-cloud-upload"></i><br>Drop files here to upload</div>
                </div>
                
                <div class="uploaded_files">
                    <ol>
                    </ol>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-6" align="right">
                    {!! Form::submit( 'Save', ['class'=>'btn btn-primary btn-sm']) !!}
                </div>
                <div class="col-sm-6" align="left">
                    <a href="{{ url(config('laraadmin.adminRoute') . '/announcements') }}" class="btn btn-default btn-sm">Cancel</a>
                </div>
            </div>
        </div>
    </div>
    
    {!! Form::close() !!}
</div>

@endsection

@push('scripts')
<script src="{{ asset('la-assets/plugins/iconpicker/fontawesome-iconpicker.js') }}"></script>

<script>
$(function () {
    $("#announcement-add-form").validate({
        
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
<script>
$(document).ready(function(){
    $(".email").hide();
    $(".is_sent").click(function () {
        $(".email").toggle();
    })
})

</script>
@endpush
