@extends("la.layouts.app")

@section("contentheader_title", "Task Checking")
@section("contentheader_description", "Task listing")
@section("section", "Task Checking")
@section("sub_section", "Listing")
@section("htmlheader_title", "Task Listing")

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
            <th>Finished Date</th>
            <th>Done By</th>
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
                <td>{{ $task->done_date }}</td>
                <td>{{ $task->pic }}</td>
                <td><small class="label  {{ (($task->status=='On Progress') ? 'label-warning' : (($task->status=='Rejected') ? 'label-danger' : (($task->status == 'Approved') ? 'label-success' : (($task->status == 'Done') ? 'label-primary' : '')))) }}">{{ $task->status }}</small></td>
                @if($show_actions)
                <td>
                    @if($task->status == 'On Progress')
                        <a class="btn btn-success btn-xs" style="display:inline;padding:2px 5px 3px 5px;" data-toggle="modal" data-target-id="{{ $task->id }}" data-task-date="{{$task->task_date}}" data-target="#ModifyDueDateModal">Extend Due Date</a>
                    @endif
                    @if($task->status == 'Done')
                        <a class="btn btn-success btn-xs" style="display:inline;padding:2px 5px 3px 5px;" data-toggle="modal" data-target-id="{{ $task->id }}" data-target="#ApproveModal">Approve</a>
                        <a class="btn btn-danger btn-xs" style="display:inline;padding:2px 5px 3px 5px;" data-toggle="modal" data-target-id="{{ $task->id }}" data-target="#RejectModal">Reject</a>
                    @endif
                </td>
                @endif
            </tr>
            @endforeach
        </tbody>
        </table>
    </div>
</div>

<div class="modal fade in" id="ApproveModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				<h4 class="modal-title" id="myModalLabel">Confirmation</h4>
            </div>
            {!! Form::open(['action' => 'LA\Task_InstancesController@approved_by_officer', 'id' => 'task_instance-add-form', 'files' => true]) !!}
			<div class="modal-body">
                <div class="box-body">
                    <input type="hidden" class="form-control input-sm" id="task_instance_id" name="task_instance_id">
					<div class="form-group">
						<label for="name">Remark :</label>
						<textarea class="form-control module_label_edit" placeholder="Remark" name="remark"></textarea>
					</div>
				</div>
			</div>
			<div class="modal-footer">
                {!! Form::submit( 'Approve', ['class'=>'btn btn-sm btn-success']) !!}
				<button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cancel</button>
            </div>
            {!! Form::close() !!}
		</div>
	</div>
</div>

<div class="modal fade in" id="RejectModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				<h4 class="modal-title" id="myModalLabel">Confirmation</h4>
            </div>
            {!! Form::open(['action' => 'LA\Task_InstancesController@rejected_by_officer', 'files' => true]) !!}
			<div class="modal-body">
                <div class="box-body">
                    <input type="hidden" class="form-control input-sm" id="task_instance_id" name="task_instance_id">
					<div class="form-group">
						<label for="name">Remark :</label>
						<textarea class="form-control module_label_edit" placeholder="Remark" name="remark"></textarea>
					</div>
				</div>
			</div>
			<div class="modal-footer">
                {!! Form::submit( 'Reject', ['class'=>'btn btn-sm btn-danger']) !!}
				<button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cancel</button>
            </div>
            {!! Form::close() !!}
		</div>
	</div>
</div>

<div class="modal fade" id="ModifyDueDateModal" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Confirmation</h4>
            </div>
            {!! Form::open(['action' => 'LA\Task_InstancesController@extend_duedate', 'files' => true]) !!}
			<div class="modal-body">
				<div class="box-body">
                <input type="hidden" class="form-control input-sm" id="task_instance_id" name="task_instance_id">
					<div class="form-group">
						<label>Current Due Date</label>
						<div class="input-group date"><input class="form-control" readonly data-rule-minlength="0" id="task_date" name="task_date" type="text"><span class="input-group-addon input_dt"><span class="fa fa-calendar"></span></span></div>
					</div>
					<div class="form-group">
						<label>Extend Due Date</label>
						<div class="input-group date"><input class="form-control" placeholder="Enter Extend Due Date" data-rule-minlength="0" id="extend_date" name="extend_date" type="text"><span class="input-group-addon input_dt"><span class="fa fa-calendar"></span></span></div>
					</div>
					<div class="form-group">
						<label for="name">Remark :</label>
						<textarea class="form-control module_label_edit" placeholder="Remark" name="remark" value=""></textarea>
					</div>
				</div>
			</div>
			<div class="modal-footer">
                {!! Form::submit( 'Modify', ['class'=>'btn btn-sm btn-success']) !!}
				<button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cancel</button>
            </div>
            {!! Form::close() !!}
		</div>
	</div>
</div>

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
    $("#ModifyDueDateModal").on("show.bs.modal", function (e) {
        var modal = $(this);
        var link = $(e.relatedTarget);
        var id = link.data('target-id');
        var task_date = link.data('task-date');
        modal.find('#task_instance_id').val(id);
        modal.find("#task_date").val(task_date);
    });
    $("#ApproveModal").on("show.bs.modal", function(e){
        var modal = $(this);
        var link = $(e.relatedTarget);
        var id = link.data('target-id');
        modal.find('#task_instance_id').val(id);
    });
    $("#RejectModal").on("show.bs.modal", function(e){
        var modal = $(this);
        var link = $(e.relatedTarget);
        var id = link.data('target-id');
        modal.find('#task_instance_id').val(id);
    });
});
</script>
@endpush
