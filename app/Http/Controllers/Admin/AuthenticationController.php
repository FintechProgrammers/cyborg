<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    function index()
    {
        return view('admin.authentication.login');
    }

    function login(Request $request)
    {
        $this->validate($request, [
            'email'   => 'required|email',
            'password' => 'required|min:4'
        ]);

        if (Auth::guard('admin')->attempt($request->only(['email', 'password']), $request->get('remember'))) {
            return redirect()->intended('/admin/dashboard');
        }

        return back()->with('error', 'Invalid credentials, check your login credentials and try again.');
    }

    function forgotPasswordForm()
    {
        return view('admin.authentication.forgot-password');
    }

    function forgotPassword(Request $request)
    {
        $this->validate($request, [
            'email'   => 'required|email|exists:admins,email',
        ]);
    }

    function resetPasword(Request $request)
    {
    }

    function logout()
    {
        Auth::guard('admin')->logout();

        return back()->with('success', 'Logged out successfully.');
    }
}
