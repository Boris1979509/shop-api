<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Class DatabaseSeeder
 * @package Database\Seeders
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        User::factory(10)->create();
        /** @var Role $role */
        $role = Role::where('name', Role::ROLE_USER);
        User::all()->each(static function ($user) use ($role) {
            /** @var User $user */
            $user->roles()->attach($role->pluck('id')->toArray());
        });
    }
}
