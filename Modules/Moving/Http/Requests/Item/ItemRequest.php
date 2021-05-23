<?php

namespace Modules\Moving\Http\Requests\Item;

use Illuminate\Foundation\Http\FormRequest;

class ItemRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name'              => 'required|string',
            'name_pt'           => 'nullable|string',
            'name_es'           => 'nullable|string',
            'description'       => 'required|string',
            'cubic_feet'        => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'packing_qty'       => 'required|int',
            'packing_id'        => 'required|int',
            'tag'               => 'required|string',
            'active'            => 'int',
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $rules = [
                'name'          => 'string',
                'name_pt'       => 'nullable|string',
                'name_es'       => 'nullable|string',
                'description'   => 'string',
                'cubic_feet'    => 'regex:/^\d+(\.\d{1,2})?$/',
                'packing_qty'      => 'int',
                'packing_id'    => 'int',
                'tag'           => 'string',
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
