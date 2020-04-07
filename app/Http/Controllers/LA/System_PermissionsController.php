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

use App\Models\System_Permission;
use App\User;

class System_PermissionsController extends Controller
{
    public $show_action = true;
    
    /**
     * Display a listing of the System_Permissions.
     *
     * @return mixed
     */
    public function index()
    {
        $module = Module::get('System_Permissions');

        $users = User::whereNull('deleted_at')->orderBy('name')->get();
        $users_permissions = array();
        foreach ($users as $user) {
            $user->permissions = DB::table('system_permissions')->where('user_id', $user->id)->first();
            $users_permissions[] = $user;
        }
        if(Module::hasAccess($module->id)) {
            return View('la.system_permissions.index', [
                'show_actions' => $this->show_action,
                'listing_cols' => Module::getListingColumns('System_Permissions'),
                'module' => $module,
                'users_permissions' => $users_permissions
            ]);
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Show the form for creating a new system_permission.
     *
     * @return mixed
     */
    public function create()
    {
        //
    }
    
    /**
     * Store a newly created system_permission in database.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        if(Module::hasAccess("System_Permissions", "create")) {
            
            $users = User::whereNull('deleted_at')->orderBy('name')->get();
        
            $now = date("Y-m-d H:i:s");
            
            foreach($users as $user) {
                $user_id = $user->id;
                $user_name = 'user_'.$user->id;
                if(isset($request->$user_name)) {
                    $hr = 'user_hr'.$user->id;
                    $ums = 'user_ums'.$user->id;
                    $rbs = 'user_rbs'.$user->id;
                    
                    if(isset($request->$hr)) {
                        $hr = 1;
                    } else {
                        $hr = 0;
                    }
                    if(isset($request->$ums)) {
                        $ums = 1;
                    } else {
                        $ums = 0;
                    }
                    if(isset($request->$rbs)) {
                        $rbs = 1;
                    } else {
                        $rbs = 0;
                    }
                    
                    $query = DB::table('system_permissions')->where('user_id', $user_id);
                    if($query->count() == 0) {
                        DB::insert('insert into system_permissions (created_at, user_id, hr, ums, rbs) values (?, ?, ?, ?, ?)', [$now, $user_id, $hr, $ums, $rbs]);
                    } else {
                        DB:: table('system_permissions')->where('user_id', $user_id)->update(['hr' => $hr, 'ums' => $ums, 'rbs' => $rbs]);
                    }
                }
            }
            return redirect()->route(config('laraadmin.adminRoute') . '.system_permissions.index');   
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Display the specified system_permission.
     *
     * @param int $id system_permission ID
     * @return mixed
     */
    public function show($id)
    {
        if(Module::hasAccess("System_Permissions", "view")) {
            
            $system_permission = System_Permission::find($id);
            if(isset($system_permission->id)) {
                $module = Module::get('System_Permissions');
                $module->row = $system_permission;
                
                return view('la.system_permissions.show', [
                    'module' => $module,
                    'view_col' => $module->view_col,
                    'no_header' => true,
                    'no_padding' => "no-padding"
                ])->with('system_permission', $system_permission);
            } else {
                return view('errors.404', [
                    'record_id' => $id,
                    'record_name' => ucfirst("system_permission"),
                ]);
            }
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Show the form for editing the specified system_permission.
     *
     * @param int $id system_permission ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        if(Module::hasAccess("System_Permissions", "edit")) {
            $system_permission = System_Permission::find($id);
            if(isset($system_permission->id)) {
                $module = Module::get('System_Permissions');
                
                $module->row = $system_permission;
                
                return view('la.system_permissions.edit', [
                    'module' => $module,
                    'view_col' => $module->view_col,
                ])->with('system_permission', $system_permission);
            } else {
                return view('errors.404', [
                    'record_id' => $id,
                    'record_name' => ucfirst("system_permission"),
                ]);
            }
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Update the specified system_permission in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id system_permission ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        if(Module::hasAccess("System_Permissions", "edit")) {
            
            $rules = Module::validateRules("System_Permissions", $request, true);
            
            $validator = Validator::make($request->all(), $rules);
            
            if($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();;
            }
            
            $insert_id = Module::updateRow("System_Permissions", $request, $id);
            
            return redirect()->route(config('laraadmin.adminRoute') . '.system_permissions.index');
            
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Remove the specified system_permission from storage.
     *
     * @param int $id system_permission ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        if(Module::hasAccess("System_Permissions", "delete")) {
            System_Permission::find($id)->delete();
            
            // Redirecting to index() method
            return redirect()->route(config('laraadmin.adminRoute') . '.system_permissions.index');
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
        $module = Module::get('System_Permissions');
        $listing_cols = Module::getListingColumns('System_Permissions');
        
        $values = DB::table('system_permissions')->select($listing_cols)->whereNull('deleted_at');
        $out = Datatables::of($values)->make();
        $data = $out->getData();
        
        $fields_popup = ModuleFields::getModuleFields('System_Permissions');
        
        for($i = 0; $i < count($data->data); $i++) {
            for($j = 0; $j < count($listing_cols); $j++) {
                $col = $listing_cols[$j];
                if($fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
                    $data->data[$i][$j] = ModuleFields::getFieldValue($fields_popup[$col], $data->data[$i][$j]);
                }
                if($col == $module->view_col) {
                    $data->data[$i][$j] = '<a href="' . url(config('laraadmin.adminRoute') . '/system_permissions/' . $data->data[$i][0]) . '">' . $data->data[$i][$j] . '</a>';
                }
                // else if($col == "author") {
                //    $data->data[$i][$j];
                // }
            }
            
            if($this->show_action) {
                $output = '';
                if(Module::hasAccess("System_Permissions", "edit")) {
                    $output .= '<a href="' . url(config('laraadmin.adminRoute') . '/system_permissions/' . $data->data[$i][0] . '/edit') . '" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-edit"></i></a>';
                }
                
                if(Module::hasAccess("System_Permissions", "delete")) {
                    $output .= Form::open(['route' => [config('laraadmin.adminRoute') . '.system_permissions.destroy', $data->data[$i][0]], 'method' => 'delete', 'style' => 'display:inline']);
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
