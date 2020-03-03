@extends("la.layouts.app")

@section("contentheader_title", "Vendor Registrations")
@section("contentheader_description", "Vendor Registrations listing")
@section("section", "Vendor Registrations")
@section("sub_section", "Listing")
@section("htmlheader_title", "Vendor Registrations Listing")

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
    <div class="box-header with-border">
        <h3 class="box-title">Vendor List</h3>
        @la_access("Vendor_Registrations", "create")
            <a class="btn btn-primary btn-sm" style="float: right;" href="<?= URL::to('/admin/vendor_registrations/create') ?>"><i class="fa fa-plus"> Add New Vendor</i></a>
        @endla_access
    </div>  
    <div class="box-body">
        <table id="example1" class="table table-bordered">
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
<script>
$(function () {
    $("#example1").DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ url(config('laraadmin.adminRoute') . '/vendor_registration_dt_ajax') }}",
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
