<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'github_token',
        'github_id',
        'github_url',
    ];

    /**
     * Relation - users has many repository
     */
    public function repositories(){
        return $this->hasMany(Repository::class);
    }
}
