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
        $superAdmin = Admin::updateOrCreate([
            'email' => 'super_admin.ws@bantayan.com',
        ], [
            'full_name' => 'super admin',
            'password' => bcrypt('password')
        ]);
        //bantayanwsapi.techcoology.com
        $role = Role::whereName('SUPER_ADMIN')->first();

        $superAdmin->assignRole($role);

        $admin = Admin::updateOrCreate([
            'email' => 'admin.ws@bantayan.com',
        ], [
            'full_name' => 'admin',
            'password' => bcrypt('password')
        ]);

        $role = Role::whereName('ADMIN')->first();

        $admin->assignRole($role);

        $reader = Admin::updateOrCreate([
            'email' => 'reader1.ws@bantayan.com',
        ], [
            'full_name' => 'John Reader',
            'password' => bcrypt('password')
        ]);

        $role = Role::whereName('READER')->first();

        $reader->assignRole($role);

        $reader2 = Admin::updateOrCreate([
            'email' => 'reader2.ws@bantayan.com',
        ], [
            'full_name' => 'Joseph Reader',
            'password' => bcrypt('password')
        ]);

        $role = Role::whereName('READER')->first();

        $reader2->assignRole($role);
    }
}
