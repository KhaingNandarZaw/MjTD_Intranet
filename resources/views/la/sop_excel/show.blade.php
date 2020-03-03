@extends("la.layouts.app")

<?php
use Dwij\Laraadmin\Models\Module;
?>

@section("contentheader_title", "SOP Excel")
@section("contentheader_description", "SOP Excel")
@section("section", "SOP Excel")
@section("sub_section", "SOP Excel")
@section("htmlheader_title", "SOP Excel")

@section("headerElems")
@endsection

@section("main-content")
<div class="box box-info">
    <!--<div class="box-header"></div>-->
    <div class="box-body">
        <div class="row">
            <div class="form-group col-sm-6">
                <input class="form-control input-sm" placeholder="Enter File" data-rule-minlength="0" data-rule-maxlength="0" required="1" name="manual_file" type="hidden" value="[]" aria-required="true">
                <div class="excelfile" id="fm_dropzone_main" name="excelfile" id="excelfile">
                    <div class="dz-message"><i class="fa fa-cloud-upload"></i><br>Drop Excel File here to upload</div>
                </div>
            </div>
        </div>

        <!-- <form action="{{route('admin.import')}}" id="formex" method="post" enctype="multipart/form-data">
        <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
        <input type="file" name="excelfile" id="excelfile"/>
        <input type="submit" id="btnsave" class="btn btn-info" value="Save"> 
        </form>-->
        <div id="wrapper">
            <a href="#" id="import" class="btn btn-sm btn-success pull-right">Import Data</a>
            <table id="sop_table" class="table table-striped table-hover table-bordered">
                <thead class="thead">
                    <tr>
                        <th>Work Description</th>
                        <th>Job Type</th>
                        <th>Time Frame</th>
                        <th>PIC</th>
                        <th>Participant</th>
                        <th>Report To</th>
                        <th>Remark</th>
                    </tr>
                </thead>
            <tbody class="tbody" id="tbody">
                
            </tbody>
            </table>
        </div>
    </div>
</div>
</html>
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('la-assets/plugins/datatables/datatables.min.css') }}"/>
@endpush

@push('scripts')
<script src="{{ asset('la-assets/plugins/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('la-assets/plugins/iconpicker/fontawesome-iconpicker.js') }}"></script>
<script>

$(function () {
	$("#course-edit-form").validate({
		
	});

    $('#sop_table').hide();
    $("#import").hide();

	$('.demo').iconpicker();

});

</script>

<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('#csrf-token').attr('content')
        }
    });

</script>

<script type="text/javascript">
$(document).ready(function(){

    new Dropzone("div.excelfile", {
        maxFilesize: 500,
        maxFiles : 1,
        acceptedFiles: ".xls, .xlsx",
        url : "{{ url(config('laraadmin.adminRoute') . '/check') }}",
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
            data = $.parseJSON(response);
            var data21=data['arr1'];
            var data22=data['arr2'];
            
            $('#sop_table').show();
            $("#import").show();
            
            $("#sop_table").DataTable({
                
            });
            
            if (data22.length > 0) {
               $('.alert-box').show();          
            }

            $.each(data21, function(i, item) {
                  if(item['job_type']== null){
                     item['job_type']="";
                  }
                  if(item['remark']==null){
                    item['remark']="";     
                  }
                if(item['pic'] == undefined || item['pic'] == null || item['pic'] == ''){
                   return false;
                }
                var pic_class=item['pic'].replace(/\s/g, '');
                 $("#tbody").append('<tr><td>'+item['work_description']+
                 '</td><td>'+item['job_type']+'</td><td>'+item['time_frame']+
                 '</td><td class="'+pic_class+'">'+item['pic']+'</td><td>'+item['participant']+
                 '</td><td>'+item['report_to']+'</td><td>'+item['remark']+'</td></tr>');
                 
                $.each(data22, function(j, v) {
                    if (item['pic'] == v) {
                       $('.'+pic_class).addClass('text-danger');
                       $('#btnsave').hide();
                    }
                 });
            });
        }
    });
 $('#excelfile').change(function(e){
    var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$/;
          
    if (regex.test(excelfile.value.toLowerCase())) {
    $.ajax({
        "url" : "{{ url(config('laraadmin.adminRoute') . '/check') }}",
        type:'POST',
        data:new FormData($('#formex')[0]),
        async:false,
        processData:false,
        contentType:false,
        success:function(response){
           data = $.parseJSON(response);
            var data21=data['arr1'];
            var data22=data['arr2'];
            
            $('#sop_table').show();
            $("#sop_table").DataTable({
                
            });
            
            if (data22.length > 0) {
               $('.alert-box').show();          
            }

            $.each(data21, function(i, item) {
                  if(item['job_type']== null){
                     item['job_type']="";
                  }
                  if(item['remark']==null){
                    item['remark']="";     
                  }

                  var pic_class=item['pic'].replace(/\s/g, '');
                 $("#tbody").append('<tr><td>'+item['work_description']+
                 '</td><td>'+item['job_type']+'</td><td>'+item['time_frame']+
                 '</td><td class="'+pic_class+'">'+item['pic']+'</td><td>'+item['participant']+
                 '</td><td>'+item['report_to']+'</td><td>'+item['remark']+'</td></tr>');
                 
                $.each(data22, function(j, v) {
                    if (item['pic'] == v) {
                       $('.'+pic_class).addClass('text-danger');
                       $('#btnsave').hide();
                    }
                 });
            });
        }
    })
    }else {
        alert("Please upload a valid Excel file.");
    }
 });
});
</script>
@endpush

@push('styles')

@endpush