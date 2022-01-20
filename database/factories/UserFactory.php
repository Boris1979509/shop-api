<?php

namespace Database\Factories;

use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * @return array
     * @throws Exception
     */
    public function definition(): array
    {
        $active = $this->faker->boolean;
        return [
            'first_name'     => $this->faker->firstName(),
            'last_name'      => $this->faker->lastName(),
            'email'          => $this->faker->unique()->safeEmail(),
            'phone'          => random_int(9000000000, 9999999999),
            'status'         => $active ? User::STATUS_ACTIVE : User::STATUS_PENDING,
            'password'       => Hash::make('123456'),
            'full_address'   => [
                'street'   => $this->faker->streetAddress(),
                'city'     => $this->faker->city(),
                'country'  => $this->faker->country(),
                'zip_code' => $this->faker->postcode(),
            ],
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return Factory
     */
    public function unverified(): Factory
    {
        return $this->state(static function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
