<?php

namespace App\Models;

use App\Models\Sequence;
use Illuminate\Database\Eloquent\Model;

class Barangay extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    public function details()
    {
        return $this->hasOne(CustomerDetail::class);
    }

    public function sequences()
    {
        return $this->hasMany(Sequence::class);
    }
}
