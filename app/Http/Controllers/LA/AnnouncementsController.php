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
use Mail;

use App\Models\Announcement;
use App\Mail\AnnouncementsMail;


class AnnouncementsController extends Controller
{
    public $show_action = true;
    
    /**
     * Display a listing of the Announcements.
     *
     * @return mixed
     */
    public function index()
    {
        $module = Module::get('Announcements');
        
        if(Module::hasAccess($module->id)) {
            return View('la.announcements.index', [
                'show_actions' => $this->show_action,
                'listing_cols' => Module::getListingColumns('Announcements'),
                'module' => $module
            ]);
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Show the form for creating a new announcement.
     *
     * @return mixed
     */
    public function create()
    {
        if(Module::hasAccess("Announcements", "create")) {
            $module = Module::get('Announcements');
            return View('la.announcements.create', [
                'module' => $module
            ]);
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Store a newly created announcement in database.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        if(Module::hasAccess("Announcements", "create")) {
            
            $rules = Module::validateRules("Announcements", $request);
            
            $validator = Validator::make($request->all(), $rules);
            
            if($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            
            $insert_id = Module::insert("Announcements", $request);

            $subject = "New announcement created by " . Auth::user()->name;
            $to = $request->email;
            $users_temp = explode(',', $to);
            $users = [];
            foreach($users_temp as $key => $ut){
            $users[$key] = $ut;
            }
            $task_title = $request->title;
            $description = $request->description;

            Mail::to($users)->send(new AnnouncementsMail($task_title, $description, $subject));
            
            return redirect()->route(config('laraadmin.adminRoute') . '.announcements.index');
            
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Display the specified announcement.
     *
     * @param int $id announcement ID
     * @return mixed
     */
    public function show($id)
    {
        if(Module::hasAccess("Announcements", "view")) {
            
            $announcement = Announcement::find($id);
            if(isset($announcement->id)) {
                $module = Module::get('Announcements');
                $module->row = $announcement;
                
                return view('la.announcements.show', [
                    'module' => $module,
                    'view_col' => $module->view_col,
                    'no_header' => true,
                    'no_padding' => "no-padding"
                ])->with('announcement', $announcement);
            } else {
                return view('errors.404', [
                    'record_id' => $id,
                    'record_name' => ucfirst("announcement"),
                ]);
            }
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Show the form for editing the specified announcement.
     *
     * @param int $id announcement ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        if(Module::hasAccess("Announcements", "edit")) {
            $announcement = Announcement::find($id);
            if(isset($announcement->id)) {
                $module = Module::get('Announcements');
                
                $module->row = $announcement;
                
                return view('la.announcements.edit', [
                    'module' => $module,
                    'view_col' => $module->view_col,
                ])->with('announcement', $announcement);
            } else {
                return view('errors.404', [
                    'record_id' => $id,
                    'record_name' => ucfirst("announcement"),
                ]);
            }
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Update the specified announcement in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id announcement ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        if(Module::hasAccess("Announcements", "edit")) {
            
            $rules = Module::validateRules("Announcements", $request, true);
            
            $validator = Validator::make($request->all(), $rules);
            
            if($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();;
            }
            
            $insert_id = Module::updateRow("Announcements", $request, $id);
            
            return redirect()->route(config('laraadmin.adminRoute') . '.announcements.index');
            
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Remove the specified announcement from storage.
     *
     * @param int $id announcement ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        if(Module::hasAccess("Announcements", "delete")) {
            Announcement::find($id)->delete();
            
            // Redirecting to index() method
            return redirect()->route(config('laraadmin.adminRoute') . '.announcements.index');
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
        $module = Module::get('Announcements');
        $listing_cols = Module::getListingColumns('Announcements');
        
        $values = DB::table('announcements')->select($listing_cols)->whereNull('deleted_at');
        $out = Datatables::of($values)->make();
        $data = $out->getData();
        
        $fields_popup = ModuleFields::getModuleFields('Announcements');
        
        for($i = 0; $i < count($data->data); $i++) {
            for($j = 0; $j < count($listing_cols); $j++) {
                $col = $listing_cols[$j];
                if($fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
                    $data->data[$i][$j] = ModuleFields::getFieldValue($fields_popup[$col], $data->data[$i][$j]);
                }
                if($col == $module->view_col) {
                    $data->data[$i][$j] = '<a href="' . url(config('laraadmin.adminRoute') . '/announcements/' . $data->data[$i][0]) . '">' . $data->data[$i][$j] . '</a>';
                }
                // else if($col == "author") {
                //    $data->data[$i][$j];
                // }
            }
            
            if($this->show_action) {
                $output = '';
                if(Module::hasAccess("Announcements", "edit")) {
                    $output .= '<a href="' . url(config('laraadmin.adminRoute') . '/announcements/' . $data->data[$i][0] . '/edit') . '" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-edit"></i></a>';
                }
                
                if(Module::hasAccess("Announcements", "delete")) {
                    $output .= Form::open(['route' => [config('laraadmin.adminRoute') . '.announcements.destroy', $data->data[$i][0]], 'method' => 'delete', 'style' => 'display:inline']);
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
