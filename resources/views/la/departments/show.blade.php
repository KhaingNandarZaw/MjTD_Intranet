@extends("la.layouts.app")

<?php
use Dwij\Laraadmin\Models\Module;
?>

@section("contentheader_title", "Department Structure")
@section("contentheader_description", "")
@section("section", "Department Structure")
@section("sub_section", "")
@section("htmlheader_title", "Department Structure")

@section("headerElems")
@la_access("Departments", "create")
	<button class="btn btn-success btn-sm pull-right" data-toggle="modal" data-target="#AddModal"><span class="fa fa-plus"></span> New Department</button>
@endla_access
@endsection

@section("main-content")

<div class="box box-primary menus">
	<div class="box-body">
		<div class="row">
			<div class="col-md-4 col-lg-4">
				<div class="dd" id="menu-nestable">
					<ol class="dd-list">
						@foreach ($menus as $menu)
							<?php echo LAHelper::print_department_editor($menu); ?>
						@endforeach
					</ol>
				</div>
			</div>
			<div class="col-md-8 col-lg-8" id="member_lists">
				<div class="box box-success">
					<div class="box-header">
						<h3 class="box-title">Member Lists</h3>
						<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
						</button>
						<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
						</button>
						</div>
					</div>
					<div class="box-body">
						<table id="members" class="table table-bordered table-striped" style="font-size:small;">
							<thead>
								<tr>
									<th>ID</th>
									<th>Name</th>
									<th>Email</th>
									<th>Designation</th>
								</tr>
							</thead>
							<tdody>

							</tdody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@la_access("Departments", "create")
<div class="modal fade" id="AddModal" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Add Department</h4>
			</div>
			{!! Form::open(['action' => 'LA\DepartmentsController@store', 'id' => 'department-add-form']) !!}
			<div class="modal-body">
				<div class="box-body">
					@la_input($module, 'name')
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

<div class="modal fade" id="EditModal" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Edit Department</h4>
			</div>
			{!! Form::open(['action' => ['LA\DepartmentsController@update', 1], 'id' => 'department-edit-form']) !!}
			<input name="_method" type="hidden" value="PUT">
			<div class="modal-body">
				<div class="box-body">
                    <input type="hidden" name="type" value="custom">
					<div class="form-group">
						<label for="name" style="font-weight:normal;">Name <span style="color:red;">*</span> </label>
						<input class="form-control" placeholder="Label" name="name" type="text" value=""  data-rule-minlength="1" required>
					</div>
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
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('la-assets/plugins/datatables/datatables.min.css') }}"/>
@endpush

@push('scripts')
<script src="{{ asset('la-assets/plugins/nestable/jquery.nestable.js') }}"></script>
<script src="{{ asset('la-assets/plugins/iconpicker/fontawesome-iconpicker.js') }}"></script>
<script src="{{ asset('la-assets/plugins/datatables/datatables.min.js') }}"></script>
<script>
$(function () {
	$("#member_lists").hide();

	$('input[name=icon]').iconpicker();

	$('#menu-nestable').nestable({
        group: 1
    });
	$('#menu-nestable').on('change', function() {
		var jsonData = $('#menu-nestable').nestable('serialize');
		// console.log(jsonData);
		$.ajax({
			url: "{{ url(config('laraadmin.adminRoute') . '/departments/update_hierarchy') }}",
			method: 'POST',
			data: {
				jsonData: jsonData,
				"_token": '{{ csrf_token() }}'
			},
			success: function( data ) {
				// console.log(data);
			}
		});
	});
	$("#menu-custom-form").validate({
		
	});

	$("#department-add-form").validate({

	});

	$("#menu-nestable .editMenuBtn").on("click", function() {
		var info = JSON.parse($(this).attr("info"));
		
		var url = $("#department-edit-form").attr("action");
		index = url.lastIndexOf("/");
		url2 = url.substring(0, index+1)+info.id;
		// console.log(url2);
		$("#department-edit-form").attr("action", url2)
		$("#EditModal input[name=name]").val(info.name);
		$("#EditModal").modal("show");
	});

	$("#menu-nestable .membersBtn").on("click", function() {
		var dept_id = JSON.parse($(this).attr("info"));
		$("#member_lists").show();
		$("#members").DataTable({
			processing: true,
			serverSide: true,
			destroy: true,
			ajax: {            
				"url": "{{ url(config('laraadmin.adminRoute') . '/departments/department_users') }}",
				"type": "POST", 
				data: { '_token': '{{ csrf_token() }}',"dept_id" : dept_id},
			},        
			language: {
				lengthMenu: "_MENU_",
				search: "_INPUT_",
				searchPlaceholder: "Search"
			}
		});
	});

	$("#mdepartment-edit-form").validate({
		
	});
	
});
</script>
@endpush