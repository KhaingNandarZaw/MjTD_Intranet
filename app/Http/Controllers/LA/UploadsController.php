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
use App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response as FacadeResponse;
use Illuminate\Support\Facades\Input;
use Collective\Html\FormFacade as Form;

use Dwij\Laraadmin\Models\Module;
use Dwij\Laraadmin\Helpers\LAHelper;
use Zizaco\Entrust\EntrustFacade as Entrust;

use Auth;
use DB;
use File;
use Validator;
use Datatables;

use App\Models\Upload;

class UploadsController extends Controller
{
	public $show_action = true;
	
	public function __construct() {
		// for authentication (optional)
		$this->middleware('auth', ['except' => 'get_file']);
	}
	
	/**
	 * Display a listing of the Uploads.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$module = Module::get('Uploads');
		
		if(Module::hasAccess($module->id)) {
			return View('la.uploads.index', [
				'show_actions' => $this->show_action,
				'module' => $module
			]);
		} else {
            return redirect(config('laraadmin.adminRoute')."/");
        }
	}
	
	/**
     * Get file
     *
     * @return \Illuminate\Http\Response
     */
    public function get_file($hash, $name)
    {
        $upload = Upload::where("hash", $hash)->first();
        
        // Validate Upload Hash & Filename
        if(!isset($upload->id) || $upload->name != $name) {
            return response()->json([
                'status' => "failure",
                'message' => "Unauthorized Access 1"
            ]);
        }

        // if($upload->public == 1) {
        //     $upload->public = true;
        // } else {
        //     $upload->public = false;
        // }

        // // Validate if Image is Public
        // if(!$upload->public && !isset(Auth::user()->id)) {
        //     return response()->json([
        //         'status' => "failure",
        //         'message' => "Unauthorized Access 2",
        //     ]);
        // }

		$path = $upload->path;

		if(!File::exists($path))
			abort(404);
		
		// Check if thumbnail
		$size = Input::get('s');
		if(isset($size)) {
			if(!is_numeric($size)) {
				$size = 150;
			}
			$thumbpath = storage_path("thumbnails/".basename($upload->path)."-".$size."x".$size);
			
			if(File::exists($thumbpath)) {
				$path = $thumbpath;
			} else {
				// Create Thumbnail
				LAHelper::createThumbnail($upload->path, $thumbpath, $size, $size, "transparent");
				$path = $thumbpath;
			}
		}

		$file = File::get($path);
		$type = File::mimeType($path);

		$download = Input::get('download');
		if(isset($download)) {
			return response()->download($path, $upload->name);
		} else {
			$response = FacadeResponse::make($file, 200);
			$response->header("Content-Type", $type);
		}
		
		return $response;        
    }

    /**
     * Upload fiels via DropZone.js
     *
     * @return \Illuminate\Http\Response
     */
    public function upload_files() {
        
		if(Module::hasAccess("Uploads", "create")) {
			$input = Input::all();
			
			if(Input::hasFile('file')) {
				/*
				$rules = array(
					'file' => 'mimes:jpg,jpeg,bmp,png,pdf|max:3000',
				);
				$validation = Validator::make($input, $rules);
				if ($validation->fails()) {
					return response()->json($validation->errors()->first(), 400);
				}
				*/
				$file = Input::file('file');
				
				// print_r($file);
				
				$folder = storage_path('uploads');
				$filename = $file->getClientOriginalName();
	
				$date_append = date("Y-m-d-His-");
				$upload_success = Input::file('file')->move($folder, $date_append.$filename);
				
				if( $upload_success ) {
	
					// Get public preferences
					// config("laraadmin.uploads.default_public")
					$public = Input::get('public');
					if(isset($public)) {
						$public = true;
					} else {
						$public = false;
					}
	
					$upload = Upload::create([
						"name" => $filename,
						"path" => $folder.DIRECTORY_SEPARATOR.$date_append.$filename,
						"extension" => pathinfo($filename, PATHINFO_EXTENSION),
						"caption" => "",
						"hash" => "",
						"public" => $public,
						"user_id" => Auth::user()->id
					]);
					// apply unique random hash to file
					while(true) {
						$hash = strtolower(str_random(20));
						if(!Upload::where("hash", $hash)->count()) {
							$upload->hash = $hash;
							break;
						}
					}
					$upload->save();
	
					return response()->json([
						"status" => "success",
						"upload" => $upload
					], 200);
				} else {
					return response()->json([
						"status" => "error"
					], 400);
				}
			} else {
				return response()->json('error: upload file not found.', 400);
			}
		} else {
			return response()->json([
				'status' => "failure",
				'message' => "Unauthorized Access"
			]);
		}
    }
	public function upload_task_files(Request $request){
		$input = Input::all();
		$today = date('Y-m-d h:i:s');

		if(Input::hasFile('file')) {
			$file = Input::file('file');
			
			$folder = storage_path('uploads');
			$filename = $file->getClientOriginalName();

			$date_append = date("Y-m-d-His-");
			$fileContent = file_get_contents($file->getRealPath());
			$data = base64_encode($fileContent);
			
			if( $data != null ) {
				$insertedID = DB::table('task_attachments')->insertGetId([
					"created_at" => $today,
					"filename" => $filename,
					"extension" => pathinfo($filename, PATHINFO_EXTENSION),
					"hash" => "",
					"attach_file" => $data,
					"status" => 1
				]);

				// apply unique random hash to file
				while(true) {
					$hash = strtolower(str_random(20));
					if(!DB::table('task_attachments')->where("hash", $hash)->count()){
						$upload = DB::table('task_attachments')->where('id', $insertedID)->update(['hash' => $hash]);
						break;
					}
				}
				$upload = DB::table('task_attachments')->where('id', $insertedID)->first();
				return response()->json([
					"status" => "success",
					"upload" => $upload
				], 200);
			} else {
				return response()->json([
					"status" => "error"
				], 400);
			}
		} else {
			return response()->json('error: upload file not found.', 400);
		}
	}
    /**
     * Get all files from uploads folder
     *
     * @return \Illuminate\Http\Response
     */
    public function uploaded_files()
    {
		if(Module::hasAccess("Uploads", "view")) {
			$uploads = array();
	
			// print_r(Auth::user()->roles);
			if(Entrust::hasRole('SUPER_ADMIN')) {
				$uploads = Upload::all();
			} else {
				if(config('laraadmin.uploads.private_uploads')) {
					// Upload::where('user_id', 0)->first();
					$uploads = Auth::user()->uploads;
				} else {
					$uploads = Upload::all();
				}
			}
			$uploads2 = array();
			foreach ($uploads as $upload) {
				$u = (object) array();
				$u->id = $upload->id;
				$u->name = $upload->name;
				$u->extension = $upload->extension;
				$u->hash = $upload->hash;
				$u->public = $upload->public;
				$u->caption = $upload->caption;
				$u->user = $upload->user->name;
				
				$uploads2[] = $u;
			}
			
			// $folder = storage_path('/uploads');
			// $files = array();
			// if(file_exists($folder)) {
			//     $filesArr = File::allFiles($folder);
			//     foreach ($filesArr as $file) {
			//         $files[] = $file->getfilename();
			//     }
			// }
			// return response()->json(['files' => $files]);
			return response()->json(['uploads' => $uploads2]);
		} else {
			return response()->json([
				'status' => "failure",
				'message' => "Unauthorized Access"
			]);
		}
    }

	public function uploaded_files_byid(Request $request){
		$hinput = $request['hinput'];
		$uploaded_lists = json_decode($hinput);
		$uploads = DB::table('uploads')->whereIn("id", $uploaded_lists)->whereNull('deleted_at')->get();
		
		return response()->json(['uploads' => $uploads]);
	}
    /**
     * Update Uploads Caption
     *
     * @return \Illuminate\Http\Response
     */
    public function update_caption()
    {
        if(Module::hasAccess("Uploads", "edit")) {
			$file_id = Input::get('file_id');
			$caption = Input::get('caption');
			
			$upload = Upload::find($file_id);
			if(isset($upload->id)) {
				if($upload->user_id == Auth::user()->id || Entrust::hasRole('SUPER_ADMIN')) {
	
					// Update Caption
					$upload->caption = $caption;
					$upload->save();
	
					return response()->json([
						'status' => "success"
					]);
	
				} else {
					return response()->json([
						'status' => "failure",
						'message' => "Upload not found"
					]);
				}
			} else {
				return response()->json([
					'status' => "failure",
					'message' => "Upload not found"
				]);
			}
		} else {
			return response()->json([
				'status' => "failure",
				'message' => "Unauthorized Access"
			]);
		}
    }

    /**
     * Update Uploads Filename
     *
     * @return \Illuminate\Http\Response
     */
    public function update_filename()
    {
        if(Module::hasAccess("Uploads", "edit")) {
			$file_id = Input::get('file_id');
			$filename = Input::get('filename');
			
			$upload = Upload::find($file_id);
			if(isset($upload->id)) {
				if($upload->user_id == Auth::user()->id || Entrust::hasRole('SUPER_ADMIN')) {
	
					// Update Caption
					$upload->name = $filename;
					$upload->save();
	
					return response()->json([
						'status' => "success"
					]);
	
				} else {
					return response()->json([
						'status' => "failure",
						'message' => "Unauthorized Access 1"
					]);
				}
			} else {
				return response()->json([
					'status' => "failure",
					'message' => "Upload not found"
				]);
			}
		} else {
			return response()->json([
				'status' => "failure",
				'message' => "Unauthorized Access"
			]);
		}
    }

    /**
     * Update Uploads Public Visibility
     *
     * @return \Illuminate\Http\Response
     */
    public function update_public()
    {
		if(Module::hasAccess("Uploads", "edit")) {
			$file_id = Input::get('file_id');
			$public = Input::get('public');
			if(isset($public)) {
				$public = true;
			} else {
				$public = false;
			}
			
			$upload = Upload::find($file_id);
			if(isset($upload->id)) {
				if($upload->user_id == Auth::user()->id || Entrust::hasRole('SUPER_ADMIN')) {
	
					// Update Caption
					$upload->public = $public;
					$upload->save();
	
					return response()->json([
						'status' => "success"
					]);
	
				} else {
					return response()->json([
						'status' => "failure",
						'message' => "Unauthorized Access 1"
					]);
				}
			} else {
				return response()->json([
					'status' => "failure",
					'message' => "Upload not found"
				]);
			}
		} else {
			return response()->json([
				'status' => "failure",
				'message' => "Unauthorized Access"
			]);
		}
    }

    /**
     * Remove the specified upload from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function delete_file()
    {
        if(Module::hasAccess("Uploads", "delete")) {
			$file_id = Input::get('file_id');
			
			$upload = Upload::find($file_id);
			if(isset($upload->id)) {
				if($upload->user_id == Auth::user()->id || Entrust::hasRole('SUPER_ADMIN')) {
	
					// Update Caption
					$upload->delete();
	
					return response()->json([
						'status' => "success"
					]);
	
				} else {
					return response()->json([
						'status' => "failure",
						'message' => "Unauthorized Access 1"
					]);
				}
			} else {
				return response()->json([
					'status' => "failure",
					'message' => "Upload not found"
				]);
			}
		} else {
			return response()->json([
				'status' => "failure",
				'message' => "Unauthorized Access"
			]);
		}
	}
	public function upload_ManualFiles() {
		if(Module::hasAccess("Uploads", "create")) {
			$input = Input::all();
			$today = date('Y-m-d h:i:s');

			if(Input::hasFile('file')) {
				$file = Input::file('file');
				
				$folder = storage_path('uploads');
				$filename = $file->getClientOriginalName();
	
				$date_append = date("Y-m-d-His-");
				$fileContent = file_get_contents($file->getRealPath());
				$data = base64_encode($fileContent);
				
				if( $data != null ) {
					$insertedID = DB::table('sop_manual_uploads')->insertGetId([
						"created_at" => $today,
						"filename" => $filename,
						"extension" => pathinfo($filename, PATHINFO_EXTENSION),
						"hash" => "",
						"createdBy" => Auth::user()->id,
						"manual_file" => $data,
						"status" => 1,
						"pic_userid" => Input::get('userid')
					]);
					// apply unique random hash to file
					while(true) {
						$hash = strtolower(str_random(20));
						if(!DB::table('sop_manual_uploads')->where("hash", $hash)->count()){
							$upload = DB::table('sop_manual_uploads')->where('id', $insertedID)->update(['hash' => $hash]);
							break;
						}
					}
					$upload = DB::table('sop_manual_uploads')->where('id', $insertedID)->first();
					return response()->json([
						"status" => "success",
						"upload" => $upload
					], 200);
				} else {
					return response()->json([
						"status" => "error"
					], 400);
				}
			} else {
				return response()->json('error: upload file not found.', 400);
			}
		} else {
			return response()->json([
				'status' => "failure",
				'message' => "Unauthorized Access"
			]);
		}
	}
	public function upload_WorkflowFiles() {
		if(Module::hasAccess("Uploads", "create")) {
			$input = Input::all();
			$today = date('Y-m-d h:i:s');

			if(Input::hasFile('file')) {
				$file = Input::file('file');
				
				$folder = storage_path('uploads');
				$filename = $file->getClientOriginalName();
	
				$date_append = date("Y-m-d-His-");
				$fileContent = file_get_contents($file->getRealPath());
				$data = base64_encode($fileContent);
				
				if( $data != null ) {
					$insertedID = DB::table('sop_flowchart_uploads')->insertGetId([
						"created_at" => $today,
						"filename" => $filename,
						"extension" => pathinfo($filename, PATHINFO_EXTENSION),
						"hash" => "",
						"createdBy" => Auth::user()->id,
						'flowchart_files' => $data,
						'status' => 1,
						"pic_userid" => Input::get('userid')
					]);
					// apply unique random hash to file
					while(true) {
						$hash = strtolower(str_random(20));
						if(!DB::table('sop_flowchart_uploads')->whereNull('deleted_at')->where("hash", $hash)->count()){
							$upload = DB::table('sop_flowchart_uploads')->where('id', $insertedID)->update(['hash' => $hash]);
							break;
						}
					}
					$upload = DB::table('sop_flowchart_uploads')->where('id', $insertedID)->whereNull('deleted_at')->first();
					return response()->json([
						"status" => "success",
						"upload" => $upload
					], 200);
				} else {
					return response()->json([
						"status" => "error"
					], 400);
				}
			} else {
				return response()->json('error: upload file not found.', 400);
			}
		} else {
			return response()->json([
				'status' => "failure",
				'message' => "Unauthorized Access"
			]);
		}
	}
	public function get_manualFile($hash, $name)
    {
        $upload = DB::table('sop_manual_uploads')->where("hash", $hash)->whereNull('deleted_at')->first();
        
        // Validate Upload Hash & Filename
        if(!isset($upload->id) || $upload->filename != $name) {
            return response()->json([
                'status' => "failure",
                'message' => "Unauthorized Access 1"
            ]);
        }

        if(Entrust::hasRole('SUPER_ADMIN') || Auth::user()->id == $upload->createdBy || Auth::user()->id == $upload->pic_userid) {
			$file_contents = base64_decode($upload->manual_file);
            file_put_contents($upload->filename, $file_contents);
            $path = public_path($upload->filename);

            if(!File::exists($path))
                abort(404);

            $file = file_get_contents($path);
			$type = File::mimeType($path);
			
            $download = Input::get('download');
            if(isset($download)) {
                return response()->download($path, $upload->filename);
            } else {
                $response = FacadeResponse::make($file, 200);
                $response->header("Content-Type", $type);
            }
            
            return $response;
        } else {
            return response()->json([
                'status' => "failure",
                'message' => "Unauthorized Access 3"
            ]);
        }
	}
	public function get_workflowFile($hash, $name)
    {
        $upload = DB::table('sop_flowchart_uploads')->where("hash", $hash)->whereNull('deleted_at')->first();
        
        // Validate Upload Hash & Filename
        if(!isset($upload->id) || $upload->filename != $name) {
            return response()->json([
                'status' => "failure",
                'message' => "Unauthorized Access 1"
            ]);
        }

        if(Entrust::hasRole('SUPER_ADMIN') || Auth::user()->id == $upload->createdBy || Auth::user()->id == $upload->pic_userid) {
			$file_contents = base64_decode($upload->flowchart_files);
            file_put_contents($upload->filename, $file_contents);
            $path = public_path($upload->filename);

            if(!File::exists($path))
                abort(404);

            $file = file_get_contents($path);
			$type = File::mimeType($path);
			
            $download = Input::get('download');
            if(isset($download)) {
                return response()->download($path, $upload->filename);
            } else {
                $response = FacadeResponse::make($file, 200);
                $response->header("Content-Type", $type);
            }
            
            return $response;
        } else {
            return response()->json([
                'status' => "failure",
                'message' => "Unauthorized Access 3"
            ]);
        }
	}
	public function get_task_attachments($hash, $name){
		$upload = DB::table('task_attachments')->where("hash", $hash)->whereNull('deleted_at')->first();
        
        // Validate Upload Hash & Filename
        if(!isset($upload->id) || $upload->filename != $name) {
            return response()->json([
                'status' => "failure",
                'message' => "Unauthorized Access 1"
            ]);
        }

        $file_contents = base64_decode($upload->attach_file);
		file_put_contents($upload->filename, $file_contents);
		$path = public_path($upload->filename);

		if(!File::exists($path))
			abort(404);

		$file = file_get_contents($path);
		$type = File::mimeType($path);
		
		$download = Input::get('download');
		if(isset($download)) {
			return response()->download($path, $upload->filename);
		} else {
			$response = FacadeResponse::make($file, 200);
			$response->header("Content-Type", $type);
		}
		
		return $response;
	}
	public function uploaded_flowchartFiles(Request $request)
    {
		if(Module::hasAccess("Uploads", "view")) {
			$pic_id = $request['pic_id'];
			$uploads = DB::table('sop_flowchart_uploads')->select('id', 'pic_userid', 'status', 'filename', 'extension', 'hash')->where("pic_userid", $pic_id)->whereNull('deleted_at')->get();
			
			return response()->json(['uploads' => $uploads]);
		} else {
			return response()->json([
				'status' => "failure",
				'message' => "Unauthorized Access"
			]);
		}
	}
	public function uploaded_task_attachments(Request $request){
		$hinput = $request['hinput'];
		$uploaded_lists = json_decode($hinput);
		$uploads = DB::table('task_attachments')->whereIn("id", $uploaded_lists)->whereNull('deleted_at')->get();
		
		return response()->json(['uploads' => $uploads]);
	}
	public function uploaded_manualFiles(Request $request)
    {
		if(Module::hasAccess("Uploads", "view")) {
			$pic_id = $request['pic_id'];
			$uploads = DB::table('sop_manual_uploads')->select('id', 'pic_userid', 'status', 'filename', 'extension', 'hash')->where("pic_userid", $pic_id)->whereNull('deleted_at')->get();
			
			return response()->json(['uploads' => $uploads]);
		} else {
			return response()->json([
				'status' => "failure",
				'message' => "Unauthorized Access"
			]);
		}
	}
	public function delete_task_attachment(Request $request){
		$file_id = $request->input('file_id');

		$upload = DB::table('task_attachments')->where("id", $file_id)->whereNull('deleted_at')->first();
		
		if(isset($upload->id)) {
			
			$today = date('Y-m-d h:i:s');
            
            DB:: table('task_attachments')->where('id', $file_id)->delete();

			return response()->json([
				'status' => "success"
			]);

		} else {
			return response()->json([
				'status' => "failure",
				'message' => "Upload not found"
			]);
		}
	}
	public function delete_manualfiles(Request $request)
    {
		$file_id = $request->input('file_id');

		$upload = DB::table('sop_manual_uploads')->where("id", $file_id)->whereNull('deleted_at')->first();
		
		if(isset($upload->id)) {
			
			$today = date('Y-m-d h:i:s');
            
            DB:: table('sop_manual_uploads')->where('id', $file_id)->delete();

			return response()->json([
				'status' => "success"
			]);

		} else {
			return response()->json([
				'status' => "failure",
				'message' => "Upload not found"
			]);
		}
	}
	public function delete_flowchartfiles(Request $request){
		$file_id = $request->input('file_id');

		$upload = DB::table('sop_flowchart_uploads')->where("id", $file_id)->whereNull('deleted_at')->first();
		
		if(isset($upload->id)) {
			
			$today = date('Y-m-d h:i:s');
            
            DB:: table('sop_flowchart_uploads')->where('id', $file_id)->delete();

			return response()->json([
				'status' => "success"
			]);

		} else {
			return response()->json([
				'status' => "failure",
				'message' => "Upload not found"
			]);
		}
	}
}
