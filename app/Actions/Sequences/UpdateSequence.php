<?php

namespace App\Actions\Sequences;

use Carbon\Carbon;
use App\Models\Sequence;
use Illuminate\Http\Request;
use Appetiser\Auth\Models\User;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\SequenceResource;

class UpdateSequence
{
    public function execute(Request $request, Sequence $sequence): SequenceResource
    {

        $sequence = DB::transaction(function () use ($request, $sequence) {
            $sequence->admin_id = $request->reader;
            $sequence->update();
            return $sequence;
        });

        return new SequenceResource($sequence);
    }
}
