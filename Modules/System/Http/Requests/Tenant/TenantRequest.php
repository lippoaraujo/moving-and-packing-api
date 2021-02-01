<?php

namespace Modules\System\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;

class TenantRequest extends FormRequest
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
            'trading_name' => 'string',
            'email' => 'required|string|unique:tenants,email',
            'password' => 'required|confirmed|string',
            'cnpj' => 'required|int|unique:tenants,email',
            'password' => 'required|string|confirmed',
            'plan_id' => 'required|int',
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $rules = [
                'name' => 'string',
                'trading_name' => 'string',
                'email' => 'string',
                'cnpj' => 'int',
                'plan_id' => 'int',
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
