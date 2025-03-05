<?php

namespace App\Models;
use Carbon\Carbon;
use App\Models\CustomerDetail;
use Illuminate\Database\Eloquent\Model;

class ReadingBatch extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'batch',
        'generated_by_id',
    ];
}
