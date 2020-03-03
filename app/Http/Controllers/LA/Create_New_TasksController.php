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

use App\Models\Create_New_Task;

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
        
        $requested_tasks = DB::table('create_new_tasks')->whereNull('deleted_at')->where('status', 'Requested')->where('created_by', Auth::user()->id)->get();
        $confirmed_tasks = DB::table('create_new_tasks')->whereNull('deleted_at')->where('status', 'Confirmed')->where('created_by', Auth::user()->id)->get();
        $rejected_tasks = DB::table('create_new_tasks')->whereNull('deleted_at')->where('status', 'Rejected')->where('created_by', Auth::user()->id)->get();

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
        
        if(Module::hasAccess("Create_New_Tasks", "create")) {
            return View('la.create_new_tasks.create', [
                'module' => $module
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
            
            $request->merge([
                'created_by' => Auth::user()->id,
                'status' => 'Requested'
            ]);

            $insert_id = Module::insert("Create_New_Tasks", $request);
            
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
    
    /**
     * Show the form for editing the specified create_new_task.
     *
     * @param int $id create_new_task ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        if(Module::hasAccess("Create_New_Tasks", "edit")) {
            $create_new_task = Create_New_Task::find($id);
            if(isset($create_new_task->id)) {
                $module = Module::get('Create_New_Tasks');
                
                $module->row = $create_new_task;
                
                return view('la.create_new_tasks.edit', [
                    'module' => $module,
                    'view_col' => $module->view_col,
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
    
    /**
     * Update the specified create_new_task in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id create_new_task ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        if(Module::hasAccess("Create_New_Tasks", "edit")) {
            
            $rules = Module::validateRules("Create_New_Tasks", $request, true);
            
            $validator = Validator::make($request->all(), $rules);
            
            if($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();;
            }
            
            $insert_id = Module::updateRow("Create_New_Tasks", $request, $id);
            
            return redirect()->route(config('laraadmin.adminRoute') . '.create_new_tasks.index');
            
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Remove the specified create_new_task from storage.
     *
     * @param int $id create_new_task ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        if(Module::hasAccess("Create_New_Tasks", "delete")) {
            Create_New_Task::find($id)->delete();
            
            // Redirecting to index() method
            return redirect()->route(config('laraadmin.adminRoute') . '.create_new_tasks.index');
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
        $module = Module::get('Create_New_Tasks');
        $listing_cols = Module::getListingColumns('Create_New_Tasks');
        
        $values = DB::table('create_new_tasks')->select($listing_cols)->whereNull('deleted_at');
        $out = Datatables::of($values)->make();
        $data = $out->getData();
        
        $fields_popup = ModuleFields::getModuleFields('Create_New_Tasks');
        
        for($i = 0; $i < count($data->data); $i++) {
            for($j = 0; $j < count($listing_cols); $j++) {
                $col = $listing_cols[$j];
                if($fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
                    $data->data[$i][$j] = ModuleFields::getFieldValue($fields_popup[$col], $data->data[$i][$j]);
                }
                if($col == $module->view_col) {
                    $data->data[$i][$j] = '<a href="' . url(config('laraadmin.adminRoute') . '/create_new_tasks/' . $data->data[$i][0]) . '">' . $data->data[$i][$j] . '</a>';
                }
                // else if($col == "author") {
                //    $data->data[$i][$j];
                // }
            }
            
            if($this->show_action) {
                $output = '';
                if(Module::hasAccess("Create_New_Tasks", "edit")) {
                    $output .= '<a href="' . url(config('laraadmin.adminRoute') . '/create_new_tasks/' . $data->data[$i][0] . '/edit') . '" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-edit"></i></a>';
                }
                
                if(Module::hasAccess("Create_New_Tasks", "delete")) {
                    $output .= Form::open(['route' => [config('laraadmin.adminRoute') . '.create_new_tasks.destroy', $data->data[$i][0]], 'method' => 'delete', 'style' => 'display:inline']);
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
