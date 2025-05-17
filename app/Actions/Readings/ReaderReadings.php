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
            ->defaultSort('-created_at')
            ->getOrPaginate();

        return $readings;
    }
}
