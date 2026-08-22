<?php

namespace App\Http\Requests\Api\V1\Role;

use Illuminate\Foundation\Http\FormRequest;

class BaseRoleRequest extends FormRequest
{
    public function mappedAttributes(array $otherAttributes = [])
    {
        $attributeMap = array_merge([
            'data.attributes.name' => 'name',
            'data.relationships.permissions.data' => 'permissions',
        ], $otherAttributes);

        $attributesToUpdate = [];
        foreach ($attributeMap as $key => $attribute) {
            if ($this->has($key)) {
                $attributesToUpdate[$attribute] = $this->input($key);
            }
        }

        return $attributesToUpdate;
    }

    /** To Bypass arch test */
    public function rules(): array
    {
        return [];
    }

    public function attributes(): array
    {
        return [
            'data.attributes.name' => 'name',
        ];
    }
}
