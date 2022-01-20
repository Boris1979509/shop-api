<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

/**
 * Class RoleSeeder
 * @package Database\Seeders
 */
class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $data = [
            ['name' => Role::ROLE_USER],
            ['name' => Role::ROLE_ADMIN],
        ];
        app(Role::class)->insert($data);
    }
}
