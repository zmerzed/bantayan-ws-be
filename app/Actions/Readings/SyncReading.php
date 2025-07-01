<?php

namespace App\Actions\Readings;

use Carbon\Carbon;
use App\Models\Reading;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;

class SyncReading
{
    public function execute(Reading $reading)
    {
        $readerAdmin = request()->user();
        $reading->meter_reading = request()->meter_reading;
        $reading->meter_reading_date = Carbon::parse(request()->meter_reading_date);
        $reading->comment = request()->comment;
        $reading->readed_by = $readerAdmin->id;
        //$reading->update();

        return $reading;
    }
}
