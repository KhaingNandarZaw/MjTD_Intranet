@extends("la.layouts.app")

@section("contentheader_title")
    <a href="{{ url(config('laraadmin.adminRoute') . '/sop_set_ups') }}">SOP Set up</a> :
@endsection
@section("contentheader_description", $sop_set_up->$view_col)
@section("section", "SOP Set ups")
@section("section_url", url(config('laraadmin.adminRoute') . '/sop_set_ups'))
@section("sub_section", "Edit")

@section("htmlheader_title", "SOP Set ups Edit : ".$sop_set_up->$view_col)

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
                {!! Form::model($sop_set_up, ['route' => [config('laraadmin.adminRoute') . '.sop_set_ups.update', $sop_set_up->id ], 'method'=>'PUT', 'id' => 'sop_set_up-edit-form']) !!}
                    @la_form($module)
                    
                    {{--
                    @la_input($module, 'name')
                    --}}
                    <br>
                    <div class="form-group">
                        {!! Form::submit( 'Update', ['class'=>'btn btn-success']) !!} <a href="{{ url(config('laraadmin.adminRoute') . '/sop_set_ups') }}" class="btn btn-default pull-right">Cancel</a>
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
    $("#sop_set_up-edit-form").validate({
        
    });
});
</script>
@endpush
