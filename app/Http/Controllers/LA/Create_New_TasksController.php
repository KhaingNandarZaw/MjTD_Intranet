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
use App\Models\Frame;
use App\Mail\CreateNewTask;

class Create_New_TasksController extends Controller
{
    public $show_action = true;
    
    /**
     * Display a listing of the Create_New_Tasks.
     *
     * @return mixed
     */
    public function index()
    {
        $module = Module::get('Create_New_Tasks');
        
        if(Entrust::hasRole("SUPER_ADMIN") || Entrust::hasRole("CEO")){
            $requested_tasks = DB::table('create_new_tasks')->whereNull('deleted_at')->where('status', 'Requested')->get();
            $confirmed_tasks = DB::table('create_new_tasks')->whereNull('deleted_at')->where('status', 'Confirmed')->get();
            $rejected_tasks = DB::table('create_new_tasks')->whereNull('deleted_at')->where('status', 'Rejected')->get();
        }else {
            $requested_tasks = DB::table('create_new_tasks')->whereNull('deleted_at')->where('status', 'Requested')->where('pic_user_id', Auth::user()->id)->get();
            $confirmed_tasks = DB::table('create_new_tasks')->whereNull('deleted_at')->where('status', 'Confirmed')->where('pic_user_id', Auth::user()->id)->get();
            $rejected_tasks = DB::table('create_new_tasks')->whereNull('deleted_at')->where('status', 'Rejected')->where('pic_user_id', Auth::user()->id)->get();
        }
        if(Module::hasAccess($module->id)) {
            return View('la.create_new_tasks.index', [
                'show_actions' => $this->show_action,
                'listing_cols' => Module::getListingColumns('Create_New_Tasks'),
                'module' => $module,
                'requested_tasks' => $requested_tasks,
                'confirmed_tasks' => $confirmed_tasks,
                'rejected_tasks' => $rejected_tasks
            ]);
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Show the form for creating a new create_new_task.
     *
     * @return mixed
     */
    public function create()
    {
        $module = Module::get('Create_New_Tasks');
        $time_frames = Frame::where('use_task', 1)->whereNull('deleted_at')->get();
        if(Module::hasAccess("Create_New_Tasks", "create")) {
            return View('la.create_new_tasks.create', [
                'module' => $module,
                'time_frames' => $time_frames
            ]);
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Store a newly created create_new_task in database.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        if(Module::hasAccess("Create_New_Tasks", "create")) {
            
            $rules = Module::validateRules("Create_New_Tasks", $request);
            
            $validator = Validator::make($request->all(), $rules);
            
            if($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            if($request->has('pic_user_id')){
                $pic_user_id = $request->input('pic_user_id');
            } else{
                $pic_user_id = Auth::user()->id;
            }

            $request->merge([
                'created_by' => Auth::user()->id,
                'pic_user_id' => $pic_user_id,
                'status' => 'Requested'
            ]);

            $insert_id = Module::insert("Create_New_Tasks", $request);

            $user = DB::table('users')->where('id', $request->report_to_userid)->first();
            $subject = "New Task Created by " . Auth::user()->name;
            $to = $user->email;
            $task_title = $request->name;
            $cc_array = $request->cc_users;
            $pic_user = DB::table('users')->where('id', $request->pic_user_id)->first();
            $pic = $pic_user->name;
            $reportTo = $user->name;

            Mail::to($to)->send(new CreateNewTask($task_title, $pic, $reportTo, $subject, $cc_array));
            
            return redirect()->route(config('laraadmin.adminRoute') . '.create_new_tasks.index');
            
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Display the specified create_new_task.
     *
     * @param int $id create_new_task ID
     * @return mixed
     */
    public function show($id)
    {
        if(Module::hasAccess("Create_New_Tasks", "view") || Module::hasAccess("Confirm_New_Tasks", "view")) {
            
            $create_new_task = Create_New_Task::find($id);
            if(isset($create_new_task->id)) {
                $module = Module::get('Create_New_Tasks');
                $module->row = $create_new_task;
                
                return view('la.create_new_tasks.show', [
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
    public function edit($id)
    {
        $new_task = Create_New_Task::find($id);
        $time_frames = Frame::where('use_task', 1)->whereNull('deleted_at')->get();
        if(isset($new_task->id)) {
            $module = Module::get('Create_New_Tasks');
            
            $module->row = $new_task;
            
            return view('la.create_new_tasks.edit', [
                'module' => $module,
                'view_col' => $module->view_col,
                'time_frames' => $time_frames
            ])->with('new_task', $new_task);
        } else {
            return view('errors.404', [
                'record_id' => $id,
                'record_name' => ucfirst("sop_setup"),
            ]);
        }
    }
    public function update(Request $request, $id)
    {
        if(Module::hasAccess("Create_New_Tasks", "edit")) {
            
            $rules = Module::validateRules("Create_New_Tasks", $request, true);
            
            $validator = Validator::make($request->all(), $rules);
            
            if($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();;
            }
            
            if($request->has('pic_user_id')){
                $pic_user_id = $request->input('pic_user_id');
            }else{
                $pic_user_id = Auth::user()->id;
            }
            $request->merge([
                'pic_user_id' => $pic_user_id,
                'status' => 'Requested'
            ]);

            $insert_id = Module::updateRow("Create_New_Tasks", $request, $id);
            
            return redirect()->route(config('laraadmin.adminRoute') . '.create_new_tasks.index');
            
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    public function cancel($id){
        $today = date('Y-m-d H:i:s');
        
        DB:: table('create_new_tasks')->where('id', $id)->update(['status' => 'Cancel']);

        return redirect(config('laraadmin.adminRoute') . "/create_new_tasks");
    }
}
