<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Hash;
use Session;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
  
class LoginAuthController extends Controller
{
 
    public function index()
    {
        return view('auth.login');
    }  
    public function loginAuto(Request $request)
    {   
        $pass = $request->password;
        $username = $request->username;
        return view('auth.login');
    }    
 
    public function customLogin(Request $request)
    {
        
        $credentials = $request->only('email', 'password');
        
        if (Auth::attempt($credentials)) {
            dd(Auth::attempt($credentials));
            return view('dashboard');
        }
        //return redirect("login")->withSuccess('are not allowed to access');
        //return redirect("auth.login")->withSuccess('Login details are not valid');
    }
 
 
 
    public function registration()
    {
        return view('registration');
    }
       
 
    public function customRegistration(Request $request)
    {  
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);
            
        $data = $request->all();
        $check = $this->create($data);
          
        return redirect("auth.dashboard")->withSuccess('have signed-in');
    }
 
 
    public function create(array $data)
    {
      return User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password'])
      ]);
    }    
     
 
    public function dashboard()
    {
        if(Auth::check()){
            return view('dashboard');
        }
   
        return redirect("login")->withSuccess('are not allowed to access');
    }
     
 
    public function signOut() {
        Session::flush();
        Auth::logout();
        
    }
}
