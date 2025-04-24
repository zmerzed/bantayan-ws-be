<?php

namespace App\Models;

use App\Models\Barangay;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Traits\InteractSequence;
use Illuminate\Database\Eloquent\Builder;
use Kolette\Auth\Models\Traits\HasAvatar;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\MediaLibrary\HasMedia as MediaLibraryHasMedia;

class Admin extends Authenticatable implements MediaLibraryHasMedia
{
    use HasRoles;
    use HasAvatar;
    use HasFactory;
    use HasApiTokens;
    use InteractSequence;

    /**
     * The default guard to use
     * This will use by laravel permission package to determine the guard to use
     *
     * @var string
     */
    protected $guard_name = 'api';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'full_name',
        'password',
        'email',
    ];

    public function scopeWithRoles(Builder $query, array $roles)
    {
        return $query->whereHas('roles', function ($q) use ($roles) {
            $q->whereIn('name', $roles);
        });
    }

}
