<?php

namespace Fadllabanie\EnvEditor\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class EnvEditorAuthController extends Controller
{
    /**
     * Show the login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('env-editor::login');
    }

    /**
     * Handle the login form submission.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        // Validate the form input
        $this->validateLogin($request);
        // dd();
        // Check for white-listed IPs if configured
        if ($this->isIpRestricted() && !$this->isAllowedIp($request->ip())) {
            return abort(403, 'Unauthorized access from your IP address.');
        }

        // Verify credentials
        if ($this->checkCredentials($request->input('username'), $request->input('password'))) {
            $this->authenticateSession();
            return redirect()->route('env.edit'); // Redirect to the env editor page
        }

        return redirect()->back()->withErrors('Invalid credentials.');
    }

    /**
     * Logout the user and forget the session.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        Session::forget('env_editor_authenticated');
        return redirect()->route('env.login');
    }

    /**
     * Validate the login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    private function validateLogin(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);
    }

    /**
     * Check if the IP restriction is enabled.
     *
     * @return bool
     */
    private function isIpRestricted()
    {
        return !empty(config('env-editor.white_ips_list'));
    }

    /**
     * Check if the provided IP is allowed.
     *
     * @param  string  $ip
     * @return bool
     */
    private function isAllowedIp($ip)
    {
        $allowedIps = explode(',', config('env-editor.white_ips_list'));
        return in_array($ip, $allowedIps);
    }

    /**
     * Check if the given credentials match the stored environment credentials.
     *
     * @param  string  $username
     * @param  string  $password
     * @return bool
     */
    private function checkCredentials($username, $password)
    {
        
        return $username === config('env-editor.username') && $password === config('env-editor.password');
    }

    /**
     * Authenticate the user session.
     *
     * @return void
     */
    private function authenticateSession()
    {
        session(['env_editor_authenticated' => true], now()->addMinutes(2));
    }
}
