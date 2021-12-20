<?php

namespace App\Http\Requests\Api\V1\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class EmailRequest
 * @package App\Http\Requests\Api\V1\Auth
 */
class EmailRequest extends FormRequest
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
            'email'    => [
                'required',
                'email',
            ],
            'password' => [
                'required',
            ],
        ];
    }
}
