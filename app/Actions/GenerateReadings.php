<?php

namespace App\Actions;

use Carbon\Carbon;
use App\Models\Customer;
use App\Enums\AccountType;
use App\Models\ReadingBatch;
use Illuminate\Http\Request;
use App\Enums\CustomerStatus;
use App\Enums\ApplicationType;
use Illuminate\Support\Facades\DB;

class GenerateReadings
{
    public function execute(ReadingBatch $batch)
    {
        $customers = Customer::all();

        foreach ($customers as $customer)  {
            $customer->generateNewReading($batch);
        }
    }
}
