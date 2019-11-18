<?php

namespace App\Http\Controllers;

use DB;
use Dwij\Laraadmin\Models\Module;
use Dwij\Laraadmin\Models\ModuleFields;
use App\Models\SOP_Set_up;
use App\Models\SOP_Management_Type;
use Illuminate\Http\Request;

class AdminthemeController extends Controller
{
    public function index(){
            // $queryusers = DB::table('sop_management_types')->select('sop_management_types.name, sop_management_types.description FROM `sop_set_ups` inner join sop_management_types on sop_management_types.id = sop_set_ups.sop_management_type group by sop_management_types.name');
            $SOP_Management_Types = SOP_Management_Type::all();

            return view('admintheme',compact('SOP_Management_Types'));

            // dd($query);

        // $value = ModuleFields::getFieldValue('SOP_Management_Types');
        // $module = Module::get('SOP_Management_Types');
        // dd($value);
    	
    	// $queries = SOP_Set_up::all();
    	// $SOP_Set_ups = SOP_Set_up::all();   	
    	// 	foreach ($SOP_Set_ups as $SOP_Set_up) {
    			
    	// 			$query = SOP_Set_up::find($SOP_Set_up->id);    				
    	// 			// print($query);

    	// }
    	// dd($SOP_Set_ups);
    	// return view('admintheme', compact('SOP_Management_Types' , 'module'));
    	// return view('admintheme',compact('SOP_Management_Types' , 'SOP_Set_ups'));
    	// return view('admintheme',compact('SOP_Management_Types','SOP_Set_ups','query'));
    }
}
