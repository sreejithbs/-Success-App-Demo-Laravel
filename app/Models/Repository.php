<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Repository extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'repository_id',
        'repository_name',
        'repository_fullname',
        'repository_private',
        'repository_url',
    ];

    /**
     * Relation - repository that belong to the user
     */
    public function user(){
    	return $this->belongsTo(User::class);
    }
}
