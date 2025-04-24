<?php

namespace App\Models;

use App\Models\Admin;
use App\Models\Barangay;
use Illuminate\Database\Eloquent\Model;

class Sequence extends Model
{
    protected $table = 'sequences';

    protected $fillable = ['barangay_id', 'number'];

    public function barangay()
    {
        return $this->belongsTo(Barangay::class);
    }

    public function reader()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}