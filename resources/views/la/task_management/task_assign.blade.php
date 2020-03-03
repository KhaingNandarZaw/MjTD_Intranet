@extends("la.layouts.app")

<?php
use Dwij\Laraadmin\Models\Module;
?>

@section("contentheader_title", "Task Assignment")
@section("contentheader_description", "Task Assign listing")
@section("section", "Task Assign")
@section("sub_section", "Task Assign listing")
@section("htmlheader_title", "Task Assign listing")

@section("headerElems")
<a class="btn btn-primary btn-sm pull-right" style="float: right;" href="<?= URL::to('/admin/task_management/create_task') ?>"><i class="fa fa-plus">Assign Task</i></a>
@endsection

@section("main-content")

<div class="box box-danger">
	<!--<div class="box-header"></div>-->
	<div class="box-body">
		<table id="dt_modules" class="table table-bordered table-striped">
		<thead>
		<tr>
			<th>ID</th>
			<th>Name</th>
			<th>Time Frame</th>
			<th>PIC</th>
			<th>Actions</th>
		</tr>
		</thead>
		<tbody>	
			<tr>
				<td>1</td>
				<td>Daily Report</td>
				<td>Daily</td>
				<td>Mg Mg</td>
				<td>
					<a class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;" href="<?= URL::to('/admin/task_management/edit_task') ?>"><i class="fa fa-edit"></i></a>
					<a class="btn btn-danger btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-trash"></i></a>
				</td>
			</tr>
			<tr>
				<td>2</td>
				<td>Weekly Report</td>
				<td>Weekly</td>
				<td>Mg Mg</td>
				<td>
					<a class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;" href="<?= URL::to('/admin/task_management/edit_task') ?>"><i class="fa fa-edit"></i></a>
					<a class="btn btn-danger btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-trash"></i></a>
				</td>
			</tr>
			<tr>
				<td>3</td>
				<td>Check Assets</td>
				<td>Once</td>
				<td>Khine Zin</td>
				<td>
					<a class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;" href="<?= URL::to('/admin/task_management/edit_task') ?>"><i class="fa fa-edit"></i></a>
					<a class="btn btn-danger btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-trash"></i></a>
				</td>
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
						<label for="icon">Type :</label>
						<div class="input-group col-sm-12">
							<select class="form-control" rel="select2" data-placeholder="Select Type">
								<option>Workflow</option>
								<option>Other</option>
							</select>
						</div>
					</div>
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
						<input class="form-control module_label_edit" placeholder="Remark" name="Remark" value=""/>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success" data-dismiss="modal">Confirm</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
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