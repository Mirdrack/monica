<?php
namespace Monica\Models;

use Monica\Infrastructure\Utils\Uuids;
use Silber\Bouncer\Database\HasRolesAndAbilities;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use Uuids, HasRolesAndAbilities;

    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'subdomain',
    ];
}
