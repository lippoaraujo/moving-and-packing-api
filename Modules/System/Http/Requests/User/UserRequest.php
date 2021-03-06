<?php

namespace Modules\System\Http\Requests\User;

use App\Traits\ApiResponser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\System\Entities\User;

class UserRequest extends FormRequest
{
    use ApiResponser;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => 'required|string',
            'email' => 'string|email|nullable|entity_disabled:users,email|unique:users,email,NULL,id,deleted_at,NULL',
            'password' => 'required|string|confirmed',
            'roles'     => 'array',
            'roles.*'   => 'int',
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $id = $this->route('user');

            $rules = [
                'name' => 'string',
                'email' => [
                    'string',
                    'email',
                    'entity_disabled:users,email',
                    Rule::unique('users', 'email')->ignore($id)->where('deleted_at', 'NULL')
                ],
                'password' => 'string',
                'roles'     => 'array',
                'roles.*'   => 'int',
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
        // if (in_array($this->method(), ['PUT', 'PATCH'])) {
        //     $id = $this->route()->parameter('id');
        //     $user = User::find($id);

        //     if(empty($user)) {
        //         return false;
        //     }
        // }

        return true;
    }

    // protected function failedAuthorization()
    // {
    //     throw new \Illuminate\Auth\Access\AuthorizationException('User not found.');
    // }
}
