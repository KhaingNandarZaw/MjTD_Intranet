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
    <div class="box-body">
        
        {!! Form::open(['action' => 'LA\Task_InstancesController@my_tasks', 'method' => 'POST']) !!}
        <div class="form-group col-sm-12">
            <div class="col-sm-3 pull-right" style="margin-top: 25px;">
            <a class="btn btn-success btn-xs" style="display:inline;padding:2px 5px 3px 5px;" data-toggle="modal" data-target="#ReportSOP"><i class="fa fa-check-square"></i> Report for unroutine work</a>
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
         
        <table id="example1" class="table table-bordered">
        <thead>
        <tr>
            <th>No.</th>
            <th>Task Title</th>
            <th>Description</th>
            <th>To Finish Date</th>
            <th>Report To</th>
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
                <td>{{ $task->task_date }}</td>
                <td>{{ $task->reportTo }}</td>
                <td><small class="label  {{ (($task->status=='On Progress') ? 'label-warning' : (($task->status=='Rejected') ? 'label-danger' : (($task->status == 'Approved') ? 'label-success' : (($task->status == 'Done') ? 'label-primary' : 'label-default')))) }}">{{ $task->status }}</small></td>
                @if($show_actions)
                <td>@if($task->status == 'On Progress' || $task->status == 'Rejected')<a class="btn btn-success btn-xs" style="display:inline;padding:2px 5px 3px 5px;" data-toggle="modal" data-target-id="{{ $task->id }}" data-target="#CompleteModal"><i class="fa fa-check-square"></i> Report</a>@endif</td>
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
                    <?php $users = App\User::all(); ?>
                    <div class="form-group">
                        <label for="cc">Cc :</label>
                        <select class="form-control input-sm" multiple data-placeholder="Select Task" rel="select2" name="cc_users[]">
                            @foreach($users as $user)
                                @if(!$user->hasRole("SUPER_ADMIN"))
                                <option value="{{ $user->email }}">{{ $user->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
					<div class="form-group">
						<label for="name">Remark :</label>
						<textarea class="form-control module_label_edit" placeholder="Remark" name="remark"></textarea>
					</div>
					<div class="form-group">
						<div class="input-group">
						    <label>Attachment :</label>
						  <div class="custom-file">
						    <input type="file" multiple class="custom-file-input" id="complete_files" name="complete_files[]" aria-describedby="inputGroupFileAddon01">
						  </div>
						</div>
					</div>
                    <div class="form-group">
                        <label for="will_sent_outsiders">Will sent to Outsiders?<span style="color:red;"> *</span> :</label>
                        <input type="checkbox" id="will_sent_outsiders" name="will_sent_outsiders" value="true"/>
                    </div>
                    <div class="form-group" id="cc_outsiders" style="display:none;">
                        <label>Cc for outsiders( comma(,) separted for each email address) :<span style="color:red;"> *</span> :</label>
                        <input type="text" class="form-control" name="cc_outsiders"/>
                    </div>
                    <div class="form-group" id="subject" style="display:none;">
                        <label>Subject :<span style="color:red;"> *</span> :</label>
                        <input type="text" class="form-control" name="subject"/>
                    </div>
                    <div class="form-group" id="contents" style="display:none;">
                        <textarea id="summernote" name="contents"></textarea>
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

<div class="modal fade in" id="ReportSOP" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				<h4 class="modal-title" id="myModalLabel">Report</h4>
            </div>
            {!! Form::open(['action' => 'LA\Task_InstancesController@report_to_officer', 'files' => true]) !!}
			<div class="modal-body">
                <div class="box-body">
                    <div class="form-group">
                        <label for="task">Task<span style="color: red;"> * </span>:</label>
                        <select class="form-control" required="1" data-placeholder="Select Task" rel="select2" name="sop_id">
                            @foreach($sop_lists as $sop_list)
                                <option value="{{ $sop_list->id }}">{{ $sop_list->work_description }} ~ {{$sop_list->time_frame}}</option>
                            @endforeach
                        </select>
                    </div>
                    <?php $users = App\User::all(); ?>
                    <div class="form-group">
                        <label for="cc">Cc :</label>
                        <select class="form-control input-sm" multiple data-placeholder="Select Task" rel="select2" name="cc_users[]">
                            @foreach($users as $user)
                                @if(!$user->hasRole("SUPER_ADMIN"))
                                <option value="{{ $user->email }}">{{ $user->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
					<div class="form-group">
						<label for="name">Remark :</label>
						<textarea class="form-control module_label_edit" placeholder="Remark" name="remark"></textarea>
					</div>
					<div class="form-group">
						<div class="input-group">
						    <label>Attachment :</label>
						  <div class="custom-file">
						    <input type="file" multiple class="custom-file-input" id="complete_files" name="complete_files[]" aria-describedby="inputGroupFileAddon01">
						  </div>
						</div>
					</div>
                    <div class="form-group">
                        <label for="will_sent_outsiders">Will sent to Outsiders?<span style="color:red;"> *</span> :</label>
                        <input type="checkbox" id="will_sent_outsiders" name="will_sent_outsiders" value="true"/>
                    </div>
                    <div class="form-group" id="cc_outsiders" style="display:none;">
                        <label>Cc for outsiders( comma(,) separted for each email address) :<span style="color:red;"> *</span> :</label>
                        <input type="text" class="form-control" name="cc_outsiders"/>
                    </div>
                    <div class="form-group" id="subject" style="display:none;">
                        <label>Subject :<span style="color:red;"> *</span> :</label>
                        <input type="text" class="form-control" name="subject"/>
                    </div>
                    <div class="form-group" id="contents" style="display:none;">
                        <textarea id="summernote" name="contents"></textarea>
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

@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('la-assets/plugins/datatables/datatables.min.css') }}"/>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.16/dist/summernote.min.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="{{ asset('la-assets/plugins/datatables/datatables.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.16/dist/summernote.min.js"></script>
<script>
$(function () {
    $('#CompleteModal #summernote').summernote('code');
    $('#ReportSOP #summernote').summernote('code');

    $("#example1").DataTable({
        
    });
    $("#task_instance-add-form").validate({
        
    });
    $("#CompleteModal").on("show.bs.modal", function (e) {
        var id = $(e.relatedTarget).data('target-id');
        $('#task_instance_id').val(id);
    });

    $('#CompleteModal #will_sent_outsiders:checkbox').bind('change', function(e) {
        $("#CompleteModal #cc_outsiders").css('display', 'none');
        $("#CompleteModal #subject").css('display', 'none');
        $("#CompleteModal #contents").css('display', 'none');
        if ($(this).is(':checked')) {
            $("#CompleteModal #cc_outsiders").css('display', 'block');
            $("#CompleteModal #subject").css('display', 'block');
            $("#CompleteModal #contents").css('display', 'block');
        }
    });

    $('#ReportSOP #will_sent_outsiders:checkbox').bind('change', function(e) {
        $("#ReportSOP #cc_outsiders").css('display', 'none');
        $("#ReportSOP #subject").css('display', 'none');
        $("#ReportSOP #contents").css('display', 'none');
        if ($(this).is(':checked')) {
            $("#ReportSOP #cc_outsiders").css('display', 'block');
            $("#ReportSOP #subject").css('display', 'block');
            $("#ReportSOP #contents").css('display', 'block');
        }
    });
});
</script>
@endpush
