@extends("la.layouts.app")

@section("contentheader_title")
    <a href="{{ url(config('laraadmin.adminRoute') . '/vendor_registrations') }}">Vendor Registration</a> :
@endsection
@section("contentheader_description", $vendor_registration->$view_col)
@section("section", "Vendor Registrations")
@section("section_url", url(config('laraadmin.adminRoute') . '/vendor_registrations'))
@section("sub_section", "Edit")

@section("htmlheader_title", "Vendor Registrations Edit : ".$vendor_registration->$view_col)

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

<div class="box box-primary">
    <div class="box-header">
        <div class="box-header with-border">
            <h3 class="box-title">Vendor Entry</h3>
        </div>  
    </div>
    <div class="box-body">
        {!! Form::model($vendor_registration, ['route' => [config('laraadmin.adminRoute') . '.vendor_registrations.update', $vendor_registration->id ], 'method'=>'PUT', 'id' => 'vendor_registration-edit-form']) !!}
            @la_input($module, 'company_name')
            @la_input($module, 'registration_no')
            @la_input($module, 'address')
            @la_input($module, 'telephone')
            @la_input($module, 'email')
            <div class="row">
                <div class="form-group col-sm-6">
                    <label class="form-label">Director(s)/Owner(s) Particular</label>
                    @la_input($module, 'director_name')
                    @la_input($module, 'director_position')
                    @la_input($module, 'director_nrc')
                    @la_input($module, 'director_mobile')
                </div>
                <div class="form-group col-sm-6">
                    <label class="form-label">Contact Person</label>
                    @la_input($module, 'contact_name')
                    @la_input($module, 'contact_position')
                    @la_input($module, 'contact_no')
                    @la_input($module, 'contact_mobile')
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-6" align="right">
                    {!! Form::submit( 'Update', ['class'=>'btn btn-success btn-sm']) !!}
                </div>
                <div class="col-sm-6" align="left">
                    <a href="{{ url(config('laraadmin.adminRoute') . '/vendor_registrations') }}" class="btn btn-default btn-sm">Cancel</a>
                </div>
            </div>
        {!! Form::close() !!}
    </div>
</div>

@endsection

@push('scripts')
<script>
$(function () {
    $("#vendor_registration-edit-form").validate({
        
    });
});
</script>
@endpush
