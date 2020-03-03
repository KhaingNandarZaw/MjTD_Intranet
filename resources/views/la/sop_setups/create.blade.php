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
        {!! Form::open(['action' => 'LA\SOP_SetupsController@store', 'id' => 'sop_setup-create-form']) !!}
            <div class="form-group col-sm-12">
                <label for="pic">PIC<span style="color: red;"> * </span>:</label>
                <select class="form-control" required="1" data-placeholder="Select User" rel="select2" name="user_id">
                <?php $users = App\User::all(); ?>
                    @foreach($users as $user)
                        @if(!$user->hasRole("SUPER_ADMIN"))
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="form-group col-sm-6">
                @la_input($pic_module, 'manual_file')
            </div>
            <div class="form-group col-sm-6">
                @la_input($pic_module, 'flowchart_files')
            </div>
            <div class="form-group col-sm-12">
                <button type="button" class="btn btn-success btn-sm pull-right" data-toggle="modal" data-target="#AddModal">Add New SOP Data</button>
            </div>
            <div class="form-group col-sm-12">
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title ">SOP Lists</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <table id="example1" class="table table-bordered">
                            <thead>
                            <tr class="info">
                                @foreach( $listing_cols as $col )
                                <th>{{ $module->fields[$col]['label'] or ucfirst($col) }}</th>
                                @endforeach
                                @if($show_actions)
                                <th>Actions</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-md-offset-6">
                <div class="form-group">
                    {!! Form::submit( 'Submit', ['class'=>'btn btn-success']) !!} 
                    <a href="{{ url(config('laraadmin.adminRoute') . '/sop_setups') }}" class="btn btn-default">Cancel</a>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

<div class="modal fade" id="AddModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Add SOP Setup</h4>
            </div>
            {!! Form::open(['action' => 'LA\SOP_SetupsController@store', 'id' => 'sop_setup-add-form']) !!}
            <div class="modal-body">
                <div class="box-body">
                    @la_input($module, 'work_description')
                    @la_input($module, 'job_type')
                    @la_input($module, 'timeframe')
                    @la_input($module, 'supporting_userid')
                    @la_input($module, 'reportto_userid')
                    @la_input($module, 'remark')
                </div>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-primary" data-dismiss="modal" onClick="addSOP_Data()">Submit</button> -->
                {!! Form::submit( 'Submit', ['class'=>'btn btn-success']) !!}
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('la-assets/plugins/datatables/datatables.min.css') }}"/>
@endpush

@push('scripts')
<script src="{{ asset('la-assets/plugins/datatables/datatables.min.js') }}"></script>
<script>
$(function () {
    $("#sop_setup-create-form").validate({
        
    });

    $("#example1").DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ url(config('laraadmin.adminRoute') . '/sop_setup_dt_ajax') }}",
        language: {
            lengthMenu: "_MENU_",
            search: "_INPUT_",
            searchPlaceholder: "Search"
        },
        @if($show_actions)
        columnDefs: [ { orderable: false, targets: [-1] }],
        @endif
    });

    $sop_lists = [];
    
});

</script>
@endpush
