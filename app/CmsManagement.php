<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CmsManagement extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'slug', 'description', 'status', 'is_deleted', 'created_at', 'updated_at'
    ];
}
