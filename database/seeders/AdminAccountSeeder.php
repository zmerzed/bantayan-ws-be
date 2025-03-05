<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AdminAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Admin::updateOrCreate([
            'email' => 'admin.ws@bantayan.com',
        ], [
            'full_name' => 'admin',
            'password' => bcrypt('password')
        ]);

        $role = Role::whereName('ADMIN')->first();

        $admin->assignRole($role);

        
        $reader = Admin::updateOrCreate([
            'email' => 'reader.ws@bantayan.com',
        ], [
            'full_name' => 'reader',
            'password' => bcrypt('password')
        ]);

        $role = Role::whereName('READER')->first();

        $reader->assignRole($role);
    }
}
