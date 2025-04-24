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

class GenerateBatchReadings
{
    public function execute(Request $request)
    {
        $barangay = Barangay::find($request->barangay_id);
        $readingBatch = new ReadingBatch();
        $readingBatch->batch = 1;
        $readingBatch->generated_by_id = 1;
        $readingBatch->save();

        if ($barangay && $readingBatch) {
            (new GenerateReadings())->execute($readingBatch);
        }
       
    }
}
