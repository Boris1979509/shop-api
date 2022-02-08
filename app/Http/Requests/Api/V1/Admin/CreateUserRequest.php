<?php

namespace App\Http\Requests\Api\V1\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreateUserRequest
 * @package App\Http\Requests\Api\V1\Admin
 */
class CreateUserRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'phone'    => [
                'required',
                'digits:10',
                'unique:users,phone,'
                //'regex:/^[0-9]{10}$/',
            ],
            'password' => [
                'required',
                'string',
                'min:6',
                'max:255',
                'confirmed',
            ],
        ];
    }
}
