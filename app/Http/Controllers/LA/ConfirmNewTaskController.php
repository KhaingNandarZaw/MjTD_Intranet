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
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
use DB;
use Validator;
use Datatables;
use Collective\Html\FormFacade as Form;
use Dwij\Laraadmin\Models\Module;
use Dwij\Laraadmin\Models\ModuleFields;
use Zizaco\Entrust\EntrustFacade as Entrust;
use Mail;

use App\Models\Create_New_Task;
use App\Mail\ConfirmNewTask;

class ConfirmNewTaskController extends Controller
{
    public $show_action = true;
    
    public function index()
    {
        $module = Module::get('Confirm_New_Tasks');

        if(Entrust::hasRole('SUPER_ADMIN')){
            $requested_tasks = DB::table('create_new_tasks')->where('status', '=', 'Requested')->get();
            $confirmed_tasks = DB::table('create_new_tasks')->where('status', '=', 'Confirmed')->get();
            $rejected_tasks = DB::table('create_new_tasks')->where('status', '=', 'Rejected')->get();
        }else{
            $user_id = Auth::user()->id;
            $child_lists = \Session::get('child_user_lists');
            $requested_tasks = DB::table('create_new_tasks')->where('status', '=', 'Requested')
            ->where(function ($query) use ($child_lists, $user_id) {
                $query->whereIn('pic_user_id', $child_lists);
                // ->orwhere('report_to_userid', $user_id);
            })->get();
            $confirmed_tasks = DB::table('create_new_tasks')->where('status', '=', 'Confirmed')->whereIn('pic_user_id', $child_lists)->get();
            $rejected_tasks = DB::table('create_new_tasks')->where('status', '=', 'Rejected')->whereIn('pic_user_id', $child_lists)->get();
        }

        if(Module::hasAccess($module->id)) {
            return View('la.confirm_new_tasks.index', [
                'show_actions' => $this->show_action,
                'requested_tasks' => $requested_tasks,
                'confirmed_tasks' => $confirmed_tasks,
                'rejected_tasks' => $rejected_tasks,
                'module' => $module
            ]);
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    public function show($id)
    {
        if(Module::hasAccess("Confirm_New_Tasks", "view")) {
            
            $create_new_task = Create_New_Task::find($id);
            if(isset($create_new_task->id)) {
                $module = Module::get('Create_New_Tasks');
                $module->row = $create_new_task;
                
                return view('la.confirm_new_tasks.show', [
                    'module' => $module,
                    'view_col' => $module->view_col,
                    'no_header' => true,
                    'no_padding' => "no-padding"
                ])->with('create_new_task', $create_new_task);
            } else {
                return view('errors.404', [
                    'record_id' => $id,
                    'record_name' => ucfirst("create_new_task"),
                ]);
            }
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }

    public function reject(Request $request){
        $today = date('Y-m-d H:i:s');
        $id = $request->input('task_id');
        $remark = $request->input('remark');
        
        DB:: table('create_new_tasks')->where('id', $id)->update(['status' => 'Rejected', 'rejected_date' => $today, 'rejected_by' => Auth::user()->id, 'remark' => $remark]);

        return redirect(config('laraadmin.adminRoute') . "/confirm_new_tasks");
    }
    
    public function confirm(Request $request){
        $today = date('Y-m-d H:i:s');
        $id = $request->input('task_id');
        $remark = $request->input('remark');
        
        DB:: table('create_new_tasks')->where('id', $id)->update(['status' => 'Confirmed', 'confirmed_date' => $today, 'confirmed_by' => Auth::user()->id, 'remark' => $remark]);

        $create_new_task = DB::table('create_new_tasks')->where('id', $id)->whereNull('deleted_at')->first();
        
        $user = DB::table('users')->where('id', $create_new_task->pic_user_id)->first();
        $subject = "New Task Approved by " . Auth::user()->name;
        $to = $user->email;
        $task_title = $create_new_task->name;
        $report_to_user = DB::table('users')->where('id', $create_new_task->confirmed_by)->first();
        $pic = $user->name;
        $reportTo = $report_to_user->name;

        Mail::to($to)->send(new ConfirmNewTask($task_title, $pic, $reportTo, $subject));

        if($request->has('use_sop')) {
            $sop_setup_id = DB::table('sop_setups')->insertGetId([
                "created_at" => $today,
                "work_description" => $create_new_task->name,
                "timeframe" => $create_new_task->time_frame,
                "pic_userid" => $create_new_task->pic_user_id,
                "dayofweek" => $create_new_task->dayofweek,
                "monthly_dayofweek" => $create_new_task->monthly_dayofweek,
                "monthly_type" => $create_new_task->monthly_type,
                "day" => $create_new_task->day,
                "week" => $create_new_task->week,
                "every_interval" => $create_new_task->every_interval,
                "report_to_userid" => $create_new_task->report_to_userid,
                "remark" => $create_new_task->remark,
                'start_date' => $create_new_task->start_date
            ]);

            $reportTo_userid = $create_new_task->report_to_userid;
            $insert = DB::table('sop_acknowledgeto_users')->insertGetId([
                "created_at" => $today,
                "reportTo_user_id" => $reportTo_userid,
                "sop_setup_id" => $sop_setup_id
            ]);
        } else {
            if(isset($create_new_task)){

                $inserted_id = DB::table('tasks')->insertGetId([
                    "created_at" => $today,
                    "name" => $create_new_task->name,
                    "description" => $create_new_task->description,
                    "priority" => $create_new_task->priority,
                    "time_frame" => $create_new_task->time_frame,
                    "due_date" => $create_new_task->due_date,
                    "dayofweek" => $create_new_task->dayofweek,
                    "monthly_dayofweek" => $create_new_task->monthly_dayofweek,
                    "monthly_type" => $create_new_task->monthly_type,
                    "day" => $create_new_task->day,
                    "week" => $create_new_task->week,
                    "start_date" => $create_new_task->start_date,
                    "every_interval" => $create_new_task->every_interval,
                    "termination_date" => $create_new_task->termination_date,
                    "created_by" => $create_new_task->pic_user_id,
                    "report_to_userid" => $create_new_task->report_to_userid,
                    "pic_userid" => $create_new_task->pic_user_id,
                    "remark" => $create_new_task->remark,
                    "attachments" => $create_new_task->attachments
                ]);

                // $insert = DB::table('task_pics')->insertGetId([
                //     "created_at" => $today,
                //     "pic_userid" => $create_new_task->pic_user_id,
                //     "task_id" => $inserted_id,
                //     "description" => ''
                // ]);
            }
        }
        return redirect(config('laraadmin.adminRoute') . "/confirm_new_tasks");
    }
}
