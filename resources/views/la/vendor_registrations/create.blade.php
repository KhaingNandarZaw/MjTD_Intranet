@extends("la.layouts.app")

@section("contentheader_title", "Vendor Registrations")
@section("contentheader_description", "Vendor Registrations Entry")
@section("section", "Vendor Registrations")
@section("section_url", url(config('laraadmin.adminRoute') . '/vendor_registerations'))
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

<div class="box box-green">
    <div class="box-header with-border">
        <h3 class="box-title">Vendor Entry</h3>
    </div>    
    <div class="box-body">
    {!! Form::open(['action' => 'LA\Vendor_RegistrationsController@store', 'id' => 'vendor_registration-add-form']) !!}
            <div class="box-body">
                
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
            </div>
            
            <div class="form-group">
                <div class="col-sm-6" align="right">
                    {!! Form::submit( 'Submit', ['class'=>'btn btn-success btn-sm']) !!}
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
    $("#vendor_registration-add-form").validate({
        
    });
});
</script>
@endpush
