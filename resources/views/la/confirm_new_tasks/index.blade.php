@extends("la.layouts.app")

@section("contentheader_title", "Tasks")
@section("contentheader_description", "Tasks listing")
@section("section", "Tasks")
@section("sub_section", "Listing")
@section("htmlheader_title", "Tasks Listing")

@section("headerElems")

@endsection

@section("main-content")

<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#fa-requested" data-toggle="tab" aria-expanded="false">Requested Task Lists</a></li>
        <li class=""><a href="#fa-confirmed" data-toggle="tab" aria-expanded="false">Confirmed Task Lists</a></li>
        <li class=""><a href="#fa-rejected" data-toggle="tab" aria-expanded="false">Rejected Task Lists</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="fa-requested">
            <table id="example1" class="table table-bordered">
                <thead>
                <tr>
                    <th>No.</th>
                    <th>Task Title</th>
                    <th>Description</th>
                    <th>Requested By</th>
                    <th>PIC</th>
                    <th>Report To</th>
                    @if($show_actions)
                    <th>Actions</th>
                    @endif
                </tr>
                </thead>
                <tbody>
                    @foreach($requested_tasks as $key=>$requested_task)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td><a href="{{ url(config('laraadmin.adminRoute') . '/create_new_tasks/'.$requested_task->id) }}">{{ $requested_task->name }}</a></td>
                        <td>{{ $requested_task->description }}</td>
                        <?php $user = DB::table('users')->where('id', $requested_task->created_by)->first(); ?>
                        <td>@if(isset($user)) {{$user->name}} @endif</td>
                        <?php $user = DB::table('users')->where('id', $requested_task->pic_user_id)->first(); ?>
                        <td>@if(isset($user)) {{$user->name}} @endif</td>
                        <?php $user = DB::table('users')->where('id', $requested_task->report_to_userid)->first(); ?>
                        <td>@if(isset($user)) {{$user->name}} @endif</td>
                        <td>
                            <a class="btn btn-success btn-xs" style="display:inline;padding:2px 5px 3px 5px;" data-toggle="modal" data-target-id="{{ $requested_task->id }}" data-target="#ConfirmModal">Confirm</a>
                            <a class="btn btn-danger btn-xs" style="display:inline;padding:2px 5px 3px 5px;" data-toggle="modal" data-target-id="{{ $requested_task->id }}" data-target="#CancelModal">Reject</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="tab-pane" id="fa-confirmed">
            <table id="confirmed_tasks" class="table table-bordered">
                <thead>
                <tr>
                    <th>No.</th>
                    <th>Task Title</th>
                    <th>Description</th>
                    <th>Requested By</th>
                    <th>PIC</th>
                    <th>Report To</th>
                    <th>Status</th>
                    <th>Confirmed Date</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($confirmed_tasks as $key=>$confirmed_task)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td><a href="{{ url(config('laraadmin.adminRoute') . '/create_new_tasks/'.$confirmed_task->id) }}">{{ $confirmed_task->name }}</a></td>
                        <td>{{ $confirmed_task->description }}</td>
                        <?php $user = DB::table('users')->where('id', $confirmed_task->created_by)->first(); ?>
                        <td>@if(isset($user)) {{$user->name}} @endif</td>
                        <?php $user = DB::table('users')->where('id', $confirmed_task->pic_user_id)->first(); ?>
                        <td>@if(isset($user)) {{$user->name}} @endif</td>
                        <?php $user = DB::table('users')->where('id', $confirmed_task->report_to_userid)->first(); ?>
                        <td>@if(isset($user)) {{$user->name}} @endif</td>
                        <td>{{ $confirmed_task->status }}</td>
                        <td>{{ $confirmed_task->confirmed_date }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="tab-pane" id="fa-rejected">
            <table id="rejected_tasks" class="table table-bordered">
                <thead>
                <tr>
                    <th>No.</th>
                    <th>Task Title</th>
                    <th>Description</th>
                    <th>Requested By</th>
                    <th>PIC</th>
                    <th>Report To</th>
                    <th>Status</th>
                    <th>Rejected Date</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($rejected_tasks as $key=>$rejected_task)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td><a href="{{ url(config('laraadmin.adminRoute') . '/create_new_tasks/'.$rejected_task->id) }}">{{ $rejected_task->name }}</a></td>
                        <td>{{ $rejected_task->description }}</td>
                        <?php $user = DB::table('users')->where('id', $rejected_task->created_by)->first(); ?>
                        <td>@if(isset($user)) {{$user->name}} @endif</td>
                        <?php $user = DB::table('users')->where('id', $rejected_task->pic_user_id)->first(); ?>
                        <td>@if(isset($user)) {{$user->name}} @endif</td>
                        <?php $user = DB::table('users')->where('id', $rejected_task->report_to_userid)->first(); ?>
                        <td>@if(isset($user)) {{$user->name}} @endif</td>
                        <td>{{ $rejected_task->status }}</td>
                        <td>{{ $rejected_task->rejected_date }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
        
<div class="modal fade in" id="ConfirmModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				<h4 class="modal-title" id="myModalLabel">Confirmation</h4>
            </div>
            {!! Form::open(['action' => 'LA\ConfirmNewTaskController@confirm', 'files' => true]) !!}
			<div class="modal-body">
                <div class="box-body">
                    <input type="hidden" class="form-control input-sm" id="task_id" name="task_id">
                    <div class="form-group">
                        <label for="use_task">Will add to SOP Lists?<span style="color:red;"> *</span> :</label>
                        <input type="checkbox" id="use_sop" name="use_sop" value="true"/>
                    </div>
					<div class="form-group">
						<label for="name">Remark :</label>
						<textarea class="form-control module_label_edit" placeholder="Remark" name="remark"></textarea>
					</div>
				</div>
			</div>
			<div class="modal-footer">
                {!! Form::submit( 'Confirm', ['class'=>'btn btn-sm btn-success']) !!}
				<button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cancel</button>
            </div>
            {!! Form::close() !!}
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
            {!! Form::open(['action' => 'LA\ConfirmNewTaskController@reject', 'files' => true]) !!}
			<div class="modal-body">
                <div class="box-body">
                    <input type="hidden" class="form-control input-sm" id="task_id" name="task_id">
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
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('la-assets/plugins/datatables/datatables.min.css') }}"/>
@endpush

@push('scripts')
<script src="{{ asset('la-assets/plugins/datatables/datatables.min.js') }}"></script>
<script>
$(function () {
    $("#example1").DataTable({
        processing: true,
        @if($show_actions)
        columnDefs: [ { orderable: false, targets: [-1] }],
        @endif
    });

    $("#confirmed_tasks").DataTable({
        processing: true,
        @if($show_actions)
        columnDefs: [ { orderable: false, targets: [-1] }],
        @endif
    });

    $("#rejected_tasks").DataTable({
        processing: true,
        @if($show_actions)
        columnDefs: [ { orderable: false, targets: [-1] }],
        @endif
    });
    $("#ConfirmModal").on("show.bs.modal", function(e){
        var modal = $(this);
        var link = $(e.relatedTarget);
        var id = link.data('target-id');
        modal.find('#task_id').val(id);
    });
    $("#CancelModal").on("show.bs.modal", function(e){
        var modal = $(this);
        var link = $(e.relatedTarget);
        var id = link.data('target-id');
        modal.find('#task_id').val(id);
    });
});
</script>
@endpush
