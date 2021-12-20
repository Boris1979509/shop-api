<?php

namespace App\Http\Requests\Api\V1\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class LoginTypeRequest
 * @package App\Http\Requests\Api\V1\Auth
 */
class LoginTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
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
            'type' => [
                'required',
                Rule::in('phone', 'email'),
            ],
        ];
    }
}
