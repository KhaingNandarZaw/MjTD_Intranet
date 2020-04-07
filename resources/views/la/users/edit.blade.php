@extends("la.layouts.app")

@section("contentheader_title")
    <a href="{{ url(config('laraadmin.adminRoute') . '/users') }}">User</a> :
@endsection
@section("contentheader_description", $user->$view_col)
@section("section", "Users")
@section("section_url", url(config('laraadmin.adminRoute') . '/users'))
@section("sub_section", "Edit")

@section("htmlheader_title", "Users Edit : ".$user->$view_col)

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
        {!! Form::model($user, ['route' => [config('laraadmin.adminRoute') . '.users.update', $user->id ], 'method'=>'PUT', 'id' => 'user-edit-form']) !!}
                                
            @la_input($module, 'name')
            @la_input($module, 'email')
            @la_input($module, 'designation')
            @la_input($module, 'date_birth')
            @la_input($module, 'gender')
            @la_input($module, 'mobile')
            @la_input($module, 'department')
            @la_input($module, 'address')

            <div class="form-group">
                <label for="role">Role<span style="color: red;"> * </span>:</label>
                <select class="form-control" required="1" data-placeholder="Select Role" rel="select2" name="role">
                <?php $roles = App\Role::all(); ?>
                    @foreach($roles as $role)
                        @if($role->id != 1 || Entrust::hasRole("SUPER_ADMIN"))
                            @if($user->hasRole($role->name))
                                <option value="{{ $role->id }}" selected>{{ $role->name }}</option>
                            @else
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endif
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <div class="col-sm-6" align="right">
                    {!! Form::submit( 'Update', ['class'=>'btn btn-primary btn-sm']) !!}
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
    $("#user-edit-form").validate({
        
    });
});
</script>
@endpush
