<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $noOfCustomers = 50;
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::table('customers')->truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $prevDate = Carbon::now()->subMonth()->format('Y-m');

        for ($i=0; $i<$noOfCustomers; $i++) {
             Customer::factory()->count(1)->create(); 
        }
  
    }
}
