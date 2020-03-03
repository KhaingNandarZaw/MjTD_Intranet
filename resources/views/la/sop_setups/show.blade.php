@extends('la.layouts.app')

@section('htmlheader_title')
    SOP Setup View
@endsection

@section('main-content')
<div id="page-content" class="profile2">
    <div class="bg-primary clearfix">
        <div class="col-md-4">
            <div class="row">
                <div class="col-md-9">
                    <h4 class="name"><i class="fa fa-list"></i> Master List of SOP </h4>
                </div>
            </div>
        </div>
    </div>
    <ul data-toggle="ajax-tab" class="nav nav-tabs profile" role="tablist">
        <li class="active"><a role="tab" data-toggle="tab" class="active" href="#tab-general-info" data-target="#tab-info"><i class="fa fa-bars"></i> General Info</a></li>
        @role("SUPER_ADMIN")
        <li class=""><a role="tab" data-toggle="tab" href="#tab-files" data-target="#tab-files"><i class="fa fa-files-o"></i> Manual & Flow Chart</a></li>
        @endrole
    </ul>

    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active fade in" id="tab-info">
            <div class="tab-content">
                <div class="panel infolist">
                    @if(!Entrust::hasRole("SUPER_ADMIN"))
                    <table class="table table-striped table-hover table-bordered">
                        <thead>
                            <tr>
                                <th class="col-sm-6"> Manual Files</th>
                                <th class="col-sm-6"> Flowchart Files </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pic_users as $key => $pic_user)
                            @if($sops[0]->PIC == $pic_user->PIC)
                            <tr>
                                <td>
                                <?php 
                                    $manualFiles = \App\Models\SOP_Manual_Upload::select('id', 'hash', 'filename', 'extension')->where('pic_userid', $pic_user->pic_user_id)->get();
                                    $value = 'No files found.';
                                    $uploads_html = "";
                                    $image = "fa-file-o";
                                        
                                    foreach($manualFiles as $manual) {
                                        if(isset($manual->id)) {  
                                            if($manual->extension == 'pdf')
                                                $image = "fa-file-pdf-o";
                                            if($manual->extension == 'docx')
                                                $image = "fa-file-word-o";
                                            if($manual->extension == 'xlsx')  
                                                $image = "fa-file-excel-o"; 
                                            if($manual->extension == "pptx")
                                                $image = "fa-file-powerpoint-o";        
                                            $uploads_html .= '<a class="preview" target="_blank" href="' . url("manualfiles/" . $manual->hash . DIRECTORY_SEPARATOR . $manual->filename) . '" data-toggle="tooltip" data-placement="top" data-container="body" style="display:inline-block;margin-right:5px;" title="' . $manual->filename . '">
                                                    <span class="fa '. $image .'"></span> ' . $manual->filename . '</a><br>';
                                        }
                                        
                                        $value = $uploads_html;
                                    }
                                    echo $value;
                                ?>
                                </td>
                                <td>
                                <?php 
                                    $flowchartFiles = \App\Models\SOP_Flowchart_Upload::select('id', 'hash', 'filename', 'extension')->where('pic_userid', $pic_user->pic_user_id)->get();
                                    $value = 'No files found.';
                                    $uploads_html = "";
                                    $image = "fa-file-o";
                                        
                                    foreach($flowchartFiles as $flowchart) {
                                        if(isset($flowchart->id)) {    
                                            if($flowchart->extension == 'pdf')
                                                $image = "fa-file-pdf-o";
                                            if($flowchart->extension == 'docx')
                                                $image = "fa-file-word-o";
                                            if($flowchart->extension == 'xlsx')  
                                                $image = "fa-file-excel-o"; 
                                            if($flowchart->extension == "pptx")
                                                $image = "fa-file-powerpoint-o";             
                                            $uploads_html .= '<a class="preview" target="_blank" href="' . url("workflowfiles/" . $flowchart->hash . DIRECTORY_SEPARATOR . $flowchart->filename) . '" data-toggle="tooltip" data-placement="top" data-container="body" style="display:inline-block;margin-right:5px;" title="' . $flowchart->filename . '">
                                                    <span class="fa '.$image.' "> ' . $flowchart->filename . '</a><br>';
                                        }
                                        
                                        $value = $uploads_html;
                                    }
                                    echo $value;
                                ?>
                                </td>
                            </tr>       
                            @endif      
                            @endforeach  
                        </tbody>
                    </table>
                    @endif
                    <br>
                    <table class="table table-striped table-hover table-bordered" id="sop_table">
                        <thead>
                            <tr>
                                <th> # </th>
                                <th> Work Description </th>
                                <th> JobType</th>
                                <th> TimeFrame </th>
                                @role("SUPER_ADMIN")
                                <th> PIC </th>
                                @endrole
                                <th> Supporting </th>
                                <th> Report To </th>
                                <th> Remark </th>
                                @role("SUPER_ADMIN")
                                <th class="col-sm-1"> Actions</th>
                                @endrole
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sops as $key => $sop_data)
                            <tr>
                                <td></td>
                                <td>{{ $sop_data->WorkDescription }}</td>
                                <td>{{ $sop_data->JobType }}</td>
                                <td>{{ $sop_data->TimeFrame }}</td>
                                @role("SUPER_ADMIN")
                                <td>{{ $sop_data->PIC }}</td>
                                @endrole
                                <td>{{ $sop_data->Supportings }}</td>
                                <td>{{ $sop_data->ReportTo }}</td>
                                <td>{{ $sop_data->Remark }}</td>
                                @role("SUPER_ADMIN")
                                <td>
                                @if(Module::hasAccess('SOP_Setups','delete'))
                                {!! Form::open(['route' => [config('laraadmin.adminRoute'). '.sop_setups.destroy', 
                                $sop_data->id], 'method' => 'delete' , 'style' => 'display:inline']) !!}
                                <button class="btn btn-danger btn-xs" type="submit"><i class="fa fa-trash"></i></button>
                                {!! Form::close() !!}
                                @endif
                                </td>
                                @endrole
                            </tr>             
                            @endforeach  
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @role("SUPER_ADMIN")
        <div role="tabpanel" class="tab-pane fade in p20 bg-white" id="tab-files">
            <div class="tab-content">
                <div class="panel infolist">
                    <table class="table table-hover table-bordered" id="files_table">
                        <thead>
                            <tr>
                                <th> # </th>
                                <th> PIC </th>
                                <th> Manual Files </th>
                                <th> Flowchart Files </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pic_users as $key => $pic_user)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $pic_user->PIC }}</td>
                                <td>
                                <ol class="list-group">
                                    <?php 
                                        $manualFiles = \App\Models\SOP_Manual_Upload::select('id', 'hash', 'filename')->where('pic_userid', $pic_user->pic_user_id)->get();
                                        $value = 'No files found.';
                                        $uploads_html = "";
                                            
                                        foreach($manualFiles as $manual) {
                                            if(isset($manual->id)) {             
                                                $uploads_html .= '<li class="list-group-item"><a class="preview" target="_blank" href="' . url("manualfiles/" . $manual->hash . DIRECTORY_SEPARATOR . $manual->filename) . '" data-toggle="tooltip" data-placement="top" data-container="body" style="display:inline-block;margin-right:5px;" title="' . $manual->filename . '">
                                                        ' . $manual->filename . '</a><a href="#" class="btn btn-xs btn-danger pull-right"><i class="fa fa-trash"></i></a></li>';
                                            }
                                            $value = $uploads_html;
                                        }
                                        echo $value;
                                    ?>
                                </ol>
                                </td>
                                <td>
                                <ol class="list-group">
                                <?php 
                                    $flowchartFiles = \App\Models\SOP_Flowchart_Upload::select('id', 'hash', 'filename')->where('pic_userid', $pic_user->pic_user_id)->get();
                                    $value = 'No files found.';
                                    $uploads_html = "";
                                    foreach($flowchartFiles as $flowchart) {
                                        if(isset($flowchart->id)) {             
                                            $uploads_html .= '<li class="list-group-item"><a class="preview" target="_blank" href="' . url("workflowfiles/" . $flowchart->hash . DIRECTORY_SEPARATOR . $flowchart->filename) . '" data-toggle="tooltip" data-placement="top" data-container="body" style="display:inline-block;margin-right:5px;" title="' . $flowchart->filename . '">
                                                    ' . $flowchart->filename . '</a><a href="#" class="btn btn-xs btn-danger pull-right"><i class="fa fa-trash"></i></a></li>';
                                        }
                                        $value = $uploads_html;
                                    }
                                    echo $value;
                                ?>
                                </ol>
                                </td>
                            </tr>             
                            @endforeach  
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endrole
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('la-assets/plugins/datatables/datatables.min.css') }}"/>
<link data-require="jqueryui@*" data-semver="1.10.0" rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.10.0/css/smoothness/jquery-ui-1.10.0.custom.min.css" />
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
<script src="{{ asset('la-assets/plugins/jQueryUI/jquery-ui.js') }}"></script>
<script src="{{ asset('la-assets/plugins/datatables/jquery.dataTables.js') }}" data-semver="1.9.4" data-require="datatables@*"></script>
<script src="{{ asset('la-assets/plugins/datatables/datatables.js') }}"></script>

<script>
$(function(){
    var groupColumn = 2;
        let oTable = $('#sop_table').DataTable({
        "columnDefs": [
            { "visible": false, "targets": groupColumn }
        ],
        "oSearch" : { "bSmart" : true },
        "order": [[ groupColumn, 'desc' ]],
        "displayLength": 25,
        "drawCallback": function ( settings ) {
            var api = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            var last=null;
 
            api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {
                if ( last !== group ) {
                    $(rows).eq( i ).before(
                        '<tr class="group"><td colspan="8">'+group+'</td></tr>'
                    );
 
                    last = group;
                }
            } );
        }
    });
    $("#files_table").DataTable({

    });
});
</script>
@endpush