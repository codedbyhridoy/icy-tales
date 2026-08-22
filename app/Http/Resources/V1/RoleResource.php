<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'role',
            'id' => $this->id,
            'attributes' => [
                'name' => $this->name,
                'createdAt' => $this->created_at,
                'updatedAt' => $this->updated_at,
            ],
            'relationships' => [
                'permissions' => [
                    'data' => $this->permissions->map(function ($permission) {
                        return [
                            'type' => 'permission',
                            'id' => $permission->id,
                            'name' => $permission->name,
                        ];
                    }),
                    'links' => [
                        'self' => '',
                    ],
                ],
            ],
            'includes' => [],
            'links' => [
                'self' => route('api.roles.show', $this->id),
            ],
        ];
    }
}
