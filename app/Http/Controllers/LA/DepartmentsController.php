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

use App\Models\Department;

class DepartmentsController extends Controller
{
	public $show_action = true;
	

	/**
	 * Show the form for creating a new department.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created department in database.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		if(Module::hasAccess("Departments", "create")) {
		
			$rules = Module::validateRules("Departments", $request);
			
			$validator = Validator::make($request->all(), $rules);
			
			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();
			}
			
			$insert_id = Module::insert("Departments", $request);

			\Session::flash('success', 'Successfully Saved.');
			
			return redirect()->route(config('laraadmin.adminRoute') . '.departments.index');
			
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Display the specified department.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		if(Module::hasAccess("Departments", "view")) {
			$module = Module::get('Departments');
			$modules = Module::all();
			// Send Menus with No Parent to Views
			$menuItems = Department::where("parent", 0)->where("status", 1)->orderBy('hierarchy', 'asc')->get();
			
			return View('la.departments.show', [
				'menus' => $menuItems,
				'modules' => $modules,
				'module' => $module
			]);
			
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	public function department_users()
    {
        $dept_id = Input::get('dept_id');
        $module = Module::get('Users');
        $listing_cols = Module::getListingColumns('Users');
        
		$values = DB::table('users')->select($listing_cols)->whereNull('deleted_at')->where('department', $dept_id);
        $out = Datatables::of($values)->make();
        $data = $out->getData();
        
        $fields_popup = ModuleFields::getModuleFields('Users');
        
        for($i = 0; $i < count($data->data); $i++) {
            for($j = 0; $j < count($listing_cols); $j++) {
                $col = $listing_cols[$j];
                if($fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
                    $data->data[$i][$j] = ModuleFields::getFieldValue($fields_popup[$col], $data->data[$i][$j]);
                }
            }
        }
        $out->setData($data);
        return $out;
	}

	public function update_hierarchy()
    {
        $parents = Input::get('jsonData');
        $parent_id = 0;
        
        for($i = 0; $i < count($parents); $i++) {
            $this->apply_hierarchy($parents[$i], $i + 1, $parent_id);
        }
        
        return $parents;
	}
	
	function apply_hierarchy($departmentItem, $num, $parent_id)
    {
        $department = Department::find($departmentItem['id']);
        $department->parent = $parent_id;
        $department->hierarchy = $num;
        $department->save();
        
        // apply hierarchy to children if exists
        if(isset($departmentItem['children'])) {
            for($i = 0; $i < count($departmentItem['children']); $i++) {
                $this->apply_hierarchy($departmentItem['children'][$i], $i + 1, $departmentItem['id']);
            }
        }
    }

	/**
	 * Update the specified department in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		if(Module::hasAccess("Departments", "edit")) {
			
			$rules = Module::validateRules("Departments", $request, true);
			
			$validator = Validator::make($request->all(), $rules);
			
			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();;
			}
			
			$insert_id = Module::updateRow("Departments", $request, $id);

			\Session::flash('success', 'Successfully Updated.');
			
			return redirect()->route(config('laraadmin.adminRoute') . '.departments.index');
			
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Remove the specified department from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		if(Module::hasAccess("Departments", "delete")) {
			Department::find($id)->delete();
			
			\Session::flash('success', 'Successfully Deleted.');

			// Redirecting to index() method
			return redirect()->route(config('laraadmin.adminRoute') . '.departments.index');
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}
}
