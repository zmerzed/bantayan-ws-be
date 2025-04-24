<?php

namespace App\Http\Controllers\V1;

use App\Models\Barangay;
use App\Models\Sequence;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Http\Resources\SequenceResource;
use App\Actions\Sequences\UpdateSequence;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Requests\Sequence\SequenceUpdateRequest;

class SequenceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //dd('llf');
        $collection = QueryBuilder::for(Sequence::class)
            ->with(['barangay', 'reader'])
            ->defaultSort('number')
            ->getOrPaginate();

        return JsonResource::collection($collection);
    }

    /**
     * Show
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Sequence $sequence)
    {
        return new SequenceResource($sequence);
    }


    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(SequenceUpdateRequest $request, Sequence $sequence)
    {
        return (new UpdateSequence)->execute($request, $sequence);
    }

}
