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

use App\Models\SOP_Setup;
use App\Models\Task_Instance;

class SOP_SetupsController extends Controller
{
    public $show_action = true;
    
    /**
     * Display a listing of the SOP_Setups.
     *
     * @return mixed
     */
    public function index()
    {        
        if(Module::hasAccess("SOP_Setups", "create")) {
            $module = Module::get('SOP_Setups');
            $manual_module = Module::get('SOP_Manual_Uploads');
            $flowchart_module = Module::get('SOP_Flowchart_Uploads');

            $sop_setup_lists = SOP_Setup::all();
            return view('la.sop_setups.index', [
                'manual_module' => $manual_module,
                'flowchart_module' => $flowchart_module,
                'module' => $module,
                'show_actions' => $this->show_action,
                'listing_cols' => Module::getListingColumns('SOP_Setups'),
                'view_col' => $module->view_col,
                'sop_setup_lists' => $sop_setup_lists
            ]);
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Show the form for creating a new sop_setup.
     *
     * @return mixed
     */
    public function create()
    {
        
    }
    
    /**
     * Store a newly created sop_setup in database.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        if(Module::hasAccess("SOP_Setups", "create")) {
            
            $rules = Module::validateRules("SOP_Setups", $request);
            $today = date('Y-m-d H:i:s');

            // $sop_setup = DB::table('sop_setups')->insertGetId([
            //     "created_at" => $today,
            //     "pic_userid" => $request->pic_userid,
            //     "work_description" => $request->work_description,
            //     "job_type" => $request->job_type,
            //     "timeframe" => $request->timeframe,
            //     "remark" => $request->remark
            // ]);

            $request->merge([
                'report_to_userid' => $request->input('report_to')
            ]);
            $insert_id = Module::insert("SOP_Setups", $request);

            $supporting_count = $request->input('supporting_count');
            for($i = 1; $i<=$supporting_count; $i++)
            {
                if($request->input('supporting_'.$i) != null)
                {
                    $supporting_userid = $request->input('supporting_'.$i);
                    $insert = DB::table('sop_supporting_users')->insertGetId([
                        "created_at" => $today,
                        "supporting_userid" => $supporting_userid,
                        "sop_setup_id" => $insert_id
                    ]);
                }
            }

            $reportTo_count = $request->input('reportTo_count');
            for($i = 1; $i<=$reportTo_count; $i++)
            {
                if($request->input('reportTo_'.$i) != null)
                {
                    $reportTo_userid = $request->input('reportTo_'.$i);
                    $insert = DB::table('sop_acknowledgeto_users')->insertGetId([
                        "created_at" => $today,
                        "reportTo_user_id" => $reportTo_userid,
                        "sop_setup_id" => $insert_id
                    ]);
                }
            }
            
            return redirect()->route(config('laraadmin.adminRoute') . '.sop_setups.index');
            
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Display the specified sop_setup.
     *
     * @param int $id sop_setup ID
     * @return mixed
     */
    public function show($id)
    {
        if(Module::hasAccess("SOP_Setups", "view")) {
            
            $sop_setup = SOP_Setup::find($id);
            if(isset($sop_setup->id)) {
                $module = Module::get('SOP_Setups');
                $module->row = $sop_setup;
                
                return view('la.sop_setups.detail', [
                    'module' => $module,
                    'view_col' => $module->view_col,
                    'no_header' => true,
                    'no_padding' => "no-padding"
                ])->with('sop_setup', $sop_setup);
            } else {
                return view('errors.404', [
                    'record_id' => $id,
                    'record_name' => ucfirst("sop_setup"),
                ]);
            }
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Show the form for editing the specified sop_setup.
     *
     * @param int $id sop_setup ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        if(Module::hasAccess("SOP_Setups", "edit")) {
            $sop_setup = SOP_Setup::find($id);
            if(isset($sop_setup->id)) {
                $module = Module::get('SOP_Setups');
                
                $module->row = $sop_setup;
                
                return view('la.sop_setups.edit', [
                    'module' => $module,
                    'view_col' => $module->view_col,
                ])->with('sop_setup', $sop_setup);
            } else {
                return view('errors.404', [
                    'record_id' => $id,
                    'record_name' => ucfirst("sop_setup"),
                ]);
            }
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Update the specified sop_setup in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id sop_setup ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        if(Module::hasAccess("SOP_Setups", "edit")) {
            
            $rules = Module::validateRules("SOP_Setups", $request, true);
            
            $validator = Validator::make($request->all(), $rules);
            
            if($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();;
            }
            
            $insert_id = Module::updateRow("SOP_Setups", $request, $id);
            
            return redirect()->route(config('laraadmin.adminRoute') . '.sop_setups.index');
            
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Remove the specified sop_setup from storage.
     *
     * @param int $id sop_setup ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        if(Module::hasAccess("SOP_Setups", "delete")) {
            SOP_Setup::find($id)->delete();
            Task_Instance::where('task_id', $id)->where('status','On Progress')->where('task_type', 'SOP')->delete();
            // Redirecting to index() method
            return redirect(config('laraadmin.adminRoute') . "/my_sops");
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

    public function getSOPDataByPIC(Request $request){
        $pic_id = $request['pic_id'];
        $values = DB::table('sop_user_by_id')
            ->select('id', 'work_description as WorkDescription', 'JobType', 'TimeFrame', 'Supportings', 'ReportTo', 'AcknowledgeTo', 'Remark')
            ->where('pic_user_id', '=', $pic_id)->get();

        return $values;
    }

    public function dtajax(Request $request)
    {
        $pic_id = $request['pic_id'];
        $module = Module::get('SOP_Setups');
        $listing_cols = Module::getListingColumns('SOP_Setups');
        
        $values = DB::table('sop_setups')->select($listing_cols)->whereNull('deleted_at')->where('pic_userid', '=', $pic_id);
        $out = Datatables::of($values)->make();
        $data = $out->getData();
        
        $fields_popup = ModuleFields::getModuleFields('SOP_Setups');
        
        for($i = 0; $i < count($data->data); $i++) {
            for($j = 0; $j < count($listing_cols); $j++) {
                $col = $listing_cols[$j];
                if($fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
                    $data->data[$i][$j] = ModuleFields::getFieldValue($fields_popup[$col], $data->data[$i][$j]);
                }
                if($col == $module->view_col) {
                    $data->data[$i][$j] = '<a href="' . url(config('laraadmin.adminRoute') . '/sop_setups/' . $data->data[$i][0]) . '">' . $data->data[$i][$j] . '</a>';
                }
            }
            
            if($this->show_action) {
                $output = '';
                if(Module::hasAccess("SOP_Setups", "edit")) {
                    $output .= '<a href="' . url(config('laraadmin.adminRoute') . '/sop_setups/' . $data->data[$i][0] . '/edit') . '" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-edit"></i></a>';
                }
                
                if(Module::hasAccess("SOP_Setups", "delete")) {
                    $output .= Form::open(['route' => [config('laraadmin.adminRoute') . '.sop_setups.destroy', $data->data[$i][0]], 'method' => 'delete', 'style' => 'display:inline']);
                    $output .= ' <button class="btn btn-danger btn-xs" type="submit"><i class="fa fa-trash"></i></button>';
                    $output .= Form::close();
                }
                $data->data[$i][] = (string)$output;
            }
        }
        $out->setData($data);
        return $out;
    }
    public function getSOPList(){
        if(Module::hasAccess("SOP_Setups", "view")) {
            $users = [];
            $child_lists = [];
			if(!Entrust::hasRole('EMPLOYEE')) {
                $childs = DB::select('call getAllChildUsers(?)', array(Auth::user()->department));

                for ($i=0; $i < count($childs); $i++) { 
                    if($childs[$i]->id != Auth::user()->id)
                        array_push($users, $childs[$i]);
                }
                for ($i=0; $i < count($users); $i++) { 
                    array_push($child_lists, $users[$i]->id);
                }
				$values = DB::table('sop_user_by_id')
                    ->select('id', 'work_description as WorkDescription', 'PIC', 'JobType', 'TimeFrame', 'Supportings', 'ReportTo', 'AcknowledgeTo', 'Remark')
                    ->whereIn('pic_user_id', $child_lists)
                    ->get();
			} else {
                $pic_id = Auth::user()->id;
				$values = DB::table('sop_user_by_id')
                    ->select('id', 'work_description as WorkDescription', 'PIC', 'JobType', 'TimeFrame', 'Supportings', 'ReportTo', 'AcknowledgeTo', 'Remark')
                    ->where('pic_user_id', $pic_id)->get();
            }
            $query = DB::table('sop_user_by_id')
                ->select('pic_user_id', 'PIC')
                ->whereNotNull('pic_user_id');
            if(isset($child_lists) && ($child_lists != []))
            {
                $query = $query->whereIn('pic_user_id', $child_lists);
            }
            $pic_users = $query->groupBy('sop_user_by_id.pic_user_id')->get();
            return view('la.sop_setups.show', [
                'no_header' => true,
                'no_padding' => "no-padding",
                'users' => $users
            ])->with('sops', $values)->with('pic_users', $pic_users)->with('pic_userid', 0);
		}else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    public function filter(Request $request){
        $pic_userid = $request->pic_userid;
        $users = [];
        $child_lists = [];

        

        if(!Entrust::hasRole('EMPLOYEE')) {
            $childs = DB::select('call getAllChildUsers(?)', array(Auth::user()->department));

            for ($i=0; $i < count($childs); $i++) { 
                if($childs[$i]->id != Auth::user()->id)
                    array_push($users, $childs[$i]);
            }
            for ($i=0; $i < count($users); $i++) { 
                array_push($child_lists, $users[$i]->id);
            }
            $query = DB::table('sop_user_by_id')
                ->select('id', 'work_description as WorkDescription', 'PIC', 'JobType', 'TimeFrame', 'Supportings', 'ReportTo', 'AcknowledgeTo', 'Remark')
                ->whereIn('pic_user_id', $child_lists);
        } else {
            $pic_id = Auth::user()->id;
            $query = DB::table('sop_user_by_id')
            ->select('id', 'work_description as WorkDescription', 'PIC', 'JobType', 'TimeFrame', 'Supportings', 'ReportTo', 'AcknowledgeTo', 'Remark');
        }

        if(isset($pic_userid) && ($pic_userid != '0'))
        {
            $query = $query->where('pic_user_id','=', $pic_userid);
        }
        $values = $query->get();
                
        $query = DB::table('sop_user_by_id')
            ->select('pic_user_id', 'PIC')
            ->whereNotNull('pic_user_id');

            if(isset($child_lists) && ($child_lists != []))
            {
                $query = $query->whereIn('pic_user_id', $child_lists);
            }
        $pic_users = $query->groupBy('sop_user_by_id.pic_user_id')->get();
        return view('la.sop_setups.show', [
            'no_header' => true,
            'no_padding' => "no-padding",
            'users' => $users
        ])->with('sops', $values)->with('pic_users', $pic_users)->with('pic_userid', $pic_userid);
    }
}
