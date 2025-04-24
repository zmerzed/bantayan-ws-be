<?php

namespace App\Models\Traits;

use App\Models\Reading;
use App\Models\ReadingBatch;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait InteractSequence
{
    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */
    public function sequences()
    {
        return $this->hasMany(Sequence::class);
    }
   
}
