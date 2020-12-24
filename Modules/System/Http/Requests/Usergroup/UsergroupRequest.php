<?php

namespace Modules\System\Http\Requests\Usergroup;

use Illuminate\Foundation\Http\FormRequest;

class UsergroupRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => 'required|string',
            'tenant_id' => 'required|int',
            'active' => 'int',
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $rules = [
                'name' => 'string',
                'tenant_id' => 'int',
                'active' => 'int',
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
