<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Role;
use App\User;
use Auth;
use DB;
use GuzzleHttp\Client;
use Dwij\Laraadmin\Models\LAConfigs;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function showLoginForm()
    {
      $roleCount = Role::count();
      if($roleCount != 0) {
        $userCount = User::count();
        if($userCount == 0) {
          return redirect('register');
        } else {
          return view('auth.login');
        }
      } else {
        return view('errors.error', [
          'title' => 'Migration not completed',
          'message' => 'Please run command <code>php artisan db:seed</code> to generate required table data.',
        ]);
      }
    }

    public function login(Request $request) {
      $bool = Auth::attempt([ 'email' => $request['email'], 'password' => $request['password']]);
      $user = Auth::user();
      if($bool)
      {
          $system_permissions = DB::table('system_permissions')->where('user_id', $user->id)->first();

          $client = new Client();
          if(isset($system_permissions) && $system_permissions->ums){
            $response = $client->request('POST', LAConfigs::getByKey("ums_url").'/api/webLogin', [
                'form_params' => [
                    'email' => $request['email'],
                    'password' => $request['password'],
                ]
            ]);
            $statusCode = $response->getStatusCode();
            if($statusCode == 200){
              $body = json_decode($response->getBody()->getContents(), true);
              $access_token = $body['success']['token'];
              DB:: table('users')->where('id', $user->id)->update(['ums_token' => $access_token]);
            }
          }
          
          // if(isset($system_permissions) && $system_permissions->rbs){
          //   $response = $client->request('POST', LAConfigs::getByKey("rbs_url").'/api/login', [
          //     'form_params' => [
          //         'email' => $request['email'],
          //         'password' => $request['password'],
          //     ]
          //   ]);
          //   $statusCode = $response->getStatusCode();
          //   if($statusCode == 200){
          //     $body = json_decode($response->getBody()->getContents(), true);
          //     $access_token = $body['success']['token'];
          //     DB:: table('users')->where('id', $user->id)->update(['rbs_token' => $access_token]);
          //   }
          // }
          return redirect('/admin');
      } else {
          return redirect('/')
          ->withErrors(array('Login failed! Try again.'));
      }
  }
}
