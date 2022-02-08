<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Rules\MatchRoleUser;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateUserRequest
 * @package App\Http\Requests\Api\V1\Admin
 */
class UpdateUserRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'first_name' => [
                'nullable',
                'string',
                'max:255',
            ],
            'last_name'  => [
                'nullable',
                'string',
                'max:255',
            ],
            'phone'      => [
                'required',
                'digits:10',
                //'regex:/^[0-9]{10}$/'
            ],
            'email'      => [
                'required',
                'string',
                'email',
            ],
            'roles'      => [
                'required',
                'array',
                'min:1',
                app(MatchRoleUser::class),
            ],
        ];
    }

}
