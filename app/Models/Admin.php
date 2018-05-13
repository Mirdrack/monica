<?php
namespace Monica\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Monica\Infrastructure\Utils\Uuids;
use Silber\Bouncer\Database\HasRolesAndAbilities;
use Illuminate\Support\Facades\Hash;

class Admin extends Authenticatable
{
    use Notifiable, Uuids, HasRolesAndAbilities;

    public $incrementing = false;

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
     * Encrypts the password before save it
     * @param string $value password to store
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }
}
