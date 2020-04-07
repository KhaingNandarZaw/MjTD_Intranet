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
use App\Models\Announcement;
use Zizaco\Entrust\EntrustFacade as Entrust;
use GuzzleHttp\Client;

/**
 * Class DashboardController
 * @package App\Http\Controllers
 */
class DashboardController extends Controller
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
    public function index()
    {
        $month_number_lists = [];
        $month_name_lists = [];
        $on_progress_lists = [];
        $overdue_lists = [];
        $completed_overdue_lists = [];
        $approved_lists = [];

        $users = [];
        $user_name_lists = [];
        $complete_percentage_lists = [];
        $child_lists = [];

        $total_tasks = [];
        $on_progress = [];
        $done = [];
        $approved = [];
        $rejected = [];
        $over_due = [];
        $on_progress_overdue = [];

        $today = date("Y-m-d");
        $start_date = Carbon::now()->startOfMonth()->toDateString();
        $end_date = Carbon::now()->endOfMonth()->toDateString();

        $date = Carbon::now();
        $current_user_id = Auth::user()->id;
        $system_permission = DB::table('system_permissions')->where('user_id', $current_user_id)->first();

        $startOfYear = $date->copy()->startOfYear()->toDateString();
        $endOfYear   = $date->copy()->endOfYear()->toDateString();

        $interval = CarbonPeriod::create($startOfYear,'1 month', $endOfYear);

        foreach ($interval as $dt) {
            array_push($month_name_lists, $dt->format("M"));
            array_push($month_number_lists, $dt->format("m"));
        }

        if(Entrust::hasRole("EMPLOYEE")){
            foreach($month_number_lists as $month_number){
                $all_onprogress_tasks= DB::table('all_tasks')
                    ->whereBetween('all_tasks.task_date',[$startOfYear, $endOfYear])
                    ->whereMonth('all_tasks.task_date', $month_number)
                    ->where('status', '=', 'On Progress')
                    ->where('pic_userid', $current_user_id)
                    ->count();
                array_push($on_progress_lists, $all_onprogress_tasks);

                $all_approved_tasks = DB::table('all_tasks')
                    ->whereBetween('all_tasks.task_date',[$startOfYear, $endOfYear])
                    ->where('status', '=', 'Approved')
                    ->whereMonth('all_tasks.task_date', $month_number)
                    ->where('pic_userid', $current_user_id)
                    ->count();            
                array_push($approved_lists, $all_approved_tasks);

                $all_overdue_tasks = DB::table('all_tasks')
                    ->whereBetween('all_tasks.task_date',[$startOfYear, $endOfYear])
                    ->where('status', '=', 'On Progress')
                    ->whereMonth('all_tasks.task_date', $month_number)
                    ->whereDate('task_date', '<', $today)
                    ->where('pic_userid', $current_user_id)
                    ->count();
                array_push($overdue_lists, $all_overdue_tasks);

                $all_completed_over_tasks = DB::table('all_tasks')
                    ->whereBetween('all_tasks.task_date',[$startOfYear, $endOfYear])
                    ->where('status', '=', 'Approved')
                    ->whereMonth('all_tasks.task_date', $month_number)
                    ->where('task_date', '<', DB::raw("(DATE_FORMAT(done_date, '%Y-%m-%d'))"))
                    ->where('pic_userid', $current_user_id)
                    ->count();
                array_push($completed_overdue_lists, $all_completed_over_tasks);
            }

            //get tasks for performance section
            $total_tasks = DB::table('all_tasks')->whereBetween('task_date', [$start_date, $end_date])->where('pic_userid', $current_user_id)->get();
            $on_progress = DB::table('all_tasks')->where('status', '=', 'On Progress')->whereBetween('task_date', [$start_date, $end_date])->where('pic_userid', $current_user_id)->get();
            $done = DB::table('all_tasks')->where('status', '=', 'Done')->where('pic_userid', $current_user_id)->whereBetween('task_date', [$start_date, $end_date])->get();
            $approved = DB::table('all_tasks')->where('status', '=', 'Approved')->where('pic_userid', $current_user_id)->whereBetween('task_date', [$start_date, $end_date])->get();
            $rejected = DB::table('all_tasks')->where('status', '=', 'Rejected')->where('pic_userid', $current_user_id)->whereBetween('task_date', [$start_date, $end_date])->get();
            $over_due = DB::table('all_tasks')->where('status', '=', 'On Progress')->whereDate('task_date', '<', $today)->whereBetween('task_date', [$start_date, $end_date])->where('pic_userid', $current_user_id)->get();
        }

        $childs = DB::select('call getAllChildUsers(?)', array(Auth::user()->department));
        for ($i=0; $i < count($childs); $i++) { 
            if($childs[$i]->id != Auth::user()->id && $childs[$i]->id != 1)
                array_push($users, $childs[$i]);
        }
        for ($i=0; $i < count($users); $i++) { 
            array_push($child_lists, $users[$i]->id);
        }
        \Session::put('child_user_lists', $child_lists);

        if(Entrust::hasRole("OFFICER") || Entrust::hasRole("SUPER_ADMIN")){
            
            $all_assigned_tasks = DB::table('tasks_by_pic')
            ->select('task_date', 'pic', 'pic_userid', DB::raw('SUM(total_tasks) as total_tasks'), 
            DB::raw('SUM(on_progress) as on_progress'), DB::raw('SUM(approved) as approved'), 
            DB::raw('SUM(approved_overdue) as approved_overdue'), DB::raw('SUM(on_progress_overdue) as on_progress_overdue'),
            DB::raw('SUM(done) as done'))
            ->whereBetween('task_date', [$start_date, $end_date])
            ->whereBetween('task_date',[$startOfYear, $endOfYear])
            ->whereIn('pic_userid', $child_lists)
            ->groupby('pic_userid')->get();

            //for percentage chart
            foreach($all_assigned_tasks as $assigned_task){
                if($assigned_task->approved != 0 && $assigned_task->total_tasks != 0){
                    $assigned_task->complete_percentage = number_format(($assigned_task->approved / $assigned_task->total_tasks)* 100, 0) ;
                } else {
                    $assigned_task->complete_percentage = 0;
                }
                array_push($user_name_lists, $assigned_task->pic);
                array_push($complete_percentage_lists, $assigned_task->complete_percentage);
                array_push($on_progress, $assigned_task->on_progress);
                array_push($approved, $assigned_task->approved);
                array_push($over_due, $assigned_task->approved_overdue);
                array_push($done, $assigned_task->done);
                array_push($on_progress_overdue, $assigned_task->on_progress_overdue);
            }
        }
        
        // get announcements data for announcement section
        $announcements = Announcement::whereDate('startdate', '<=', $today)->whereDate('enddate',   '>=', $today)->whereNull('deleted_at')->get();

        try{
            $client = new Client();
            $response = $client->request('GET', 'https://cloud.acedatasystems.com:8028/api/smarthr/gettodayleaves');
            $statusCode = $response->getStatusCode();
            $leave_ppl_lists = json_decode($response->getBody()->getContents(), true);
        }catch(Exception $e){
            $leave_ppl_lists = [];
        }

        return view('la.dashboard',[
            'total_tasks' => $total_tasks,
            'announcements' => $announcements,
            'on_progress' => $on_progress,
            'done' => $done,
            'approved' => $approved,
            'over_due' => $over_due,
            'rejected' => $rejected,
            'month_number_lists' => $month_number_lists, // Jan, Feb
            'month_name_lists' => $month_name_lists, // 01, 02
            'approved_lists' => $approved_lists,
            'on_progress_lists' => $on_progress_lists,
            'overdue_lists' => $overdue_lists,
            'completed_overdue_lists' => $completed_overdue_lists,
            'complete_percentage_lists' => $complete_percentage_lists,
            'user_name_lists' => $user_name_lists,
            'on_progress_overdue' => $on_progress_overdue,
            'users' => $users,
            'leave_ppl_lists' => $leave_ppl_lists,
            'system_permission' => $system_permission
        ]);
    }
}