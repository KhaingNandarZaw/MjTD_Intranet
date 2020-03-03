@extends("la.layouts.app")

@section("contentheader_title", "Reports")
@section("contentheader_description", "Evaluation Summary Report")

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
    <div class="box-header with-border"><h3 class="box-title">Evaluation Report</h3></div>
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
                                <th> Name </th>
                                <th> No. Assigned Tasks </th>
                                <th> No. Finished Tasks </th>
                                <th> No. Finished Tasks With Overdue </th>
                                <th> Completed(%) </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Mg Mg</td>
                                <td>50</td>
                                <td>40</td>
                                <td>10</td>
                                <td>90%</td>
                            </tr>
                            <tr>
                                <td>Khine Zin</td>
                                <td>49</td>
                                <td>48</td>
                                <td>10</td>
                                <td>99%</td>
                            </tr>
                            <tr>
                                <td>Aye Aye</td>
                                <td>30</td>
                                <td>20</td>
                                <td>10</td>
                                <td>90%</td>
                            </tr>
                            <tr>
                                <td>Aung Aung</td>
                                <td>50</td>
                                <td>25</td>
                                <td>10</td>
                                <td>50%</td>
                            </tr>
                            <tr>
                                <td>Khine Nandar</td>
                                <td>30</td>
                                <td>25</td>
                                <td>5</td>
                                <td>75%</td>
                            </tr>
                            <tr>
                                <td>Ma Hla</td>
                                <td>30</td>
                                <td>25</td>
                                <td>5</td>
                                <td>25%</td>
                            </tr>
                            <tr>
                                <td>Mg Tun Win</td>
                                <td>30</td>
                                <td>25</td>
                                <td>5</td>
                                <td>65%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane" id="fa-charts">
                    <div class="row">
                        <div id="chart"></div>
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
          name: 'Evaluation(%)',
          data: [50, 90, 75, 99, 90, 25, 65, 30, 10, 45]
        }],
          chart: {
          height: 350,
          type: 'bar',
        },
        plotOptions: {
          bar: {
            dataLabels: {
              position: 'top', // top, center, bottom
            },
          }
        },
        dataLabels: {
          enabled: true,
          formatter: function (val) {
            return val + "%";
          },
          offsetY: -20,
          style: {
            fontSize: '12px',
            colors: ["#304758"]
          }
        },
        
        xaxis: {
          categories: ["Aung Aung", "Aye Aye", "Khine Nandar", "Khine Zin", "Mg Mg", "Ma Hla", 'Mg Tun Win', "Aung Than", "Zaw Zaw", "Thuzar"],
          position: 'bottom',
          labels: {
            offsetX: -10,
            offsetY: 0
        
          },
          axisBorder: {
            show: false
          },
          axisTicks: {
            show: false
          },
          crosshairs: {
            fill: {
              type: 'gradient',
              gradient: {
                colorFrom: '#D8E3F0',
                colorTo: '#BED1E6',
                stops: [0, 100],
                opacityFrom: 0.4,
                opacityTo: 0.5,
              }
            }
          },
          tooltip: {
            enabled: true,
            offsetY: -35,
        
          }
        },
        fill: {
          gradient: {
            shade: 'light',
            type: "horizontal",
            shadeIntensity: 0.25,
            gradientToColors: undefined,
            inverseColors: true,
            opacityFrom: 0.0,
            opacityTo: 0.5,
            stops: [50, 0, 100, 100]
          },
        },
        yaxis: {
          axisBorder: {
            show: false
          },
          axisTicks: {
            show: false,
          },
          labels: {
            show: false,
            formatter: function (val) {
              return val + "%";
            }
          }
        
        }
    };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
});
</script>
@endpush