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

use App\Models\Task_Instance;
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

            if(Auth::user()->id == 1){
                $all_tasks = DB::table('all_tasks')->get();
                $this->show_action = false;
            }else {
                $all_tasks = DB::table('all_tasks')->where('pic_userid', '=', Auth::user()->id)->get();
                $this->show_action = true;
            }
        
            return View('la.task_instances.index', [
                'show_actions' => $this->show_action,
                'listing_cols' => Module::getListingColumns('Task_Instances'),
                'module' => $module,
                'all_tasks' => $all_tasks
            ]);
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    public function task_checking(){
        if(Module::hasAccess("Task_Instances", "edit")) {
            $module = Module::get('Task_Instances');

            if(Auth::user()->id == 1){
                $all_tasks = DB::table('all_tasks')->get();
                $this->show_action = false;
            }else {
                $all_tasks = DB::table('all_tasks')->where('report_to_userid', '=', Auth::user()->id)->get();
                $this->show_action = true;
            }
        
            return View('la.task_instances.task_checking', [
                'show_actions' => $this->show_action,
                'listing_cols' => Module::getListingColumns('Task_Instances'),
                'module' => $module,
                'all_tasks' => $all_tasks
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
            
            if(Input::hasFile('complete_files')) {
				$file = Input::file('complete_files');
				
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
					$upload = DB::table('task_files')->where('id', $insertedID)->first();
					
				} 
			} 

            return redirect(config('laraadmin.adminRoute') . "/task_instances");
            
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
            
            DB:: table('task_instances')->where('id', $task_instance_id)->update(['done_date' => $today, 'status' => 'Approved']);
            
            $insert = DB::table('task_remarks')->insertGetId([
                "created_at" => $today,
                "task_instance_id" => $task_instance_id,
                "remark" => $remark,
                "user_id" => Auth::user()->id,
                "status" => 'Approved'
            ]);
            
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
            
            DB:: table('task_instances')->where('id', $task_instance_id)->update(['done_date' => $today, 'status' => 'Rejected']);
            
            $insert = DB::table('task_remarks')->insertGetId([
                "created_at" => $today,
                "task_instance_id" => $task_instance_id,
                "remark" => $remark,
                "user_id" => Auth::user()->id,
                "status" => 'Rejected'
            ]);
            
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
        $extend_due_date = date('Y-m-d',strtotime($extend_dates));
        
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
}
