<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    function index()
    {
        $data['admin'] = auth()->guard('admin')->user();

        return view('admin.profile.index', $data);
    }

    function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $admin = $request->user();

        $admin->update([
            'name' => $request->name
        ]);

        return back()->with('success', 'Profile updated successfully');
    }

    function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|confirmed'
        ]);

        $admin = $request->user();

        // check if previous password is correct
        if (!Hash::check($request->current_password, $admin->password)) {
            return back()->with('error', 'Invalid old password.');
        }

        $admin->update([
            'password' => Hash::make($request->password)
        ]);


        return back()->with('success', 'Password successfully updated');
    }
}
