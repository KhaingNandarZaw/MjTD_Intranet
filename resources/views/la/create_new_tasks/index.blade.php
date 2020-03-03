@extends("la.layouts.app")

@section("contentheader_title", "Tasks")
@section("contentheader_description", "Tasks listing")
@section("section", "Tasks")
@section("sub_section", "Listing")
@section("htmlheader_title", "Tasks Listing")

@section("headerElems")
@la_access("Tasks", "create")
    <a class="btn btn-primary btn-sm" style="float: right;" href="<?= URL::to('/admin/create_new_tasks/create') ?>"><i class="fa fa-plus"> Create New Task</i></a>
@endla_access
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
                    <th>Task Name</th>
                    <th>Description</th>
                    <th>Report To</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($requested_tasks as $key=>$requested_task)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td><a href="{{ url(config('laraadmin.adminRoute') . '/create_new_tasks/'.$requested_task->id) }}">{{ $requested_task->name }}</a></td>
                        <td>{{ $requested_task->description }}</td>
                        <?php $user = DB::table('users')->where('id', $requested_task->report_to_userid)->first(); ?>
                        <td>{{ $user->name }}</td>
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
                    <th>Task Name</th>
                    <th>Description</th>
                    <th>Requested By</th>
                    <th>Status</th>
                    <th>Confirmed By</th>
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
                        <td>{{ $confirmed_task->status }}</td>
                        <?php $confirmed_user = DB::table('users')->where('id', $confirmed_task->confirmed_by)->whereNull('deleted_at')->first(); ?>
                        <td>@if(isset($confirmed_user)) {{$confirmed_user->name}} @endif</td>
                        <td>{{ $confirmed_task->confirmed_date }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="tab-pane" id="fa-rejected">
            <table id="rejected_task" class="table table-bordered">
                <thead>
                <tr>
                    <th>No.</th>
                    <th>Task Name</th>
                    <th>Description</th>
                    <th>Requested By</th>
                    <th>Status</th>
                    <th>Rejected By</th>
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
                        <td>{{ $rejected_task->status }}</td>
                        <?php $rejected_user = DB::table('users')->where('id', $rejected_task->rejected_by)->whereNull('deleted_at')->first(); ?>
                        <td>@if(isset($rejected_user)) {{$rejected_user->name}} @endif</td>
                        <td>{{ $rejected_task->rejected_date }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
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
        // ajax: "{{ url(config('laraadmin.adminRoute') . '/create_new_task_dt_ajax') }}",
        language: {
            lengthMenu: "_MENU_",
            search: "_INPUT_",
            searchPlaceholder: "Search"
        }
    });
    $("#confirmed_tasks").DataTable({
        processing: true,
        language: {
            lengthMenu: "_MENU_",
            search: "_INPUT_",
            searchPlaceholder: "Search"
        }
    });
    $("#rejected_task").DataTable({
        processing: true,
        language: {
            lengthMenu: "_MENU_",
            search: "_INPUT_",
            searchPlaceholder: "Search"
        }
    })
});
</script>
@endpush
