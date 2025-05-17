<?php

namespace Database\Seeders;

use App\Models\Barangay;
use App\Models\Sequence;
use Illuminate\Database\Seeder;
use App\Models\OldCustInformation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Schema;

class BrgySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        \DB::table('barangays')->truncate();
        \DB::table('sequences')->truncate();

        $oldCustomers = OldCustInformation::query()
            ->distinct()
            ->orderBy('custBarangay', 'asc')
            ->get(['custBarangay']);

        foreach($oldCustomers as $customer) {
            $brgy = Barangay::insert([
                ['name' => $customer->custBarangay, 'municipality' => 'bantayan'],
            ]);
        }

        $oldSequences = OldCustInformation::query()
            //->distinct()
            ->orderBy('sequence', 'ASC')
            ->get(['sequence']);

        foreach($oldSequences as $sequence) 
        {
            $oldSequence = OldCustInformation::where('sequence', $sequence->sequence)->first();

            if ($oldSequence && $brgy = Barangay::where('name', $oldSequence->custBarangay)->first()) 
            {
                $existingSequence = Sequence::query()
                ->where('number', $oldSequence->sequence)
                ->where('barangay_id', $brgy->id)
                ->first();

                if ($existingSequence) {
                    continue;
                }

                echo "Barangay: {$brgy->id} - {$oldSequence->custBarangay} \n";
                echo "Sequence: {$sequence->sequence}\n";

                $sequence = Sequence::create([
                    'number' => $sequence->sequence,
                    'barangay_id' => $brgy->id,
                    'admin_id' => rand(
                        3, // reader admin ID: 3
                        4
                    ) // reader admin ID: 4
                ]);

            }
        }

        Schema::enableForeignKeyConstraints();
    }
}
