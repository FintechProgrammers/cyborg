<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesController extends Controller
{
    function index()
    {
        $data['roles'] = Role::latest()->latest()->get();

        return view('admin.roles.index', $data);
    }

    public function create()
    {
        $data['permissions'] = Permission::where('guard_name', 'admin')->get();

        return view('admin.roles.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'role_name' => 'required'
        ]);

        $checkRole = Role::where('name', $request->role_name)->first();

        if ($checkRole) {
            return back()->with('error', 'Role with this name ' . $request->role_name . ' already exist');
        }

        $role = Role::create([
            'uuid' => \Illuminate\Support\Str::uuid()->toString(),
            'name' => $request->role_name,
            'guard_name' => 'admin'
        ]);

        $role->givePermissionTo($request->permissions);

        return redirect()->route('admin.roles.index')->with('success', ' Role was successfully created.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($role)
    {
        $role = Role::where('uuid', $role)->firstOrFail();

        $role_permissions = count($role->permissions) > 0 ? $role->permissions->map(fn ($permissions) => $permissions->id) : $role->permissions;

        return view('admin.roles.edit', [
            'role'      => $role,
            'permissions'  => Permission::where('guard_name', 'admin')->get(),
            'role_permissions' => json_decode($role_permissions, 1)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $role)
    {
        $request->validate([
            'role_name' => 'required'
        ]);

        $role = Role::where('uuid', $role)->firstOrFail();

        $checkRole = Role::where('name', $request->role_name)->first();

        if ($checkRole && $request->role_name != $role->name) {
            return back()->with('error', 'a role with the name ' . $request->role_name . ' already exist');
        }

        $role->update(['name' => $request->role_name]);

        $role->syncPermissions($request->permissions);

        return  redirect()->route('admin.roles.index')->with('success', ' role was successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {

        $newRole = Role::where('name', 'default')->first();

        if ($newRole) {
            $usersWithRole = $role->users;

            foreach ($usersWithRole as $user) {
                $user->syncRoles([$newRole->id]);
            }
        }
        $role->delete();

        return response()->json(['success' => true, 'message' =>' Role was successfully deleted.']);
    }
}
