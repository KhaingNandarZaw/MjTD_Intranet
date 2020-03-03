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
use Dwij\Laraadmin\Helpers\LAHelper;
use App\Mail\sendtoUser;
use Mail;


use App\User;
use App\Role;

class UsersController extends Controller
{
    public $show_action = true;
    
    /**
     * Display a listing of the Users.
     *
     * @return mixed
     */
    public function index()
    {
        $module = Module::get('Users');
        
        if(Module::hasAccess($module->id)) {
            return View('la.users.index', [
                'show_actions' => $this->show_action,
                'listing_cols' => Module::getListingColumns('Users'),
                'module' => $module
            ]);
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Show the form for creating a new user.
     *
     * @return mixed
     */
    public function create()
    {
        if(Module::hasAccess("Users", "create")) {
            $module = Module::get('Users');
            return view('la.users.create', [
                    'module' => $module]);
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Store a newly created user in database.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        if(Module::hasAccess("Users", "create")) {
            
            $rules = Module::validateRules("Users", $request);
            
            $validator = Validator::make($request->all(), $rules);
            
            if($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
     
            $insert_id = Module::insert("Users", $request);
 

            //$hash = bcrypt('1111100000');
            // Modify Code
            $password = LAHelper::gen_password();
            $hash = bcrypt($password);
            
            $update = DB::table("users")
                    ->where('id', $insert_id)
                    ->update([
                        'password' => $hash
                        //'confirm_password' => $hash
                    ]);
            $username = $request->username;
            $mail = $request->email;
            $address = $mail;
            // $url = "http://admin.umsmjtd.com/";

            // Mail::to($address)->send(new sendtoUser($username,$mail,$password,$url));

            // End
            $user = User::find($insert_id);
            
            // update user role
            $user->detachRoles();
            $role = Role::find($request->role);
            $user->attachRole($role);
            
            return redirect()->route(config('laraadmin.adminRoute') . '.users.index');
            
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Display the specified user.
     *
     * @param int $id user ID
     * @return mixed
     */
    public function show($id)
    {
            
        $user = User::find($id);
        if(isset($user->id)) {
            $module = Module::get('Users');
            $module->row = $user;
            
            return view('la.users.show', [
                'module' => $module,
                'view_col' => $module->view_col,
                'no_header' => true,
                'no_padding' => "no-padding"
            ])->with('user', $user);
        } else {
            return view('errors.404', [
                'record_id' => $id,
                'record_name' => ucfirst("user"),
            ]);
        }
        
    }
    
    /**
     * Show the form for editing the specified user.
     *
     * @param int $id user ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        if(Module::hasAccess("Users", "edit")) {
            $user = User::find($id);
            if(isset($user->id)) {
                $module = Module::get('Users');
                
                $module->row = $user;
                
                return view('la.users.edit', [
                    'module' => $module,
                    'view_col' => $module->view_col,
                    'user' => $user
                ])->with('user', $user);
            } else {
                return view('errors.404', [
                    'record_id' => $id,
                    'record_name' => ucfirst("user"),
                ]);
            }
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Update the specified user in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id user ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        if(Module::hasAccess("Users", "edit")) {
            
            $rules = Module::validateRules("Users", $request, true);
            
            $validator = Validator::make($request->all(), $rules);
            
            if($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();;
            }
            
            $insert_id = Module::updateRow("Users", $request, $id);

            // Update User
            $user = User::where('id', $insert_id)->first();
            
            // update user role
            $user->detachRoles();
            $role = Role::find($request->role);
            $user->attachRole($role);
            
            return redirect()->route(config('laraadmin.adminRoute') . '.users.index');
            
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Remove the specified user from storage.
     *
     * @param int $id user ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        if(Module::hasAccess("Users", "delete")) {
            User::find($id)->delete();
            
            // Redirecting to index() method
            return redirect()->route(config('laraadmin.adminRoute') . '.users.index');
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
        $module = Module::get('Users');
        $listing_cols = Module::getListingColumns('Users');
        
        $values = DB::table('users')->select($listing_cols)->whereNull('deleted_at');
        $out = Datatables::of($values)->make();
        $data = $out->getData();
        
        $fields_popup = ModuleFields::getModuleFields('Users');
        
        for($i = 0; $i < count($data->data); $i++) {
            for($j = 0; $j < count($listing_cols); $j++) {
                $col = $listing_cols[$j];
                if($fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
                    $data->data[$i][$j] = ModuleFields::getFieldValue($fields_popup[$col], $data->data[$i][$j]);
                }
                if($col == $module->view_col) {
                    $data->data[$i][$j] = '<a href="' . url(config('laraadmin.adminRoute') . '/users/' . $data->data[$i][0]) . '">' . $data->data[$i][$j] . '</a>';
                }
                // else if($col == "author") {
                //    $data->data[$i][$j];
                // }
            }
            
            if($this->show_action) {
                $output = '';
                if(Module::hasAccess("Users", "edit")) {
                    $output .= '<a href="' . url(config('laraadmin.adminRoute') . '/users/' . $data->data[$i][0] . '/edit') . '" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a>';
                }
                
                if(Module::hasAccess("Users", "delete")) {
                    $output .= Form::open(['route' => [config('laraadmin.adminRoute') . '.users.destroy', $data->data[$i][0]], 'method' => 'delete', 'style' => 'display:inline','class' => 'form-delete']);
                    $output .= ' <button class="btn btn-danger btn-xs" type="submit"><i class="fa fa-trash"></i></button>';
                    $output .= Form::close();
                }
                $data->data[$i][] = (string)$output;
            }
        }
        $out->setData($data);
        return $out;
    }

    /**
     * Change Employee Password
     *
     * @return
     */
    public function change_password($id, Request $request) {
        
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:6',
            'password_confirmation' => 'required|min:6|same:password'
        ]);
        
        if ($validator->fails()) {
            return \Redirect::to(config('laraadmin.adminRoute') . '/users/'.$id)->withErrors($validator);
        }
        
        $user = User::where("id", $id)->first();
        $user->password = bcrypt($request->password);
        $user->save();
        
        \Session::flash('success_message', 'Password is successfully changed');
        
        return redirect(config('laraadmin.adminRoute') . '/users/'.$id.'#tab-account-settings');
    }
}
