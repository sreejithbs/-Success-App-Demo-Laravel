<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uid',
        'title',
        'description',
        'status',
        'reference_url',
        'is_synced',
    ];
    
    /**
     * Relation - issue that belongs to the repository
     */
    public function repository(){
    	return $this->belongsTo(Repository::class);
    }
}
