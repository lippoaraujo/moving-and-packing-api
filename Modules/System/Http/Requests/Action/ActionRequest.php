<?php

namespace Modules\System\Http\Requests\Action;

use Illuminate\Foundation\Http\FormRequest;

class ActionRequest extends FormRequest
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
            'type' => 'required|string',
            'active' => 'int|nullable',
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $rules = [
                'name' => 'string',
                'type' => 'string',
                'active' => 'int|nullable',
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
