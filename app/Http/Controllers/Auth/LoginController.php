<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Services\GithubLogin;

class LoginController extends Controller
{
    private $githubLoginService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(GithubLogin $githubLoginService)
    {
        $this->githubLoginService = $githubLoginService;
        $this->middleware('guest')->except('handleLogout');
    }

    /**
     * Display login page.
     * 
     * @return Renderable
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle Github Oauth login redirect request
     * 
     * @return \Illuminate\Routing\Redirector
     */
    public function handleRedirect()
    {
        return redirect()->away($this->githubLoginService->redirect());
    }

    /**
     * Handle account login request
     * 
     * @return \Illuminate\Routing\Redirector
     */
    public function handleLogin(Request $request)
    {
        $isAuthenticated = $this->githubLoginService->authenticate($request->input('code'));
        if( ! $isAuthenticated ){
            return redirect()->back()->withErrors(['error' => 'Oops! Unable to authorize your login attempt']);
        }

        return redirect('home')->with('success', "Your account has been successfully authenticated.");
    }

    /**
     * Log the user out of the application.
     *
     * @return \Illuminate\Routing\Redirector
     */
    public function handleLogout()
    {
        Session::flush();
        Auth::logout();

        return redirect()->route('login.show');
    }
}
