<?php

namespace Modules\Moving\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

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
            'email'     => 'string|email|unique:users|nullable',
            'phone'     => 'required|string',
            'address'   => 'string|nullable',
            'locality'  => 'string|nullable',
            'city'      => 'string|nullable',
            'country'   => 'string|nullable',
            'postcode'  => 'string|nullable',
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $rules = [
                'name'      => 'string|nullable',
                'email'     => 'string|email|unique:users|nullable',
                'phone'     => 'string|nullable',
                'address'   => 'string|nullable',
                'locality'  => 'string|nullable',
                'city'      => 'string|nullable',
                'country'   => 'string|nullable',
                'postcode'  => 'string|nullable',
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
