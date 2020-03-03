@extends('la.layouts.app')

@section('htmlheader_title')
	User View
@endsection

@section('main-content')
<div id="page-content" class="profile2">
	<div class="box box-widget widget-user-2">
        <!-- Add the bg color to the header using any of the bg-* classes -->
        <div class="widget-user-header bg-olive">
          <div class="widget-user-image">
            <img class="img-circle" src="{{asset('la-assets/img/user1-128x128.jpg')}}" alt="User Avatar">
          </div>
          <!-- /.widget-user-image -->
          <h3 class="widget-user-username">{{$user->name}}</h3>
          <h5 class="widget-user-desc">{{$user->designation}}</h5>
        </div>
        <div class="box-footer no-padding">
        	<ul data-toggle="ajax-tab" class="nav nav-tabs profile" role="tablist">
				<li class=""><a href="javascript:history.back()" data-toggle="tooltip" data-placement="right" title="Back to users"><i class="fa fa-chevron-left"></i></a></li>
				<li class="active"><a role="tab" data-toggle="tab" class="active" href="#tab-info" data-target="#tab-info"><i class="fa fa-bars"></i> General Info</a></li>
				@if($user->id == Auth::user()->id || Entrust::hasRole("SUPER_ADMIN"))
					<li class=""><a role="tab" data-toggle="tab" href="#tab-account-settings" data-target="#tab-account-settings"><i class="fa fa-key"></i> Account settings</a></li>
				@endif
			</ul>
			<div class="tab-content">
				<div role="tabpanel" class="tab-pane active fade in" id="tab-info">
					<div class="tab-content">
						<div class="panel infolist">
							<div class="panel-default panel-heading">
								<h4>General Info</h4>
							</div>
							<div class="panel-body">
								@la_display($module, 'name', '', 'write')
								@la_display($module, 'designation', '', 'write')
								@la_display($module, 'gender', '', 'write')
								@la_display($module, 'date_birth', '', 'write')
								@la_display($module, 'mobile', '', 'write')
								@la_display($module, 'mobile2', '', 'write')
								@la_display($module, 'email', '', 'write')
								@la_display($module, 'department', '', 'write')
								@la_display($module, 'address', '', 'write')
							</div>
						</div>
					</div>
				</div>
				
				@if($user->id == Auth::user()->id || Entrust::hasRole("SUPER_ADMIN"))
				<div role="tabpanel" class="tab-pane fade" id="tab-account-settings">
					<div class="tab-content">
						<form action="{{ url(config('laraadmin.adminRoute') . '/change_password/'.$user->id) }}" id="password-reset-form" class="general-form dashed-row white" method="post" accept-charset="utf-8">
							{{ csrf_field() }}
							<div class="panel">
								<div class="panel-default panel-heading">
									<h4>Account settings</h4>
								</div>
								<div class="panel-body">
									@if (count($errors) > 0)
										<div class="alert alert-danger">
											<ul>
												@foreach ($errors->all() as $error)
													<li>{{ $error }}</li>
												@endforeach
											</ul>
										</div>
									@endif
									@if(Session::has('success_message'))
										<p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('success_message') }}</p>
									@endif
									<div class="form-group">
										<label for="password" class=" col-md-2">Password</label>
										<div class=" col-md-10">
											<input type="password" name="password" value="" id="password" class="form-control" placeholder="Password" autocomplete="off" required="required" data-rule-minlength="6" data-msg-minlength="Please enter at least 6 characters.">
										</div>
									</div>
									<div class="form-group">
										<label for="password_confirmation" class=" col-md-2">Retype password</label>
										<div class=" col-md-10">
											<input type="password" name="password_confirmation" value="" id="password_confirmation" class="form-control" placeholder="Retype password" autocomplete="off" required="required" data-rule-equalto="#password" data-msg-equalto="Please enter the same value again.">
										</div>
									</div>
								</div>
								<div class="panel-footer">
									<button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> Change Password</button>
								</div>
							</div>
						</form>
					</div>
				</div>
				@endif
			</div>
        </div>
      </div>
	</div>
	</div>
</div>
@endsection

@push('scripts')
<script>
$(function () {
	@if($user->id == Auth::user()->id || Entrust::hasRole("SUPER_ADMIN"))
	$('#password-reset-form').validate({
		
	});
	@endif
});
</script>
@endpush
