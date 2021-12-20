<?php

namespace App\Http\Requests\Api\V1\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class PhoneRequest
 * @package App\Http\Requests\Api\V1\Auth
 */
class PhoneRequest extends FormRequest
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
            'phone'    => [
                'required',
                'digits:10',
            ],
            'password' => [
                'required',
            ],
        ];
    }
}
