<?php

namespace Database\Seeders;

use App\Models\Barangay;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BrgySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('barangays')->truncate();

        Barangay::insert([
            ['name' => 'Atop-atop', 'municipality' => 'bantayan'],
            ['name' => 'Baigad', 'municipality' => 'bantayan'],
            ['name' => 'Bantigue', 'municipality' => 'bantayan'],
            ['name' => 'Baod', 'municipality' => 'bantayan'],
            ['name' => 'Binaobao', 'municipality' => 'bantayan'],
            ['name' => 'Botigues', 'municipality' => 'bantayan'],
            ['name' => 'Doong', 'municipality' => 'bantayan'],
            ['name' => 'Guiwanon', 'municipality' => 'bantayan'],
            ['name' => 'Hilotongan', 'municipality' => 'bantayan'],
            ['name' => 'Kabac', 'municipality' => 'bantayan'],
            ['name' => 'Kabangbang', 'municipality' => 'bantayan'],
            ['name' => 'Kampingganon', 'municipality' => 'bantayan'],
            ['name' => 'Kangkaibe', 'municipality' => 'bantayan'],
            ['name' => 'Lipayran', 'municipality' => 'bantayan'],
            ['name' => 'Luyongbaybay', 'municipality' => 'bantayan'],
            ['name' => 'Mojon', 'municipality' => 'bantayan'],
            ['name' => 'Obo-ob', 'municipality' => 'bantayan'],
            ['name' => 'Patao', 'municipality' => 'bantayan'],
            ['name' => 'Putian', 'municipality' => 'bantayan'],
            ['name' => 'Sillon', 'municipality' => 'bantayan'],
            ['name' => 'Suba', 'municipality' => 'bantayan'],
            ['name' => 'Sulangan', 'municipality' => 'bantayan'],
            ['name' => 'Sungko', 'municipality' => 'bantayan'],
            ['name' => 'Tamiao', 'municipality' => 'bantayan'],
            ['name' => 'Ticad', 'municipality' => 'bantayan']
        ]);
    }
}
