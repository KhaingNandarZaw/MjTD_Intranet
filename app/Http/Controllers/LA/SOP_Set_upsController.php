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

use App\Models\SOP_Set_up;

class SOP_Set_upsController extends Controller
{
    public $show_action = true;
    
    /**
     * Display a listing of the SOP_Set_ups.
     *
     * @return mixed
     */
    public function index()
    {
        $module = Module::get('SOP_Set_ups');
        
        if(Module::hasAccess($module->id)) {
            return View('la.sop_set_ups.index', [
                'show_actions' => $this->show_action,
                'listing_cols' => Module::getListingColumns('SOP_Set_ups'),
                'module' => $module
            ]);
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Show the form for creating a new sop_set_up.
     *
     * @return mixed
     */
    public function create()
    {
        //
    }
    
    /**
     * Store a newly created sop_set_up in database.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        if(Module::hasAccess("SOP_Set_ups", "create")) {
            
            $rules = Module::validateRules("SOP_Set_ups", $request);
            
            $validator = Validator::make($request->all(), $rules);
            
            if($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            
            $insert_id = Module::insert("SOP_Set_ups", $request);
            
            return redirect()->route(config('laraadmin.adminRoute') . '.sop_set_ups.index');
            
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Display the specified sop_set_up.
     *
     * @param int $id sop_set_up ID
     * @return mixed
     */
    public function show($id)
    {
        if(Module::hasAccess("SOP_Set_ups", "view")) {
            
            $sop_set_up = SOP_Set_up::find($id);
            if(isset($sop_set_up->id)) {
                $module = Module::get('SOP_Set_ups');
                $module->row = $sop_set_up;
                
                return view('la.sop_set_ups.show', [
                    'module' => $module,
                    'view_col' => $module->view_col,
                    'no_header' => true,
                    'no_padding' => "no-padding"
                ])->with('sop_set_up', $sop_set_up);
            } else {
                return view('errors.404', [
                    'record_id' => $id,
                    'record_name' => ucfirst("sop_set_up"),
                ]);
            }
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Show the form for editing the specified sop_set_up.
     *
     * @param int $id sop_set_up ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        if(Module::hasAccess("SOP_Set_ups", "edit")) {
            $sop_set_up = SOP_Set_up::find($id);
            if(isset($sop_set_up->id)) {
                $module = Module::get('SOP_Set_ups');
                
                $module->row = $sop_set_up;
                
                return view('la.sop_set_ups.edit', [
                    'module' => $module,
                    'view_col' => $module->view_col,
                ])->with('sop_set_up', $sop_set_up);
            } else {
                return view('errors.404', [
                    'record_id' => $id,
                    'record_name' => ucfirst("sop_set_up"),
                ]);
            }
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Update the specified sop_set_up in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id sop_set_up ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        if(Module::hasAccess("SOP_Set_ups", "edit")) {
            
            $rules = Module::validateRules("SOP_Set_ups", $request, true);
            
            $validator = Validator::make($request->all(), $rules);
            
            if($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();;
            }
            
            $insert_id = Module::updateRow("SOP_Set_ups", $request, $id);
            
            return redirect()->route(config('laraadmin.adminRoute') . '.sop_set_ups.index');
            
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Remove the specified sop_set_up from storage.
     *
     * @param int $id sop_set_up ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        if(Module::hasAccess("SOP_Set_ups", "delete")) {
            SOP_Set_up::find($id)->delete();
            
            // Redirecting to index() method
            return redirect()->route(config('laraadmin.adminRoute') . '.sop_set_ups.index');
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
        $module = Module::get('SOP_Set_ups');
        $listing_cols = Module::getListingColumns('SOP_Set_ups');
        
        $values = DB::table('sop_set_ups')->select($listing_cols)->whereNull('deleted_at');
        $out = Datatables::of($values)->make();
        $data = $out->getData();
        
        $fields_popup = ModuleFields::getModuleFields('SOP_Set_ups');
        
        for($i = 0; $i < count($data->data); $i++) {
            for($j = 0; $j < count($listing_cols); $j++) {
                $col = $listing_cols[$j];
                if($fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
                    $data->data[$i][$j] = ModuleFields::getFieldValue($fields_popup[$col], $data->data[$i][$j]);
                }
                if($col == $module->view_col) {
                    $data->data[$i][$j] = '<a href="' . url(config('laraadmin.adminRoute') . '/sop_set_ups/' . $data->data[$i][0]) . '">' . $data->data[$i][$j] . '</a>';
                }
                // else if($col == "author") {
                //    $data->data[$i][$j];
                // }
            }
            
            if($this->show_action) {
                $output = '';
                if(Module::hasAccess("SOP_Set_ups", "edit")) {
                    $output .= '<a href="' . url(config('laraadmin.adminRoute') . '/sop_set_ups/' . $data->data[$i][0] . '/edit') . '" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-edit"></i></a>';
                }
                
                if(Module::hasAccess("SOP_Set_ups", "delete")) {
                    $output .= Form::open(['route' => [config('laraadmin.adminRoute') . '.sop_set_ups.destroy', $data->data[$i][0]], 'method' => 'delete', 'style' => 'display:inline']);
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
