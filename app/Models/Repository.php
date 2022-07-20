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
        'uid',
        'name',
        'full_name',
        'visibility',
        'reference_url',
    ];

    /**
     * Relation - repository that belong to the user
     */
    public function user(){
    	return $this->belongsTo(User::class);
    }

    /**
     * Relation - repository has many issues
     */
    public function issues(){
        return $this->hasMany(Issue::class);
    }
}
