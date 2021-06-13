<?php

namespace Modules\System\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name'          => 'required|unique:roles,name|string',
            'permission'    => 'array',
            'permission.*'  => 'integer'
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $rules = [
                'name'          => 'required|string',
                'permission'    => 'array',
                'permission.*'  => 'integer'
            ];
        }

        return $rules;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
