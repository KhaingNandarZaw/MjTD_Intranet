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
            <li><a href="{{ url(config('laraadmin.adminRoute') . '/task_instances') }}"><i class='fa fa-database'></i>My Tasks</a><li>
            <li><a href="{{ url(config('laraadmin.adminRoute') . '/task_management/calendar') }}"><i class='fa fa-calendar'></i>My Calendar</a><li>
            @endif
            <!-- <li class="treeview"><a href="{{ url(config('laraadmin.adminRoute')) }}"><i class='fa fa-tasks'></i> <span>Management Operation</span><i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu"> -->
            @if(Entrust::hasRole("OFFICER"))
                <li><a href="{{ url(config('laraadmin.adminRoute') . '/tasks') }}"><i class='fa fa-pencil-square-o'></i>Task Assignment</a><li>
                <li><a href="{{ url(config('laraadmin.adminRoute') . '/task_checking') }}"><i class='fa fa-check-square-o'></i>Task Checking</a><li>
            @endif
            <!-- </ul> -->
            @if(Entrust::hasRole("SUPER_ADMIN"))
            <!-- <li><a href="{{ url(config('laraadmin.adminRoute')). '/sopexcel', 'SopExcelController@index' }}"><i class='fa fa-download'></i> <span>Import SOP Excel File</span></a></li> -->
            @endif
            
            
            </li>
            @if(!Entrust::hasRole("EMPLOYEE"))
            <li class="treeview"><a href="{{ url(config('laraadmin.adminRoute')) }}"><i class='fa fa-upload'></i> <span>Reports</span><i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="{{ url(config('laraadmin.adminRoute') . '/task_management/evaluation_report') }}"><i class='fa fa-upload'></i> Summary Evaluation Report</a><li>
                    <li><a href="{{ url(config('laraadmin.adminRoute') . '/task_management/detail_evaluation_report') }}"><i class='fa fa-upload'></i> Detail Evaluation Report</a><li>    
                </ul>
            </li>
            @endif
            @if(Entrust::hasRole("SUPER_ADMIN"))
            <li class="treeview"><a href="{{ url(config('laraadmin.adminRoute')) }}"><i class='fa fa-upload'></i> <span>Procurement Reports</span><i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu"> 
                <li><a href="{{ url(config('laraadmin.adminRoute') . '/task_management/summary_po') }}"><i class='fa fa-upload'></i>Summary PO Report</a><li>
                <li><a href="{{ url(config('laraadmin.adminRoute') . '/task_management/detail_po') }}"><i class='fa fa-upload'></i>Detail PO Report</a><li>
            </ul> 
            @endif
        </ul><!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>
