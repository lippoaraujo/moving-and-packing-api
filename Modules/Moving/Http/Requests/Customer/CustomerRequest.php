<?php

namespace Modules\Moving\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name'      => 'required|string',
            'email'     => [
                'string',
                'email',
                'entity_disabled:customers,email',
                'unique:customers,email,NULL,id,deleted_at,NULL'
            ],
            'phone'     => [
                'string',
                'nullable',
                'entity_disabled:customers,phone',
                'unique:customers,phone,NULL,id,deleted_at,NULL'
            ],
            'customer_address'      => 'array|required',
            'customer_address.address'   => 'string|nullable',
            'customer_address.locality'  => 'string|required',
            'customer_address.city'   => 'string|required',
            'customer_address.country'   => 'string|required',
            'customer_address.postcode'   => 'string|nullable',
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $id = $this->route('customer');
            $rules = [
                'id'        => "exists:customers,id",
                'email'     => [
                    'string',
                    'email',
                    'entity_disabled:customers,email',
                    Rule::unique('customers', 'email')->ignore($id)->where('deleted_at', 'NULL')
                ],
                'phone'     => [
                    'string',
                    'entity_disabled:customers,phone',
                    Rule::unique('customers', 'phone')->ignore($id)->where('deleted_at', 'NULL')
                ],
                'customer_address'      => 'array|required',
                'customer_address.address'   => 'string|nullable',
                'customer_address.locality'  => 'string|required',
                'customer_address.city'   => 'string|required',
                'customer_address.country'   => 'string|required',
                'customer_address.postcode'   => 'string|nullable',
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
