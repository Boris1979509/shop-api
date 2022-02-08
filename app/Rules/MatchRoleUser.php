<?php

namespace App\Rules;

use App\Models\Role;
use Illuminate\Contracts\Validation\Rule;

/**
 * Class MatchRoleUser
 * @package App\Rules
 */
class MatchRoleUser implements Rule
{


    public function passes($attribute, $value): bool
    {
        return in_array(
            Role::where('name', 'user')
                ->value('id'),
            $value, true);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return trans('The user must have the default role');
    }
}
