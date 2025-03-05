<?php

namespace App\Models;

use Kolette\Auth\Models\Traits\HasAvatar;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia as MediaLibraryHasMedia;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable implements MediaLibraryHasMedia
{
    use HasRoles;
    use HasAvatar;
    use HasFactory;
    use HasApiTokens;

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
}
