<?php

namespace Modules\Moving\Http\Requests\Image;

use Illuminate\Foundation\Http\FormRequest;

class ImageRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'image'         => 'required|string',
            'order_room_id' => 'required|int',
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $rules = [
               'image'          => 'string',
               'order_room_id'  => 'int',
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
