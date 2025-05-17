<?php

namespace App\Models;
use Carbon\Carbon;
use App\Models\Admin;
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
        'month_no',
        'year',
        'reading_day',
        'sequence',
        'reader_id',
        'customer_id'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function readedBy()
    {
        return $this->belongsTo(Admin::class, 'readed_by');
    }
}
