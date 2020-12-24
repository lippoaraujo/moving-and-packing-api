<?php

namespace Modules\System\Http\Requests\Module;

use Illuminate\Foundation\Http\FormRequest;

class ModuleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name'          => 'required|string',
            'description'   => 'required|string',
            'parent_module' => 'int|nullable',
            'parent_module' => 'int|nullable',
            'order'         => 'int|nullable',
            'color'         => 'string|nullable',
            'image'         => 'string|nullable',
            'active'        => 'int',
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $rules = [
                'name'          => 'string',
                'description'   => 'string',
                'parent_module' => 'int|nullable',
                'order'         => 'int|nullable',
                'color'         => 'string|nullable',
                'image'         => 'string|nullable',
                'active'        => 'int',
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
