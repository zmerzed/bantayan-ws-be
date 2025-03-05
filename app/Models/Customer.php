<?php

namespace App\Models;
use Carbon\Carbon;
use App\Models\CustomerDetail;
use App\Models\Traits\InteractReadings;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;
    use InteractReadings;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'mi',
        'account_number',
        'address',
        'brgy',
        'phone_number',
        'work_phone_number',
        'account_type',
        'application_type',
        'status',
        'created_by',
        'updated_by'
    ];

    public function details()
    {
        return $this->hasOne(CustomerDetail::class);
    }

    public static function generateAccountNo()
    {
        $date = Carbon::now();
        $year = $date->year;

        $customerLatest = Self::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();
        
        if ($customerLatest) {
            return ((int) $customerLatest->account_number) + 1 . "";
        }

        $accountNo = ((int) "{$date->year}" . str_pad(1, 4, '0', STR_PAD_LEFT)) . "";
        return $accountNo;
    }
}
