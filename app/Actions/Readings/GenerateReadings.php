<?php

namespace App\Actions\Readings;

use Carbon\Carbon;
use App\Models\Reading;
use App\Models\Barangay;
use App\Models\Customer;
use App\Models\Sequence;
use App\Enums\AccountType;
use App\Models\ReadingBatch;
use Illuminate\Http\Request;
use App\Enums\CustomerStatus;
use App\Enums\ApplicationType;
use Illuminate\Support\Facades\DB;

class GenerateReadings
{
    public function execute(Barangay $brgy, int $day)
    {
        //dd($brgy);

        $ids_sequence = Sequence::query()
            ->where('barangay_id', $brgy->id)
            ->get()
            ->pluck('number')
            ->toArray();

        
        //dd($ids_sequence);
        
        $customers = Customer::query()
            ->whereHas('details', function($query) use($day) {
                return $query->where('reading_day', $day);
            })
            ->where('barangay_id', $brgy->id)
            ->whereIn('sequence', $ids_sequence)
            ->get();
        
        foreach ($customers as $customer)  
        {
            $customer->generateNewReading($day);
        }

        ///dd($customers);
        $today = Carbon::now();
        $readings = Reading::query()
            ->where('reading_day', $day)
            ->whereIn('sequence', $ids_sequence)
            ->where('month_no', $today->month)
            ->where('year', $today->year)
            ->get();

        return $readings;
    }
}
