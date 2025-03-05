<?php

namespace App\Models;
use Carbon\Carbon;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;

class CustomerDetail extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_id',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
