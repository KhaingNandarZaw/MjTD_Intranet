@extends("la.layouts.app")

@section("contentheader_title", "Reports")
@section("contentheader_description", "Detail Evaluation Report")

@section("main-content")

<div class="box box-warning">
    <div class="box-header with-border"><h3 class="box-title">Detail Evaluation Report</h3></div>
    <div class="box-body">
        {!! Form::open(['action' => 'LA\ReportController@detail_evaluation_report', 'method' => 'POST']) !!}
        <div class="row">
            <div class="col-md-2">
              <label>Department</label>
              <select class="form-control input-sm" data-placeholder="Select Department" rel="select2" name="dept_id">
                  <option value="0" selected>*</option>
                  @foreach($departments as $dept)
                    @if($dept->id == $dept_id)
                      <option value="{{ $dept->id }}" selected>{{ $dept->name }}</option>
                    @else
                      <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                    @endif
                  @endforeach
              </select>
            </div>
            <div class="col-md-2">
              <label>Name</label>
              <select class="form-control input-sm" data-placeholder="Select User" rel="select2" name="user_id">
                  <option value="0" selected>*</option>
                  @foreach($users as $user)
                    @if($user->id == $user_id)
                      <option value="{{ $user->id }}" selected>{{ $user->name }}</option>
                    @else
                      <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endif
                  @endforeach
              </select>
            </div>
            <div class="col-md-2">
              <label>Status</label>
              <select class="form-control input-sm" data-placeholder="Select Status" rel="select2" id="status" name="status">
                <option value="0" selected>*</option>
                @foreach($status_lists as $status)
                  @if($selected_status == $status)
                    <option value="{{$status}}" selected>{{$status}}</option>
                  @else
                    <option value="{{$status}}">{{$status}}</option>
                  @endif
                @endforeach
              </select>
            </div>
            <div class="col-md-2">
              <label>Month</label>
              <select class="form-control input-sm" required="1" data-placeholder="Select Month" rel="select2" id="month" name="month">
                <option value="0" selected>*</option>
                @foreach($month_lists as $month)
                  @if($month['month_number'] == $selected_month)
                  <option value="{{$month['month_number']}}" selected>{{$month['month_name']}}</option>
                  @else
                  <option value="{{$month['month_number']}}">{{$month['month_name']}}</option>
                  @endif
                @endforeach
              </select>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label>Year</label>
                <input type="number" class="form-control input-sm" placeholder="Please Enter Year" name="year" id="year" value="{{ $selected_year }}">
              </div>
            </div>
            <div class="col-md-1" style="margin-top:25px;">
              {{ Form::button('<i class="fa fa-search"> Search</i>', ['type' => 'submit', 'class' => 'btn btn-primary btn-sm'] )  }} 
            </div>
        </div>
        {!! Form::close() !!}
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#fa-icons" data-toggle="tab" aria-expanded="false">Lists</a></li>
                <li class=""><a href="#fa-charts" data-toggle="tab" aria-expanded="false">Charts</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="fa-icons">
                    <table class="table table-striped table-hover table-bordered" id="data1">
                        <thead>
                            <tr>
                                <th> Task Title </th>
                                <th> Task Description </th>
                                <th> PIC </th>
                                <th> Report To</th>
                                <th> Status </th>
                                <th> To Finish Date </th>
                                <th> Finished Date </th>
                                <th> Approved By </th>
                                <th> Approved Date </th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($all_assigned_tasks as $assigned_task)
                            <tr>
                                <td>{{$assigned_task->name}}</td>
                                <td>{{$assigned_task->MainDescription}}</td>
                                <td>{{$assigned_task->pic}}</td>
                                <td>{{$assigned_task->reportTo}}
                                <td>{{$assigned_task->status}}</td>
                                <td>{{$assigned_task->task_date}}</td>
                                <td>{{$assigned_task->done_date}}</td>
                                <td>{{$assigned_task->approvedBy}}</td>
                                <td>{{$assigned_task->approved_date}}</td>
                            </tr>
                        @endforeach
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

<script src="{{ asset('la-assets/plugins/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('la-assets/js/apexcharts.js') }}"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script>

$(function () {
    
    $('#fa-icons #data1').DataTable({
        "searching": false,
        'dom' : 'Bfrtip', 
        buttons: [   
          {
           extend:   'excel',
           title: "Detail Evaluation Report " + $("#year").val() + " " + ($("#month option:selected").text() == '*' ? '' : $("#month option:selected").text()),
           filename: 'Detail Evaluation Report'
           
          }                    
        ]
    });

    $(".dt-buttons").show(); 
    document.getElementsByClassName('dt-button')[0].children[0].innerHTML = "<i class='fa fa-download'> Export Excel</i>";
    document.getElementsByClassName('dt-button')[0].className += " btn btn-success btn-sm";

    var options = {
        series: [{
          name: 'On Progress',
          data: <?php echo json_encode($onprogress_tasks); ?>
        }, {
          name: 'Approved',
          data: <?php echo json_encode($completed_tasks); ?>
        }, {
          name: 'Done',
          data: <?php echo json_encode($done_tasks); ?>
        }, {
          name: 'Rejected',
          data: <?php echo json_encode($rejected_tasks); ?>
        }],
        colors:['#39CCCC', '#3D9970', '#48B0F7', '#f05050'],
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
        xaxis: {
          categories: <?php echo json_encode($child_name_lists); ?>,
          labels: {
            formatter: function (val) {
              return val
            }
          }
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return val
            }
          }
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