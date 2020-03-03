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

use App\Models\SOP_Management_Type;

class SOP_Management_TypesController extends Controller
{
    public $show_action = true;
    
    /**
     * Display a listing of the SOP_Management_Types.
     *
     * @return mixed
     */
    public function index()
    {
        $module = Module::get('SOP_Management_Types');
        
        if(Module::hasAccess($module->id)) {
            return View('la.sop_management_types.index', [
                'show_actions' => $this->show_action,
                'listing_cols' => Module::getListingColumns('SOP_Management_Types'),
                'module' => $module
            ]);
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Show the form for creating a new sop_management_type.
     *
     * @return mixed
     */
    public function create()
    {
        //
    }
    
    /**
     * Store a newly created sop_management_type in database.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        if(Module::hasAccess("SOP_Management_Types", "create")) {
            
            $rules = Module::validateRules("SOP_Management_Types", $request);
            
            $validator = Validator::make($request->all(), $rules);
            
            if($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            
            $insert_id = Module::insert("SOP_Management_Types", $request);
            
            return redirect()->route(config('laraadmin.adminRoute') . '.sop_management_types.index');
            
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Display the specified sop_management_type.
     *
     * @param int $id sop_management_type ID
     * @return mixed
     */
    public function show($id)
    {
        if(Module::hasAccess("SOP_Management_Types", "view")) {
            
            $sop_management_type = SOP_Management_Type::find($id);
            if(isset($sop_management_type->id)) {
                $module = Module::get('SOP_Management_Types');
                $module->row = $sop_management_type;
                
                return view('la.sop_management_types.show', [
                    'module' => $module,
                    'view_col' => $module->view_col,
                    'no_header' => true,
                    'no_padding' => "no-padding"
                ])->with('sop_management_type', $sop_management_type);
            } else {
                return view('errors.404', [
                    'record_id' => $id,
                    'record_name' => ucfirst("sop_management_type"),
                ]);
            }
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Show the form for editing the specified sop_management_type.
     *
     * @param int $id sop_management_type ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        if(Module::hasAccess("SOP_Management_Types", "edit")) {
            $sop_management_type = SOP_Management_Type::find($id);
            if(isset($sop_management_type->id)) {
                $module = Module::get('SOP_Management_Types');
                
                $module->row = $sop_management_type;
                
                return view('la.sop_management_types.edit', [
                    'module' => $module,
                    'view_col' => $module->view_col,
                ])->with('sop_management_type', $sop_management_type);
            } else {
                return view('errors.404', [
                    'record_id' => $id,
                    'record_name' => ucfirst("sop_management_type"),
                ]);
            }
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Update the specified sop_management_type in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id sop_management_type ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        if(Module::hasAccess("SOP_Management_Types", "edit")) {
            
            $rules = Module::validateRules("SOP_Management_Types", $request, true);
            
            $validator = Validator::make($request->all(), $rules);
            
            if($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();;
            }
            
            $insert_id = Module::updateRow("SOP_Management_Types", $request, $id);
            
            return redirect()->route(config('laraadmin.adminRoute') . '.sop_management_types.index');
            
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Remove the specified sop_management_type from storage.
     *
     * @param int $id sop_management_type ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        if(Module::hasAccess("SOP_Management_Types", "delete")) {
            SOP_Management_Type::find($id)->delete();
            
            // Redirecting to index() method
            return redirect()->route(config('laraadmin.adminRoute') . '.sop_management_types.index');
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
        $module = Module::get('SOP_Management_Types');
        $listing_cols = Module::getListingColumns('SOP_Management_Types');
        
        $values = DB::table('sop_management_types')->select($listing_cols)->whereNull('deleted_at');
        $out = Datatables::of($values)->make();
        $data = $out->getData();
        
        $fields_popup = ModuleFields::getModuleFields('SOP_Management_Types');
        
        for($i = 0; $i < count($data->data); $i++) {
            for($j = 0; $j < count($listing_cols); $j++) {
                $col = $listing_cols[$j];
                if($fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
                    $data->data[$i][$j] = ModuleFields::getFieldValue($fields_popup[$col], $data->data[$i][$j]);
                }
                if($col == $module->view_col) {
                    $data->data[$i][$j] = '<a href="' . url(config('laraadmin.adminRoute') . '/sop_management_types/' . $data->data[$i][0]) . '">' . $data->data[$i][$j] . '</a>';
                }
                // else if($col == "author") {
                //    $data->data[$i][$j];
                // }
            }
            
            if($this->show_action) {
                $output = '';
                if(Module::hasAccess("SOP_Management_Types", "edit")) {
                    $output .= '<a href="' . url(config('laraadmin.adminRoute') . '/sop_management_types/' . $data->data[$i][0] . '/edit') . '" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-edit"></i></a>';
                }
                
                if(Module::hasAccess("SOP_Management_Types", "delete")) {
                    $output .= Form::open(['route' => [config('laraadmin.adminRoute') . '.sop_management_types.destroy', $data->data[$i][0]], 'method' => 'delete', 'style' => 'display:inline']);
                    $output .= ' <button class="btn btn-danger btn-xs" type="submit"><i class="fa fa-trash"></i></button>';
                    $output .= Form::close();
                }
                $data->data[$i][] = (string)$output;
            }
        }
        $out->setData($data);
        return $out;
    }
}
