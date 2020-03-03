@extends("la.layouts.app")

@section("contentheader_title", "Announcements")
@section("contentheader_description", "Announcements listing")
@section("section", "Announcements")
@section("sub_section", "Listing")
@section("htmlheader_title", "Announcements Listing")

@section("headerElems")
@la_access("Announcements", "create")
    <a class="btn btn-primary btn-sm" style="float: right;" href="<?= URL::to('/admin/announcements/create') ?>"><i class="fa fa-plus"> Add New Announcement</i></a>
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
        <table id="example1" class="table table-bordered table-striped">
        <thead>
        <tr>
            @foreach( $listing_cols as $col )
            <th>{{ $module->fields[$col]['label'] or ucfirst($col) }}</th>
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
<script src="{{ asset('la-assets/plugins/iconpicker/fontawesome-iconpicker.js') }}"></script>
<script>
$(function () {
    $("#example1").DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ url(config('laraadmin.adminRoute') . '/announcement_dt_ajax') }}",
        language: {
            lengthMenu: "_MENU_",
            search: "_INPUT_",
            searchPlaceholder: "Search"
        },
        @if($show_actions)
        columnDefs: [ { orderable: false, targets: [-1] }],
        @endif
    });
    $("#announcement-add-form").validate({
        
    });
    $("input[name=icon]").iconpicker();
    
});
</script>
@endpush
