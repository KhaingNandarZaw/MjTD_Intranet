@extends("la.layouts.app")

@section("contentheader_title", "System Permissions")
@section("contentheader_description", "System Permissions listing")
@section("section", "System Permissions")
@section("sub_section", "Listing")
@section("htmlheader_title", "System Permissions Listing")

@section("headerElems")
@la_access("System_Permissions", "create")
    <button class="btn btn-success btn-sm pull-right" data-toggle="modal" data-target="#AddModal">Add System Permission</button>
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

<div class="box box-info">
    <!--<div class="box-header"></div>-->
    <div class="box-body">
    {!! Form::open(['action' => 'LA\System_PermissionsController@store']) !!}
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <table class="table table-bordered dataTable no-footer table-access">
                <thead>
                    <tr class="blockHeader">
                        <th width="30%">
                            Users
                        </th>
                        <th width="20%">
                            HR System
                        </th>
                        <th width="20%">
                            Utility Managment System
                        </th>
                        <th width="20%">
                            Resource Booking System
                        </th>
                    </tr>
                </thead>
                @foreach($users_permissions as $user)
                    <tr>
                        <td><input user_id="{{ $user->id }}" class="module_checkb" type="checkbox" name="user_{{$user->id}}" id="user_{{$user->id}}" style="display:none;" checked="checked">&nbsp; {{ $user->name }}</td>
                        <td><input user_id="{{ $user->id }}" class="view_checkb" type="checkbox" name="user_hr{{$user->id}}" id="user_hr{{$user->id}}" <?php if($user->permissions->hr == 1) { echo 'checked="checked"'; } ?> ></td>
                        <td><input user_id="{{ $user->id }}" class="create_checkb" type="checkbox" name="user_ums{{$user->id}}" id="user_ums{{$user->id}}" <?php if($user->permissions->ums == 1) { echo 'checked="checked"'; } ?>></td>
                        <td><input user_id="{{ $user->id }}" class="edit_checkb" type="checkbox" name="user_rbs{{$user->id}}" id="user_rbs{{$user->id}}"  <?php if($user->permissions->rbs == 1) { echo 'checked="checked"'; } ?>></td>
                    </tr>
                @endforeach
            </table>
            <center><input class="btn btn-success" type="submit" name="Save"></center>
        </form>
    </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('la-assets/plugins/datatables/datatables.min.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('la-assets/plugins/bootstrap-slider/slider.css') }}"/>
<style>

.table-access{border:1px solid #CCC;}
.table-access thead tr{background-color: #DDD;}
.table-access thead tr th{border-bottom:1px solid #CCC;padding:10px 10px;text-align:center;}
.table-access thead tr th:first-child{text-align:left;}
.table-access input[type="checkbox"]{margin-right:5px;vertical-align:text-top;}
.table-access > tbody > tr > td{border-bottom:1px solid #EEE !important;padding:10px 10px;text-align:center;}
.table-access > tbody > tr > td:first-child {text-align:left;}

</style>
@endpush

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('la-assets/plugins/datatables/datatables.min.css') }}"/>
@endpush

@push('scripts')
<script src="{{ asset('la-assets/plugins/datatables/datatables.min.js') }}"></script>
<script>
$(function () {
   
});
</script>
@endpush
