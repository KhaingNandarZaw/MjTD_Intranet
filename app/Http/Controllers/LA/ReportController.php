<?php
/**
 * Controller generated using LaraAdmin
 * Help: http://laraadmin.com
 * LaraAdmin is open-sourced software licensed under the MIT license.
 * Developed by: Dwij IT Solutions
 * Developer Website: http://dwijitsolutions.com
 */

namespace App\Http\Controllers\LA;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use DB;
use Auth;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Collective\Html\FormFacade as Form;

/**
 * Class ReportController
 * @package App\Http\Controllers
 */
class ReportController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function evaluation_report(Request $request)
    {
        $users = [];
        $child_lists = [];
        $dept_child_lists = [];
        $month_lists = [];
        $departments = [];

        $pic_userid = 0;
        $selected_month = 0;
        $selected_year = date("Y");
        $dept_id = 0;

        //filter
        if($request->has('dept_id')){
            $dept_id = $request->input('dept_id');
        }
        if($request->has('user_id')){
            $pic_userid = $request->input('user_id');
        }
        if($request->has("month")){
            $selected_month = $request->input('month');
        }
        if($request->has('year')){
            $selected_year = $request->input('year');
        }

        //month for select box
        $date = Carbon::now();
        $startOfYear = $date->copy()->startOfYear()->toDateString();
        $endOfYear   = $date->copy()->endOfYear()->toDateString();
        $interval = CarbonPeriod::create($startOfYear,'1 month', $endOfYear);

        foreach ($interval as $dt) {
            $data = array(
                "month_number" => $dt->format("m"),
                "month_name" => $dt->format("F")
                );
            array_push($month_lists, $data);
        }
        
        //get all users from under user's department
        $childs = DB::select('call getAllChildUsers(?)', array(Auth::user()->department));
        for ($i=0; $i < count($childs); $i++) { 
            if($childs[$i]->id != Auth::user()->id && $childs[$i]->id != 1)
                array_push($users, $childs[$i]);
        }
        for ($i=0; $i < count($users); $i++) { 
            array_push($child_lists, $users[$i]->id);
        }

        $departments = DB::select('call getAllChildDepartment(?)', array(Auth::user()->department));
        
        if($dept_id != null || $dept_id != '0'){
            $childs = DB::select('call getAllChildUsers(?)', array($dept_id));
            for ($i=0; $i < count($childs); $i++) { 
                array_push($dept_child_lists, $childs[$i]->id);
            }
        }

        //start date and end date of year
        $date = Carbon::now();
        $current_user_id = Auth::user()->id;
        $startOfYear = $date->copy()->startOfYear()->toDateString();
        $endOfYear   = $date->copy()->endOfYear()->toDateString();

        $query = DB::table('tasks_by_pic')
            ->select('task_date', 'pic', 'pic_userid', DB::raw('SUM(total_tasks) as total_tasks'), 
            DB::raw('SUM(on_progress) as on_progress'), DB::raw('SUM(approved) as approved'), 
            DB::raw('SUM(approved_overdue) as approved_overdue'))
            ->whereBetween('task_date',[$startOfYear, $endOfYear])
            ->whereYear('task_date', $selected_year)
            ->groupby('pic_userid');
        if(isset($pic_userid) && ($pic_userid != '0'))
        {
            $query = $query->where('pic_userid','=', $pic_userid);
        }
        if(isset($dept_id) && ($dept_id != '0'))
        {
            $query = $query->whereIn('pic_userid', $dept_child_lists);
        }else
            $query = $query->whereIn('pic_userid', $child_lists);
        if(isset($selected_month) && $selected_month != 0){
            $query = $query->whereMonth('task_date','=', $selected_month);
        }
        $all_assigned_tasks = $query->get();
        
        //data for chart
        $user_name_lists = [];
        $complete_percentage_lists = [];
        foreach($all_assigned_tasks as $assigned_task){
            if($assigned_task->approved != 0 && $assigned_task->total_tasks != 0){
                $assigned_task->complete_percentage = number_format(($assigned_task->approved / $assigned_task->total_tasks)* 100, 0) ;
            } else {
                $assigned_task->complete_percentage = 0;
            }
            array_push($user_name_lists, $assigned_task->pic);
            array_push($complete_percentage_lists, $assigned_task->complete_percentage);
        }
        return view('la.reports.evaluation_report',[
            'users' => $users,
            'month_lists' => $month_lists,
            'all_assigned_tasks' => $all_assigned_tasks,
            'complete_percentage_lists' => $complete_percentage_lists,
            'departments' => $departments,
            'user_name_lists' => $user_name_lists,
            'user_id' => $pic_userid,
            'selected_month' => $selected_month,
            'selected_year' => $selected_year,
            'dept_id' => $dept_id
        ]);
    }

    public function detail_evaluation_report(Request $request){
        $users = [];
        $child_lists = [];
        $child_name_lists = [];
        $month_lists = [];
        $status_lists = [];
        $dept_child_lists = [];

        $pic_userid = 0;
        $selected_status = '0';
        $selected_month = 0;
        $selected_year = date("Y");
        $dept_id = 0;

        //filter
        if($request->has('user_id')){
            $pic_userid = $request->input('user_id');
        }
        if($request->has('dept_id')){
            $dept_id = $request->input('dept_id');
        }
        if($request->has('status')){
            $selected_status = $request->input('status');
        }
        if($request->has("month")){
            $selected_month = $request->input('month');
        }
        if($request->has('year')){
            $selected_year = $request->input('year');
        }
        //list of status
        $status_lists = array('On Progress', 'Done', 'Approved', 'Rejected', 'Cancel');

        //month for select box
        $date = Carbon::now();
        $startOfYear = $date->copy()->startOfYear()->toDateString();
        $endOfYear   = $date->copy()->endOfYear()->toDateString();
        $interval = CarbonPeriod::create($startOfYear,'1 month', $endOfYear);

        foreach ($interval as $dt) {
            $data = array(
                "month_number" => $dt->format("m"),
                "month_name" => $dt->format("F")
                );
            array_push($month_lists, $data);
        }
        
        //get all users from under user's department
        $childs = DB::select('call getAllChildUsers(?)', array(Auth::user()->department));
        for ($i=0; $i < count($childs); $i++) { 
            if($childs[$i]->id != Auth::user()->id && $childs[$i]->id != 1)
                array_push($users, $childs[$i]);
        }
        for ($i=0; $i < count($users); $i++) { 
            array_push($child_lists, $users[$i]->id);
        }

        $departments = DB::select('call getAllChildDepartment(?)', array(Auth::user()->department));

        if($dept_id != null || $dept_id != '0'){
            $childs = DB::select('call getAllChildUsers(?)', array($dept_id));
            for ($i=0; $i < count($childs); $i++) { 
                array_push($dept_child_lists, $childs[$i]->id);
            }
        }

        //start date and end date of year
        $date = Carbon::now();
        $current_user_id = Auth::user()->id;
        $startOfYear = $date->copy()->startOfYear()->toDateString();
        $endOfYear   = $date->copy()->endOfYear()->toDateString();

        $query = DB::table('all_tasks')
            ->whereBetween('task_date',[$startOfYear, $endOfYear])
            ->whereYear('task_date', $selected_year);
        if(isset($pic_userid) && ($pic_userid != '0'))
        {
            $query = $query->where('pic_userid','=', $pic_userid);
        }
        if(isset($dept_id) && ($dept_id != '0'))
        {
            $query = $query->whereIn('pic_userid', $dept_child_lists);
        }else
            $query = $query->whereIn('pic_userid', $child_lists);

        if(isset($selected_status) && $selected_status != "0"){
            $query = $query->where('status', '=', $selected_status);
        }
        if(isset($selected_month) && $selected_month != '0'){
            $query = $query->whereMonth('task_date','=', $selected_month);
        }
        $all_assigned_tasks = $query->get();
        
        //for chart data
        $query = DB::table('tasks_by_pic')
            ->select('task_date', 'pic', 'pic_userid', DB::raw('SUM(total_tasks) as total_tasks'), 
            DB::raw('SUM(on_progress) as on_progress'), DB::raw('SUM(approved) as approved'), 
            DB::raw('SUM(done) as done'), DB::raw('SUM(rejected) as rejected'),
            DB::raw('SUM(approved_overdue) as approved_overdue'))
            ->whereBetween('task_date',[$startOfYear, $endOfYear])
            ->whereYear('task_date', $selected_year)
            ->whereIn('pic_userid', $child_lists)
            ->groupby('pic_userid');
        if(isset($pic_userid) && ($pic_userid != '0'))
        {
            $query = $query->where('pic_userid','=', $pic_userid);
        }
        if(isset($selected_status) && $selected_status != ""){
            $query = $query->where('status', '=', $selected_status);
        }
        if(isset($selected_month) && $selected_month != 0){
            $query = $query->whereMonth('task_date','=', $selected_month);
        }
        if(isset($dept_id) && ($dept_id != '0'))
        {
            $query = $query->whereIn('pic_userid', $dept_child_lists);
        }else
            $query = $query->whereIn('pic_userid', $child_lists);

        $all_tasks = $query->get();

        $completed_tasks = [];
        $onprogress_tasks = [];
        $done_tasks = [];
        $rejected_tasks = [];

        foreach($all_tasks as $task){
            array_push($completed_tasks, $task->approved);
            array_push($onprogress_tasks, $task->on_progress);
            array_push($done_tasks, $task->done);
            array_push($rejected_tasks, $task->rejected);
            array_push($child_name_lists, $task->pic);
        }

        return view('la.reports.detail_evaluation_report',[
            'users' => $users,
            'child_name_lists' => $child_name_lists,
            'status_lists' => $status_lists,
            'month_lists' => $month_lists,
            'departments' => $departments,
            'all_assigned_tasks' => $all_assigned_tasks,
            'user_id' => $pic_userid,
            'selected_month' => $selected_month,
            'selected_year' => $selected_year,
            'selected_status' => $selected_status,
            'completed_tasks' => $completed_tasks,
            'onprogress_tasks' => $onprogress_tasks,
            'done_tasks' => $done_tasks,
            'rejected_tasks' => $rejected_tasks,
            'dept_id' => $dept_id
        ]);
    }
}