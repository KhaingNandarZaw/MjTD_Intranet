@extends('la.layouts.app')

@section('htmlheader_title')
    Task Instance View
@endsection


@section('main-content')
<div id="page-content" class="profile2">
    
    <ul data-toggle="ajax-tab" class="nav nav-tabs profile" role="tablist">
        <li class=""><a href="javascript:history.back()" data-toggle="tooltip" data-placement="right" title="Back to Task Instances"><i class="fa fa-chevron-left"></i></a></li>
        <li class="active"><a role="tab" data-toggle="tab" class="active" href="#tab-general-info" data-target="#tab-info"><i class="fa fa-bars"></i> General Info</a></li>
    </ul>

    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active fade in" id="tab-info">
            <div class="tab-content">
                <div class="panel infolist">
                    <div class="panel-default panel-heading">
                        <h4>
                            General Info
                            @if(Entrust::hasRole("SUPER_ADMIN") || Entrust::hasRole("OFFICER") || Entrust::hasRole("CEO") || Entrust::hasRole("DGM") || $task_instance->report_to_userid == Auth::user()->id)
                            <span class="pull-right">
                            @if($task_instance->status == 'On Progress')
                                <a class="btn btn-success btn-xs" style="display:inline;padding:2px 5px 3px 5px;" data-toggle="modal" data-target-id="{{ $task_instance->id }}" data-task-date="{{$task_instance->task_date}}" data-target="#ModifyDueDateModal">Extend Due Date</a>
                                <a class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;" data-toggle="modal" data-target-id="{{ $task_instance->id }}" data-target="#CancelModal">Cancel Assignement</a>
                                @if(Entrust::hasRole("SUPER_ADMIN") || Entrust::hasRole("OFFICER") || Entrust::hasRole("CEO"))
                                <a class="btn btn-primary btn-xs" style="display:inline;padding:2px 5px 3px 5px;" data-toggle="modal" data-target-id="{{ $task_instance->id }}" data-pic="{{$task_instance->pic}}" data-target="#ReAssignModal">Reassign PIC</a>
                                @endif
                            @endif
                            @if($task_instance->status == 'Done' && $task_instance->report_to_userid == Auth::user()->id)
                                <a class="btn btn-success btn-xs" style="display:inline;padding:2px 5px 3px 5px;" data-toggle="modal" data-target-id="{{ $task_instance->id }}" data-target="#ApproveModal">Approve</a>
                                <a class="btn btn-danger btn-xs" style="display:inline;padding:2px 5px 3px 5px;" data-toggle="modal" data-target-id="{{ $task_instance->id }}" data-target="#RejectModal">Reject</a>
                            @endif
                            </span>
                            @endif
                        </h4>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="col-md-4 col-sm-6 col-xs-6">Title :</label><div class="col-md-8 col-sm-6 col-xs-6">{{$task_instance->name}}</div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 col-sm-6 col-xs-6">Description :</label><div class="col-md-8 col-sm-6 col-xs-6">{{$task_instance->MainDescription}}</div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 col-sm-6 col-xs-6">PIC :</label><div class="col-md-8 col-sm-6 col-xs-6">{{$task_instance->pic}}</div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 col-sm-6 col-xs-6">Report To :</label><div class="col-md-8 col-sm-6 col-xs-6">{{$task_instance->reportTo}}</div>
                        </div>
                        @if($task_instance->task_type == 'Assigned')
                        <div class="form-group">
                            <label class="col-md-4 col-sm-6 col-xs-6">Assigned By :</label><div class="col-md-8 col-sm-6 col-xs-6">{{$task_instance->assignedBy}}</div>
                        </div>
                        @endif
                        <div class="form-group">
                            <label class="col-md-4 col-sm-6 col-xs-6">Task Type :</label><div class="col-md-8 col-sm-6 col-xs-6">{{$task_instance->task_type == 'Assigned' ? 'Task Assignement' : $task_instance->task_type}}</div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 col-sm-6 col-xs-6">Time Frame :</label><div class="col-md-8 col-sm-6 col-xs-6">{{$task_instance->time_frame}}</div>
                        </div>
                        <div class="form-group">
                            <?php
                                $dt = strtotime($task_instance->task_date);
                                $value = date("d M Y", $dt);
                            ?>
                            <label class="col-md-4 col-sm-6 col-xs-6">To Finish Date :</label><div class="col-md-8 col-sm-6 col-xs-6">{{$value}}</div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 col-sm-6 col-xs-6">Remark :</label><div class="col-md-8 col-sm-6 col-xs-6">{{$task_instance->remark}}</div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 col-sm-6 col-xs-6">Attachments :</label>
                            <div class="col-md-8 col-sm-6 col-xs-6 fvalue">
                                <?php
                                $value = $task_instance->attachments;
                                if($value != "" && $value != "[]" && $value != "null" && starts_with($value, "[")) {
                                    $uploads = json_decode($value);
                                    $uploads_html = "";
                                    
                                    foreach($uploads as $uploadId) {
                                        $upload = DB::table('task_attachments')->whereNull('deleted_at')->where('id', $uploadId)->first();;
                                        if(isset($upload->id)) {
                                            $uploadIds[] = $upload->id;
                                            $fileImage = "";
                                            
                                                $fileImage = "<i class='fa fa-file-o'></i> " . $upload->filename;
                                            
                                            $uploads_html .= '<a class="preview" target="_blank" href="' . url("task_attachments/" . $upload->hash . DIRECTORY_SEPARATOR . $upload->filename) . '" data-toggle="tooltip" data-placement="top" data-container="body" style="display:inline-block;margin-right:5px;" title="' . $upload->filename . '">
                                                    ' . $fileImage . '</a><br/>';
                                        }
                                    }
                                    $value = $uploads_html;
                                } else {
                                    $value = 'No files found.';
                                }
                                echo $value;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if(count($task_remarks) > 0)
            <div class="tab-content">
                <div class="panel infolist">
                    <div class="panel-default panel-heading">
                        <h4>Approval History</h4>
                    </div>
                    <div class="panel-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Status</th>
                                    <th>Effected Date</th>
                                    <th>Remarks</th>
                                    <th>Cc</th>
                                    <th>Attached Files</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($task_remarks as $key=>$task_remark)
                                <tr>
                                    <td>{{ $task_remark->name }}</td>
                                    <td>
                                        <span class="label  {{ (($task_remark->status=='On Progress') ? 'label-warning' : (($task_remark->status=='Rejected') ? 'label-danger' : (($task_remark->status == 'Approved') ? 'label-success' : (($task_remark->status == 'Done') ? 'label-primary' : 'label-default')))) }}">{{$task_remark->status}}</span>
                                    </td>
                                    <?php 
                                        $dt = strtotime($task_remark->created_at);
                                        $value = date("d M Y, h:i A", $dt);
                                    ?>
                                    <td>{{ $value }}</td>
                                    <td>{{ $task_remark->remark }}</td>
                                    <td>{{ $task_remark->cc_users }}</td>
                                    <td>
                                        <ol>
                                            <?php $task_files = DB::table('task_files')->select('id', 'filename', 'extension', 'hash')->where("task_remark_id", $task_remark->id)->where('task_instance_id', $task_instance->id)->get(); ?>
                                            @foreach($task_files as $task_file)
                                             <li><a target='_blank' href="{{ url('attached_files/'. $task_file->hash .'/'. $task_file->filename) }}" >{{$task_file->filename}}</a></li><!-- href= '". bsurl . /attached_files/'. $task_file->hash . / . $task_file->filename . "' -->
                                            @endforeach
                                        </ol>
                                        @if(count($task_files) == 0)
                                        <span>No attachments found.</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
        
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

@push('scripts')
<script>
$(function () {
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