<?php

namespace App\Actions\Readings;

use Carbon\Carbon;
use App\Models\Reading;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;

class ReaderReadings
{
    public function get()
    {
        $readerAdmin = request()->user();
        //dd($readerAdmin);
        $readings = QueryBuilder::for(Reading::class)
            ->where('assigned_reader_id', $readerAdmin->id);

        if (request()->has('barangay_id')) {
            $readings = $readings->whereHas('customer', function($query) {
                $query->where('barangay_id', request()->barangay_id);
            });
        }

        $readings = $readings->defaultSort('-created_at')
            ->getOrPaginate();

        return $readings;
    }
}
