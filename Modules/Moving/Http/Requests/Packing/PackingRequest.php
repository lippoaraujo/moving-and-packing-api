<?php

namespace Modules\Moving\Http\Requests\Packing;

use Illuminate\Foundation\Http\FormRequest;

class PackingRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name'  => 'required|string',
            'unity'  => 'required|string',
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $rules = [
               'name' => 'string',
               'unity' => 'string',
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
