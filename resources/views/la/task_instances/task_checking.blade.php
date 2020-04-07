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
    <div class="box-body">
        {!! Form::open(['action' => 'LA\Task_InstancesController@task_checking', 'method' => 'POST']) !!}
        <div class="row form-group">
            <div class="col-md-2">
                <label>Name</label>
                <select class="form-control input-sm" data-placeholder="Select User" rel="select2" name="user_id">
                    <option value="0" selected>*</option>
                    @foreach($users as $user)
                        @if($user->id == $pic_userid)
                        <option value="{{ $user->id }}" selected>{{ $user->name }}</option>
                        @else
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label>Status</label>
                <select class="form-control input-sm" data-placeholder="Select Status" rel="select2" id="status" name="status">
                    <option value="0" selected>*</option>
                    @foreach($status_lists as $status)
                    @if($selected_status == $status)
                        <option value="{{$status}}" selected>{{$status}}</option>
                    @else
                        <option value="{{$status}}">{{$status}}</option>
                    @endif
                @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label>Task Type</label>
                <select class="form-control input-sm" data-placeholder="Select Task Type" rel="select2" id="task_type" name="task_type">
                    <option value="0" selected>*</option>
                    @if($task_type == 'SOP')
                        <option value="SOP" selected>SOP</option>
                    @else
                        <option value="SOP">SOP</option>
                    @endif
                    @if($task_type == 'Assigned')
                        <option value="Assigned" selected>Assigned</option>
                    @else
                        <option value="Assigned">Assigned</option>
                    @endif
                </select>
            </div>
            <div class="col-sm-2">
                <label>From Date</label>
                <div class="input-group date"><input class="form-control input-sm" placeholder="Enter From Date" data-rule-minlength="0" id="from_date" name="from_date" type="text" value="{{$from_date}}"><span class="input-group-addon input_dt"><span class="fa fa-calendar"></span></span></div>
            </div>
            <div class="col-sm-2">
                <label>To Date</label>
                <div class="input-group date"><input class="form-control input-sm" placeholder="Enter To Date" data-rule-minlength="0" id="to_date" name="to_date" type="text" value="{{$to_date}}"><span class="input-group-addon input_dt"><span class="fa fa-calendar"></span></span></div>
            </div>
            <div class="col-md-1" style="margin-top:25px;">
                {{ Form::button('<i class="fa fa-search"> Search</i>', ['type' => 'submit', 'class' => 'btn btn-primary btn-sm'] )  }} 
            </div>
        </div>
        {!! Form::close() !!}
        <table id="example1" class="table table-bordered table-striped table-hover">
        <thead>
        <tr>
            <th>No.</th>
            <th class="col-sm-4">Task Title</th>
            <th class="col-sm-1">Task Type</th>
            <th class="col-sm-1">PIC</th>
            @if(!Entrust::hasRole('EMPLOYEE'))
            <th class="col-sm-1">Report To</th>
            @endif
            <th class="col-sm-1">To Finish Date</th>
            <!-- <th class="col-sm-1">Finished Date</th> -->
            <th class="col-sm-1">Approved By</th>
            <th class="col-sm-1">Status</th>
            @if($show_actions)
            <th class="col-sm-2">Actions</th>
            @endif
        </tr>
        </thead>
        <tbody>
            @foreach($all_tasks as $key=>$task)
            <tr>
                <td>{{ $key+1 }}</td>
                <td><a href="{{ url(config('laraadmin.adminRoute') . '/task_instances/'.$task->id) }}" style="display:inline;padding:2px 5px 3px 5px;">{{ $task->name }}</a></td>
                <td><a href="{{ url(config('laraadmin.adminRoute') . '/task_instances/'.$task->id) }}" style="display:inline;padding:2px 5px 3px 5px;">{{$task->task_type == 'Assigned' ? 'Task Assignement' : $task->task_type}}</a></td>
                <td>{{ $task->pic }}</td>
                @if(!Entrust::hasRole('EMPLOYEE'))
                <td>{{ $task->reportTo }}</td>
                @endif
                <td>{{ $task->task_date }}</td>
                <!-- <td>{{ $task->done_date }}</td> -->
                <td>{{ $task->approvedBy }}</td>
                <td><small class="label  {{ (($task->status=='On Progress') ? 'label-warning' : (($task->status=='Rejected') ? 'label-danger' : (($task->status == 'Approved') ? 'label-success' : (($task->status == 'Done') ? 'label-primary' : 'label-default')))) }}">{{ $task->status }}</small></td>
                @if($show_actions)
                <td>
                    @if($task->status == 'On Progress')
                        <a class="btn btn-success btn-xs" style="display:inline;padding:2px 5px 3px 5px;" data-toggle="modal" data-target-id="{{ $task->id }}" data-task-date="{{$task->task_date}}" data-target="#ModifyDueDateModal">Extend Due Date</a>
                        <a class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;" data-toggle="modal" data-target-id="{{ $task->id }}" data-target="#CancelModal">Cancel Assignement</a>
                        @if(Entrust::hasRole("SUPER_ADMIN") || Entrust::hasRole("OFFICER") || Entrust::hasRole("CEO"))
                        <a class="btn btn-primary btn-xs" style="display:inline;padding:2px 5px 3px 5px;" data-toggle="modal" data-target-id="{{ $task->id }}" data-pic="{{$task->pic}}" data-target="#ReAssignModal">Reassign PIC</a>
                        @endif
                    @endif
                    @if(Entrust::hasRole("SUPER_ADMIN") && $task->status == 'Done')
                    <a class="btn btn-success btn-xs" style="display:inline;padding:2px 5px 3px 5px;" data-toggle="modal" data-target-id="{{ $task->id }}" data-target="#ApproveModal">Approve</a>
                        <a class="btn btn-danger btn-xs" style="display:inline;padding:2px 5px 3px 5px;" data-toggle="modal" data-target-id="{{ $task->id }}" data-target="#RejectModal">Reject</a>
                    @endif
                    @if((Entrust::hasRole("OFFICER") || Entrust::hasRole("CEO") || Entrust::hasRole("EMPLOYEE")) &&$task->status == 'Done' && $task->report_to_userid == Auth::user()->id)
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

<div class="modal fade in" id="CancelModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				<h4 class="modal-title" id="myModalLabel">Confirmation</h4>
            </div>
            {!! Form::open(['action' => 'LA\Task_InstancesController@cancel_task', 'id' => 'task_instance-cancel-form', 'files' => true]) !!}
			<div class="modal-body">
                <div class="box-body">
                    <input type="hidden" class="form-control input-sm" id="task_instance_id" name="task_instance_id">
                    <p>Do you really want to remove this task? This action cannot be undone.</p>
                    <div class="form-group">
						<label for="name">Remark :</label>
						<textarea class="form-control module_label_edit" required placeholder="Remark" name="remark"></textarea>
					</div>
				</div>
			</div>
			<div class="modal-footer">
                {!! Form::submit( 'Remove', ['class'=>'btn btn-sm btn-warning']) !!}
				<button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cancel</button>
            </div>
            {!! Form::close() !!}
		</div>
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
            {!! Form::open(['action' => 'LA\Task_InstancesController@rejected_by_officer', 'id' => 'task_instance-reject-form', 'files' => true]) !!}
			<div class="modal-body">
                <div class="box-body">
                    <input type="hidden" class="form-control input-sm" id="task_instance_id" name="task_instance_id">
                    <div class="form-group">
						<div class="input-group">
						    <label>Attachment :</label>
						  <div class="custom-file">
						    <input type="file" multiple class="custom-file-input" id="complete_files" name="complete_files[]" aria-describedby="inputGroupFileAddon01">
						  </div>
						</div>
					</div>
					<div class="form-group">
						<label for="name">Remark <span style="color:red;">*</span> :</label>
						<textarea class="form-control module_label_edit" placeholder="Remark" required name="remark"></textarea>
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

<div class="modal fade" id="ReAssignModal" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Reassign PIC</h4>
            </div>
            {!! Form::open(['action' => 'LA\Task_InstancesController@reassign_pic', 'id' => 'task_instance-reassign-form', 'files' => true]) !!}
			<div class="modal-body">
				<div class="box-body">
                <input type="hidden" class="form-control input-sm" id="task_instance_id" name="task_instance_id">
					<div class="form-group">
						<label>Current PIC</label>
						<input type="text" id="current_pic" name="current_pic" readonly class="form-control input-sm">
					</div>
					<div class="form-group">
						<label>New PIC <span style="color:red;">*</span></label>
                        <select class="form-control input-sm" required data-placeholder="Select PIC" rel="select2" id="new_pic" name="new_pic">
                            <option selected disabled>Choose NEW PIC</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select> 
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
<script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script>
$(function () {
    $("#example1").DataTable({
        'dom' : 'Bfrtip', 
        buttons: [   
          {
           extend:   'excel',
           title: "Tasks",
           filename: 'Tasks',
           exportOptions: {
                columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8 ]
            }
          }                    
        ]
    });

    $(".dt-buttons").show(); 
    document.getElementsByClassName('dt-button')[0].children[0].innerHTML = "<i class='fa fa-download'> Export Excel</i>";
    document.getElementsByClassName('dt-button')[0].className += " btn btn-success btn-sm pull-right";

    $("#task_instance-add-form").validate({
        
    });
    $("#task_instance-cancel-form").validate({

    });
    $("#task_instance-reject-form").validate({

});
    $("#task_instance-reassign-form").validate({

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
    $("#CancelModal").on("show.bs.modal", function(e){
        var modal = $(this);
        var link = $(e.relatedTarget);
        var id = link.data('target-id');
        modal.find('#task_instance_id').val(id);
    });
    $("#ReAssignModal").on("show.bs.modal", function(e){
        var modal = $(this);
        var link = $(e.relatedTarget);
        var id = link.data('target-id');
        var current_pic = link.data('pic');
        modal.find('#task_instance_id').val(id);
        modal.find("#current_pic").val(current_pic);
    });
});
</script>
@endpush
