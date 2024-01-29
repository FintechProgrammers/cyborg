<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class ManageAdministratorsController extends Controller
{
    function index()
    {
        $data['admins'] =  Admin::where('id', '!=', 1)->where('id', '!=', Auth::guard('admin')->user()->id)->latest()->get();

        return view('admin.administrators.index', $data);
    }

    function create()
    {
        $data['roles'] =  Role::select('id', 'name')->where('name', '<>', 'super admin')->orderBy('name', 'ASC')->get();

        return view('admin.administrators.create', $data);
    }

    function store(Request $request)
    {
        $request->validate([
            'name'      => 'required',
            'email'     => 'required|email|unique:admins,email',
            'password'  => 'required',
            'roles' => 'required|array',
            'roles.*' => 'required',
        ]);

        $admin = Admin::create([
            'name'  => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $admin->assignRole($request->roles);

        return redirect()->route('admin.administrators.index')->with('success', 'Administrators added successfully.');
    }

    function show(Admin $admin)
    {
        $data['admin'] = $admin;
        $data['roles'] =  Role::select('id', 'name')->where('name', '<>', 'super admin')->orderBy('name', 'ASC')->get();

        return view('admin.administrators.show', $data);
    }

    function update(Request $request, Admin $admin)
    {
        $request->validate([
            'name'      => 'required',
            'email'     => 'required|email',
            'roles' => 'required|array',
            'roles.*' => 'required',
        ]);

        $admin->update([
            'name'  => $request->name,
            'email' => $request->email
        ]);

        $admin->syncRoles($request->roles);

        return redirect()->route('admin.administrators.index')->with('success', 'Administrators updated successfully.');
    }

    function destroy(Admin $admin)
    {
        $admin->delete();

        return response()->json(['success' => true, 'message' => 'Administrators deleted successfully']);
    }
}
