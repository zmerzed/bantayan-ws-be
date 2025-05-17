<?php

namespace App\Models\Traits;

use Carbon\Carbon;
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

    public function previousReading()
    {
        $today = Carbon::now();
        $today->subMonth();
        
        return $this->readings()
            ->orderBy('id', 'desc')
            ->first();
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */
    public function generateNewReading($readingDay = null): void
    {
        $today = Carbon::now();
        //$today = Carbon::parse('2025-05-01');
        //dd($today->month);
        //dd($today);
        $readingThisMonth = $this->readings()
            //->where('customer_id', $this->id)
            ->where('month_no', $today->month)
            ->where('year', $today->year)
            ->first();
        
        //dd($this->previousReading());
        //dd($readingThisMonth);
        if (!$readingThisMonth && $readingDay) 
        {
            $previousReading = $this->previousReading();
            //dd($previousReading);
            $newReading = new Reading();
            $newReading->customer_id = $this->id;
            $newReading->month_no = $today->month;
            $newReading->year = $today->year;
            $newReading->reading_day = $readingDay;
            $newReading->sequence = $this->sequence;
            
            if ($previousReading) {
                $newReading->prev_meter_reading = $previousReading->meter_reading;
            }
            
            $newReading->save();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */
   
}
