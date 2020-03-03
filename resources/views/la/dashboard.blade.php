@extends('la.layouts.app')

@section('htmlheader_title') Dashboard @endsection
@section('contentheader_title') Dashboard @endsection
@section('contentheader_description') Organisation Overview @endsection

@section('main-content')

<?php
  $date = \Carbon\Carbon::now();

  $current_month = $date->format('F'); // July
?>
<section class="content">
    <div class="row">
      <div class="col-md-6 col-lg-2 col-xlg-3">
        <!-- small box -->
        <div class="small-box bg-aqua">
          <div class="icon">
            <i class="ion ion-ios-people"></i>
          </div>
          <div class="inner">
            <h3>HR System</h3>
          </div>
        </div>
      </div><!-- ./col -->
      <div class="col-md-6 col-lg-2 col-xlg-3">
        <!-- small box -->
        <div class="small-box bg-success">
          <div class="icon">
            <i class="ion ion-ios-calculator"></i>
          </div>
          <div class="inner">
            <h3>Accounting System</h3>
          </div>
        </div>
      </div><!-- ./col -->
      <div class="col-md-6 col-lg-2 col-xlg-3">
        <!-- small box -->
        <div class="small-box bg-danger">
          <div class="icon">
            <i class="ion ion-bag"></i>
          </div>
          <div class="inner">
            <h3><a href="https://admin.umsmjtd.com/" target="_blank" style="color:white;"> Utility Management System</a></h3>
          </div>
        </div>
      </div><!-- ./col -->
      <!-- <div class="col-md-6 col-lg-2 col-xlg-3">
        <div class="small-box bg-yellow">
          <div class="icon">
            <i class="fa fa-file-code-o"></i>
          </div>
          <div class="inner">
            <h3>Document Managment System</h3>
          </div>
        </div>
      </div> ./col -->
      <div class="col-md-6 col-lg-2 col-xlg-3">
        <!-- small box -->
        <div class="small-box bg-purple">
          <div class="icon">
            <i class="fa fa-cubes"></i>
          </div>
          <div class="inner">
            <h3>Procurement System</h3>
          </div>
        </div>
      </div><!-- ./col -->
      <div class="col-md-6 col-lg-2 col-xlg-3">
        <!-- small box -->
        <div class="small-box bg-olive">
          <div class="icon">
            <i class="ion ion-calendar"></i>
          </div>
          <div class="inner">
            <h3>Resource Booking System</h3>
          </div>
        </div>
      </div><!-- ./col -->
    </div>
    <div class="row">
      <div class="col-md-6 col-lg-4 col-xlg-2">
        <div class="box box-danger" style="height: 270px;overflow: auto;">
          <div class="box-header with-border">
            <h3 class="box-title">Today Leaves</h3>

            <div class="box-tools pull-right">
              <span class="label label-danger">7 Members</span>
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
              </button>
              <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
              </button>
            </div>
          </div>
          <!-- /.box-header -->
          <div class="box-body no-padding">
            <ul class="users-list clearfix">
              <li>
                <img src="{{asset('la-assets/img/user1-128x128.jpg')}}" style="width:50%;height:50%;" alt="User Image">
                <a class="users-list-name" href="#">Mg Aung</a>
              </li>
              <li>
                <img src="{{asset('la-assets/img/user8-128x128.jpg')}}" style="width:50%;height:50%;"  alt="User Image">
                <a class="users-list-name" href="#">Mg Mg</a>
              </li>
              <li>
                <img src="{{asset('la-assets/img/user7-128x128.jpg')}}" style="width:50%;height:50%;"  alt="User Image">
                <a class="users-list-name" href="#">Khine Zin</a>
              </li>
              <li>
                <img src="{{asset('la-assets/img/user6-128x128.jpg')}}" style="width:50%;height:50%;"  alt="User Image">
                <a class="users-list-name" href="#">Aye Min</a>
              </li>
              <li>
                <img src="{{asset('la-assets/img/user5-128x128.jpg')}}" style="width:50%;height:50%;"  alt="User Image">
                <a class="users-list-name" href="#">Su Su</a>
              </li>
              <li>
                <img src="{{asset('la-assets/img/user4-128x128.jpg')}}" style="width:50%;height:50%;"  alt="User Image">
                <a class="users-list-name" href="#">Thandar</a>
              </li>
              <li>
                <img src="{{asset('la-assets/img/user3-128x128.jpg')}}" style="width:50%;height:50%;"  alt="User Image">
                <a class="users-list-name" href="#">Phyo Nandar</a>
              </li>
            </ul>
            <!-- /.users-list -->
          </div>
          <!-- /.box-footer -->
        </div>
      </div>
      <div class="col-md-6 col-lg-4" style="display: none;">
        <!-- TO DO List -->
        <div class="box box-primary">
          <div class="box-header">
            <i class="ion ion-clipboard"></i>
            <h3 class="box-title">To Do List</h3>
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
            </button>
          </div><!-- /.box-header -->
          <div class="box-body">
            <ul class="todo-list">
              <li>
                <!-- drag handle -->
                <span class="handle">
                  <i class="fa fa-ellipsis-v"></i>
                  <i class="fa fa-ellipsis-v"></i>
                </span>
                <!-- checkbox -->
                <input type="checkbox" value="" name="">
                <!-- todo text -->
                <span class="text">Design a nice theme</span>
                <!-- Emphasis label -->
                <small class="label label-danger"><i class="fa fa-clock-o"></i> 2 mins</small>
                <!-- General tools such as edit or delete-->
                <div class="tools">
                  <i class="fa fa-edit"></i>
                  <i class="fa fa-trash-o"></i>
                </div>
              </li>
              <li>
                <span class="handle">
                  <i class="fa fa-ellipsis-v"></i>
                  <i class="fa fa-ellipsis-v"></i>
                </span>
                <input type="checkbox" value="" name="">
                <span class="text">Make the theme responsive</span>
                <small class="label label-info"><i class="fa fa-clock-o"></i> 4 hours</small>
                <div class="tools">
                  <i class="fa fa-edit"></i>
                  <i class="fa fa-trash-o"></i>
                </div>
              </li>
              <li>
                <span class="handle">
                  <i class="fa fa-ellipsis-v"></i>
                  <i class="fa fa-ellipsis-v"></i>
                </span>
                <input type="checkbox" value="" name="">
                <span class="text">Let theme shine like a star</span>
                <small class="label label-warning"><i class="fa fa-clock-o"></i> 1 day</small>
                <div class="tools">
                  <i class="fa fa-edit"></i>
                  <i class="fa fa-trash-o"></i>
                </div>
              </li>
              <li>
                <span class="handle">
                  <i class="fa fa-ellipsis-v"></i>
                  <i class="fa fa-ellipsis-v"></i>
                </span>
                <input type="checkbox" value="" name="">
                <span class="text">Let theme shine like a star</span>
                <small class="label label-success"><i class="fa fa-clock-o"></i> 3 days</small>
                <div class="tools">
                  <i class="fa fa-edit"></i>
                  <i class="fa fa-trash-o"></i>
                </div>
              </li>
              <li>
                <span class="handle">
                  <i class="fa fa-ellipsis-v"></i>
                  <i class="fa fa-ellipsis-v"></i>
                </span>
                <input type="checkbox" value="" name="">
                <span class="text">Check your messages and notifications</span>
                <small class="label label-primary"><i class="fa fa-clock-o"></i> 1 week</small>
                <div class="tools">
                  <i class="fa fa-edit"></i>
                  <i class="fa fa-trash-o"></i>
                </div>
              </li>
              <li>
                <span class="handle">
                  <i class="fa fa-ellipsis-v"></i>
                  <i class="fa fa-ellipsis-v"></i>
                </span>
                <input type="checkbox" value="" name="">
                <span class="text">Let theme shine like a star</span>
                <small class="label label-default"><i class="fa fa-clock-o"></i> 1 month</small>
                <div class="tools">
                  <i class="fa fa-edit"></i>
                  <i class="fa fa-trash-o"></i>
                </div>
              </li>
            </ul>
          </div>
        </div><!-- /.box -->
      </div>
      @if(count($announcements) > 0)
      <div class="col-md-6 col-lg-4">
        <div class="box box-success" style="height: 270px;overflow: auto;">
              <div class="box-header">
                  <h4 class="box-title">Announcements</h4>
              </div>
              <div class="box-body">
                <ul class="products-list product-list-in-box">
                @foreach ($announcements as $key=>$announcement )
                  <li class="item">
                    <div class="product-img">
                      <span class="fa {{$announcement->icon}}" style="font-size:25px;"></span>
                    </div>
                    <div class="product-info">
                      <a href="{{ url(config('laraadmin.adminRoute') . '/announcements/'.$announcement->id) }}" class="product-title">{{$announcement->title}}
                        <span class="label {{ ($key % 4 == 0) ? 'label-success' : (($key % 3 == 0) ? 'label-warning' : 'label-primary') }} pull-right">{{date('M d, Y', strtotime($announcement->startdate))}}</span>
                      </a>
                      <span class="product-description">
                        {{$announcement->description}}
                      </span>
                    </div>
                  </li>
                @endforeach
                </ul>
              </div>
        </div>
      </div>
      @endif
      @if(Entrust::hasRole("EMPLOYEE"))
      <div class="col-md-6 col-lg-4">
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">{{$current_month}} Task Results</h3>
            <div class="box-tools pull-right">
              <span class="label" style="background-color: cornflowerblue;">Total {{count($total_tasks)}} Tasks</span>
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
              </button>
              <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
              </button>
            </div>
          </div>
          <div class="box-body">
            <ul class="nav nav-stacked">
              <li><a href="#">On Progress <span class="pull-right badge" style="background-color: rgb(57, 204, 204);">{{count($on_progress)}}</span></a></li>
              <li><a href="#">Overdue(On Progress) <span class="pull-right badge" style="background-color: rgb(240, 80, 80);">{{count($over_due)}}</span></a></li>
              <li><a href="#">Done <span class="pull-right badge" style="background-color: #3a87ad;">{{count($done)}}</span></a></li>
              <li><a href="#">Approved <span class="pull-right badge" style="background-color: rgb(61, 153, 112);">{{count($approved)}}</span></a></li>
              <li><a href="#">Rejected <span class="pull-right badge bg-red">{{count($rejected)}}</span></a></li>
            </ul>
          </div>
        </div>
      </div>
      @endif
    </div>
    @if(Entrust::hasRole("EMPLOYEE"))
    <div class="row">
      <div class="col-md-6">
        <div class="box box-success">
          <div class="box-header with-border">
            <h3 class="box-title">Calendar</h3>
            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
              </button>
              <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
              </button>
            </div>
          </div>
          <div class="box-body">
            <div id="fullCalendar"></div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <!-- <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">Monthly Task Results</h3>
            <div class="box-tools pull-right">
              <span class="label" style="background-color: cornflowerblue;">Total {{count($total_tasks)}} Tasks</span>
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
              </button>
              <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
              </button>
            </div>
          </div>
          <div class="box-body">
            <div class="col-xs-4 text-center">
              <input type="text" class="knob" data-readonly="true" data-thickness="0.2" value="{{count($on_progress)}}" data-width="60" data-height="60" data-fgColor="#39CCCC">
              <div class="knob-label">On Progress</div>
            </div>
            <div class="col-xs-4 text-center">
              <input type="text" class="knob" data-readonly="true" data-thickness="0.2" value="{{count($over_due)}}" data-width="60" data-height="60" data-fgColor="#f05050">
              <div class="knob-label">Overdue(On Progress)</div>
            </div>
            <div class="col-xs-4 text-center">
              <input type="text" class="knob" data-readonly="true" data-thickness="0.2" value="{{count($done)}}" data-width="60" data-height="60" data-fgColor="#3a87ad">
              <div class="knob-label">Done</div>
            </div>
            <div class="col-xs-4 text-center">
              <input type="text" class="knob" data-readonly="true" data-thickness="0.2" value="{{count($approved)}}" data-width="60" data-height="60" data-fgColor="#3D9970">
              <div class="knob-label">Approved</div>
            </div>
            <div class="col-xs-4 text-center">
              <input type="text" class="knob" data-readonly="true" data-thickness="0.2" value="{{count($rejected)}}" data-width="60" data-height="60" data-fgColor="#f05050">
              <div class="knob-label">Rejected</div>
            </div>
        </div> -->
        
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">Yearly Task Results</h3>
          </div>
          <div id="barchart"></div>
        </div>
      </div>
    </div>
    @endif
    @if(Entrust::hasRole("OFFICER") || Entrust::hasRole("SUPER_ADMIN"))
    <div class="row">
      <div class="col-md-12">        
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">This Month({{$current_month}}) Task Results Chart by Status</h3>
            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
              </button>
            </div>
          </div>
          <div class="box-body">
            <div id="evaluation_chart"></div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12 form-group">        
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">This Month({{$current_month}}) Task Results(%)</h3>
            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
              </button>
            </div>
          </div>
          <div class="box-body">
            <div id="evaluation_percentage"></div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-8">
        <div class="box box-success">
          <div class="box-header with-border">
            <h3 class="box-title">Calendar</h3>
            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
              </button>
              <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
              </button>
            </div>
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-6">
                <label>Employee</label>
                <select class="form-control input-sm" data-placeholder="Select User" onchange="filter_user(this.value)" rel="select2" name="user_id">
                    <option value="0" selected>*</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" selected>{{ $user->name }}</option>
                      
                    @endforeach
                </select>
              </div>
            </div>
            <div id="all_calendar"></div>
          </div>
        </div>
      </div>
    </div>
    @endif
</section><!-- /.content -->
@endsection

@push('styles')
<!-- Morris chart -->
<link rel="stylesheet" href="{{ asset('la-assets/plugins/morris/morris.css') }}">
<!-- jvectormap -->
<link rel="stylesheet" href="{{ asset('la-assets/plugins/jvectormap/jquery-jvectormap-1.2.2.css') }}">
<!-- Date Picker -->
<link rel="stylesheet" href="{{ asset('la-assets/plugins/datepicker/datepicker3.css') }}">
<!-- Daterange picker -->
<link rel="stylesheet" href="{{ asset('la-assets/plugins/daterangepicker/daterangepicker-bs3.css') }}">
<!-- bootstrap wysihtml5 - text editor -->
<link rel="stylesheet" href="{{ asset('la-assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('la-assets/css/calendar.css') }}"/>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.css" />
<style>
  .event-overdue{
    background-color: rgb(240, 80, 80);
  }
  .event-finish{
    background-color: rgb(61, 153, 112);
  }
  .event-onprogress{
    background-color: rgb(57, 204, 204);
  }
  .event-approved-overdue{
    background-color: rgb(243, 156, 18);
  }
  .event-onprogress{
    background-color: rgb(57, 204, 204);
  }
</style>
@endpush

@push('scripts')
<!-- jQuery UI 1.11.4 -->
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Morris.js charts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="{{ asset('la-assets/plugins/morris/morris.min.js') }}"></script>
<!-- Sparkline -->
<script src="{{ asset('la-assets/plugins/sparkline/jquery.sparkline.min.js') }}"></script>
<!-- jvectormap -->
<script src="{{ asset('la-assets/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
<script src="{{ asset('la-assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
<!-- jQuery Knob Chart -->
<script src="{{ asset('la-assets/plugins/knob/jquery.knob.js') }}"></script>
<!-- daterangepicker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="{{ asset('la-assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
<!-- datepicker -->
<script src="{{ asset('la-assets/plugins/datepicker/bootstrap-datepicker.js') }}"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="{{ asset('la-assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}"></script>
<!-- FastClick -->
<script src="{{ asset('la-assets/plugins/fastclick/fastclick.js') }}"></script>
<!-- dashboard -->
<script src="{{ asset('la-assets/js/pages/dashboard.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.js"></script>
<script src="{{ asset('la-assets/js/apexcharts.js') }}"></script>
@endpush

@push('scripts')
<script>
(function($) {
  // $('body').pgNotification({
  //  style: 'circle',
  //  title: 'LaraAdmin',
  //  message: "Welcome to LaraAdmin...",
  //  position: "top-right",
  //  timeout: 0,
  //  type: "success",
  //  thumbnail: '<img width="40" height="40" style="display: inline-block;" src="{{ Gravatar::fallback(asset('la-assets/img/user2-160x160.jpg'))->get(Auth::user()->email, 'default') }}" data-src="assets/img/profiles/avatar.jpg" data-src-retina="assets/img/profiles/avatar2x.jpg" alt="">'
  // }).show();
  $('#fullCalendar').fullCalendar({
	    // put your options and callbacks here
	    left:   'title',
	    header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month,basicWeek,basicDay,listWeek'
      },
      selectable: true,
      selectHelper: true,
	    weekNumbers: true,
	    defaultView: 'month',
	    events: "{{ url('/fullcalendar') }}"
	});

  var options = {
          series: [{
          name: 'On Progress',
          data: <?php echo json_encode($on_progress_lists); ?>
        }, {
          name: 'Overdue(On Progress)',
          data: <?php echo json_encode($overdue_lists); ?>
        }, {
          name: 'Completed',
          data: <?php echo json_encode($approved_lists); ?>
        }, {
          name: 'Completed by overdue',
          data: <?php echo json_encode($completed_overdue_lists); ?>
        }],
          chart: {
          type: 'bar',
          height: 350
        },
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '65%'
          },
        },
        dataLabels: {
          enabled: false
        },
        colors:['rgb(57, 204, 204)', 'rgb(240, 80, 80)', 'rgb(61, 153, 112)', 'rgb(243, 156, 18)'],
        xaxis: {
          categories: <?php echo json_encode($month_name_lists); ?>,
        },
        yaxis: {
          title: {
            text: 'No. of Tasks'
          }
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return  val 
            }
          }
        }
    };

        var chart = new ApexCharts(document.querySelector("#barchart"), options);
        chart.render();

        var evaluation_options = {
          series: [{
          name: 'On Progress',
          data: <?php echo json_encode($on_progress); ?>
        }, {
          name: 'On Progress(Overdue)',
          data: <?php echo json_encode($on_progress_overdue); ?>
        }, {
          name: 'Done',
          data: <?php echo json_encode($done); ?>
        }, {
          name: 'Completed',
          data: <?php echo json_encode($approved); ?>
        }, {
          name: 'Completed With Overdue',
          data: <?php echo json_encode($over_due); ?>
        }],
          chart: {
          type: 'bar',
          height: 400,
          stacked: true,
          toolbar: {
            show: true
          }
        },
        colors:['#39CCCC', '#f05050', '#48B0F7', '#3D9970', '#f39c12'],
        responsive: [{
          breakpoint: 480        
        }],
        plotOptions: {
          bar: {
            horizontal: false,
          },
        },
        xaxis: {
          type: 'text',
          categories: <?php echo json_encode($user_name_lists); ?>,
        },
        legend: {
          position: 'top'
        }
        };

        var chart = new ApexCharts(document.querySelector("#evaluation_chart"), evaluation_options);
        chart.render();
      
        var evaluation_percentage = {
          series: [{
          name: 'Evaluation(%)',
          data: <?php echo json_encode($complete_percentage_lists); ?>
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
          categories: <?php echo json_encode($user_name_lists); ?>,
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
        var chart = new ApexCharts(document.querySelector("#evaluation_percentage"), evaluation_percentage);
        chart.render();
        
})(window.jQuery);

function filter_user(emp_id){
  
  $('#all_calendar').fullCalendar({
    left:   'title',
    header: {
      left: 'prev,next today',
      center: 'title',
      right: 'month,basicWeek,basicDay,listWeek'
    },
    defaultView: 'month',
    events: function(start, end, timezone, callback) {
      jQuery.ajax({
          url: "{{ url('/all_calendar') }}",
          type: 'POST',
          dataType: 'json',
          data: {
            _token: '{{ csrf_token() }}',
              start: start.format(),
              end: end.format(),
              user_id : emp_id
          },
          success: function(doc) {
              var event_lists = [];
              if(!!doc){
                  $.map( doc, function( r ) {
                    event_lists.push({
                          id: r.id,
                          title: r.title,
                          start: r.start,
                          className: r.className
                      });
                  });
              }
              callback(event_lists);
          }
      });
    }
  });
}
</script>
@endpush