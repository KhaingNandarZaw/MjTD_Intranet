@extends("la.layouts.app")

@section("contentheader_title", "Reports")
@section("contentheader_description", "Detail Evaluation Report")

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
    <div class="box-header with-border"><h3 class="box-title">Detail Evaluation Report</h3></div>
    <div class="box-body">
        <div class="row">
            
            <div class="col-md-2">
                <label>Name</label>
                <select class="form-control selectpicker input-sm" id="facility_type_id" name="facility_type_id" data-live-search="true">  
                        <option value="*" data-tokens="*">*</option>
                        <option value="1" data-tokens="power">Mg Mg</option>
                        <option value="2" data-tokens="water">Hla Hla</option>
                </select>
            </div>
            <div class="col-md-2">
                <label>Status</label>
                <select class="form-control selectpicker input-sm" id="facility_type_id" name="facility_type_id" data-live-search="true">  
                        <option value="*" data-tokens="*">*</option>
                        <option value="1" data-tokens="power">On Progress</option>
                        <option value="2" data-tokens="water">Todo</option>
                        <option value="3">Done</option>
                        <option value="4">Completed</option>
                        <option valu="5">Due</option>
                        <option value="6">Rejected</option>
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

        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#fa-icons" data-toggle="tab" aria-expanded="false">Lists</a></li>
                <li class=""><a href="#fa-charts" data-toggle="tab" aria-expanded="false">Charts</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="fa-icons">
                    <table class="table table-striped table-hover table-bordered display nowrap" id="data1">
                        <thead>
                            <tr>
                                <th> Task Title </th>
                                <th> PIC </th>
                                <th> Time Frame </th>
                                <th> Status </th>
                                <th> Assigned Date </th>
                                <th> Completed Date </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Task 1</td>
                                <td>Mg Mg</td>
                                <td>Monthly</td>
                                <td>On Progress</td>
                                <td>2019-12-10</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Task 2</td>
                                <td>Mg Mg</td>
                                <td>Monthly</td>
                                <td>Completed</td>
                                <td>2019-12-10</td>
                                <td>2019-12-20</td>
                            </tr>
                            <tr>
                                <td>Task 3</td>
                                <td>Aung Aung</td>
                                <td>Monthly</td>
                                <td>On Progress</td>
                                <td>2019-12-10</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Task 4</td>
                                <td>Aung Aung</td>
                                <td>Monthly</td>
                                <td>Rejected</td>
                                <td>2019-12-12</td>
                                <td>2019-12-20</td>
                            </tr>
                            <tr>
                                <td>Task 5</td>
                                <td>Khine Zin</td>
                                <td>Monthly</td>
                                <td>On Progress</td>
                                <td>2019-12-10</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane" id="fa-charts">
                    <div class="row">
                        <div id="barchart"></div>
                    </div>	
                </div>
            </div>
        </div>
        
    </div>                  
</div>
          
@endsection

@push('scripts')
<link rel="stylesheet" type="text/css" href="{{ asset('la-assets/plugins/datatables/datatables.min.css') }}"/>
<link rel="stylesheet" href="{{ asset('css/custom.css') }}" type="text/css">
<script src="{{ asset('la-assets/plugins/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('la-assets/js/apexcharts.js') }}"></script>
<script>

$(function () {
    
    $('#fa-icons #data1').DataTable({
        'scrollX' : true
    });
    var options = {
        series: [{
          name: 'On Progress',
          data: [44, 55, 41, 37, 22, 43, 21]
        }, {
          name: 'Approved',
          data: [53, 32, 33, 52, 13, 43, 32]
        }, {
          name: 'Done',
          data: [9, 7, 5, 8, 6, 9, 4]
        }, {
          name: 'Overdue',
          data: [12, 17, 11, 9, 15, 11, 20]
        }],
        chart: {
          type: 'bar',
          height: 350,
          stacked: true,
        },
        plotOptions: {
          bar: {
            horizontal: true,
          },
        },
        stroke: {
          width: 1,
          colors: ['#fff']
        },
        title: {
          text: 'Members'
        },
        xaxis: {
          categories: ['Mg Mg', 'Khine Zin', 'Aung Khant', 'Thiha', 'Aung aung', 'Myo Myo', 'Hla Hla'],
          labels: {
            formatter: function (val) {
              return val
            }
          }
        },
        yaxis: {
          title: {
            text: undefined
          },
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return val
            }
          }
        },
        fill: {
          opacity: 0.8
        },
        legend: {
          position: 'top',
          horizontalAlign: 'left',
          offsetX: 40
        }
      };

      var chart = new ApexCharts(document.querySelector("#barchart"), options);
      chart.render();
});
</script>
@endpush