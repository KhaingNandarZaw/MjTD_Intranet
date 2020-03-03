@extends("la.layouts.app")

<?php
use Dwij\Laraadmin\Models\Module;
?>

@section("contentheader_title", "Tasks")
@section("contentheader_description", "listing")
@section("section", "Tasks")
@section("sub_section", "Listing")
@section("htmlheader_title", "Task Listing")

@section("headerElems")
<a class="btn btn-primary btn-sm pull-right" style="float: right;" href="<?= URL::to('/admin/task_management/create_task') ?>"><i class="fa fa-plus"> Create New Task</i></a>
@endsection

@section("main-content")

<div class="box box-info">
	<div class="box-body">
		<div class="row">
			<div class="form-group col-md-2">
				<label for="icon">Time Frame :</label>
				<select class="form-control input-sm" rel="select2" data-placeholder="Select Type">
					<option value="daily" selected>Daily</option>
					<option value="weekly">Weekly</option>
					<option value="monthly">Monthly</option>
					<option value="yearly">Yearly</option>
					<option value="once">Once</option>
				</select>
			</div>
			<div class="form-group col-md-2">
				<label for="icon">Status :</label>
					<select class="form-control" rel="select2" data-placeholder="Select Type">
						<option>On Progress</option>
						<option>Done</option>
						<option>Approved</option>
						<option>Rejected</option>
					</select>
			</div>
			<div class="form-group col-md-2">
				<label>From Date</label>
				<div class='input-group date' id='datetimepicker1'>
					<input type='text' class="form-control" placeholder="Choose Date"/>
					<span class="input-group-addon">
						<span class="glyphicon glyphicon-calendar"></span>
					</span>
				</div>
			</div>
			<div class="form-group col-md-2">
				<label>To Date</label>
				<div class='input-group date' id='datetimepicker1'>
					<input type='text' class="form-control" placeholder="Choose Date"/>
					<span class="input-group-addon">
						<span class="glyphicon glyphicon-calendar"></span>
					</span>
				</div>
			</div>
		</div>
		<table id="dt_modules" class="table table-bordered table-striped">
			<thead>
			<tr>
				<th>ID</th>
				<th class="col-sm-3">Task Name</th>
				<th>Time Frame</th>
				<th>Assigned By</th>
				<th class="col-sm-1">Status</th>
				<th class="col-sm-1">Actions</th>
			</tr>
			</thead>
			<tbody>	
				<tr>
					<td>1</td>
					<td><a href="#">Task 1</a></td>
					<td>Daily</td>
					<td>Manager A</td>
					<td><small class="label label-warning">On Progress</small></td>
					<td><a class="btn btn-success btn-xs" style="display:inline;padding:2px 5px 3px 5px;" data-toggle="modal" data-target="#AddModal"><i class="fa fa-check-square"></i></a></td>
				</tr>
				<tr>
					<td>2</td>
					<td><a href="#">Task 2</a></td>
					<td>Weekly</td>
					<td>Manager B</td>
					<td><small class="label label-danger">Rejected</td>
					<td><a class="btn btn-success btn-xs" style="display:inline;padding:2px 5px 3px 5px;" data-toggle="modal" data-target="#AddModal"><i class="fa fa-check-square"></i></a></td>
				</tr>
				<tr>
					<td>3</td>
					<td><a href="#">Task 3</a></td>
					<td>Monthly</td>
					<td>Manager C</td>
					<td><small class="label label-success">Approved</td>
					<td></td>
				</tr>
				<tr>
					<td>4</td>
					<td><a href="#">Task 4</a></td>
					<td>Monthly</td>
					<td>Manager C</td>
					<td><small class="label label-primary">Done</td>
					<td></td>
				</tr>
				<tr>
					<td>5</td>
					<td><a href="#">Task 5</a></td>
					<td>Yearly</td>
					<td>Manager D</td>
					<td><small class="label label-warning">On Progress</td>
					<td><a class="btn btn-success btn-xs" style="display:inline;padding:2px 5px 3px 5px;" data-toggle="modal" data-target="#AddModal"><i class="fa fa-check-square"></i></a></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>

<!-- Confirmation -->
<div class="modal fade" id="AddModal" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Confirmation</h4>
			</div>
			<div class="modal-body">
				<div class="box-body">
					<div class="form-group">
						<div class="input-group">
						    <label>Attachment :</label>
						  <div class="custom-file">
						    <input type="file" class="custom-file-input" id="inputGroupFile01"
						      aria-describedby="inputGroupFileAddon01">
						  </div>
						</div>
					</div>
					<div class="form-group">
						<label for="name">Remark :</label>
						<textarea class="form-control module_label_edit" placeholder="Remark" name="Remark" value=""></textarea>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-sm btn-success" data-dismiss="modal">Sent for Approval</button>
				<button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cancel</button>
			</div>
		</div>
	</div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('la-assets/plugins/datatables/datatables.min.css') }}"/>
@endpush


@push('scripts')
<script src="{{ asset('la-assets/plugins/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('la-assets/plugins/iconpicker/fontawesome-iconpicker.js') }}"></script>
<script>

$(function () {
	$("#course-edit-form").validate({
		
	});

	$("#dt_modules").DataTable({
		
	});
});
</script>
@endpush