<?php

namespace App\Models;

use Config;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use HasRoles;
    use HasFactory;

    protected $guard_name = 'api';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getImage()
    {
        $settings = Config::get('filesconfig.users');
        if('' != $this->image)
        {
            if (Storage::exists($settings['images'] . $this->image))
            {
                return url('storage'.$settings['images'] . $this->image);
            }
        }

        return url('storage/default/default-user.png');
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function statusName()
    {
        $statusName = 'Inactivo';

        if(1 == $this->status)
        {
            $statusName = 'Activo';
        }

        return $statusName;
    }

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_users', 'company_id', 'user_id')
                    ->withPivot('id', 'user_id', 'company_id')->withTimestamps();
    }
}
