@extends("la.layouts.app")

@section("contentheader_title")
    <a href="{{ url(config('laraadmin.adminRoute') . '/create_new_tasks') }}">Create New Task</a> :
@endsection
@section("contentheader_description", $create_new_task->$view_col)
@section("section", "Create New Tasks")
@section("section_url", url(config('laraadmin.adminRoute') . '/create_new_tasks'))
@section("sub_section", "Edit")

@section("htmlheader_title", "Create New Tasks Edit : ".$create_new_task->$view_col)

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
                {!! Form::model($create_new_task, ['route' => [config('laraadmin.adminRoute') . '.create_new_tasks.update', $create_new_task->id ], 'method'=>'PUT', 'id' => 'create_new_task-edit-form']) !!}
                    @la_form($module)
                    
                    {{--
                    @la_input($module, 'name')
					@la_input($module, 'description')
					@la_input($module, 'priority')
					@la_input($module, 'time_frame')
					@la_input($module, 'due_date')
					@la_input($module, 'dayofweek')
					@la_input($module, 'monthly_type')
					@la_input($module, 'day')
					@la_input($module, 'week')
					@la_input($module, 'start_date')
					@la_input($module, 'every_interval')
					@la_input($module, 'termination_date')
					@la_input($module, 'created_by')
					@la_input($module, 'report_to_userid')
					@la_input($module, 'status')
					@la_input($module, 'confirmed_by')
					@la_input($module, 'confirmed_date')
                    --}}
                    <br>
                    <div class="form-group">
                        {!! Form::submit( 'Update', ['class'=>'btn btn-success']) !!} <a href="{{ url(config('laraadmin.adminRoute') . '/create_new_tasks') }}" class="btn btn-default pull-right">Cancel</a>
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
    $("#create_new_task-edit-form").validate({
        
    });
});
</script>
@endpush
