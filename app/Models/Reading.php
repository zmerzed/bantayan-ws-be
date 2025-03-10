<?php

namespace App\Models;
use Carbon\Carbon;
use App\Models\CustomerDetail;
use Illuminate\Database\Eloquent\Model;

class Reading extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'meter_reading',
        'prev_meter_reading',
        'meter_reading_date',
        'comment',
        'customer_id'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
