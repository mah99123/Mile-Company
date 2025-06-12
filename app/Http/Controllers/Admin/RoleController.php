<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with(['permissions', 'users'])->paginate(15);
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all()->groupBy('module');
        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::create(['name' => $validated['name']]);

        if (isset($validated['permissions']) && !empty($validated['permissions'])) {
            // Get permission objects instead of just IDs
            $permissions = Permission::whereIn('id', $validated['permissions'])->get();
            $role->syncPermissions($permissions);
        }

        return redirect()->route('admin.roles.index')
            ->with('success', 'تم إنشاء الدور بنجاح');
    }

    public function show(Role $role)
    {
        $role->load('permissions', 'users');
        return view('admin.roles.show', compact('role'));
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all()->groupBy('module');
        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->update(['name' => $validated['name']]);

        if (isset($validated['permissions']) && !empty($validated['permissions'])) {
            // Get permission objects instead of just IDs
            $permissions = Permission::whereIn('id', $validated['permissions'])->get();
            $role->syncPermissions($permissions);
        } else {
            // Remove all permissions if none selected
            $role->syncPermissions([]);
        }

        return redirect()->route('admin.roles.show', $role)
            ->with('success', 'تم تحديث الدور بنجاح');
    }

    public function destroy(Role $role)
    {
        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'تم حذف الدور بنجاح');
    }

    public function bulkAssign(Request $request)
    {
        $validated = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'role_id' => 'required|exists:roles,id',
        ]);

        $role = Role::findOrFail($validated['role_id']);
        $users = User::whereIn('id', $validated['user_ids'])->get();

        foreach ($users as $user) {
            $user->assignRole($role);
        }

        return redirect()->back()->with('success', 'تم تعيين الأدوار بنجاح');
    }

    public function assignUsers(Request $request, Role $role)
    {
        $validated = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $users = User::whereIn('id', $validated['user_ids'])->get();

        foreach ($users as $user) {
            $user->assignRole($role);
        }

        return redirect()->back()->with('success', 'تم تعيين المستخدمين للدور بنجاح');
    }

    public function removeUser(Request $request, Role $role)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($validated['user_id']);
        $user->removeRole($role);

        return response()->json(['success' => true]);
    }

    public function export()
    {
        $roles = Role::with('permissions', 'users')->get();
        
        return response()->json([
            'message' => 'Export functionality will be implemented with Excel package',
            'data' => $roles
        ]);
    }

    public function exportDetails(Role $role)
    {
        $data = [
            'name' => $role->name,
            'permissions' => $role->permissions->pluck('name')->toArray(),
            'users' => $role->users->map(function($user) {
                return [
                    'name' => $user->name,
                    'email' => $user->email,
                ];
            })->toArray(),
            'created_at' => $role->created_at->format('Y-m-d H:i:s'),
        ];

        return response()->json([
            'message' => 'Role details export',
            'data' => $data
        ]);
    }
}
