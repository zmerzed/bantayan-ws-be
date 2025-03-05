<?php

namespace App\Models\Traits;

use App\Models\Reading;
use App\Models\ReadingBatch;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait InteractReadings
{
    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */
    public function readings(): HasMany
    {
        return $this->hasMany(Reading::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */
    public function generateNewReading(ReadingBatch $batch): void
    {
        $previousReading = $this->readings()->orderBy('id', 'desc')->first();
        $newReading = new Reading();
        $newReading->customer_id = $this->id;
        $newReading->batch_no = $batch->batch;

        if ($previousReading) {
            $newReading->prev_meter_reading = $previousReading->meter_reading_date;
        }
        
        $newReading->save();
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */
   
}
