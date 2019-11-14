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

use App\Models\Timeframe;

class TimeframesController extends Controller
{
    public $show_action = true;
    
    /**
     * Display a listing of the Timeframes.
     *
     * @return mixed
     */
    public function index()
    {
        $module = Module::get('Timeframes');
        
        if(Module::hasAccess($module->id)) {
            return View('la.timeframes.index', [
                'show_actions' => $this->show_action,
                'listing_cols' => Module::getListingColumns('Timeframes'),
                'module' => $module
            ]);
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Show the form for creating a new timeframe.
     *
     * @return mixed
     */
    public function create()
    {
        //
    }
    
    /**
     * Store a newly created timeframe in database.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        if(Module::hasAccess("Timeframes", "create")) {
            
            $rules = Module::validateRules("Timeframes", $request);
            
            $validator = Validator::make($request->all(), $rules);
            
            if($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            
            $insert_id = Module::insert("Timeframes", $request);
            
            return redirect()->route(config('laraadmin.adminRoute') . '.timeframes.index');
            
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Display the specified timeframe.
     *
     * @param int $id timeframe ID
     * @return mixed
     */
    public function show($id)
    {
        if(Module::hasAccess("Timeframes", "view")) {
            
            $timeframe = Timeframe::find($id);
            if(isset($timeframe->id)) {
                $module = Module::get('Timeframes');
                $module->row = $timeframe;
                
                return view('la.timeframes.show', [
                    'module' => $module,
                    'view_col' => $module->view_col,
                    'no_header' => true,
                    'no_padding' => "no-padding"
                ])->with('timeframe', $timeframe);
            } else {
                return view('errors.404', [
                    'record_id' => $id,
                    'record_name' => ucfirst("timeframe"),
                ]);
            }
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Show the form for editing the specified timeframe.
     *
     * @param int $id timeframe ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        if(Module::hasAccess("Timeframes", "edit")) {
            $timeframe = Timeframe::find($id);
            if(isset($timeframe->id)) {
                $module = Module::get('Timeframes');
                
                $module->row = $timeframe;
                
                return view('la.timeframes.edit', [
                    'module' => $module,
                    'view_col' => $module->view_col,
                ])->with('timeframe', $timeframe);
            } else {
                return view('errors.404', [
                    'record_id' => $id,
                    'record_name' => ucfirst("timeframe"),
                ]);
            }
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Update the specified timeframe in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id timeframe ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        if(Module::hasAccess("Timeframes", "edit")) {
            
            $rules = Module::validateRules("Timeframes", $request, true);
            
            $validator = Validator::make($request->all(), $rules);
            
            if($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();;
            }
            
            $insert_id = Module::updateRow("Timeframes", $request, $id);
            
            return redirect()->route(config('laraadmin.adminRoute') . '.timeframes.index');
            
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Remove the specified timeframe from storage.
     *
     * @param int $id timeframe ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        if(Module::hasAccess("Timeframes", "delete")) {
            Timeframe::find($id)->delete();
            
            // Redirecting to index() method
            return redirect()->route(config('laraadmin.adminRoute') . '.timeframes.index');
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
        $module = Module::get('Timeframes');
        $listing_cols = Module::getListingColumns('Timeframes');
        
        $values = DB::table('timeframes')->select($listing_cols)->whereNull('deleted_at');
        $out = Datatables::of($values)->make();
        $data = $out->getData();
        
        $fields_popup = ModuleFields::getModuleFields('Timeframes');
        
        for($i = 0; $i < count($data->data); $i++) {
            for($j = 0; $j < count($listing_cols); $j++) {
                $col = $listing_cols[$j];
                if($fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
                    $data->data[$i][$j] = ModuleFields::getFieldValue($fields_popup[$col], $data->data[$i][$j]);
                }
                if($col == $module->view_col) {
                    $data->data[$i][$j] = '<a href="' . url(config('laraadmin.adminRoute') . '/timeframes/' . $data->data[$i][0]) . '">' . $data->data[$i][$j] . '</a>';
                }
                // else if($col == "author") {
                //    $data->data[$i][$j];
                // }
            }
            
            if($this->show_action) {
                $output = '';
                if(Module::hasAccess("Timeframes", "edit")) {
                    $output .= '<a href="' . url(config('laraadmin.adminRoute') . '/timeframes/' . $data->data[$i][0] . '/edit') . '" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-edit"></i></a>';
                }
                
                if(Module::hasAccess("Timeframes", "delete")) {
                    $output .= Form::open(['route' => [config('laraadmin.adminRoute') . '.timeframes.destroy', $data->data[$i][0]], 'method' => 'delete', 'style' => 'display:inline']);
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
