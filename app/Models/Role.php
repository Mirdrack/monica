<?php

namespace Monica\Models;

use Silber\Bouncer\Database\Role as BouncerRole;

class Role extends BouncerRole
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'title', 'level', 'type'];
}
