<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\BrgySeeder;
use Database\Seeders\OldCustomerSeeder;
use Illuminate\Support\Facades\Artisan;
use Database\Seeders\AdminAccountSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Artisan::call("app:acl:sync");
        $this->call(AdminAccountSeeder::class);
        $this->call(BrgySeeder::class);
        $this->call(OldCustomerSeeder::class);

        //$this->call(CustomerSeeder::class);
        //$this->call(GenerateStartReadingsSeeder::class);
 
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
