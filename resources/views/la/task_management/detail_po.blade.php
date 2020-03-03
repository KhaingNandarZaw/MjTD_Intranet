@extends("la.layouts.app")

@section("contentheader_title", "Reports")
@section("contentheader_description", "Detail PO Report")

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

 
        <div class="box box-warning">
            <div class="box-header with-border"><h3 class="box-title">Detail PO Report</h3></div>
            <div class="box-body">
            <div class="row">
                
                <div class="col-md-2">
                <label>Vendor Name</label>
                <select class="form-control selectpicker input-sm" id="facility_type_id" name="facility_type_id" data-live-search="true">  
                        <option value="*" data-tokens="*">*</option>
                        <option value="1" data-tokens="power">ABC Co.,Ltd</option>
                        <option value="2" data-tokens="water">ACE Data Systems</option>
                </select>
                </div>

                <div class="col-md-2">
                <label>Month</label>
                <select class="form-control selectpicker input-sm" id="month" name="month" data-live-search="true">  
                        <option value="*" data-tokens="*">*</option>
                        <option value="01" data-tokens="Jan">January</option>
                        <option value="02" data-tokens="Feb">February</option>
                        <option value="03" data-tokens="March">March</option>
                        <option value="04" data-tokens="April">April</option>
                        <option value="05" data-tokens="May">May</option>
                        <option value="06" data-tokens="June">June</option>
                        <option value="07" data-tokens="July">July</option>
                        <option value="08" data-tokens="Aug">August</option>
                        <option value="09" data-tokens="Sept">September</option>
                        <option value="10" data-tokens="Oct">October</option>
                        <option value="11" data-tokens="Nov">November</option>
                        <option value="12" data-tokens="Dec">December</option>
                </select>
                </div>
            
                <div class="col-md-2">
                    <div class="form-group">
                    <label>Year</label>
                    <input type="number" class="form-control input-sm" placeholder="Please Enter Year" name="year" id="year" value="{{ date('Y') }}">
                    </div>
                </div>
           
            <div class="col-md-1" style="margin-top:25px;">
                <a href="#" class="btn btn-primary btn-sm"><i class="fa fa-search"></i> Search</a>
                <!-- {{ Form::button('<i class="fa fa-search"> Search</i>', ['type' => 'submit', 'class' => 'btn btn-primary btn-sm'] )  }} -->
            </div>
            <div class="col-md-1" style="margin-top:25px;">
                <a class="btn btn-success btn-sm" href="#"><i class="fa fa-download"> Download </i></a>
            </div>
            
            </div>
           
            <table class="table table-striped table-hover table-bordered display nowrap" id="data1">
                <thead>
                    <tr>
                        <th> Date </th>
                        <th> PO No. </th>
                        <th> Product Name / Description </th>
                        <th> Qty </th>
                        <th> Currency </th>
                        <th> Unit Price </th>
                        <th> Total Amount </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>20.12.2019</td>
                        <td>123456</td>
                        <td>Item 1</td>
                        <td>2</td>
                        <td>USD</td>
                        <td>1000</td>
                        <td>2000</td>
                    </tr>
                    <tr>
                        <td>20.12.2019</td>
                        <td>123456</td>
                        <td>Item 2</td>
                        <td>1</td>
                        <td>USD</td>
                        <td>2500</td>
                        <td>2500</td>
                    </tr>
                    <tr>
                        <td>25.12.2019</td>
                        <td>456789</td>
                        <td>Item 1</td>
                        <td>2</td>
                        <td>USD</td>
                        <td>1000</td>
                        <td>2000</td>
                    </tr>
                    <tr>
                        <td>25.12.2019</td>
                        <td>456789</td>
                        <td>Item 2</td>
                        <td>2</td>
                        <td>USD</td>
                        <td>2500</td>
                        <td>5000</td>
                    </tr>
                    <tr>
                        <td>25.12.2019</td>
                        <td>456789</td>
                        <td>Item 3</td>
                        <td>1</td>
                        <td>USD</td>
                        <td>500</td>
                        <td>500</td>
                    </tr>
                </tbody>
            </table>
            </div>                  
        </div>
          
@endsection

@push('scripts')
<link rel="stylesheet" type="text/css" href="{{ asset('la-assets/plugins/datatables/datatables.min.css') }}"/>
<link rel="stylesheet" href="{{ asset('css/custom.css') }}" type="text/css">
<script src="{{ asset('la-assets/plugins/datatables/datatables.min.js') }}"></script>
<script>

$(function () {
    
    $('#data1').DataTable({
        'scrollX' : true
    });
});
</script>
@endpush