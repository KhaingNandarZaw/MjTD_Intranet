@extends("la.layouts.app")

@section("contentheader_title")
    <a href="{{ url(config('laraadmin.adminRoute') . '/frames') }}">Frame</a> :
@endsection
@section("contentheader_description", $frame->$view_col)
@section("section", "Frames")
@section("section_url", url(config('laraadmin.adminRoute') . '/frames'))
@section("sub_section", "Edit")

@section("htmlheader_title", "Frames Edit : ".$frame->$view_col)

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

<div class="box">
    <div class="box-header">
        
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                {!! Form::model($frame, ['route' => [config('laraadmin.adminRoute') . '.frames.update', $frame->id ], 'method'=>'PUT', 'id' => 'frame-edit-form']) !!}
                    @la_form($module)
                    
                    {{--
                    @la_input($module, 'name')
                    --}}
                    <br>
                    <div class="form-group">
                        {!! Form::submit( 'Update', ['class'=>'btn btn-success']) !!} <a href="{{ url(config('laraadmin.adminRoute') . '/frames') }}" class="btn btn-default pull-right">Cancel</a>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(function () {
    $("#frame-edit-form").validate({
        
    });
});
</script>
@endpush
