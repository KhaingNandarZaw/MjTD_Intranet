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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use Auth;
use DB;
use Validator;
use Datatables;
use Collective\Html\FormFacade as Form;
use Dwij\Laraadmin\Models\Module;
use Zizaco\Entrust\EntrustFacade as Entrust;
use Dwij\Laraadmin\Models\ModuleFields;
use Illuminate\Support\Facades\Response as FacadeResponse;

use Mail;
use App\Models\Task_Instance;
use App\Models\User;
use App\Mail\ActionByOfficer;
use App\Mail\SentToOfficer;
use App\Mail\ExtendDueDate;

use File;
use Carbon\Carbon;

class Task_InstancesController extends Controller
{
    public $show_action = true;
    
    /**
     * Display a listing of the Task_Instances.
     *
     * @return mixed
     */
    public function index()
    {
        if(Module::hasAccess("Task_Instances", "edit")) {
            $module = Module::get('Task_Instances');
            $query = "";
            $selected_status = "0";
            $task_type = "0";
            $today = date("Y-m-d");
            $input_from_date = '';
            $input_to_date = '';

            $from_date = Carbon::now()->startOfMonth()->toDateString();
            $to_date = Carbon::now()->endOfMonth()->toDateString();
            $input_from_date = Carbon::createFromFormat('Y-m-d', $from_date)->format('d/m/Y');
            $input_to_date = Carbon::createFromFormat('Y-m-d', $to_date)->format('d/m/Y');
            
            //list of status
            $status_lists = array('On Progress', 'On Progress(Overdue)', 'Done', 'Approved', 'Approved(Overdue)', 'Rejected', 'Cancel');

            if(Auth::user()->id == 1){
                $all_tasks = DB::table('all_tasks')->get();
                $this->show_action = false;
                $sql = "select sop_setups.id, sop_setups.work_description, frames.name as time_frame, pic_userid
                from sop_setups left join frames on frames.id = sop_setups.timeframe
                where sop_setups.deleted_at is null and frames.deleted_at is null and frames.use_task = 0";
                $query = DB::table(DB::raw("($sql)"));
                $sop_lists = $query->get();
            }else {
                $all_tasks = DB::table('all_tasks')->where('pic_userid', '=', Auth::user()->id)->get();
                $this->show_action = true;
                $sql = "select sop_setups.id, sop_setups.work_description, frames.name as time_frame, pic_userid
                from sop_setups left join frames on frames.id = sop_setups.timeframe
                where sop_setups.deleted_at is null and frames.deleted_at is null and frames.use_task = 0 ";
                $query = DB::table(DB::raw("($sql) as catch"))->where('pic_userid', Auth::user()->id);
                $sop_lists = $query->get();
            }
        
            return View('la.task_instances.index', [
                'show_actions' => $this->show_action,
                'listing_cols' => Module::getListingColumns('Task_Instances'),
                'module' => $module,
                'all_tasks' => $all_tasks,
                'sop_lists' => $sop_lists,
                'status_lists' => $status_lists,
                'selected_status' => $selected_status,
                'task_type' => $task_type,
                'from_date' => $input_from_date,
                'to_date' => $input_to_date
            ]);
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    public function my_tasks(Request $request)
    {
        if(Module::hasAccess("Task_Instances", "edit")) {
            $module = Module::get('Task_Instances');
            $query = "";
            $selected_status = "On Progress";
            $task_type = "0";
            $today = date("Y-m-d");
            $input_from_date = '';
            $input_to_date = '';

            $from_date = Carbon::now()->startOfMonth()->toDateString();
            $to_date = Carbon::now()->endOfMonth()->toDateString();
            $input_from_date = Carbon::createFromFormat('Y-m-d', $from_date)->format('d/m/Y');
            $input_to_date = Carbon::createFromFormat('Y-m-d', $to_date)->format('d/m/Y');

            //list of status
            $status_lists = array('On Progress', 'On Progress(Overdue)', 'Done', 'Approved', 'Approved(Overdue)', 'Rejected', 'Cancel');

            //filter
            if($request->has('status')){
                $selected_status = $request->input('status');
            }
            if($request->has('task_type')){
                $task_type = $request->input('task_type');
            }
            if($request->has('from_date') && $request->input('from_date') != ''){
                $input_from_date = $request->input('from_date');
                $from_date = Carbon::createFromFormat('d/m/Y', $input_from_date)->format('Y-m-d');
            }
            if($request->has('to_date') && $request->input('to_date') != ''){
                $input_to_date = $request->input('to_date');
                $to_date = Carbon::createFromFormat('d/m/Y', $input_to_date)->format('Y-m-d');
            }
            if(Auth::user()->id == 1){
                $sql = "select sop_setups.id, sop_setups.work_description, frames.name as time_frame, pic_userid
                from sop_setups left join frames on frames.id = sop_setups.timeframe
                where sop_setups.deleted_at is null and frames.deleted_at is null and frames.use_task = 0";
                $query = DB::table(DB::raw("($sql)"));
                $sop_lists = $query->get();

                $query = DB::table('all_tasks');
                $this->show_action = false;
            }else {
                $sql = "select sop_setups.id, sop_setups.work_description, frames.name as time_frame, pic_userid
                from sop_setups left join frames on frames.id = sop_setups.timeframe
                where sop_setups.deleted_at is null and frames.deleted_at is null and frames.use_task = 0 ";
                $query = DB::table(DB::raw("($sql) as catch"))->where('pic_userid', Auth::user()->id);
                $sop_lists = $query->get();

                $query = DB::table('all_tasks')->where('pic_userid', '=', Auth::user()->id);
                $this->show_action = true;
            }

            if(isset($selected_status) && $selected_status != "0"){
                if($selected_status == 'On Progress(Overdue)')
                    $query = $query->where('status', '=', 'On Progress')->whereDate('task_date', '<', $today);
                else if($selected_status == 'Approved(Overdue)')
                    $query = $query->where('status', '=', 'Approved')->whereDate('task_date', '>', 'approved_date');
                else
                    $query = $query->where('status', '=', $selected_status);
            }
            if(isset($task_type) && $task_type != "0"){
                $query = $query->where('task_type', '=', $task_type);
            }
            if(isset($from_date) && $from_date != ""){
                $query = $query->whereDate('task_date', '>=', $from_date);
            }
            if(isset($to_date) && $to_date != ""){
                $query = $query->whereDate('task_date', '<=', $to_date);
            }

            $all_tasks = $query->get();
        
            return View('la.task_instances.index', [
                'show_actions' => $this->show_action,
                'listing_cols' => Module::getListingColumns('Task_Instances'),
                'module' => $module,
                'all_tasks' => $all_tasks,
                'sop_lists' => $sop_lists,
                'status_lists' => $status_lists,
                'selected_status' => $selected_status,
                'task_type' => $task_type,
                'from_date' => $input_from_date,
                'to_date' => $input_to_date
            ]);
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    public function task_checking(Request $request){
        $users = [];
        $pic_userid = 0;
        $selected_status = "Done";
        $task_type = "0";
        $today = date("Y-m-d");
        $input_from_date = '';
        $input_to_date = '';

        $from_date = Carbon::now()->startOfMonth()->toDateString();
        $to_date = Carbon::now()->endOfMonth()->toDateString();
        $input_from_date = Carbon::createFromFormat('Y-m-d', $from_date)->format('d/m/Y');
        $input_to_date = Carbon::createFromFormat('Y-m-d', $to_date)->format('d/m/Y');

        //filter
        if($request->has('user_id')){
            $pic_userid = $request->input('user_id');
        }
        if($request->has('status')){
            $selected_status = $request->input('status');
        }
        if($request->has('task_type')){
            $task_type = $request->input('task_type');
        }
        if($request->has('from_date') && $request->input('from_date') != ''){
            $input_from_date = $request->input('from_date');
            $from_date = Carbon::createFromFormat('d/m/Y', $input_from_date)->format('Y-m-d');
        }
        if($request->has('to_date') && $request->input('to_date') != ''){
            $input_to_date = $request->input('to_date');
            $to_date = Carbon::createFromFormat('d/m/Y', $input_to_date)->format('Y-m-d');
        }
        
        //list of status
        $status_lists = array('On Progress', 'On Progress(Overdue)', 'Done', 'Approved', 'Approved(Overdue)', 'Rejected', 'Cancel');

        if(Module::hasAccess("Task_Instances", "edit")) {
            $module = Module::get('Task_Instances');

            if(Entrust::hasRole("SUPER_ADMIN") || Entrust::hasRole("CEO")){
                $query = DB::table('all_tasks');
                $users = User::whereNull('deleted_at')->where('id', '!=', 1)->get();
            }else if(Entrust::hasRole("EMPLOYEE")) {
                $query = DB::table('all_tasks')->where('report_to_userid', '=', Auth::user()->id);
                
            }else if(Entrust::hasRole("OFFICER") || Entrust::hasRole("DGM")){
                $child_lists = \Session::get('child_user_lists');
                $childs = DB::select('call getAllChildUsers(?)', array(Auth::user()->department));

                for ($i=0; $i < count($childs); $i++) { 
                    if($childs[$i]->id != Auth::user()->id)
                        array_push($users, $childs[$i]);
                }
                
                $query = DB::table('all_tasks')->where(function ($query) use ($child_lists) {
                    $query->whereIn('pic_userid', $child_lists)
                    ->orwhere('report_to_userid', Auth::user()->id);
                });
            }

            if(isset($pic_userid) && ($pic_userid != '0'))
            {
                $query = $query->where('pic_userid','=', $pic_userid);
            }
            if(isset($selected_status) && $selected_status != "0"){
                if($selected_status == 'On Progress(Overdue)')
                    $query = $query->where('status', '=', 'On Progress')->whereDate('task_date', '<', $today);
                else if($selected_status == 'Approved(Overdue)')
                    $query = $query->where('status', '=', 'Approved')->whereDate('task_date', '>', 'approved_date');
                else
                    $query = $query->where('status', '=', $selected_status);
            }
            if(isset($task_type) && $task_type != "0"){
                $query = $query->where('task_type', '=', $task_type);
            }
            if(isset($from_date) && $from_date != ""){
                $query = $query->whereDate('task_date', '>=', $from_date);
            }
            if(isset($to_date) && $to_date != ""){
                $query = $query->whereDate('task_date', '<=', $to_date);
            }
            $all_tasks = $query->get();
        
            return View('la.task_instances.task_checking', [
                'show_actions' => $this->show_action,
                'listing_cols' => Module::getListingColumns('Task_Instances'),
                'module' => $module,
                'all_tasks' => $all_tasks,
                'users' => $users,
                'pic_userid' => $pic_userid,
                'status_lists' => $status_lists,
                'selected_status' => $selected_status,
                'task_type' => $task_type,
                'from_date' => $input_from_date,
                'to_date' => $input_to_date
            ]);
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Show the form for creating a new task_instance.
     *
     * @return mixed
     */
    public function create()
    {
        //
    }
    
    /**
     * Store a newly created task_instance in database.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        if(Module::hasAccess("Task_Instances", "edit")) {
            
            $rules = Module::validateRules("Task_Instances", $request);
            
            $validator = Validator::make($request->all(), $rules);
            
            if($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            
            $insert_id = Module::insert("Task_Instances", $request);
            
            return redirect()->route(config('laraadmin.adminRoute') . '.task_instances.index');
            
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    public function report_to_officer(Request $request){
        if(Module::hasAccess("Task_Instances", "edit")) {
            
            $rules = Module::validateRules("Task_Instances", $request);
            $today = date('Y-m-d h:i:s');

            $sop_id = $request->input('sop_id');
            $remark = $request->input('remark');
            
            $sop_setup = DB:: table('sop_setups')->where('id', $sop_id)->first();
            
            if(isset($sop_setup)){
                $task_instance_inserted_id = DB::table('task_instances')->insertGetId([
                    "created_at" => $today,
                    "task_id" => $sop_setup->id,
                    "task_userid" => Auth::user()->id,
                    "status" => 'Done',
                    'done_date' => $today,
                    'task_date' => $today,
                    'task_type' => 'SOP',
                    'report_to_userid' => $sop_setup->report_to_userid,
                    'timeframe' => $sop_setup->timeframe
                ]);
    
                $inserted_id = DB::table('task_remarks')->insertGetId([
                    "created_at" => $today,
                    "task_instance_id" => $task_instance_inserted_id,
                    "remark" => $remark,
                    "user_id" => Auth::user()->id,
                    "status" => 'Done'
                ]);
                if($request->hasFile('complete_files')) {
                    $files = $request->file('complete_files');
                    
                    foreach($files as $file){
                        $folder = storage_path('uploads');
                        $filename = $file->getClientOriginalName();
            
                        $date_append = date("Y-m-d-His-");
                        $fileContent = file_get_contents($file->getRealPath());
                        $data = base64_encode($fileContent);
                        
                        if( $data != null ) {
                            $insertedID = DB::table('task_files')->insertGetId([
                                "task_instance_id" => $task_instance_inserted_id,
                                "created_at" => $today,
                                "filename" => $filename,
                                "extension" => pathinfo($filename, PATHINFO_EXTENSION),
                                "hash" => "",
                                "task_files" => $data,
                                'task_remark_id' => $inserted_id
                            ]);
                            // apply unique random hash to file
                            while(true) {
                                $hash = strtolower(str_random(20));
                                if(!DB::table('task_files')->where("hash", $hash)->count()){
                                    $upload = DB::table('task_files')->where('id', $insertedID)->update(['hash' => $hash]);
                                    break;
                                }
                            }
                            $upload = DB::table('task_files')->where('id', $insertedID)->first();
                        } 
                    }
                } 
            }
            $user = DB::table('users')->where('id', $sop_setup->report_to_userid)->first();
            $subject = "Report From " . Auth::user()->name;
            $to = $user->email;
            $task_title = $sop_setup->work_description;
            $pic_user = DB::table('users')->where('id', $sop_setup->pic_userid)->first();
            $pic = $pic_user->name;
            $reportTo = $user->name;
            $files = $request->complete_files;
            $cc_array = $request->cc_users;

            Mail::to($to)->send(new SentToOfficer($task_title, $pic, $reportTo, $files, $cc_array, $subject));
            
            // try {
            //     return response()->json("Email Sent!");
            // } catch (\Exception $e) {
            //     return response()->json($e->getMessage());
            // }
            return redirect(config('laraadmin.adminRoute') . "/task_instances");
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    public function sent_to_officer(Request $request){
        if(Module::hasAccess("Task_Instances", "edit")) {
            
            $rules = Module::validateRules("Task_Instances", $request);
            $today = date('Y-m-d h:i:s');

            $task_instance_id = $request->input('task_instance_id');
            $remark = $request->input('remark');
            
            DB:: table('task_instances')->where('id', $task_instance_id)->update(['done_date' => $today, 'status' => 'Done']);
            
            $inserted_id = DB::table('task_remarks')->insertGetId([
                "created_at" => $today,
                "task_instance_id" => $task_instance_id,
                "remark" => $remark,
                "user_id" => Auth::user()->id,
                "status" => 'Done'
            ]);
            
            if($request->hasFile('complete_files')) {
				$files = $request->file('complete_files');
                
                foreach($files as $file){
                    $folder = storage_path('uploads');
                    $filename = $file->getClientOriginalName();
        
                    $date_append = date("Y-m-d-His-");
                    $fileContent = file_get_contents($file->getRealPath());
                    $data = base64_encode($fileContent);
                    
                    if( $data != null ) {
                        $insertedID = DB::table('task_files')->insertGetId([
                            "task_instance_id" => $task_instance_id,
                            "created_at" => $today,
                            "filename" => $filename,
                            "extension" => pathinfo($filename, PATHINFO_EXTENSION),
                            "hash" => "",
                            "task_files" => $data,
                            'task_remark_id' => $inserted_id
                        ]);
                        // apply unique random hash to file
                        while(true) {
                            $hash = strtolower(str_random(20));
                            if(!DB::table('task_files')->where("hash", $hash)->count()){
                                $upload = DB::table('task_files')->where('id', $insertedID)->update(['hash' => $hash]);
                                break;
                            }
                        }
                        //$upload = DB::table('task_files')->where('id', $insertedID)->first();
                    }
                }
				 
            } 
            
            $task = DB::table('all_tasks')->where('id', $task_instance_id)->first();

            $user = DB::table('users')->where('id', $task->report_to_userid)->first();

            $subject = "Report From " . Auth::user()->name;
            $to = $user->email;
            $task_title = $task->name;
            $pic = $task->pic;
            $reportTo = $task->reportTo;
            $files = $request->complete_files;
            $cc_array = $request->cc_users;

            Mail::to($to)->send(new SentToOfficer($task_title, $pic, $reportTo, $files, $cc_array, $subject));
            
            // try {
            //     return response()->json("Email Sent!");
            // } catch (\Exception $e) {
            //     return response()->json($e->getMessage());
            // }
            return redirect(config('laraadmin.adminRoute') . "/task_instances");
            
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    public function cancel_task(Request $request){
        if(Module::hasAccess("Task_Instances", "edit")) {
            
            $rules = Module::validateRules("Task_Instances", $request);
            $today = date('Y-m-d h:i:s');

            $task_instance_id = $request->input('task_instance_id');
            $remark = $request->input('remark');
            
            DB:: table('task_instances')->where('id', $task_instance_id)->update(['status' => 'Cancel']);
            
            $insert = DB::table('task_remarks')->insertGetId([
                "created_at" => $today,
                "task_instance_id" => $task_instance_id,
                "remark" => $remark,
                "user_id" => Auth::user()->id,
                "status" => 'Cancel'
            ]);
            
            // sent email
            $task = DB::table('all_tasks')->where('id', $task_instance_id)->first();
            $user = DB::table('users')->where('id', $task->pic_userid)->first();
            $subject = "Cancel Task '" . $task->name . "' By " . Auth::user()->name;
            $to = $user->email;
            $task_title = $task->name;
            $pic = $task->pic;
            $action_by = Auth::user()->name;
            $status = 'canceled';
            Mail::to($to)->send(new ActionByOfficer($task_title, $pic, $action_by, $subject, $status));

            return redirect(config('laraadmin.adminRoute') . "/task_checking");
            
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    public function reassign_pic(Request $request){
        if(Module::hasAccess("Task_Instances", "edit")) {
            
            $rules = Module::validateRules("Task_Instances", $request);
            $today = date('Y-m-d h:i:s');

            $task_instance_id = $request->input('task_instance_id');
            $remark = $request->input('remark');
            $new_pic = $request->input('new_pic');

            $task = DB::table('all_tasks')->where('id', $task_instance_id)->first();
            $old_pic_user = DB::table('users')->where('id', $task->pic_userid)->first();
            $old_pic_name = $old_pic_user->name;
            $old_pic_email = $old_pic_user->email;

            $new_pic_user = DB::table('users')->where('id', $new_pic)->first();
            $new_pic_name = $new_pic_user->name;
            $new_pic_email = $new_pic_user->email;

            $to_emails = [];
            array_push($to_emails, $old_pic_email);
            array_push($to_emails, $new_pic_email);
            
            DB:: table('task_instances')->where('id', $task_instance_id)->update(['task_userid' => $new_pic]);
            
            $insert = DB::table('task_remarks')->insertGetId([
                "created_at" => $today,
                "task_instance_id" => $task_instance_id,
                "remark" => $remark,
                "user_id" => Auth::user()->id,
                "status" => 'Reassign'
            ]);

            // sent email
            $user = DB::table('users')->where('id', $task->pic_userid)->first();
            $subject = "Reassign Task '" . $task->name . "' By " . Auth::user()->name;
            $to = $user->email;
            $task_title = $task->name;
            $pic = $task->pic;
            $action_by = Auth::user()->name;
            $status = 'reassigned';
            Mail::to($to_emails)->send(new ActionByOfficer($task_title, $new_pic_name, $action_by, $subject, $status, $old_pic_name));

            return redirect(config('laraadmin.adminRoute') . "/task_checking");
            
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    public function approved_by_officer(Request $request){
        if(Module::hasAccess("Task_Instances", "edit")) {
            
            $rules = Module::validateRules("Task_Instances", $request);
            $today = date('Y-m-d h:i:s');

            $task_instance_id = $request->input('task_instance_id');
            $remark = $request->input('remark');
            
            DB:: table('task_instances')->where('id', $task_instance_id)->update(['status' => 'Approved', 'approved_by' => Auth::user()->id, 'approved_date' => $today]);
            
            $insert = DB::table('task_remarks')->insertGetId([
                "created_at" => $today,
                "task_instance_id" => $task_instance_id,
                "remark" => $remark,
                "user_id" => Auth::user()->id,
                "status" => 'Approved'
            ]);
            
            // sent email
            $task = DB::table('all_tasks')->where('id', $task_instance_id)->first();
            $user = DB::table('users')->where('id', $task->pic_userid)->first();
            $subject = "Approved Task '" . $task->name . "' By " . Auth::user()->name;
            $to = $user->email;
            $task_title = $task->name;
            $pic = $task->pic;
            $action_by = Auth::user()->name;
            $status = 'Approved';
            Mail::to($to)->send(new ActionByOfficer($task_title, $pic, $action_by, $subject, $status));

            return redirect(config('laraadmin.adminRoute') . "/task_checking");
            
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    public function rejected_by_officer(Request $request){
        if(Module::hasAccess("Task_Instances", "edit")) {
            
            $rules = Module::validateRules("Task_Instances", $request);
            $today = date('Y-m-d h:i:s');

            $task_instance_id = $request->input('task_instance_id');
            $remark = $request->input('remark');
            
            DB:: table('task_instances')->where('id', $task_instance_id)->update(['rejected_date' => $today, 'status' => 'Rejected', 'rejected_by' => Auth::user()->id]);
            
            $insert = DB::table('task_remarks')->insertGetId([
                "created_at" => $today,
                "task_instance_id" => $task_instance_id,
                "remark" => $remark,
                "user_id" => Auth::user()->id,
                "status" => 'Rejected'
            ]);
            
            // sent email
            $task = DB::table('all_tasks')->where('id', $task_instance_id)->first();
            $user = DB::table('users')->where('id', $task->pic_userid)->first();
            $subject = "Rejected Task '" . $task->name . "' By " . Auth::user()->name;
            $to = $user->email;
            $task_title = $task->name;
            $pic = $task->pic;
            $action_by = Auth::user()->name;
            $status = 'Rejected';
            Mail::to($to)->send(new ActionByOfficer($task_title, $pic, $action_by, $subject, $status));
            
            return redirect(config('laraadmin.adminRoute') . "/task_checking");
            
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    public function extend_duedate(Request $request){
        $rules = Module::validateRules("Task_Instances", $request);
        $today = date('Y-m-d h:i:s');
        
        $task_instance_id = $request->input('task_instance_id');
        $remark = $request->input('remark');
        $extend_date = strtr($request->input('extend_date'), '/', '-');
        $extend_due_date = date('Y-m-d',strtotime($extend_date));

        // sent email
        $task = DB::table('all_tasks')->where('id', $task_instance_id)->first();
        $user = DB::table('users')->where('id', $task->pic_userid)->first();
        $subject = "Extended Due Date of Task '" . $task->name . "' By " . Auth::user()->name;
        $to = $user->email;
        $task_title = $task->name;
        $pic = $task->pic;
        $action_by = Auth::user()->name;
        $task_date = $task->task_date;
        Mail::to($to)->send(new ExtendDueDate($task_title, $pic, $action_by, $subject, $task_date, $extend_due_date));
        
        DB:: table('task_instances')->where('id', $task_instance_id)->update(['task_date' => $extend_due_date]);
        
        $insert = DB::table('task_remarks')->insertGetId([
            "created_at" => $today,
            "task_instance_id" => $task_instance_id,
            "remark" => $remark,
            "user_id" => Auth::user()->id,
            "status" => 'Extended Due Date'
        ]);
        
        return redirect(config('laraadmin.adminRoute') . "/task_checking");
    }
    /**
     * Display the specified task_instance.
     *
     * @param int $id task_instance ID
     * @return mixed
     */
    public function show($id)
    {
        if(Module::hasAccess("Task_Instances", "edit")) {
            
            // $task_instance = Task_Instance::find($id);
            $task_instance = DB::table('all_tasks')->where('id', '=', $id)->first();
            
            $task_remarks = DB::table('task_remarks')
                ->select('task_remarks.*', 'users.name')
                ->join('users', 'users.id', '=', 'task_remarks.user_id')
                ->where('task_instance_id', $id)
                ->whereNull('task_remarks.deleted_at')
                ->whereNull('users.deleted_at')
                ->orderBy('task_remarks.id')
                ->get();
            
                if(isset($task_instance->id)) {
                $module = Module::get('Task_Instances');
                $module->row = $task_instance;
                
                return view('la.task_instances.show', [
                    'module' => $module,
                    'view_col' => $module->view_col,
                    'no_header' => true,
                    'no_padding' => "no-padding",
                    'task_remarks' => $task_remarks
                ])->with('task_instance', $task_instance);
            } else {
                return view('errors.404', [
                    'record_id' => $id,
                    'record_name' => ucfirst("task_instance"),
                ]);
            }
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Show the form for editing the specified task_instance.
     *
     * @param int $id task_instance ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        if(Module::hasAccess("Task_Instances", "edit")) {
            $task_instance = Task_Instance::find($id);
            if(isset($task_instance->id)) {
                $module = Module::get('Task_Instances');
                
                $module->row = $task_instance;
                
                return view('la.task_instances.edit', [
                    'module' => $module,
                    'view_col' => $module->view_col,
                ])->with('task_instance', $task_instance);
            } else {
                return view('errors.404', [
                    'record_id' => $id,
                    'record_name' => ucfirst("task_instance"),
                ]);
            }
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Update the specified task_instance in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id task_instance ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        if(Module::hasAccess("Task_Instances", "edit")) {
            
            $rules = Module::validateRules("Task_Instances", $request, true);
            
            $validator = Validator::make($request->all(), $rules);
            
            if($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();;
            }
            
            $insert_id = Module::updateRow("Task_Instances", $request, $id);
            
            return redirect()->route(config('laraadmin.adminRoute') . '.task_instances.index');
            
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Remove the specified task_instance from storage.
     *
     * @param int $id task_instance ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        if(Module::hasAccess("Task_Instances", "delete")) {
            Task_Instance::find($id)->delete();
            
            // Redirecting to index() method
            return redirect()->route(config('laraadmin.adminRoute') . '.task_instances.index');
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Server side Datatable fetch via Ajax
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function dtajax(Request $request)
    {
        $module = Module::get('Task_Instances');
        $listing_cols = Module::getListingColumns('Task_Instances');
        
        $values = DB::table('task_instances')->select($listing_cols)->whereNull('deleted_at');
        $out = Datatables::of($values)->make();
        $data = $out->getData();
        
        $fields_popup = ModuleFields::getModuleFields('Task_Instances');
        
        for($i = 0; $i < count($data->data); $i++) {
            for($j = 0; $j < count($listing_cols); $j++) {
                $col = $listing_cols[$j];
                if($fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
                    $data->data[$i][$j] = ModuleFields::getFieldValue($fields_popup[$col], $data->data[$i][$j]);
                }
                if($col == $module->view_col) {
                    $data->data[$i][$j] = '<a href="' . url(config('laraadmin.adminRoute') . '/task_instances/' . $data->data[$i][0]) . '">' . $data->data[$i][$j] . '</a>';
                }
                // else if($col == "author") {
                //    $data->data[$i][$j];
                // }
            }
            
            if($this->show_action) {
                $output = '';
                if(Module::hasAccess("Task_Instances", "edit")) {
                    $output .= '<a href="' . url(config('laraadmin.adminRoute') . '/task_instances/' . $data->data[$i][0] . '/edit') . '" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-edit"></i></a>';
                }
                
                if(Module::hasAccess("Task_Instances", "delete")) {
                    $output .= Form::open(['route' => [config('laraadmin.adminRoute') . '.task_instances.destroy', $data->data[$i][0]], 'method' => 'delete', 'style' => 'display:inline']);
                    $output .= ' <button class="btn btn-danger btn-xs" type="submit"><i class="fa fa-times"></i></button>';
                    $output .= Form::close();
                }
                $data->data[$i][] = (string)$output;
            }
        }
        $out->setData($data);
        return $out;
    }
    public function getAttachedFiles($hash, $name)
    {
        $upload = DB::table('task_files')->where("hash", $hash)->first();
        
        // Validate Upload Hash & Filename
        if(!isset($upload->id) || $upload->filename != $name) {
            return response()->json([
                'status' => "failure",
                'message' => "Unauthorized Access 1"
            ]);
        }

        $file_contents = base64_decode($upload->task_files);
        file_put_contents($upload->filename, $file_contents);
        $path = public_path($upload->filename);

        if(!File::exists($path))
            abort(404);

        $file = file_get_contents($path);
        $type = File::mimeType($path);
        
        $download = Input::get('download');
        if(isset($download)) {
            return response()->download($path, $upload->filename);
        } else {
            $response = FacadeResponse::make($file, 200);
            $response->header("Content-Type", $type);
        }
        
        return $response;
    }
    public function fullcalendar()
    {
        if(request()->ajax()) 
        {
            $start = (!empty($_GET["start"])) ? ($_GET["start"]) : ('');
            $end = (!empty($_GET["end"])) ? ($_GET["end"]) : ('');
 
            $task_lists = DB::table('all_tasks')->select('id', 'name as title', 'task_date as start', 'status', 'done_date as done')->where('pic_userid', Auth::user()->id)->whereDate('task_date', '>=', $start)->whereDate('task_date',   '<=', $end)->where('status', '!=', 'Cancel')->get();
            $data = [];
            $current_date = date('Y-m-d');
            for ($i=0; $i < count($task_lists); $i++) { 
                $task = $task_lists[$i];
                $task_date = $task->start;
                if($task_date < $current_date && $task->status == 'On Progress'){
                    $task->className = 'event-full event-overdue';
                }
                if($task->status == 'Approved' || $task->status == 'Done'){
                    $task->className = 'event-full event-finish';
                }
                if($task->status == 'Approved' && ($task->start < date('Y-m-d', strtotime($task->done)))){
                    $task->className = 'event-full event-approved-overdue';
                }
                if($task_date >= $current_date &&$task->status == 'On Progress'){
                    $task->className = 'event-full event-onprogress';
                }
                array_push($data, $task);
            }
            return response()->json($data);
        }
        return view('fullcalender');
    }
    public function fullcalendar_bytask(Request $request){
        if(request()->ajax()) 
        {
            $start = $request->start;
            $end = $request->end;
            $task_id = $request->task_id;
 
            $task_lists = DB::table('all_tasks')->select('id', 'name as title', 'task_date as start', 'status', 'done_date as done')->where('task_id', $task_id)->whereDate('task_date', '>=', $start)->whereDate('task_date',   '<=', $end)->get();
            $data = [];
            $current_date = date('Y-m-d');
            for ($i=0; $i < count($task_lists); $i++) { 
                $task = $task_lists[$i];
                $task_date = $task->start;
                if($task_date < $current_date && $task->status == 'On Progress'){
                    $task->className = 'event-full event-overdue';
                }
                if($task->status == 'Approved' || $task->status == 'Done'){
                    $task->className = 'event-full event-finish';
                }
                if($task->status == 'Cancel'){
                    $task->className = 'event-full event-cancel';
                }
                if($task->status == 'Approved' && ($task->start < date('Y-m-d', strtotime($task->done)))){
                    $task->className = 'event-full event-approved-overdue';
                }
                if($task->status == 'On Progress'){
                    $task->className = 'event-full event-onprogress';
                }
                array_push($data, $task);
            }
            return response()->json($data);
        }
        return view('fullcalender');
    }
    public function get_taskdata_forcalendar(Request $request){
        $title = $request->title;
        $task_id = $request->id;

        $data = DB::table('all_tasks')->where('id', $task_id)->first();

        return response()->json($data);
    }
    public function all_calendar(Request $request)
    {
        $child_lists = \Session::get('child_user_lists');
        $data  = [];
        
            $start = $request->start;
            $end = $request->end;
            $user_id = $request->user_id;
 
            $query = DB::table('all_tasks')
                ->select('id', 'name as title', 'task_date as start', 'status', 'done_date as done')
                ->whereIn('pic_userid', $child_lists)->whereDate('task_date', '>=', $start)
                ->whereDate('task_date',   '<=', $end)->where('status', '!=', 'Cancel');
            
            if(isset($user_id) && $user_id != "0"){
                $query = $query->where('pic_userid', '=', $user_id);
            }
            $task_lists = $query->get();

            $data = [];
            $current_date = date('Y-m-d');
            for ($i=0; $i < count($task_lists); $i++) { 
                $task = $task_lists[$i];
                $task_date = $task->start;
                if($task_date < $current_date && $task->status == 'On Progress'){
                    $task->className = 'event-full event-overdue';
                }
                if($task->status == 'Approved' || $task->status == 'Done'){
                    $task->className = 'event-full event-finish';
                }
                if($task->status == 'Approved' && ($task->start < date('Y-m-d', strtotime($task->done)))){
                    $task->className = 'event-full event-approved-overdue';
                }
                if($task_date >= $current_date &&$task->status == 'On Progress'){
                    $task->className = 'event-full event-onprogress';
                }
                array_push($data, $task);
            
        }
        return response()->json($data);
    }
}
