<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;

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
    protected $redirectTo = '/store/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('guest:admin')->except('adminLogout');
        $this->middleware('guest:custodian')->except('custodianLogout');
        $this->middleware('guest:hos3pl')->except('hos3plLogout');
        $this->middleware('guest:inventory')->except('inventoryLogout');
    }

    

    public function showLoginForm()
    {
        return view('hos.hos_login');
    }

    protected function credentials(Request $request)
    {
        return $request->only($this->username(), 'password','user_type');
    }

     public function showAdminLoginForm()
    {
        return view('admin.admin_login', ['url' => 'admin']);
    }

    public function adminLogin(Request $request)
    {
        $this->validate($request, [
            'email'   => 'required|email',
            'password' => 'required|min:6'
        ]);

        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {
           return redirect()->intended('/admin/home');
            
        }
        return back()->withInput($request->only('email', 'remember'))->withErrors(['common-error'=>'Email or Password is Wrong.']);
    }

    public function adminLogout(Request $request){
        Auth::guard('admin')->logout();
        $request->session()->flush();
        $request->session()->regenerate();
        return redirect()->guest(route('main_page'));
    }

    public function showCustodianLoginForm()
    {
        return view('custodian.custodian_login', ['url' => 'admin']);
    }

    public function custodianLogin(Request $request)
    {
        $this->validate($request, [
            'email'   => 'required|email',
            'password' => 'required|min:6'
        ]);

        if (Auth::guard('custodian')->attempt(['email' => $request->email, 'password' => $request->password,'user_type'=>2], $request->get('remember'))) {
            return redirect()->intended('/custodian/home');
            //return back()->withInput($request->only('email', 'remember'))->withErrors(['common-error'=>'Email or Password is Wrong.']);
        }
        return back()->withInput($request->only('email', 'remember'))->withErrors(['common-error'=>'Email or Password is Wrong.']);
    }

    public function custodianLogout(Request $request){
       Auth::guard('custodian')->logout();
        $request->session()->flush();
        $request->session()->regenerate();
        return redirect()->guest(route( 'main_page' ));
    }

    
    public function showHos3plLoginForm()
    {
        return view('hos_3pl.hos3pl_login', ['url' => 'admin']);
    }

    public function hos3plLogin(Request $request)
    {
        $this->validate($request, [
            'email'   => 'required|email',
            'password' => 'required|min:6'
        ]);

        if (Auth::guard('hos3pl')->attempt(['email' => $request->email, 'password' => $request->password,'user_type'=>3], $request->get('remember'))) {
            return redirect()->intended('/hos3pl/home');
            //return back()->withInput($request->only('email', 'remember'))->withErrors(['common-error'=>'Email or Password is Wrong.']);
        }
        return back()->withInput($request->only('email', 'remember'))->withErrors(['common-error'=>'Email or Password is Wrong.']);
    }

    public function hos3plLogout(Request $request){
        Auth::guard('hos3pl')->logout();
        $request->session()->flush();
        $request->session()->regenerate();
        return redirect()->guest(route('main_page'));
    }

    public function showInventoryLoginForm()
    {
        return view('inventory.inventory_login', ['url' => 'admin']);
    }

    public function inventoryLogin(Request $request)
    {
        $this->validate($request, [
            'email'   => 'required|email',
            'password' => 'required|min:6'
        ]);

        if (Auth::guard('inventory')->attempt(['email' => $request->email, 'password' => $request->password,'user_type'=>4], $request->get('remember'))) {
            return redirect()->intended('/inventory/home');
            //return back()->withInput($request->only('email', 'remember'))->withErrors(['common-error'=>'Email or Password is Wrong.']);
        }
        return back()->withInput($request->only('email', 'remember'))->withErrors(['common-error'=>'Email or Password is Wrong.']);
    }

    public function inventoryLogout(Request $request){
        Auth::guard('inventory')->logout();
        $request->session()->flush();
        $request->session()->regenerate();
        return redirect()->guest(route('main_page'));
    }

    protected function authenticated(Request $request, $user) {
        $request->session()->put('user_type', $user->user_type);
        if ($user->user_type == 1) {
            return redirect('/store/home');
        } else if ($user->user_type == 2) {
            return redirect('/custodian/home');
        }else if ($user->user_type == 3) {
            return redirect('/hos3pl/home');
        }else if ($user->user_type == 4) {
            return redirect('/inventory/home');
        } else {
            return redirect('/');
        }
   }
}


