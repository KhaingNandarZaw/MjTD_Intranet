@extends('la.layouts.app')

@section('htmlheader_title')
    Vendor Registration View
@endsection

@section('main-content')
<div id="page-content" class="profile2">
    
    <ul data-toggle="ajax-tab" class="nav nav-tabs profile" role="tablist">
        <li class=""><a href="{{ url(config('laraadmin.adminRoute') . '/vendor_registrations') }}" data-toggle="tooltip" data-placement="right" title="Back to Vendor Registrations"><i class="fa fa-chevron-left"></i></a></li>
        <li class="active"><a role="tab" data-toggle="tab" class="active" href="#tab-general-info" data-target="#tab-info"><i class="fa fa-bars"></i> General Info</a></li>
    </ul>

    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active fade in" id="tab-info">
            <div class="tab-content">
                <div class="panel infolist">
                    <div class="panel-default panel-heading">
                        <h4>General Info</h4>
                    </div>
                    <div class="panel-body">
                        @la_display($module, 'company_name')
						@la_display($module, 'registration_no')
						@la_display($module, 'address')
						@la_display($module, 'telephone')
                        @la_display($module, 'email')
                        <div class="row">
                            <div class="col-sm-6">
                                <label class="form-label">Director(s)/Owner(s) Particular</label>
                                @la_display($module, 'director_name')
                                @la_display($module, 'director_position')
                                @la_display($module, 'director_nrc')
                                @la_display($module, 'director_mobile')
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label">Contact Person</label>
                                @la_display($module, 'contact_name')
                                @la_display($module, 'contact_position')
                                @la_display($module, 'contact_no')
                                @la_display($module, 'contact_mobile')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
</div>
@endsection
