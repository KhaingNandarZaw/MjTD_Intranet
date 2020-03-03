@extends("la.layouts.app")

@section("section", "Users")
@section("sub_section", "Listing")
@section("htmlheader_title", "User Listing")

@section("contentheader_title", "Users")
@section("contentheader_description", "Users listing")

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

<div class="box box-green">
    <div class="box-header with-border">
        User List
        @la_access("Users", "create")
            <a class="btn btn-primary btn-sm" style="float: right;" href="<?= URL::to('/admin/users/create') ?>"><i class="fa fa-plus"> Add New User</i></a>
        @endla_access
    </div>    
    <div class="box-body">
        <table id="example1" class="table table-bordered table-striped" data-form="deleteFormusers">
        <thead>
        <tr>
            @foreach( $listing_cols as $col )
            <th>{{ Lang::has('words.'.$col)? Lang::get('words.'.$col) : ucfirst($col) }}
            @endforeach
            @if($show_actions)
            <th>Actions</th>
            @endif
        </tr>
        </thead>
        <tbody>
            
        </tbody>
        </table>
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
    $('table[data-form="deleteFormusers"]').on('click', '.form-delete', function(e){
    e.preventDefault();
    var $form=$(this);
    $('#confirm').modal({ backdrop: 'static', keyboard: false })
        .on('click', '#delete-btn', function(){
            $form.submit();
        });
    });
    $("#example1").DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ url(config('laraadmin.adminRoute') . '/user_dt_ajax') }}",
        language: {
            lengthMenu: "_MENU_",
            search: "_INPUT_",
            searchPlaceholder: "Search"
        },
        @if($show_actions)
        columnDefs: [ { orderable: false, targets: [-1] }],
        @endif
    });
    
});
</script>
@endpush
