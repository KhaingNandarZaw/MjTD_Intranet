<?php

namespace App\Http\Controllers;

use App\Imports\ImportUsers;
use Illuminate\Http\Request;
use DB;
use Excel;
use App\SopExcel;
use App\User;
use Session;

class SopExcelController extends Controller
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function importExportView()
    {
       return view('la.sop_excel.show');
    }
   
    /**
    * @return \Illuminate\Support\Collection
    */
    public function export() 
    {
        
    }

    public function import(Request $request)
	{
		if($request->hasFile('excelfile')){
			$path = $request->file('excelfile')->getRealPath();

			$data = Excel::load($path, function($reader) {})->get();
            //dd($data);

			if(!empty($data) && $data->count()){

                $array_data=$data->toArray();

				foreach ($array_data as $key => $value) {

					if(!empty($value)){
                        
                            $insert[] = [
                                'description' => $value['work_description'],
                                'jobtype' => $value['job_type'],
                                'timeframe' => $value['time_frame'],
                                'pic' => $value['pic'],
                                'participant' => $value['participant'],
                                'reportto' => $value['report_to'],
                                'remark' => $value['remark'],
                                        
                        ];
					}
				}

				
				if(!empty($insert)){
					SopExcel::insert($insert);
					return back()->with('success','Insert Record successfully.');
				}
			}
		}
		return back()->with('error','Please Check your file, Something is wrong there.');
	}
  
    public function checkUser(Request $request)
    {        
        if($request->hasFile('file')){            
			$path = $request->file('file')->getRealPath();
			$data = Excel::load($path, function($reader) {})->get();
            
			if(!empty($data) && $data->count()){
                $array_data=$data->toArray();
                $users=User::all();
                $invalidusers=array();

				foreach ($array_data as $key => $value) {
                    $user=count(User::where('name',$value['pic'])->get());
                   
                    if($user == 0){
                        $invalidusers[]=$value['pic'];
                    }
                }
                 $arr= array();
                $arr['arr1'] = $array_data;
                $arr['arr2'] = $invalidusers;
                return json_encode($arr);
			}
		}
    }
}











