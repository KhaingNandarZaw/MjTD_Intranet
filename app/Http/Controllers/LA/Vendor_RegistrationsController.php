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

use App\Models\Vendor_Registration;

class Vendor_RegistrationsController extends Controller
{
    public $show_action = true;
    
    /**
     * Display a listing of the Vendor_Registrations.
     *
     * @return mixed
     */
    public function index()
    {
        $module = Module::get('Vendor_Registrations');
        
        if(Module::hasAccess($module->id)) {
            return View('la.vendor_registrations.index', [
                'show_actions' => $this->show_action,
                'listing_cols' => Module::getListingColumns('Vendor_Registrations'),
                'module' => $module
            ]);
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Show the form for creating a new vendor_registration.
     *
     * @return mixed
     */
    public function create()
    {
        if(Module::hasAccess("Vendor_Registrations", "create")) {
            $module = Module::get('Vendor_Registrations');
            return view('la.vendor_registrations.create', [
                    'module' => $module]);
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Store a newly created vendor_registration in database.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        if(Module::hasAccess("Vendor_Registrations", "create")) {
            
            $rules = Module::validateRules("Vendor_Registrations", $request);
            
            $validator = Validator::make($request->all(), $rules);
            
            if($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            
            $insert_id = Module::insert("Vendor_Registrations", $request);
            
            return redirect()->route(config('laraadmin.adminRoute') . '.vendor_registrations.index');
            
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Display the specified vendor_registration.
     *
     * @param int $id vendor_registration ID
     * @return mixed
     */
    public function show($id)
    {
        if(Module::hasAccess("Vendor_Registrations", "view")) {
            
            $vendor_registration = Vendor_Registration::find($id);
            if(isset($vendor_registration->id)) {
                $module = Module::get('Vendor_Registrations');
                $module->row = $vendor_registration;
                
                return view('la.vendor_registrations.show', [
                    'module' => $module,
                    'view_col' => $module->view_col,
                    'no_header' => true,
                    'no_padding' => "no-padding"
                ])->with('vendor_registration', $vendor_registration);
            } else {
                return view('errors.404', [
                    'record_id' => $id,
                    'record_name' => ucfirst("vendor_registration"),
                ]);
            }
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Show the form for editing the specified vendor_registration.
     *
     * @param int $id vendor_registration ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        if(Module::hasAccess("Vendor_Registrations", "edit")) {
            $vendor_registration = Vendor_Registration::find($id);
            if(isset($vendor_registration->id)) {
                $module = Module::get('Vendor_Registrations');
                
                $module->row = $vendor_registration;
                
                return view('la.vendor_registrations.edit', [
                    'module' => $module,
                    'view_col' => $module->view_col,
                ])->with('vendor_registration', $vendor_registration);
            } else {
                return view('errors.404', [
                    'record_id' => $id,
                    'record_name' => ucfirst("vendor_registration"),
                ]);
            }
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Update the specified vendor_registration in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id vendor_registration ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        if(Module::hasAccess("Vendor_Registrations", "edit")) {
            
            $rules = Module::validateRules("Vendor_Registrations", $request, true);
            
            $validator = Validator::make($request->all(), $rules);
            
            if($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();;
            }
            
            $insert_id = Module::updateRow("Vendor_Registrations", $request, $id);
            
            return redirect()->route(config('laraadmin.adminRoute') . '.vendor_registrations.index');
            
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Remove the specified vendor_registration from storage.
     *
     * @param int $id vendor_registration ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        if(Module::hasAccess("Vendor_Registrations", "delete")) {
            Vendor_Registration::find($id)->delete();
            
            // Redirecting to index() method
            return redirect()->route(config('laraadmin.adminRoute') . '.vendor_registrations.index');
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
        $module = Module::get('Vendor_Registrations');
        $listing_cols = Module::getListingColumns('Vendor_Registrations');
        
        $values = DB::table('vendor_registrations')->select($listing_cols)->whereNull('deleted_at');
        $out = Datatables::of($values)->make();
        $data = $out->getData();
        
        $fields_popup = ModuleFields::getModuleFields('Vendor_Registrations');
        
        for($i = 0; $i < count($data->data); $i++) {
            for($j = 0; $j < count($listing_cols); $j++) {
                $col = $listing_cols[$j];
                if($fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
                    $data->data[$i][$j] = ModuleFields::getFieldValue($fields_popup[$col], $data->data[$i][$j]);
                }
                if($col == $module->view_col) {
                    $data->data[$i][$j] = '<a href="' . url(config('laraadmin.adminRoute') . '/vendor_registrations/' . $data->data[$i][0]) . '">' . $data->data[$i][$j] . '</a>';
                }
                // else if($col == "author") {
                //    $data->data[$i][$j];
                // }
            }
            
            if($this->show_action) {
                $output = '';
                if(Module::hasAccess("Vendor_Registrations", "edit")) {
                    $output .= '<a href="' . url(config('laraadmin.adminRoute') . '/vendor_registrations/' . $data->data[$i][0] . '/edit') . '" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-edit"></i></a>';
                }
                
                if(Module::hasAccess("Vendor_Registrations", "delete")) {
                    $output .= Form::open(['route' => [config('laraadmin.adminRoute') . '.vendor_registrations.destroy', $data->data[$i][0]], 'method' => 'delete', 'style' => 'display:inline']);
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
