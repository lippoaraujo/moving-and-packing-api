<?php

namespace Modules\System\Http\Requests\Route;

use Illuminate\Foundation\Http\FormRequest;

class RouteRequest extends FormRequest
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
            'controllers' => 'required|string',
            'module_id' => 'required|int',
            'active' => 'int|nullable',
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $rules = [
                'name' => 'string',
                'controllers' => 'string',
                'module_id' => 'int',
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
