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

use App\Models\Task;

class TasksController extends Controller
{
    public $show_action = true;
    
    /**
     * Display a listing of the Tasks.
     *
     * @return mixed
     */
    public function index()
    {
        $module = Module::get('Tasks');
        
        if(Module::hasAccess($module->id, 'create')) {
            return View('la.tasks.index', [
                'show_actions' => $this->show_action,
                'listing_cols' => Module::getListingColumns('Tasks'),
                'module' => $module
            ]);
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Show the form for creating a new task.
     *
     * @return mixed
     */
    public function create()
    {
        $module = Module::get('Tasks');
        
        if(Module::hasAccess("Tasks", "create")) {
            return View('la.tasks.create', [
                'module' => $module
            ]);
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Store a newly created task in database.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        if(Module::hasAccess("Tasks", "create")) {
            
            $rules = Module::validateRules("Tasks", $request);
            $today = date('Y-m-d h:i:s');

            $validator = Validator::make($request->all(), $rules);
            
            if($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            
            $request->merge([
                'created_by' => Auth::user()->id,
                'report_to_userid' => Auth::user()->id
            ]);
            
            $insert_id = Module::insert("Tasks", $request);

            $pic_count = $request->input('pic_count');
            for($i = 1; $i<=$pic_count; $i++)
            {
                if($request->input('pic_'.$i) != null)
                {
                    $pic_userid = $request->input('pic_'.$i);
                    $description = $request->input('desc_'.$i);
                    $insert = DB::table('task_pics')->insertGetId([
                        "created_at" => $today,
                        "pic_userid" => $pic_userid,
                        "task_id" => $insert_id,
                        "description" => $description
                    ]);
                }
            }
            
            return redirect()->route(config('laraadmin.adminRoute') . '.tasks.index');
            
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Display the specified task.
     *
     * @param int $id task ID
     * @return mixed
     */
    public function show($id)
    {
        if(Module::hasAccess("Tasks", "edit")) {
            
            $task = Task::find($id);
            
            $task_pics = DB::table('task_pics')
                ->select('task_pics.*', 'users.name')
                ->join('users', 'users.id', '=', 'task_pics.pic_userid')
                ->where('task_pics.task_id', $id)
                ->whereNull('task_pics.deleted_at')
                ->whereNull('users.deleted_at')
                ->orderBy('task_pics.id')
                ->get();

            if(isset($task->id)) {
                $module = Module::get('Tasks');
                $module->row = $task;
                
                return view('la.tasks.show', [
                    'module' => $module,
                    'view_col' => $module->view_col,
                    'no_header' => true,
                    'no_padding' => "no-padding"
                ])->with('task', $task)->with('task_pics', $task_pics);
            } else {
                return view('errors.404', [
                    'record_id' => $id,
                    'record_name' => ucfirst("task"),
                ]);
            }
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Show the form for editing the specified task.
     *
     * @param int $id task ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        if(Module::hasAccess("Tasks", "edit")) {
            $task = Task::find($id);
            if(isset($task->id)) {
                $module = Module::get('Tasks');
                
                $module->row = $task;

                $task_pics = DB::table('task_pics')->where('task_id', '=', $id)->whereNull('deleted_at')->get();
                
                return view('la.tasks.edit', [
                    'module' => $module,
                    'view_col' => $module->view_col,
                ])->with('task', $task)->with('task_pics', $task_pics);
            } else {
                return view('errors.404', [
                    'record_id' => $id,
                    'record_name' => ucfirst("task"),
                ]);
            }
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Update the specified task in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id task ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        if(Module::hasAccess("Tasks", "edit")) {
            
            $rules = Module::validateRules("Tasks", $request, true);
            $today = date('Y-m-d h:i:s');

            $validator = Validator::make($request->all(), $rules);
            
            if($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();;
            }
            
            $insert_id = Module::updateRow("Tasks", $request, $id);

            DB:: table('task_pics')->where('task_id', $id)->update(['deleted_at' => $today]);

            $pic_count = $request->input('pic_count');
            for($i = 1; $i<=$pic_count; $i++)
            {
                if($request->input('pic_'.$i) != null)
                {
                    $pic_userid = $request->input('pic_'.$i);
                    $description = $request->input('desc_'.$i);
                    $insert = DB::table('task_pics')->insertGetId([
                        "created_at" => $today,
                        "pic_userid" => $pic_userid,
                        "task_id" => $insert_id,
                        "description" => $description
                    ]);
                }
            }
            
            return redirect()->route(config('laraadmin.adminRoute') . '.tasks.index');
            
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Remove the specified task from storage.
     *
     * @param int $id task ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        if(Module::hasAccess("Tasks", "delete")) {
            Task::find($id)->delete();
            
            // Redirecting to index() method
            return redirect()->route(config('laraadmin.adminRoute') . '.tasks.index');
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
        $module = Module::get('Tasks');
        $listing_cols = Module::getListingColumns('Tasks');
        
        if(Auth::user()->id == 1)
            $values = DB::table('tasks')->select($listing_cols)->whereNull('deleted_at');
        else
            $values = DB::table('tasks')->select($listing_cols)->whereNull('deleted_at')->where('created_by', '=' ,Auth::user()->id);

        $out = Datatables::of($values)->make();
        $data = $out->getData();
        
        $fields_popup = ModuleFields::getModuleFields('Tasks');
        
        for($i = 0; $i < count($data->data); $i++) {
            for($j = 0; $j < count($listing_cols); $j++) {
                $col = $listing_cols[$j];
                if($fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
                    $data->data[$i][$j] = ModuleFields::getFieldValue($fields_popup[$col], $data->data[$i][$j]);
                }
                if($col == $module->view_col) {
                    $data->data[$i][$j] = '<a href="' . url(config('laraadmin.adminRoute') . '/tasks/' . $data->data[$i][0]) . '">' . $data->data[$i][$j] . '</a>';
                }
                // else if($col == "author") {
                //    $data->data[$i][$j];
                // }
            }
            
            if($this->show_action) {
                $output = '';
                if(Module::hasAccess("Tasks", "edit")) {
                    $output .= '<a href="' . url(config('laraadmin.adminRoute') . '/tasks/' . $data->data[$i][0] . '/edit') . '" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-edit"></i></a>';
                }
                
                if(Module::hasAccess("Tasks", "delete")) {
                    $output .= Form::open(['route' => [config('laraadmin.adminRoute') . '.tasks.destroy', $data->data[$i][0]], 'method' => 'delete', 'style' => 'display:inline']);
                    $output .= ' <button class="btn btn-danger btn-xs" type="submit"><i class="fa fa-times"></i></button>';
                    $output .= Form::close();
                }
                $data->data[$i][] = (string)$output;
            }
        }
        $out->setData($data);
        return $out;
    }
}
