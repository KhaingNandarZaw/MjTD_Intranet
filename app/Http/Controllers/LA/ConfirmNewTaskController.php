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

use App\Models\Create_New_Task;

class ConfirmNewTaskController extends Controller
{
    public $show_action = true;
    
    public function index()
    {
        $module = Module::get('Confirm_New_Tasks');

        $requested_tasks = DB::table('create_new_tasks')->where('status', '=', 'Requested')->where('report_to_userid', Auth::user()->id)->get();

        $confirmed_tasks = DB::table('create_new_tasks')->where('status', '=', 'Confirmed')->where('report_to_userid', Auth::user()->id)->get();

        $rejected_tasks = DB::table('create_new_tasks')->where('status', '=', 'Rejected')->where('report_to_userid', Auth::user()->id)->get();

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
        $today = date('Y-m-d h:i:s');
        $id = $request->input('task_id');
        $remark = $request->input('remark');
        
        DB:: table('create_new_tasks')->where('id', $id)->update(['status' => 'Rejected', 'rejected_date' => $today, 'rejected_by' => Auth::user()->id, 'remark' => $remark]);

        return redirect(config('laraadmin.adminRoute') . "/confirm_new_tasks");
    }
    
    public function confirm(Request $request){
        if($request->has('use_sop')) {
            return 'on';
        } else {
            $today = date('Y-m-d h:i:s');
            $id = $request->input('task_id');
            $remark = $request->input('remark');
            
            DB:: table('create_new_tasks')->where('id', $id)->update(['status' => 'Confirmed', 'confirmed_date' => $today, 'confirmed_by' => Auth::user()->id, 'remark' => $remark]);

            $create_new_task = DB::table('create_new_tasks')->where('id', $id)->whereNull('deleted_at')->first();
            
            if(isset($create_new_task)){

                $inserted_id = DB::table('tasks')->insertGetId([
                    "created_at" => $today,
                    "name" => $create_new_task->name,
                    "description" => $create_new_task->description,
                    "priority" => $create_new_task->priority,
                    "time_frame" => $create_new_task->time_frame,
                    "due_date" => $create_new_task->due_date,
                    "dayofweek" => $create_new_task->dayofweek,
                    "monthly_type" => $create_new_task->monthly_type,
                    "day" => $create_new_task->day,
                    "week" => $create_new_task->week,
                    "start_date" => $create_new_task->start_date,
                    "every_interval" => $create_new_task->every_interval,
                    "termination_date" => $create_new_task->termination_date,
                    "created_by" => Auth::user()->id,
                    "report_to_userid" => $create_new_task->report_to_userid
                ]);

                $insert = DB::table('task_pics')->insertGetId([
                    "created_at" => $today,
                    "pic_userid" => $create_new_task->created_by,
                    "task_id" => $inserted_id,
                    "description" => ''
                ]);
            }
        }
        return redirect(config('laraadmin.adminRoute') . "/confirm_new_tasks");
    }
}
