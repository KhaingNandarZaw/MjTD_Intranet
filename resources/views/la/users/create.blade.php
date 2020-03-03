@extends("la.layouts.app")

@section("section", "Users")
@section("section_url", url(config('laraadmin.adminRoute') . '/users'))
@section("sub_section", "Create") 

@section("contentheader_title", "Users")
@section("contentheader_description", "Create New User")

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
        User Entry
    </div>    
    <div class="box-body">
    	{!! Form::open(['action' => 'LA\UsersController@store', 'id' => 'user-add-form']) !!}
            <div class="box-body">
                        @la_input($module, 'name')
                        @la_input($module, 'email')
                        @la_input($module, 'designation')
                        @la_input($module, 'department')
                        @la_input($module, 'gender')
                        @la_input($module, 'date_birth')
                        @la_input($module, 'mobile')
                        @la_input($module, 'address')
                <div class="form-group">
                    <label for="role">Role <span style="color: red;">* </span>:</label>
                    <select class="form-control" required="1" data-placeholder="Select Role" rel="select2" name="role">
                        <?php $roles = App\Role::all(); ?>
                        @foreach($roles as $role)
                            @if($role->id != 1)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>  
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <div class="col-sm-6" align="right">
                    {!! Form::submit( 'Save', ['class'=>'btn btn-primary btn-sm']) !!}
                </div>
                <div class="col-sm-6" align="left">
                    <a href="{{ url(config('laraadmin.adminRoute') . '/users') }}" class="btn btn-default btn-sm">Cancel</a>
                </div>
            </div>
            {!! Form::close() !!}
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function () {
    $("#user-add-form").validate({
        
    });

});
</script>
@endpush
