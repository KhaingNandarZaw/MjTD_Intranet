@extends("la.layouts.app")

@section("contentheader_title")
    <a href="{{ url(config('laraadmin.adminRoute') . '/sop_setups') }}">SOP Setup</a> :
@endsection
@section("contentheader_description", $sop_setup->$view_col)
@section("section", "SOP Setups")
@section("section_url", url(config('laraadmin.adminRoute') . '/sop_setups'))
@section("sub_section", "Edit")

@section("htmlheader_title", "SOP Setups Edit : ".$sop_setup->$view_col)

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
                {!! Form::model($sop_setup, ['route' => [config('laraadmin.adminRoute') . '.sop_setups.update', $sop_setup->id ], 'method'=>'PUT', 'id' => 'sop_setup-edit-form']) !!}
                    @la_form($module)
                    
                    {{--
                    @la_input($module, 'work_description')
					@la_input($module, 'job_type')
					@la_input($module, 'timeframe')
					@la_input($module, 'remark')
					@la_input($module, 'pic_userid')
					@la_input($module, 'manual_file')
					@la_input($module, 'flowchart_files')
                    --}}
                    <br>
                    <div class="form-group">
                        {!! Form::submit( 'Update', ['class'=>'btn btn-success']) !!} <a href="{{ url(config('laraadmin.adminRoute') . '/sop_setups') }}" class="btn btn-default pull-right">Cancel</a>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(function () {
    $("#sop_setup-edit-form").validate({
        
    });
});
</script>
@endpush
