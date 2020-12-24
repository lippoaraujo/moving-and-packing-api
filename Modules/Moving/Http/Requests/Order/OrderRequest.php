<?php

namespace Modules\Moving\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $rules = [
            'customer_signature'        => 'string|nullable',
            'price'                     => 'required|numeric',
            'customer_id'               => 'required|int',
            'user_id'                   => 'required|int',
            'expected_date'             => 'required|date',

            'address_data'              => 'array|nullable',
            'address_data.address'      => 'string',
            'address_data.locality'     => 'string',
            'address_data.city'         => 'string',
            'address_data.country'      => 'string',
            'address_data.postcode'     => 'string',

            'rooms'                     => 'required|array',
            'rooms.*.room_id'           => 'required|int',
            'rooms.*.obs'               => 'string|nullable',

            'rooms.*.items'             => 'required|array',
            'rooms.*.items.*.item_id'   => 'required|int',
            'rooms.*.items.*.obs'       => 'string|nullable',

            'rooms.*.images'            => 'array|nullable',
            'rooms.*.images.*.image'    => 'string',
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $rules = [
                'customer_signature'        => 'string|nullable',
                'price'                     => 'numeric',
                'address_id'                => 'int',
                'customer_id'               => 'int',
                'user_id'                   => 'int',
                'expected_date'             => 'date',

                'address_data'              => 'array|nullable',
                'address_data.address'      => 'string',
                'address_data.locality'     => 'string',
                'address_data.city'         => 'string',
                'address_data.country'      => 'string',
                'address_data.postcode'     => 'string',

                'rooms'                     => 'array',
                'rooms.*.room_id'           => 'int',
                'rooms.*.obs'               => 'string|nullable',

                'rooms.*.items'             => 'array',
                'rooms.*.items.*.item_id'   => 'int',
                'rooms.*.items.*.obs'       => 'string|nullable',

                'rooms.*.images'            => 'array|nullable',
                'rooms.*.images.*.image'    => 'string',
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
