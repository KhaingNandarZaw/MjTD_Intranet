<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        {{-- @if (! Auth::guest())
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="{{ Gravatar::fallback(asset('la-assets/img/user2-160x160.jpg'))->get(Auth::user()->email) }}" class="img-circle" alt="User Image" />
                </div>
                <div class="pull-left info">
                    <p>{{ Auth::user()->name }}</p>
                    <!-- Status -->
                    <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                </div>
            </div>
        @endif --}}

        <!-- search form (Optional) -->
        @if(LAConfigs::getByKey('sidebar_search'))
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
	                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
              </span>
            </div>
        </form>
        @endif
        <!-- /.search form -->

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
            <li class="header">MODULES</li>

            <!-- Optionally, you can add icons to the links -->
            <li><a href="{{ url(config('laraadmin.adminRoute')) }}"><i class='fa fa-home'></i> <span>Dashboard</span></a></li>

            @if(Entrust::hasRole("SUPER_ADMIN") || Entrust::hasRole("CEO"))
                <li><a href="{{ url(config('laraadmin.adminRoute') . '/create_new_tasks') }}"><i class='fa fa-tasks'></i>Create New Tasks</a><li>
                <li><a href="{{ url(config('laraadmin.adminRoute') . '/confirm_new_tasks') }}"><i class='fa fa-eye'></i>
                    Confirm New Tasks
                    <?php
                        $requested_tasks = DB::table('create_new_tasks')->where('status', '=', 'Requested')->count();
                    ?>
                    @if($requested_tasks > 0)
                    <span class="pull-right-container">
                        <small class="label pull-right bg-blue">
                            {{$requested_tasks}}
                        </small>
                    </span>
                    @endif
                    </a>
                <li>
                <li><a href="{{ url(config('laraadmin.adminRoute') . '/tasks') }}"><i class='fa fa-pencil-square-o'></i>Task Assignment</a><li>
                <li><a href="{{ url(config('laraadmin.adminRoute') . '/task_checking') }}"><i class='fa fa-check-square-o'></i>
                    Task Checking
                    <?php
                        $child_lists = \Session::get('child_user_lists');
                        $task_count = DB::table('all_tasks')->where('status', '=', 'Done')->count();
                    ?>
                    @if($task_count > 0)
                    <span class="pull-right-container">
                        <small class="label pull-right bg-orange">
                            {{$task_count}}
                        </small>
                    </span>
                    @endif
                    </a>
                <li>
            @endif

            <?php
            $menuItems = Dwij\Laraadmin\Models\Menu::where("parent", 0)->orderBy('hierarchy', 'asc')->get();
            ?>
            @foreach ($menuItems as $menu)
                @if($menu->type == "module")
                    <?php
                    $temp_module_obj = Module::get($menu->name);
                    ?>
                    @la_access($temp_module_obj->id)
						@if(isset($module->id) && $module->name == $menu->name)
                        	<?php echo LAHelper::print_menu($menu ,true); ?>
						@else
							<?php echo LAHelper::print_menu($menu); ?>
						@endif
                    @endla_access
                @else
                    <?php echo LAHelper::print_menu($menu); ?>
                @endif
            @endforeach
            <!-- LAMenus -->
            
            <!-- <li class="treeview"><a href="{{ url(config('laraadmin.adminRoute')) }}"><i class='fa fa-tasks'></i> <span>Task Management</span><i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu"> -->
            @if(Entrust::hasRole("EMPLOYEE"))
            <li>
                <a href="{{ url(config('laraadmin.adminRoute') . '/create_new_tasks') }}"><i class='fa fa-tasks'></i>
                Create New Tasks
                </a>
            <li>
            <li>
                <a href="{{ url(config('laraadmin.adminRoute') . '/my_tasks') }}"><i class='fa fa-tasks'></i>
                My Tasks
                <?php
                    $from_date = Carbon\Carbon::now()->startOfMonth()->toDateString();
                    $to_date = Carbon\Carbon::now()->endOfMonth()->toDateString();
                    $onprogress_task_count = DB::table('all_tasks')->where('pic_userid', '=', Auth::user()->id)->where('status', '=', 'On Progress')->whereDate('task_date', '>=', $from_date)->whereDate('task_date', '<=', $to_date)->count();
                    $rejected_task_count = DB::table('all_tasks')->where('pic_userid', '=', Auth::user()->id)->where('status', '=', 'Rejected')->whereDate('task_date', '>=', $from_date)->whereDate('task_date', '<=', $to_date)->count();
                ?>
                @if($rejected_task_count > 0)
                <span class="pull-right-container">
                    <small class="label pull-right bg-red">
                        {{$rejected_task_count}}
                    </small>
                </span>
                @endif
                @if($onprogress_task_count > 0)
                <span class="pull-right-container">
                    <small class="label pull-right bg-orange">
                        {{$onprogress_task_count}}
                    </small>
                </span>
                @endif
                </a>
            <li>
            <li><a href="{{ url(config('laraadmin.adminRoute') . '/task_management/calendar') }}"><i class='fa fa-calendar'></i>My Calendar</a><li>
            <li><a href="{{ url(config('laraadmin.adminRoute') . '/task_checking') }}"><i class='fa fa-check-square-o'></i>
                Task Checking
                <?php
                    $task_count = DB::table('all_tasks')->where('report_to_userid', '=', Auth::user()->id)->where('status', '=', 'Done')->count();
                ?>
                @if($task_count > 0)
                <span class="pull-right-container">
                    <small class="label pull-right bg-blue">
                        {{$task_count}}
                    </small>
                </span>
                @endif
                </a>
            <li>
            @endif
            
            @if(Entrust::hasRole("OFFICER"))
                <li><a href="{{ url(config('laraadmin.adminRoute') . '/confirm_new_tasks') }}"><i class='fa fa-eye'></i>
                    Confirm New Tasks
                    <?php
                        $child_lists = \Session::get('child_user_lists');
                        $requested_tasks = DB::table('create_new_tasks')->where('status', '=', 'Requested')
                        ->where(function ($query) use ($child_lists) {
                            $query->whereIn('pic_user_id', $child_lists);
                        })->count();
                    ?>
                    @if($requested_tasks > 0)
                    <span class="pull-right-container">
                        <small class="label pull-right bg-blue">
                            {{$requested_tasks}}
                        </small>
                    </span>
                    @endif
                    </a>
                <li>
                <li>
                    <a href="{{ url(config('laraadmin.adminRoute') . '/tasks') }}"><i class='fa fa-pencil-square-o'></i>Task Assignment</a>
                <li>
                <li><a href="{{ url(config('laraadmin.adminRoute') . '/task_checking') }}"><i class='fa fa-check-square-o'></i>
                    Task Checking
                    <?php
                        $child_lists = \Session::get('child_user_lists');
                        $task_count = DB::table('all_tasks')->whereIn('pic_userid', $child_lists)->where('status', '=', 'Done')->count();
                    ?>
                    @if($task_count > 0)
                    <span class="pull-right-container">
                        <small class="label pull-right bg-orange">
                            {{$task_count}}
                        </small>
                    </span>
                    @endif
                    </a>
                <li>
            @endif

            @if(Entrust::hasRole("DGM"))
                
                <li><a href="{{ url(config('laraadmin.adminRoute') . '/task_checking') }}"><i class='fa fa-check-square-o'></i>
                    Task Checking
                    <?php
                        $child_lists = \Session::get('child_user_lists');
                        $task_count = DB::table('all_tasks')->whereIn('pic_userid', $child_lists)->where('status', '=', 'Done')->count();
                    ?>
                    @if($task_count > 0)
                    <span class="pull-right-container">
                        <small class="label pull-right bg-orange">
                            {{$task_count}}
                        </small>
                    </span>
                    @endif
                    </a>
                <li>
            @endif
           
            @if(Entrust::hasRole("SUPER_ADMIN"))
            <!-- <li><a href="{{ url(config('laraadmin.adminRoute')). '/sopexcel', 'SopExcelController@index' }}"><i class='fa fa-download'></i> <span>Import SOP Excel File</span></a></li> -->
            @endif
            
            @if(!Entrust::hasRole("EMPLOYEE"))
            <li class="treeview"><a href="{{ url(config('laraadmin.adminRoute')) }}"><i class='fa fa-upload'></i> <span>Reports</span><i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="{{ url(config('laraadmin.adminRoute') . '/reports/evaluation_report') }}"><i class='fa fa-upload'></i> Summary Evaluation Report</a><li>
                    <li><a href="{{ url(config('laraadmin.adminRoute') . '/reports/detail_evaluation_report') }}"><i class='fa fa-upload'></i> Detail Evaluation Report</a><li>    
                </ul>
            </li>
            @endif
            
        </ul><!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>
