<?php

namespace App\Http\Controllers\RolePermission;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        if (! auth()->guard('admin')->user()->hasPermissionTo('manage role-permission')) {
            return abort(401);
        }
        $permissions = Permission::with('roles')
            ->when($request->get('search') ?? null, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%'.$search.'%');
                });
            })
            ->when($request->get('sort_by') ?? null, function ($query, $sort_by) use ($request) {
                $query->orderBy($sort_by, $request->get('sort_type') ?? 'asc');
            }
            )
            ->paginate($request->per_page ?? 10)
            ->withQueryString();

        return Inertia::render('SuperAdmin/Permission/Index', [
            'permissions' => $permissions,
            'filters' => $request->all('search', 'sort_by', 'sort_type', 'per_page'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): Response
    {
        if (! auth()->guard('admin')->user()->hasPermissionTo('manage role-permission')) {
            return abort(401);
        }

        $permission = Permission::find($id);
        $roles = Role::all();
        $data = [];
        $permission_has_roles = $permission->roles->pluck('name')->toArray();
        $data = [];
        foreach ($roles as $role) {
            if (in_array($role->name, $permission_has_roles)) {
                $data[$role->name] = true;
            } else {
                $data[$role->name] = false;
            }
        }

        return Inertia::render('SuperAdmin/Permission/AssignRole', [
            'permission' => $permission,
            'role_data' => $roles,
            'permission_has_roles' => $data,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        if (! auth()->guard('admin')->user()->hasPermissionTo('manage role-permission')) {
            return abort(401);
        }

        $request->validate([
            'roles' => 'required',
        ]);

        $selectedRoles = array_keys(array_filter($request->roles));
        $permission = Permission::find($id);
        $permission->syncRoles($selectedRoles);

        return Redirect::route('super_admin.permission.assign-role', $id)->with('success', __('Successfully updated!'));
    }
}
