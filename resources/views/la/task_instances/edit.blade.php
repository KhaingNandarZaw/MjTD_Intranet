@extends("la.layouts.app")

@section("contentheader_title")
    <a href="{{ url(config('laraadmin.adminRoute') . '/task_instances') }}">Task Instance</a> :
@endsection
@section("contentheader_description", $task_instance->$view_col)
@section("section", "Task Instances")
@section("section_url", url(config('laraadmin.adminRoute') . '/task_instances'))
@section("sub_section", "Edit")

@section("htmlheader_title", "Task Instances Edit : ".$task_instance->$view_col)

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
                {!! Form::model($task_instance, ['route' => [config('laraadmin.adminRoute') . '.task_instances.update', $task_instance->id ], 'method'=>'PUT', 'id' => 'task_instance-edit-form']) !!}
                    @la_form($module)
                    
                    {{--
                    @la_input($module, 'task_id')
					@la_input($module, 'task_userid')
					@la_input($module, 'task_date')
					@la_input($module, 'status')
					@la_input($module, 'done_date')
					@la_input($module, 'approved_date')
					@la_input($module, 'rejected_date')
                    --}}
                    <br>
                    <div class="form-group">
                        {!! Form::submit( 'Update', ['class'=>'btn btn-success']) !!} <a href="{{ url(config('laraadmin.adminRoute') . '/task_instances') }}" class="btn btn-default pull-right">Cancel</a>
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
    $("#task_instance-edit-form").validate({
        
    });
});
</script>
@endpush
