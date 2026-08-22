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

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {

        if (! auth()->guard('admin')->user()->hasPermissionTo('manage role-permission')) {
            return abort(401);
        }

        $roles = Role::when($request->get('search') ?? null, function ($query, $search) {
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

        return Inertia::render('SuperAdmin/Role/Index', [
            'roles' => $roles,
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

        $role = Role::find($id);
        $permissions = Permission::all();
        $data = [];
        $role_has_permissions = $role->permissions->pluck('name')->toArray();
        $data = [];
        foreach ($permissions as $permission) {
            if (in_array($permission->name, $role_has_permissions)) {
                $data[$permission->name] = true;
            } else {
                $data[$permission->name] = false;
            }
        }

        return Inertia::render('SuperAdmin/Role/AssignPermission', [
            'role' => $role,
            'permission_data' => $permissions,
            'role_has_permissions' => $data,
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
            'permissions' => 'required',
        ]);
        $selectedPermissions = array_keys(array_filter($request->permissions));
        $role = Role::find($id);
        $role->syncPermissions($selectedPermissions);

        return Redirect::route('super_admin.role.assign-permission', $id)->with('success', __('Successfully updated!'));
    }
}
