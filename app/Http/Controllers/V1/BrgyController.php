<?php

namespace App\Http\Controllers\V1;

use App\Models\Barangay;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Http\Resources\Json\JsonResource;

class BrgyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $collection = QueryBuilder::for(Barangay::class)
            ->defaultSort('name')
            ->getOrPaginate();

        return JsonResource::collection($collection);
    }

}
