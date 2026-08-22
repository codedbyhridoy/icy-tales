<?php

namespace App\Policies\V1;

use App\Enums\PharmacyPermission;
use App\Models\User;
use Spatie\Permission\Models\Role;

class RolePolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo(PharmacyPermission::MANAGE_ROLES_AND_PERMISSIONS);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Role $role): bool
    {
        return $user->hasPermissionTo(PharmacyPermission::MANAGE_ROLES_AND_PERMISSIONS) && $role->tenant_id === $user->tenant_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Role $role): bool
    {
        return $user->hasPermissionTo(PharmacyPermission::MANAGE_ROLES_AND_PERMISSIONS) && $role->tenant_id === $user->tenant_id;
    }
}
