<?php 

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


class ViewController extends Controller {

    public function task_management($method){
        if($method == "check_task") {
            return view('la.task_management.check_task');
        }
        else if($method == "task") {
            return view('la.task_management.task');
        }
        else if($method == "task_assign") {
            return view('la.task_management.task_assign');
        }
        else if($method == "create_task") {
            return view('la.task_management.create_task');
        }
        else if($method == "edit_task") {
            return view('la.task_management.edit_task');
        }
        else if($method == "summary_po"){
            return view('la.task_management.summary_po');
        }else if($method == 'detail_po'){
            return view('la.task_management.detail_po');
        }else if($method == 'calendar'){
            return view('la.task_management.calendar');
        }else if($method == 'evaluation_report'){
            return view('la.task_management.evaluation_report');
        } else if($method == 'detail_evaluation_report'){
            return view('la.task_management.detail_evaluation_report');
        }
    }

    public function currency($method) {
        if($method == "index") {
            return view('la.currency.index');
        }
    }
    
}


?>