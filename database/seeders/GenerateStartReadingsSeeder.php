<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\ReadingBatch;
use Illuminate\Database\Seeder;
use App\Actions\GenerateReadings;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class GenerateStartReadingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        if (env('DB_CONNECTION') !== 'sqlite') {
            \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        \DB::table('readings')->truncate();
        \DB::table('reading_batches')->truncate();
       
        if (env('DB_CONNECTION') !== 'sqlite') {
            \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        $readingBatch = new ReadingBatch();
        $readingBatch->batch = 1;
        $readingBatch->generated_by_id = 1;
        $readingBatch->save();
        (new GenerateReadings())->execute($readingBatch);
    }
}
