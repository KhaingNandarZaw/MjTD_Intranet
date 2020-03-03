@extends("la.layouts.app")

@section("contentheader_title", "Tasks")
@section("contentheader_description", "Tasks listing")
@section("section", "Tasks")
@section("sub_section", "Listing")
@section("htmlheader_title", "Tasks Listing")

@section("headerElems")

@endsection

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

<div class="box box-info">
    <!--<div class="box-header"></div>-->
    <div class="box-body">
        <table id="example1" class="table table-bordered">
        <thead>
        <tr>
            <th>No.</th>
            <th>Task Name</th>
            <th>Main Description</th>
            <th>Task Description</th>
            <th>To Finish Date</th>
            <th>Assigned By</th>
            <th>Status</th>
            @if($show_actions)
            <th>Actions</th>
            @endif
        </tr>
        </thead>
        <tbody>
            @foreach($all_tasks as $key=>$task)
            <tr>
                <td>{{ $key+1 }}</td>
                <td><a href="{{ url(config('laraadmin.adminRoute') . '/task_instances/'.$task->id) }}" style="display:inline;padding:2px 5px 3px 5px;">{{ $task->name }}</a></td>
                <td>{{ $task->MainDescription }}</td>
                <td>{{ $task->TaskDescription }}</td>
                <td>{{ $task->task_date }}</td>
                <td>{{ $task->assignedBy }}</td>
                <td><small class="label  {{ (($task->status=='On Progress') ? 'label-warning' : (($task->status=='Rejected') ? 'label-danger' : (($task->status == 'Approved') ? 'label-success' : (($task->status == 'Done') ? 'label-primary' : '')))) }}">{{ $task->status }}</small></td>
                @if($show_actions)
                <td>@if($task->status == 'On Progress' || $task->status == 'Rejected')<a class="btn btn-success btn-xs" style="display:inline;padding:2px 5px 3px 5px;" data-toggle="modal" data-target-id="{{ $task->id }}" data-target="#CompleteModal"><i class="fa fa-check-square"></i> Report to Manager</a>@endif</td>
                @endif
            </tr>
            @endforeach
        </tbody>
        </table>
    </div>
</div>
<div class="modal fade in" id="CompleteModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				<h4 class="modal-title" id="myModalLabel">Confirmation</h4>
            </div>
            {!! Form::open(['action' => 'LA\Task_InstancesController@sent_to_officer', 'id' => 'task_instance-add-form', 'files' => true]) !!}
			<div class="modal-body">
                <div class="box-body">
                    <input type="hidden" class="form-control input-sm" id="task_instance_id" name="task_instance_id">
					<div class="form-group">
						<div class="input-group">
						    <label>Attachment :</label>
						  <div class="custom-file">
						    <input type="file" multiple class="custom-file-input" id="complete_files" name="complete_files" aria-describedby="inputGroupFileAddon01">
						  </div>
						</div>
					</div>
					<div class="form-group">
						<label for="name">Remark :</label>
						<textarea class="form-control module_label_edit" placeholder="Remark" name="remark"></textarea>
					</div>
				</div>
			</div>
			<div class="modal-footer">
                {!! Form::submit( 'Sent for Approval', ['class'=>'btn btn-sm btn-success']) !!}
				<button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cancel</button>
            </div>
            {!! Form::close() !!}
		</div>
	</div>
</div>
@la_access("Task_Instances", "create")
<div class="modal fade" id="AddModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Add Task Instance</h4>
            </div>
            {!! Form::open(['action' => 'LA\Task_InstancesController@store', 'id' => 'task_instance-add-form']) !!}
            <div class="modal-body">
                <div class="box-body">
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
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                {!! Form::submit( 'Submit', ['class'=>'btn btn-success']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endla_access

@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('la-assets/plugins/datatables/datatables.min.css') }}"/>
@endpush

@push('scripts')
<script src="{{ asset('la-assets/plugins/datatables/datatables.min.js') }}"></script>
<script>
$(function () {
    $("#example1").DataTable({
        
    });
    $("#task_instance-add-form").validate({
        
    });
    $("#CompleteModal").on("show.bs.modal", function (e) {
        var id = $(e.relatedTarget).data('target-id');
        $('#task_instance_id').val(id);
    });
});
</script>
@endpush
