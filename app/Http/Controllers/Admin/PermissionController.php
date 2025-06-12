<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::with('roles')->orderBy('module')->orderBy('name')->paginate(20);
        $modules = Permission::distinct()->pluck('module');
        
        return view('admin.permissions.index', compact('permissions', 'modules'));
    }

    public function create()
    {
        $modules = Permission::distinct()->pluck('module');
        return view('admin.permissions.create', compact('modules'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permissions',
            'module' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        Permission::create($validated);

        return redirect()->route('admin.permissions.index')
            ->with('success', 'تم إنشاء الصلاحية بنجاح');
    }

    public function show(Permission $permission)
    {
        $permission->load('roles.users');
        return view('admin.permissions.show', compact('permission'));
    }

    public function edit(Permission $permission)
    {
        $modules = Permission::distinct()->pluck('module');
        return view('admin.permissions.edit', compact('permission', 'modules'));
    }

    public function update(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $permission->id,
            'module' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        $permission->update($validated);

        return redirect()->route('admin.permissions.show', $permission)
            ->with('success', 'تم تحديث الصلاحية بنجاح');
    }

    public function destroy(Permission $permission)
    {
        // Check if permission is assigned to any roles
        if ($permission->roles()->count() > 0) {
            return back()->with('error', 'لا يمكن حذف الصلاحية لأنها مخصصة لأدوار');
        }

        $permission->delete();

        return redirect()->route('admin.permissions.index')
            ->with('success', 'تم حذف الصلاحية بنجاح');
    }

    public function syncToRoles(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'role_ids' => 'array',
            'role_ids.*' => 'exists:roles,id',
        ]);

        if (isset($validated['role_ids'])) {
            $permission->syncRoles($validated['role_ids']);
        } else {
            $permission->roles()->detach();
        }

        return back()->with('success', 'تم تحديث الأدوار بنجاح');
    }
}
